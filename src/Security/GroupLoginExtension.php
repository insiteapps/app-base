<?php

namespace InsiteApps\Security;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataExtension;


class GroupLoginExtension extends DataExtension
{
    
    private static $db = array(
        "GoToAdmin" => "Boolean",
    );
    
    private static $has_one = array(
        "LinkPage" => "SiteTree",
    );
    
    public function updateCMSFields( FieldList $fields )
    {
        
        $fields->addFieldsToTab( "Root.Members(after login)", [
            CheckboxField::create( "GoToAdmin", " Go to Admin area" ),
            TreeDropdownField::create( "LinkPageID", "Or select a Page to redirect to", SiteTree::class ),
        ] );
        
    }
    
}
