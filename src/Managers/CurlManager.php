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

namespace InsiteApps\Data\Curl;

use InsiteApps\Common\Manager;

class CurlManager extends Manager
{
    
    /**
     * @param       $url
     * @param null  $postFields
     * @param array $aHeaders
     *
     * @return mixed
     */
    public function processCurl( $url, $postFields = null, array $aHeaders = array() )
    {
        ob_start();
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30 );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $aHeaders );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $results = json_decode( curl_exec( $ch ), true );
        curl_close( $ch );
        
        return $results;
    }
    
    /**
     * @param       $url
     * @param array $aHeaders
     *
     * @return mixed
     */
    public function processCurlWithHeaders( $url, array $aHeaders = array() )
    {
        ob_start();
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 30 ); //30 seconds
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $aHeaders );
        curl_setopt( $ch, CURLOPT_HEADER, false );
        curl_setopt( $ch, CURLOPT_NOBODY, false );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $return = curl_exec( $ch );
        curl_close( $ch );
        
        return json_decode( $return, true );
    }
} 
