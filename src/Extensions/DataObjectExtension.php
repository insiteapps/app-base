<?php
/**
 *
 * @copyright (c) 2018 Insite Apps - http://www.insiteapps.co.za
 * @package       insiteapps
 * @author        Patrick Chitovoro  <patrick@insiteapps.co.za>
 * All rights reserved. No warranty, explicit or implicit, provided.
 *
 * NOTICE:  All information contained herein is, and remains the property of Insite Apps and its suppliers,  if any.
 * The intellectual and technical concepts contained herein are proprietary to Insite Apps and its suppliers and may be
 * covered by South African. and Foreign Patents, patents in process, and are protected by trade secret or copyright
 * laws. Dissemination of this information or reproduction of this material is strictly forbidden unless prior written
 * permission is obtained from Insite Apps. Proprietary and confidential. There is no freedom to use, share or change
 * this file.
 *
 *
 */

namespace InsiteApps\ORM {
    
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Control\Controller;
    use SilverStripe\Control\Director;
    use SilverStripe\Control\HTTP;
    use SilverStripe\Core\Convert;
    use SilverStripe\i18n\i18n;
    use SilverStripe\ORM\ArrayList;
    use SilverStripe\ORM\DataExtension;
    use SilverStripe\ORM\DataList;
    use SilverStripe\ORM\FieldType\DBField;
    use SilverStripe\Security\Permission;
    use SilverStripe\Security\Security;
    use SilverStripe\View\Requirements;
    use SilverStripe\View\SSViewer;
    
    class DataObjectExtension extends DataExtension
    {
        public function GoogleAPIKey()
        {
            return GOOGLE_MAP_API_KEY;
        }
        
        public function Summary( $len = 150 )
        {
            
            $aContent = array(
                $this->owner->Content,
                $this->owner->Description,
            );
            
            $aContent    = array_filter( $aContent );
            $raw_content = reset( $aContent );
            
            $content_strip = preg_replace( "/<img[^>]+\>/i", " ", $raw_content );
            $content       = preg_replace( "/<p[^>]*>[\s|&nbsp;]*<\/p>/", '', $content_strip );
            $html          = static::truncate( str_replace( [
                '<p></p>',
                '<p> </p>',
            ], '', $content ), $len );
            if ( $html->value ) {
                return $html->value;
            }
            
            return $html;
            
            
        }
        
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
            if ( ( $class ) ) {
                return 'animated ' . $class;
            }
            
            $classList       = array(
                'bounce',
                'bounceIn',
                'bounceInDown',
                'bounceInLeft',
                'bounceInRight',
                'bounceInUp',
                'fadeIn',
                'fadeInDown',
                'fadeInLeft',
                'fadeInRight',
                'fadeInUp',
                'slideDown',
                'slideInLeft',
                'slideInRight',
            );
            $randomClassList = array_rand( $classList, 1 );
            $randomClassList = array( $classList[ $randomClassList ] );
            $randomClassList = $randomClassList[ 0 ];
            
            return 'animated ' . $randomClassList;
            
        }
        
        public function Lowercase( $string )
        {
            return strtolower( $string );
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
            if ( $level == 1 ) {
                $result = SiteTree::get()->filter( array(
                    "ShowInMenus" => 1,
                    "ParentID"    => 0,
                ) );
                
            } else {
                //custom
                $controller = Controller::curr();
                //end custom
                //$parent = $this->data();
                $parent = $controller->data();
                $stack  = array( $parent );
                
                if ( $parent ) {
                    while ( $parent = $parent->Parent ) {
                        array_unshift( $stack, $parent );
                    }
                }
                
                if ( isset( $stack[ $level - 2 ] ) ) {
                    $result = $stack[ $level - 2 ]->Children();
                }
            }
            
            $visible = array();
            
            // Remove all entries the can not be viewed by the current user
            // We might need to create a show in menu permission
            if ( isset( $result ) ) {
                foreach ( $result as $page ) {
                    if ( $page->canView() ) {
                        $visible[] = $page;
                    }
                }
            }
            
            return new ArrayList( $visible );
        }
        
        public function Menu( $level )
        {
            return $this->getMenu( $level );
        }
        
        
        /**
         * Return "link", "current" or section depending on if this page is the current page, or not on the current
         * page but in the current section.
         *
         * @return string
         */
        public function LinkingMode()
        {
            if ( $this->owner->isCurrent() ) {
                return 'active';
            } elseif ( $this->owner->isSection() ) {
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
            return $this->owner->isCurrent() || ( Director::get_current_page() instanceof SiteTree && in_array( $this->owner->ID, Director::get_current_page()
                                                                                                                                          ->getAncestors()
                                                                                                                                          ->column() ) );
        }
        
        /**
         * Returns the page in the current page stack of the given level.
         * Level(1) will return the main menu item that we're currently inside, etc.
         */
        public function Level( $level )
        {
            //$parent = $this;
            $controller = Controller::curr();
            $parent     = $controller;
            
            $stack = array( $parent );
            while ( $parent = $parent->Parent ) {
                array_unshift( $stack, $parent );
            }
            
            return isset( $stack[ $level - 1 ] ) ? $stack[ $level - 1 ] : null;
        }
        
        
        /**
         * Return "link", "current" or section depending on if the current requesu ID parameter
         *
         * @return string
         */
        public function DataObjectLinkingModeByID()
        {
            $controller = Controller::curr()->request;
            $urlParamID = $controller->param( 'ID' );
            if ( $urlParamID ) {
                if ( $this->owner->ID == $urlParamID ) {
                    return 'active';
                }
                
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
            if ( isset( $className ) ) {
                if ( Controller::curr()->data()->ClassName == (string) $className ) {
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
            $req = isset( $_REQUEST[ 'search-in' ] ) ? $_REQUEST[ 'search-in' ] : false;
            
            return $req;
        }
        
        /**
         * Get Show Search Results Link In
         *
         * @return string
         */
        public function getShowSearchResultsLinkIn( $type )
        {
            return HTTP::setGetVar( 'type', $type );
        }
        
        
        /* Get Country code from request */
        public function getCountryCodeFromRequest()
        {
            $countryCode = isset( $_REQUEST[ 'country' ] ) ? $_REQUEST[ 'country' ] : false;
            
            if ( $countryCode && ( strlen( $countryCode ) == 2 ) ) {
                return Convert::raw2sql( $countryCode );
            }
            
            return false;
        }
        
        
        /* Get Review rating value from request */
        public function getReviewRatingFromRequest()
        {
            $reviewRating = isset( $_REQUEST[ 'rating' ] ) ? $_REQUEST[ 'rating' ] : false;
            
            if ( $reviewRating ) {
                return Convert::raw2sql( $reviewRating );
            }
            
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
            if ( !isset( $ratingFilterValue ) ) {
                $ratingFilterValue = $this->getReviewRatingFromRequest();
            }
            
            if ( $ratingFilterValue ) {
                $dataList = $dataList->innerJoin( 'Review', '"Review"."ParentID" = "' . $dataList->dataClass() . '"."ID"' );
                //$dataList = $dataList->where('"Review"."ParentID" = '. "'".$this->owner->ID."'");
                $dataList = $dataList->alterDataQuery( function ( $query ) use ( $ratingFilterValue ) {
                    $query->having( 'AVG("Review"."Rating") >= ' . "$ratingFilterValue" );
                } );
                $dataList = $dataList->alterDataQuery( function ( $query ) use ( $dataList ) {
                    $query->groupby( '"' . $dataList->dataClass() . '"."ID"' );
                } );
            }
            
            return $dataList;
        }
        
        
        public function URLEncode( $str )
        {
            return urlencode( $str );
        }
        
        //On of our favourite cache keys (instead of using ID, we will just evaluate the server request URI)
        public function URLCacheKey()
        {
            $url = $_SERVER[ 'HTTP_HOST' ] . $_SERVER[ 'REQUEST_URI' ];
            //always add the ?pjax=#main $_GET query parameter and set it to empty as its invalidating our cache
            //when it should not be. This way the request URI will be the same for ajax and non ajax request
            return HTTP::setGetVar( '_pjax', '', $url );
        }
        
        //Timed cache key ()
        // Returns a new number every five minutes ($time)
        public function TimedCacheKey( $time = 5 )
        {
            return (int) ( time() / 60 / $time );
        }
        
        /**
         * Returns the country name (not the 2 character code).
         *
         * @return string
         */
        public function getCountryName()
        {
            return \Zend_Locale::getTranslation( $this->owner->Country, 'territory', i18n::get_locale() );
        }
        
        
        /**
         * Returns true if the current user is an admin, or is the owner of this project item
         *
         * @return Boolean
         */
        public function IsOwner()
        {
            if ( Security::getCurrentUser() ) {
                if ( $this->owner->OwnerID === Security::getCurrentUser()->ID || Permission::check( 'ADMIN' ) ) {
                    return true;
                }
                
                return false;
            }
            
            return false;
        }
        
        
        //Check if Live Mode
        public function isLive()
        {
            return ( Director::isLive() );
        }
        
        //Check if Dev Mode
        public function isDev()
        {
            return ( Director::isDev() );
        }
        
        //if Dev Delete Else Dont Delete
        public function IfDevDeleteElseDontDelete()
        {
            if ( Director::isDev() ) {
                return true;
            }
            
            return false;
        }
        
        /**
         * Returns true if this user is an administrator.
         * Administrators have access to everything.
         *
         * @return bool|int
         */
        public function isAdmin()
        {
            return Permission::check( 'ADMIN' );
        }
        
        /** writeToStateAndPublish
         * Data Object Write for SiteTree object in the frontend
         */
        public function writeToStateAndPublish( SiteTree $object )
        {
            $object->writeToStage( 'Stage' );
            // will copy the saved record information to the `Business_Live` table
            $object->publish( 'Stage', 'Live' );
        }
        
        
        /**
         * setRenderPageTemplate
         */
        public function setRenderPageTemplate( $temaplateNameOrDataRecord )
        {
            if ( Director::is_ajax() ) {
                Requirements::clear();
                
                return $this->owner->renderWith( array( $temaplateNameOrDataRecord ) );
            }
            
            return $this->owner->renderWith( array(
                $temaplateNameOrDataRecord,
                'Page',
            ) );
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
            if ( $size && ( strtolower( $size ) == 'big' ) ) {
                $url = $themeDir . "/images/no-photo-big.png";
                
                return "<img src=\"$url\" alt=\"No photo\" />";
            }
            $url = $themeDir . "/images/no-photo.png";
            
            return "<img src=\"$url\" alt=\"No photo\" />";
        }
        
        
        public function getGUID(){
            mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45);// "-"
            $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
            return $uuid;
        }
        
        public static function truncate( $text, $length = "50", $ending = '...', $exact = false, $considerHtml = true )
        {
            if ( $considerHtml ) {
                // if the plain text is shorter than the maximum length, return the whole text
                if ( strlen( preg_replace( '/<.*?>/', '', $text ) ) <= $length ) {
                    return $text;
                }
                // splits all html-tags to scanable lines
                preg_match_all( '/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER );
                $total_length = strlen( $ending );
                $open_tags    = array();
                $truncate     = '';
                foreach ( $lines as $line_matchings ) {
                    // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                    if ( !empty( $line_matchings[ 1 ] ) ) {
                        // if it's an "empty element" with or without xhtml-conform closing slash
                        if ( preg_match( '/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[ 1 ] ) ) {
                            // do nothing
                            // if tag is a closing tag
                        } else {
                            if ( preg_match( '/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[ 1 ], $tag_matchings ) ) {
                                // delete tag from $open_tags list
                                $pos = array_search( $tag_matchings[ 1 ], $open_tags );
                                if ( $pos !== false ) {
                                    unset( $open_tags[ $pos ] );
                                }
                                // if tag is an opening tag
                            } else {
                                if ( preg_match( '/^<\s*([^\s>!]+).*?>$/s', $line_matchings[ 1 ], $tag_matchings ) ) {
                                    // add tag to the beginning of $open_tags list
                                    array_unshift( $open_tags, strtolower( $tag_matchings[ 1 ] ) );
                                }
                            }
                        }
                        // add html-tag to $truncate'd text
                        $truncate .= $line_matchings[ 1 ];
                    }
                    // calculate the length of the plain text part of the line; handle entities as one character
                    $content_length = strlen( preg_replace( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[ 2 ] ) );
                    if ( $total_length + $content_length > $length ) {
                        // the number of characters which are left
                        $left            = $length - $total_length;
                        $entities_length = 0;
                        // search for html entities
                        if ( preg_match_all( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[ 2 ], $entities, PREG_OFFSET_CAPTURE ) ) {
                            // calculate the real length of all entities in the legal range
                            foreach ( $entities[ 0 ] as $entity ) {
                                if ( $entity[ 1 ] + 1 - $entities_length <= $left ) {
                                    $left--;
                                    $entities_length += strlen( $entity[ 0 ] );
                                } else {
                                    // no more characters left
                                    break;
                                }
                            }
                        }
                        $truncate .= substr( $line_matchings[ 2 ], 0, $left + $entities_length );
                        // maximum lenght is reached, so get off the loop
                        break;
                    } else {
                        $truncate     .= $line_matchings[ 2 ];
                        $total_length += $content_length;
                    }
                    // if the maximum length is reached, get off the loop
                    if ( $total_length >= $length ) {
                        break;
                    }
                }
            } else {
                if ( strlen( $text ) <= $length ) {
                    return $text;
                } else {
                    $truncate = substr( $text, 0, $length - strlen( $ending ) );
                }
            }
            // if the words shouldn't be cut in the middle...
            if ( !$exact ) {
                // ...search the last occurance of a space...
                $spacepos = strrpos( $truncate, ' ' );
                if ( isset( $spacepos ) ) {
                    // ...and cut the text in this position
                    $truncate = substr( $truncate, 0, $spacepos );
                }
            }
            // add the defined ending to the text
            $truncate .= $ending;
            if ( $considerHtml ) {
                // close all unclosed html-tags
                foreach ( $open_tags as $tag ) {
                    $truncate .= '</' . $tag . '>';
                }
            }
            return DBField::create_field( 'HTMLText', $truncate );
        }
    }
}