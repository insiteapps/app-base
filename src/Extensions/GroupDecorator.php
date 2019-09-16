<?php

/*
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Forms\FieldList;
*/

namespace InsiteApps\Extensions {
    
    use SilverStripe\Forms\CheckboxField;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\TreeDropdownField;
    use SilverStripe\ORM\DataExtension;
    
    class GroupDecorator extends DataExtension
    {
        
        private static $db      = array(
            'GoToAdmin' => 'Boolean',
        );
        private static $has_one = array(
            'LinkPage' => 'SiteTree',
        );
        
        public function updateCMSFields( FieldList $fields )
        {
            
            $fields->addFieldToTab( 'Root.Members', new CheckboxField( 'GoToAdmin', ' Go to Admin area' ), 'Members' );
            $fields->addFieldToTab( 'Root.Members', new TreeDropdownField( 'LinkPageID', 'Or select a Page to redirect to', 'SiteTree' ), 'Members' );
        }
        
    }
}