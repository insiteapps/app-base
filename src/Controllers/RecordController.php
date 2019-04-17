<?php
/**
 *
 * @copyright (c) 2018 Insite Apps - http://www.insiteapps.co.za
 * @package insiteapps
 * @author Patrick Chitovoro  <patrick@insiteapps.co.za>
 * All rights reserved. No warranty, explicit or implicit, provided.
 *
 * NOTICE:  All information contained herein is, and remains the property of Insite Apps and its suppliers,  if any.
 * The intellectual and technical concepts contained herein are proprietary to Insite Apps and its suppliers and may be covered by South African. and Foreign Patents, patents in process, and are protected by trade secret or copyright laws.
 * Dissemination of this information or reproduction of this material is strictly forbidden unless prior written permission is obtained from Insite Apps.
 * Proprietary and confidential.
 * There is no freedom to use, share or change this file.
 *
 *
 */

namespace InsiteApps\Control;

use SilverStripe\Core\Convert;

class RecordController extends MainController
{
    
    private static $allowed_actions = array();
    
    function Link( $action = null )
    {
        return "records/$action";
    }
    
    static function find_link( $action = false )
    {
        
        return self::create()->Link( $action );
    }
    
    public static function Guid()
    {
        mt_srand((double)microtime() * 10000);
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        return substr($charid, 0, 8) . $hyphen . substr($charid, 8, 4) . $hyphen . substr($charid, 12, 4) . $hyphen . substr($charid, 16, 4) . $hyphen . substr($charid, 20, 12);
    }
    
    function generate_page_controller( $title = "Page" )
    {
        $tmpPage             = new \Page();
        $tmpPage->Title      = $title;
        $tmpPage->URLSegment = strtolower( str_replace( ' ', '-', $title ) );
        // Disable ID-based caching  of the log-in page by making it a random number
        $tmpPage->ID = -1 * rand( 1, 10000000 );
        
        $controller = \PageController::create( $tmpPage );
        //$controller->setDataModel($this->model);
        $controller->init();
        
        return $controller;
    }
    
    function urlParamsID()
    {
        return Convert::raw2sql( $this->urlParams[ 'ID' ] );
    }
    
    /**
     * Converts decimal longitude / latitude to DMS
     * ( Degrees / minutes / seconds )
     *
     * @param $coord
     *
     * @return string
     */
    public static function DECtoDMS( $coord )
    {
        $isnorth = $coord >= 0;
        $coord   = abs( $coord );
        $deg     = floor( $coord );
        $coord   = ( $coord - $deg ) * 60;
        $min     = floor( $coord );
        $sec     = floor( ( $coord - $min ) * 60 );
        
        return sprintf( "%d&deg; %d' %d\" %s", $deg, $min, $sec, $isnorth ? 'N' : 'S' );
    }
    
}
