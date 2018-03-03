<?php

/**
 *
 * @copyright (c) 2017 Insite Apps - http://www.insiteapps.co.za
 * @package       insiteapps
 * @author        Patrick Chitovoro  <patrick@insiteapps.co.za>
 * All rights reserved. No warranty, explicit or implicit, provided.
 *
 * NOTICE:  All information contained herein is, and remains the property of Insite Apps and its suppliers,  if
 *     any.
 * The intellectual and technical concepts contained herein are proprietary to Insite Apps and its suppliers and
 *     may be covered by South African. and Foreign Patents, patents in process, and are protected by trade secret
 *     or copyright law. Dissemination of this information or reproduction of this material is strictly forbidden
 *     unless prior written permission is obtained from Insite Apps.
 *
 * There is no freedom to use, share or change this file.
 *
 *
 */

namespace InsiteApps\AppBase;

use SilverStripe\Core\Convert;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBField;

class ContactDetail extends DataObject
{
    private static $table_name   = 'ContactDetail';
    private static $default_sort = 'SortOrder';
    
    private static $db = array(
        'Name'      => 'Varchar(255)',
        "Type"      => 'Varchar(255)',
        'Content'   => 'Varchar(255)',
        'SortOrder' => 'Int',
    );
    
    private static $has_one = array(
        "Page" => "Page",
    );
    private static $casting = array(
        "Tag" => "HTMLText",
    );
    
    public function getCMSFields()
    {
        $f = parent::getCMSFields();
        $f->removeByName( array(
            "SortOrder",
            "PageID",
        ) );
        $f->addFieldToTab( "Root.Main", DropdownField::create( "Type" )->setSource( self::availableTypes() )
                                                     ->setEmptyString( '----' ), "Name" );
        
        
        return $f;
    }
    
    private function availableTypes()
    {
        $names = array(
            "Telephone",
            "Cellphone",
            "Fax",
            "Email",
            "WebLink",
            "Time",
        );
        
        $usedNames   = [];
        $field_names = array_diff( $names, $usedNames );
        $aNames      = array_combine( array_values( $field_names ), array_values( $field_names ) );
        if ( $this->ID ) {
            $aNames[ $this->Type ] = $this->Type;
        }
        
        return $aNames;
    }
    
    private static $summary_fields = array(
        'Name',
        'Content',
        'Type',
    );
    
    public function getTag()
    {
        $link = "";
        switch ( $this->Type ) {
            case "Email";
                $link = sprintf( "<a target='_blank' href=\"mailto:%s\">%s</a>", $this->Content, $this->Name );
                break;
            case "WebLink":
                $link = sprintf( "<a target='_blank' href=\"%s\">%s</a>", $this->Content, $this->Name );
                break;
            default:
                $link = $this->Content;
                break;
        }
        
        return $link;
        
    }
    
    public function TypeIcon()
    {
        switch ( $this->Type ) {
            case "Email":
                $icon = "envelope";
                break;
            case "Telephone":
                $icon = "phone";
                break;
            case "Cellphone":
                $icon = "mobile";
                break;
            case "Time":
                $icon = "clock-o";
                break;
            case "WebLink":
                $icon = "globe";
                break;
            default:
                $icon = "cog";
                break;
        }
        
        return $icon;
    }
}
