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

namespace InsiteApps\DateTime;

use InsiteApps\Common\Manager;
use SilverStripe\Control\HTTPResponse_Exception;

class CalendarManagerToolkit extends Manager
{
    
    public static function breakdown( $ts = null )
    {
        // default to now
        if ( $ts === null ) {
            $ts = self::now();
        }
        
        // gather individual variables
        $H = date( 'H', $ts ); // hour
        $i = date( 'i', $ts ); // minute
        $s = date( 's', $ts ); // second
        $m = date( 'm', $ts ); // month
        $d = date( 'd', $ts ); // day
        $Y = date( 'Y', $ts ); // year
        
        return array(
            $H,
            $i,
            $s,
            $m,
            $d,
            $Y,
        );
    }
    
    /**
     * Returns the current timestamp.
     *
     * @return    timestamp
     *
     * @see        time
     */
    public static function now()
    {
        return time();
    }
    
    /**
     * Retrieve the timestamp from a number of different formats.
     *
     * @param    mixed    value to use for timestamp retrieval
     */
    public static function getTS( $value = null )
    {
        if ( $value === null ) {
            return self::now();
        } else {
            if ( $value instanceof sfDate ) {
                return $value->get();
            } else {
                if ( !is_numeric( $value ) ) {
                    return strtotime( $value );
                } else {
                    if ( is_numeric( $value ) ) {
                        return $value;
                    }
                }
            }
        }
        
        throw new HTTPResponse_Exception( sprintf( 'A timestamp could not be retrieved from the value: %s', $value ) );
    }
}
