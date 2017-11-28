<?php

    /*
    use SilverStripe\ORM\DataExtension;
    use SilverStripe\Forms\ReadonlyField;
    use SilverStripe\Control\Director;
    use SilverStripe\Core\Convert;
    use SilverStripe\Core\Config\Config;
    use SilverStripe\Forms\FieldList;
    */

    namespace InsiteApps\ORM;

    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\Control\Director;
    use SilverStripe\Core\Config\Config;
    use SilverStripe\Core\Convert;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\ReadonlyField;
    use SilverStripe\ORM\DataExtension;

    class DataObjectURLSegmentExtension extends DataExtension
    {

        private static $db = array(
            'URLSegment' => 'Varchar(200)',
        );

        private static $indexes = array(
            "URLSegment" => true,
        );

        private static $casting = array(
            "Breadcrumbs"  => "HTMLText",
            "LastEdited"   => "SS_Datetime",
            "Created"      => "SS_Datetime",
            'Link'         => 'Text',
            'RelativeLink' => 'Text',
            'AbsoluteLink' => 'Text',
            'TreeTitle'    => 'HTMLText',
        );

        /**
         * @param \SilverStripe\Forms\FieldList $fields
         */
        public function updateCMSFields( FieldList $fields )
        {
            $fields->addFieldToTab("Root.Main", ReadonlyField::create("URLSegment"));

        }

        public function AbsoluteLink()
        {
            return Director::absoluteURL($this->owner->Link());
        }


        public function MenuTitle()
        {
            return $this->owner->getField("Title");
        }


        /**
         * Return the title, description, keywords and language metatags.
         *
         * @todo Move <title> tag in separate getter for easier customization and more obvious usage
         *
         * @param boolean|string $includeTitle Show default <title>-tag, set to false for custom templating
         *
         * @return string The XHTML metatags
         */
        public function MetaTags( $includeTitle = true )
        {
            $tags = "";
            if ($includeTitle === true || $includeTitle == 'true') {
                $tags .= "<title>" . Convert::raw2xml($this->owner->Title) . "</title>\n";
            }

            $generator = trim(Config::inst()->get('SiteTree', 'meta_generator'));
            if (!empty($generator)) {
                $tags .= "<meta name=\"generator\" content=\"" . Convert::raw2att($generator) . "\" />\n";
            }

            $charset = Config::inst()->get('ContentNegotiator', 'encoding');
            $tags .= "<meta http-equiv=\"Content-type\" content=\"text/html; charset=$charset\" />\n";
            if ($this->owner->MetaDescription) {
                $tags .= "<meta name=\"description\" content=\"" . Convert::raw2att($this->owner->MetaDescription) . "\" />\n";
            }
            if ($this->owner->ExtraMeta) {
                $tags .= $this->owner->ExtraMeta . "\n";
            }

            return $tags;
        }


        public function onBeforeWrite()
        {
            // If there is no URLSegment set, generate one from Title
            if ((!$this->owner->URLSegment && $this->owner->Title)) {
                $this->owner->URLSegment = $this->generateUniqueURLSegment($this->owner->Title);
            }
            parent::onBeforeWrite();
        }

        /*
        * Generate Unique URLSegment
        */
        public function generateUniqueURLSegment( $title )
        {
            $URLSegment = singleton(SiteTree::class)->generateURLSegment($title);
            $prevurlsegment = $URLSegment;
            $i = 1;
            while (!$this->validURLSegment($URLSegment)) {
                $URLSegment = $prevurlsegment . "-" . $i;
                $i++;
            }

            return $URLSegment;

        }

        public function validURLSegment( $URLSegment )
        {
            $existingPage = $this->owner->get()->filter(array(
                'URLSegment' => $URLSegment,
            ))->exclude(array(
                'ID' => $this->owner->ID,
            ))->first();
            if ($existingPage) {
                return false;
            }

            return true;
        }
    }
