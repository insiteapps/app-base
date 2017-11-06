<?php
/**
 *
 * Copyright (c) 2017 Insite Apps - http://www.insiteapps.co.za
 * All rights reserved.
 * @package insiteapps
 * @author Patrick Chitovoro  <patrick@insiteapps.co.za>
 * Redistribution and use in source and binary forms, with or without modification, are NOT permitted at all.
 * There is no freedom to share or change it this file.
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
class DatabaseQueryTimer {

    private $timer;

    public function __construct() {
        $this->timer = microtime(true);
    }

    function StartTimer($what = '') {
        $this->timer = 0; //global variable to store time       
        echo '<p style="border:1px solid black; color: black; background: yellow;">';
        echo " Running <i>$what</i>. ";
        flush(); //output this to the browser       
        list ($usec, $sec) = explode(' ', microtime());
        $this->timer = ((float) $usec + (float) $sec); //set the timer
    }

    function StopTimer() {
        if ($this->timer > 0) {
            list ($usec, $sec) = explode(' ', microtime()); //get the current time
            $this->timer = ((float) $usec + (float) $sec) - $this->timer; //the time taken in milliseconds
            echo ' Took ' . number_format($this->timer, 4) . ' seconds.</p>';
            flush();
        }
    }

}
