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

namespace InsiteApps\AppBase;


use InsiteApps\Control\ContentController;
use SilverStripe\Assets\Image;
use SilverStripe\Core\Config\Config;
use SilverStripe\Dev\Debug;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\Subsites\Model\Subsite;
use SilverStripe\View\Requirements;
use SilverStripe\View\SSViewer;
use SilverStripe\View\ThemeResourceLoader;

class PageExtension extends DataExtension
{
    
    private static $db      = array( "CustomTitle" => "Varchar(255)" );
    private static $casting = [
        'PageSummary' => 'HTMLText',
        'Image'       => Image::class,
    ];
    
    
    public function updateCMSFields( FieldList $fields )
    {
        $fields->addFieldToTab( 'Root.Main', TextField::create( "CustomTitle" ), "Content" );
        
    }
    
    public function ThemeDir()
    {
        if ( class_exists( Subsite::class ) ) {
            if ( $subsite = Subsite::currentSubsite() ) {
                if ( $theme = $subsite->Theme ) {
                    SSViewer::set_themes( [
                        $theme,
                        SSViewer::DEFAULT_THEME,
                    ] );
                }
            }
            
        }
        $loader = ThemeResourceLoader::inst();
        $themes = SSViewer::get_themes();
        $paths  = $loader->getThemePaths( $themes );
        
        //Debug::show( $paths );
        
        return $paths[ 0 ];
        
    }
    
    public function CustomPageTitle()
    {
        if ( $ttl = $this->owner->CustomTitle ) {
            return $ttl;
        }
        
        return $this->owner->Title;
    }
    
    public function PageSummary( $len = 150 )
    {
        $aContent    = array(
            $this->owner->Summary,
            $this->owner->Excerpt,
            $this->owner->Content,
        );
        $aContent    = array_filter( $aContent );
        $raw_content = reset( $aContent );
        
        $content_strip = preg_replace( "/<img[^>]+\>/i", " ", $raw_content );
        $content       = preg_replace( "/<p[^>]*>[\s|&nbsp;]*<\/p>/", '', $content_strip );
        $html          = ContentController::truncate( str_replace( [
            "<p></p>",
            "<p> </p>",
        ], "", $content ), $len );
        
        /*
        if ($html->value) {
            return $html->value;
        }
        */
        
        return $html;
        
        
    }
    
    public function Image()
    {
        
        if ( $this->owner->HeroImageID ) {
            return $this->HeroImage();
        }
        
        if ( count( $this->owner->Images() ) ) {
            $oImage = $this->owner->Images()->first();
            
            return $oImage->Image();
        }
        
        return false;
    }
}

class PageControllerExtension extends DataExtension
{
    
    public function onAfterInit()
    {
        // Requirements::insertHeadTags( '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.1.0/css/all.css" integrity="sha384-lKuwvrZot6UHsBSfcMvOkWwlCMgc0TaWr+30HWe3a4ltaBwTZhyTEggF5tJv8tbt" crossorigin="anonymous">' );
        
    }
    
}