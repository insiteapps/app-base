<?php

    /*
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\ORM\DataExtension;
    use SilverStripe\Control\Controller;
    use SilverStripe\Core\Convert;
    use SilverStripe\Forms\Tests\CheckboxSetFieldTest\Tag;
    use SilverStripe\Control\HTTP;
    use SilverStripe\Security\Member;
    use SilverStripe\Security\Permission;
    use SilverStripe\View\Requirements;
    use SilverStripe\View\SSViewer;
    */

    namespace InsiteApps\ORM;

    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Control\Controller;
    use SilverStripe\Control\Director;
    use SilverStripe\Control\HTTP;
    use SilverStripe\Core\Convert;
    use SilverStripe\ORM\ArrayList;
    use SilverStripe\ORM\DataExtension;
    use SilverStripe\ORM\DataList;
    use SilverStripe\Security\Member;
    use SilverStripe\Security\Permission;
    use SilverStripe\View\Requirements;
    use SilverStripe\View\SSViewer;

    class DataObjectExtension extends DataExtension
    {

        /*
        * Animate.css classes
        * A DO helper method that enables us to list place a random list of
        * animate.css classes on any dataobject or subclass objects
        */
        public function animate( $class = null )
        {
            //if there is a hash tag in the url, then dont return the animated class
            //as the animation thens to break the # tag url behaivour on the browser
            //but unfortunately browsers dont send the HASH part of the url to the server,
            //so php doesnt know how to parse
            //low priority for now so we will just do a redirect back
            if (($class)) {
                return 'animated ' . $class;
            }

            $classList = array(
                'bounce', 'bounceIn', 'bounceInDown', 'bounceInLeft', 'bounceInRight', 'bounceInUp',
                'fadeIn', 'fadeInDown', 'fadeInLeft', 'fadeInRight', 'fadeInUp',
                'slideDown', 'slideInLeft', 'slideInRight',
            );
            $randomClassList = array_rand($classList, 1);
            $randomClassList = array( $classList[ $randomClassList ] );
            $randomClassList = $randomClassList[ 0 ];

            return 'animated ' . $randomClassList;

        }

        public function Lowercase( $string )
        {
            return strtolower($string);
        }

        /**
         * Returns a fixed navigation menu of the given level.
         *
         * @param int $level Menu level to return.
         *
         * @return ArrayList
         */
        public function getMenu( $level = 1 )
        {
            if ($level == 1) {
                $result = SiteTree::get()->filter(array(
                    "ShowInMenus" => 1,
                    "ParentID"    => 0,
                ));

            } else {
                //custom
                $controller = Controller::curr();
                //end custom
                //$parent = $this->data();
                $parent = $controller->data();
                $stack = array( $parent );

                if ($parent) {
                    while ($parent = $parent->Parent) {
                        array_unshift($stack, $parent);
                    }
                }

                if (isset($stack[ $level - 2 ])) $result = $stack[ $level - 2 ]->Children();
            }

            $visible = array();

            // Remove all entries the can not be viewed by the current user
            // We might need to create a show in menu permission
            if (isset($result)) {
                foreach ($result as $page) {
                    if ($page->canView()) {
                        $visible[] = $page;
                    }
                }
            }

            return new ArrayList($visible);
        }

        public function Menu( $level )
        {
            return $this->getMenu($level);
        }


        /**
         * Return "link", "current" or section depending on if this page is the current page, or not on the current
         * page but in the current section.
         *
         * @return string
         */
        public function LinkingMode()
        {
            if ($this->owner->isCurrent()) {
                return 'active';
            } elseif ($this->owner->isSection()) {
                return 'active section';
            } else {
                return 'link';
            }
        }

        /**
         * Returns TRUE if this is the currently active page that is being used to handle a request.
         *
         * @return bool
         */
        public function isCurrent()
        {
            return $this->owner->ID ? $this->owner->ID == Director::get_current_page()->ID : $this === Director::get_current_page();
        }

        /**
         * Check if this page is in the currently active section (e.g. it is either current or one of it's children is
         * currently being viewed.
         *
         * @return bool
         */
        public function isSection()
        {
            return $this->owner->isCurrent() || (
                    Director::get_current_page() instanceof SiteTree && in_array($this->owner->ID, Director::get_current_page()->getAncestors()->column())
                );
        }

        /**
         * Returns the page in the current page stack of the given level.
         * Level(1) will return the main menu item that we're currently inside, etc.
         */
        public function Level( $level )
        {
            //$parent = $this;
            $controller = Controller::curr();
            $parent = $controller;

            $stack = array( $parent );
            while ($parent = $parent->Parent) {
                array_unshift($stack, $parent);
            }

            return isset($stack[ $level - 1 ]) ? $stack[ $level - 1 ] : null;
        }


        /**
         * Make categories and Subcategories from this
         *
         * @return array;
         */
        public function makeCategoriesAndSubcategriesFromThis( $objectAndDecendents )
        {
            //now make this map into mulidimensional arrays for use of our grouped dropdown.
            $result = array();
            foreach ($objectAndDecendents as $item) {
                $itemsChildren = $item->Children();
                if ($itemsChildren->count()) {
                    $result[ $item->Title ] = array();

                    foreach ($itemsChildren as $itemChild) {
                        $result[ $item->Title ][ $itemChild->ID ] = $itemChild->Title;
                    }
                } else {
                    $result[ $item->ID ] = $item->Title;
                    continue;
                }
            }

            return $result;
        }

        /**
         * checkIfCategoryHasAnyItems
         * Check if this category has any children based on class name  (services, products etc)
         *
         * @return Datalist
         */
        public function checkIfCategoryHasAnyItems( $className )
        {
            return $className::get()->filter("ParentID", $this->owner->getThisCategoryAndItsChildren());
        }


        /**
         * Return "link", "current" or section depending on if the current requesu ID parameter
         *
         * @return string
         */
        public function DataObjectLinkingModeByID()
        {
            $controller = Controller::curr()->request;
            $urlParamID = $controller->param('ID');
            if ($urlParamID) {
                if ($this->owner->ID == $urlParamID)
                    return 'active';

                return 'link';
            }

            return false;
        }


        /**
         * Return "link", "current" or section depending on if this page is the current page if the current classname
         * corresponds to it
         *
         * @return string
         */
        public function DataObjectLinkingModeByClassName( $className )
        {
            if (isset($className)) {
                if (Controller::curr()->data()->ClassName == (string)$className) {
                    return 'active';
                }

                return 'link';
            }

            return false;
        }

        /**
         * Show Search Results IN
         *
         * @return string
         */
        public function getShowSearchResultsIn()
        {
            $req = isset($_REQUEST[ 'search-in' ]) ? $_REQUEST[ 'search-in' ] : false;

            return $req;
        }

        /**
         * Get Show Search Results Link In
         *
         * @return string
         */
        public function getShowSearchResultsLinkIn( $type )
        {
            return HTTP::setGetVar('type', $type);
        }


        /* Get Country code from request */
        public function getCountryCodeFromRequest()
        {
            $countryCode = isset($_REQUEST[ 'country' ]) ? $_REQUEST[ 'country' ] : false;

            if ($countryCode && (strlen($countryCode) == 2))
                return Convert::raw2sql($countryCode);

            return false;
        }


        /* Get Review rating value from request */
        public function getReviewRatingFromRequest()
        {
            $reviewRating = isset($_REQUEST[ 'rating' ]) ? $_REQUEST[ 'rating' ] : false;

            if ($reviewRating)
                return Convert::raw2sql($reviewRating);

            return false;
        }

        /* Get Single By Classname Page
        * Helper function to get a single page based on the pages class name
        **/
        public function getPageByClassName( $classname )
        {
            return $classname::get()->first();
        }


        /*
        * applyRatingQueryFilter for with the ratings extension
        * Used in the business directory filter by rating section
        */
        public function applyRatingQueryFilter( DataList $dataList, $ratingFilterValue = null )
        {

            //set the ratingFilterValue if it is not set
            if (!isset($ratingFilterValue)) {
                $ratingFilterValue = $this->getReviewRatingFromRequest();
            }

            if ($ratingFilterValue) {
                $dataList = $dataList->innerJoin('Review', '"Review"."ParentID" = "' . $dataList->dataClass() . '"."ID"');
                //$dataList = $dataList->where('"Review"."ParentID" = '. "'".$this->owner->ID."'");
                $dataList = $dataList->alterDataQuery(function ( $query ) use ( $ratingFilterValue ) {
                    $query->having('AVG("Review"."Rating") >= ' . "$ratingFilterValue");
                });
                $dataList = $dataList->alterDataQuery(function ( $query ) use ( $dataList ) {
                    $query->groupby('"' . $dataList->dataClass() . '"."ID"');
                });
            }

            return $dataList;
        }

        /**
         * Get This Category And Its Children
         * The categories under this category first (becuase categories can be nested)
         *
         * @return array
         */
        public function getThisCategoryAndItsChildren()
        {
            $theOwner = $this->owner;

            //get the categories under this category first (becuase categories can be nested)
            $categories = $theOwner->getDescendantIDList();

            if ($categories) {
                $categories[] = $theOwner->ID;

                return array_filter($categories);
            } else {
                return array( $theOwner->ID );
            }
        }

        /**
         * Get Blog Tags
         */
        public function SidebarTags( $tagClass )
        {
            $tags = Tag::get()
                ->innerJoin("{$tagClass}_Tags", "\"{$tagClass}_Tags\".\"TagID\" = \"Tag\".\"ID\"");

            return $tags;
        }


        /* Featured  Object (eg Businesses, event, forum post, blog post) */
        public function getFeaturedObjectListings( $className, $limit = 10 )
        {

            $featuredObjects = $className::get()->filter('Featured', true)->limit($limit)->sort('LastEdited', 'DESC');

            return ($featuredObjects ? $featuredObjects : false);
        }

        /* Featured  Objects Listings CacheKey */
        public function getFeaturedObjectListingsCachekey( $className )
        {
            $featuredObjects = $className::get();

            if ($featuredObjects) {
                return implode('_', array(
                    $className,
                    $featuredObjects->count(),
                    $featuredObjects->filter('Featured', true)->max('LastEdited'),
                ));
            }

            return false;
        }

        /* Latest Object Listings  (eg Businesses, event, forum post, blog post) */
        public function getLastestObjectListings( $className, $limit = 6 )
        {
            $listings = $className::get()->filter('Featured', false)->limit($limit)->sort('Created', 'DESC');

            return ($listings ? $listings : false);
        }

        /* Latest Object Listings  (eg Businesses, event, forum post, blog post) */
        public function getLastestObjectListingsCacheKey( $className, $limit = 6 )
        {
            $listings = $className::get();

            if ($listings) {
                return implode('_', array(
                    $className,
                    $listings->Count(),
                    $listings->filter('Featured', false)->max('LastEdited'),
                ));
            }

            return false;
        }

        /* Get Upcoming Events */
        public function getUpcomingObjectListing( $className, $limit = 2 )
        {
            $events = $className::get()->limit($limit)->sort('Created', 'DESC');

            return ($events ? $events : false);
        }


        /* Get Recently concluded Events */
        public function getRecentlyConcludedObjectListing( $className, $limit = 2 )
        {
            $events = $className::get()->limit($limit)->sort('Created', 'DESC');

            return ($events ? $events : false);
        }

        /* Does Any Item of this class exist */
        public function doesAnyItemOfThisClassExist( $className )
        {
            $items = $className::get()->limit(1)->count();

            return ($items ? $items : false);
        }

        /**
         * getCustomEventListFromFunction
         * This function provides an alias to call getRecentlyConcludedEvents, getFeaturedObjectListings
         * and getLastestObjectListings for events on the events page
         */
        public function getCustomEventListFromFunction( $functionName, $number = 2 )
        {
            return $this->owner->$functionName('Event', $number);
        }

        public function URLEncode( $str )
        {
            return urlencode($str);
        }

        //On of our favourite cache keys (instead of using ID, we will just evaluate the server request URI)
        public function URLCacheKey()
        {
            $url = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
            //always add the ?pjax=#main $_GET query parameter and set it to empty as its invalidating our cache
            //when it should not be. This way the request URI will be the same for ajax and non ajax request
            return HTTP::setGetVar('_pjax', '', $url);
        }

        //Timed cache key ()
        // Returns a new number every five minutes ($time)
        public function TimedCacheKey( $time = 5 )
        {
            return (int)(time() / 60 / $time);
        }

        /**
         * Returns the country name (not the 2 character code).
         *
         * @return string
         */
        public function getCountryName()
        {
            return \Zend_Locale::getTranslation($this->owner->Country, 'territory', i18n::get_locale());
        }


        /**
         * Returns true if the current user is an admin, or is the owner of this project item
         *
         * @return Boolean
         */
        public function IsOwner()
        {
            if (Member::currentUserID()) {
                if ($this->owner->OwnerID == Member::currentUserID() || Permission::check('ADMIN')) {
                    return true;
                }

                return false;
            }

            return false;
        }


        //Check if Live Mode
        public function isLive()
        {
            return (Director::isLive());
        }

        //Check if Dev Mode
        public function isDev()
        {
            return (Director::isDev());
        }

        //if Dev Delete Else Dont Delete
        public function IfDevDeleteElseDontDelete()
        {
            if (Director::isDev()) {
                return true;
            }

            return false;
        }

        /**
         * Returns true if this user is an administrator.
         * Administrators have access to everything.
         *
         * @deprecated Use Permission::check('ADMIN') instead
         * @return Returns TRUE if this user is an administrator.
         */
        public function isSiteAdmin()
        {
            return Permission::check('ADMIN');
        }

        /** writeToStateAndPublish
         * Data Object Write for SiteTree object in the frontend
         */
        public function writeToStateAndPublish( SiteTree $object )
        {
            $object->writeToStage('Stage');
            // will copy the saved record information to the `Business_Live` table
            $object->publish('Stage', 'Live');
        }


        /**
         * setRenderPageTemplate
         */
        public function setRenderPageTemplate( $temaplateNameOrDataRecord )
        {
            if (Director::is_ajax()) {
                Requirements::clear();

                return $this->owner->renderWith(array( $temaplateNameOrDataRecord ));
            }

            return $this->owner->renderWith(array( $temaplateNameOrDataRecord, 'Page' ));
        }

        /**
         * getNoLogo
         * This is the default placeholder image
         * for items without a logo image uploaded with them
         */
        public function NoLogo()
        {
            $url = SSViewer::get_theme_folder() . "/images/no-logo.png";

            return "<img src=\"$url\" alt=\"No logo\" />";
        }

        /**
         * getNoImage
         * This is the default placeholder image for
         * items without an image uploaded with them
         */
        public function NoPhoto( $size = null )
        {
            $themeDir = SSViewer::get_theme_folder();
            if ($size && (strtolower($size) == 'big')) {
                $url = $themeDir . "/images/no-photo-big.png";

                return "<img src=\"$url\" alt=\"No photo\" />";
            }
            $url = $themeDir . "/images/no-photo.png";

            return "<img src=\"$url\" alt=\"No photo\" />";
        }
    }