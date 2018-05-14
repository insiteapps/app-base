<?php
/**
 *
 * @copyright (c) 2017 Insite Apps - http://www.insiteapps.co.za
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
use SilverStripe\Core\Convert;
use SilverStripe\CMS\Model\SiteTree;
 */

namespace InsiteApps\Maps;

use InsiteApps\Listings\AbstractListing;
use InsiteApps\Listings\Business\Listing;
use SilverStripe\Control\Controller;
use SilverStripe\Core\Convert;
use SilverStripe\Dev\Debug;

class MapMarkersXML extends Controller
{
    
    function Link( $action = null )
    {
        return "markers/$action";
    }
    
    private static $allowed_actions = array(
        'xml',
        'sitetreexml',
    );
    
    private static $skip = array( 'url' );
    
    function xml()
    {
        $this->getResponse()->addHeader( "Content-type", "text/xml" );
        $pageId = Convert::raw2xml( $this->urlParams[ 'ID' ] );
        // $page = SiteTree::get()->byID($pageId);
        $oListings = AbstractListing::get();
        
        $tag = "<marker name=\"%s\" address=\"%s\" lat=\"%s\" lng=\"%s\" class=\"%s\"  />\n";
        $xml = "<markers>\n";
        //$xml .= sprintf($tag, Convert::raw2xml($page->Title), Convert::raw2xml($page->Address), $page->Latitude, $page->Longitude, $page->ClassName);
        if ( $oListings ) {
            foreach ( $oListings as $oListing ) {
                $name = Convert::raw2xml( $oListing->Name );
                $xml  .= sprintf( $tag, $name, $name . "-" . $oListing->ID,//  Convert::raw2xml($oListings->Address()),
                    $oListing->Latitude, $oListing->Longitude, $oListing->ClassName );
            }
        }
        $xml .= "</markers>";
        return $xml;
        
    }
    
    /**
     * @param $name
     * @param $address
     * @param $lat
     * @param $lng
     * @param $class
     *
     * @return string
     */
    protected function tag( $name, $address, $lat, $lng, $class )
    {
        return "<marker name=\"{$name}\" address=\"{$address}\" lat=\"{$lat}\" lng=\"{$lng}\" class=\"{$class}\"  />\n";
    }
    
}
