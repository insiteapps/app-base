<?php

namespace InsiteApps\Control;

use SilverStripe\Core\Convert;

class RecordController extends MainController
{
    
    private static $allowed_actions = array();
    
    function Link($action = null)
    {
        return "records/$action";
    }
    
    static function find_link($action = false)
    {
        
        return self::create()->Link($action);
    }
    
    function generate_page_controller($title = "Page")
    {
        $tmpPage = new \Page();
        $tmpPage->Title = $title;
        $tmpPage->URLSegment = strtolower(str_replace(' ', '-', $title));
        // Disable ID-based caching  of the log-in page by making it a random number
        $tmpPage->ID = -1 * rand(1, 10000000);
        
        $controller = \PageController::create($tmpPage);
        //$controller->setDataModel($this->model);
        $controller->init();
        
        return $controller;
    }
    
    function urlParamsID()
    {
        return Convert::raw2sql($this->urlParams['ID']);
    }
    
    /**
     * Converts decimal longitude / latitude to DMS
     * ( Degrees / minutes / seconds )
     *
     * @param $coord
     *
     * @return string
     */
    public static function DECtoDMS($coord)
    {
        $isnorth = $coord >= 0;
        $coord = abs($coord);
        $deg = floor($coord);
        $coord = ($coord - $deg) * 60;
        $min = floor($coord);
        $sec = floor(($coord - $min) * 60);
        
        return sprintf("%d&deg; %d' %d\" %s", $deg, $min, $sec, $isnorth ? 'N' : 'S');
    }
    
}
