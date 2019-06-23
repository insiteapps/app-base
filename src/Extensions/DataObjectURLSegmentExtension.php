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

/*
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\FieldList;
*/

namespace InsiteApps\ORM;

use SilverStripe\CMS\Forms\SiteTreeURLSegmentField;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Director;
use SilverStripe\Core\Config\Config;
use SilverStripe\Core\Convert;
use SilverStripe\Dev\Debug;
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
        'Link'         => 'Text',
        'RelativeLink' => 'Text',
        'AbsoluteLink' => 'Text',
        'TreeTitle'    => 'HTMLText',
    );
    
    public function updateCMSFields( FieldList $fields )
    {
        $baseLink = $this->owner->BaseLink();
        if ( !empty( $baseLink ) ) {
            $urlSegment = SiteTreeURLSegmentField::create( 'URLSegment' )->setURLPrefix( $baseLink );
            
            $fields->addFieldToTab( "Root.Main", $urlSegment );
        } else {
            $fields->addFieldToTab( "Root.Main", ReadonlyField::create( "URLSegment" ) );
        }
        
        
    }
    
    public function AbsoluteLink()
    {
        return Director::absoluteURL( $this->owner->Link() );
    }
    
    
    public function MenuTitle()
    {
        return $this->owner->getField( "Title" );
    }
    
    public function onBeforeWrite()
    {
        $aFields        = array(
            'Name',
            'Title',
        );
        $aChangedFields = $this->owner->getChangedFields( true, 2 );
        if ( count( $aChangedFields ) ) {
            $aChanged = array_intersect( array_keys( $aChangedFields ), $aFields );
            if ( count( $aChanged ) ) {
                $this->owner->URLSegment = $this->generateUniqueURLSegment( $this->owner->Title );
                $this->owner->URLSegment = $this->generateUniqueURLSegment( $this->owner->Title );
            }
        }
        $name                    = $this->owner->Title ? : $this->owner->Name;
        $this->owner->URLSegment = $this->generateUniqueURLSegment( $name );
        //if ( !$this->owner->URLSegment ) {
        
        //}
        
        parent::onBeforeWrite();
    }
    
    /*
    * Generate Unique URLSegment
    */
    public function generateUniqueURLSegment( $title )
    {
        $URLSegment     = singleton( SiteTree::class )->generateURLSegment( $title );
        $prevurlsegment = $URLSegment;
        $i              = 1;
        while ( !$this->validURLSegment( $URLSegment ) ) {
            $URLSegment = $prevurlsegment . "-" . $i;
            $i++;
        }
        
        return $URLSegment;
        
    }
    
    public function validURLSegment( $URLSegment )
    {
        $existingPage = $this->owner->get()->filter( array(
            'URLSegment' => $URLSegment,
        ) )->exclude( array(
            'ID' => $this->owner->ID,
        ) )->first();
        if ( $existingPage ) {
            return false;
        }
        
        return true;
    }
}
