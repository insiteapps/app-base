<?php

namespace InsiteApps\Security;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataExtension;


class GroupLoginExtension extends DataExtension
{
    
    static $db = array(
        "GoToAdmin" => "Boolean",
    );
    
    static $has_one = array(
        "LinkPage" => "SiteTree",
    );
    
    public function updateCMSFields( FieldList $fields )
    {
        $fields->addFieldsToTab( "Root.Members(after login)", [
            CheckboxField::create( "GoToAdmin", " Go to Admin area" ),
            TreeDropdownField::create( "LinkPageID", "Or select a Page to redirect to", "SiteTree" ),
        ], 'Members' );
        
    }
    
}
