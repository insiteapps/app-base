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

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DatabaseQueryTimer
 *
 * @author User
 */
class DatabaseQueryTimer
{
    
    private $timer;
    
    public function __construct()
    {
        $this->timer = microtime( true );
    }
    
    function StartTimer( $what = '' )
    {
        $this->timer = 0; //global variable to store time       
        echo '<p style="border:1px solid black; color: black; background: yellow;">';
        echo " Running <i>$what</i>. ";
        flush(); //output this to the browser       
        list ( $usec, $sec ) = explode( ' ', microtime() );
        $this->timer = ( (float) $usec + (float) $sec ); //set the timer
    }
    
    function StopTimer()
    {
        if ( $this->timer > 0 ) {
            list ( $usec, $sec ) = explode( ' ', microtime() ); //get the current time
            $this->timer = ( (float) $usec + (float) $sec ) - $this->timer; //the time taken in milliseconds
            echo ' Took ' . number_format( $this->timer, 4 ) . ' seconds.</p>';
            flush();
        }
    }
    
}
