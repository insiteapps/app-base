<?php
/**
 *
 * Copyright (c) 2017 Insite Apps - http://www.insiteapps.co.za
 * All rights reserved.
 *
 * @package insiteapps
 * @author  Patrick Chitovoro  <patrick@insiteapps.co.za>
 * Redistribution and use in source and binary forms, with or without modification, are NOT permitted at all.
 * There is no freedom to share or change it this file.
 *
 *
 */

namespace InsiteApps\DateTime;

use SilverStripe\Control\HTTPResponse_Exception;

class CalendarDateManager extends CalendarTimeManager
{
    
    
    /**
     * The timestamp for this sfDate instance.
     */
    private $ts = null;
    
    /**
     * The original timestamp for this sfDate instance.
     */
    private $init = null;
    
    /**
     * Retrieves a new instance of this class.
     *
     * NOTE: This is not the singleton pattern. Instead, it is for chainability ease-of-use.
     *
     * <b>Example:</b>
     * <code>
     *   echo sfDate::getInstance()->getFirstDayOfWeek()->addDay()->format('Y-m-d');
     * </code>
     *
     * @param    mixed    timestamp, string, or sfDate object
     *
     * @return    sfDate
     */
    public static function getInstance( $value = null )
    {
        return new self( $value );
    }
    
    /**
     * Construct an sfDate object.
     *
     * @param    mixed    timestamp, string, or sfDate object
     */
    public function __construct( $value = null )
    {
        $this->set( $value );
    }
    
    /**
     * Format the date according to the <code>date</code> function.
     *
     * @return    string
     */
    public function format( $format )
    {
        return date( $format, $this->ts );
    }
    
    /**
     * Formats the date according to the <code>format_date</code> helper of the Date helper group.
     *
     * @return    string
     */
    public function date( $format = 'd' )
    {
        return date( 'Y-m-d', $this->ts );
    }
    
    /**
     * Formats the date according to the <code>format_datetime</code> helper of the Date helper group.
     *
     * @return    string
     */
    public function datetime( $format = 'F' )
    {
        return date( 'Y-m-d H:i:s', $this->ts );
    }
    
    /**
     * Format the date as a datetime value.
     *
     * @return    string
     */
    public function dump()
    {
        return date( 'Y-m-d H:i:s', $this->ts );
    }
    
    /**
     * Retrieves the given unit of time from the timestamp.
     *
     * @param    int    unit of time (accepts sfTime constants).
     *
     * @return    int    the unit of time
     *
     * @throws    sfDateTimeException
     */
    public function retrieve( $unit = CalendarTimeManager::DAY )
    {
        switch ( $unit ) {
            case CalendarTimeManager::SECOND:
                return date( 's', $this->ts );
            case CalendarTimeManager::MINUTE:
                return date( 'i', $this->ts );
            case CalendarTimeManager::HOUR:
                return date( 'H', $this->ts );
            case CalendarTimeManager::DAY:
                return date( 'd', $this->ts );
            case CalendarTimeManager::WEEK:
                return date( 'W', $this->ts );
            case CalendarTimeManager::MONTH:
                return date( 'm', $this->ts );
            case CalendarTimeManager::QUARTER:
                return ceil( date( 'm', $this->ts ) / 3 );
            case CalendarTimeManager::YEAR:
                return date( 'Y', $this->ts );
            case CalendarTimeManager::DECADE:
                return ceil( ( date( 'Y', $this->ts ) % 100 ) / 10 );
            case CalendarTimeManager::CENTURY:
                return ceil( date( 'Y', $this->ts ) / 100 );
            case CalendarTimeManager::MILLENIUM:
                return ceil( date( 'Y', $this->ts ) / 1000 );
            default:
                throw new HTTPResponse_Exception( sprintf( 'The unit of time provided is not valid: %s', $unit ) );
        }
    }
    
    /**
     * Retrieve the timestamp value of this sfDate instance.
     *
     * @return    timestamp
     */
    public function get()
    {
        return $this->ts;
    }
    
    /**
     * Sets the timestamp value of this sfDate instance.
     *
     * This function accepts several froms of a date value:
     * - timestamp
     * - string, parsed with <code>strtotime</code>
     * - sfDate object
     *
     * @return    sfDate    the modified object, for chainability
     */
    public function set( $value = null )
    {
        $ts = CalendarManagerToolkit::getTS( $value );
        
        $this->ts = $ts;
        if ( $this->init === null ) {
            $this->init = $ts;
        }
        
        return $this;
    }
    
    /**
     * Resets the timestamp value of this sfDate instance to its original value.
     *
     * @return    sfDate    the reset object, for chainability
     */
    public function reset()
    {
        $this->ts = $this->init;
        
        return $this;
    }
    
    /**
     * Compares two date values.
     *
     * @param    mixed    timestamp, string, or sfDate object
     *
     * @return    int        -1, 0, or 1
     */
    public function cmp( $value )
    {
        $ts = CalendarManagerToolkit::getTS( $value );
        
        if ( $this->ts < $ts ) {
            // less than
            return -1;
        } else {
            if ( $this->ts > $ts ) {
                // greater than
                return 1;
            }
        }
        
        // equal to
        return 0;
    }
    
    /**
     * Gets the difference of two date values in seconds.
     *
     * @param    mixed      timestamp, string, or sfDate object
     * @param    int        the difference in seconds
     */
    public function diff( $value )
    {
        $ts = CalendarManagerToolkit::getTS( $value );
        
        return $this->ts - $ts;
    }
    
    /**
     * Call any function available in the sfTime library, but without the ts parameter.
     *
     * <b>Example:</b>
     * <code>
     *   $ts = sfTime::firstDayOfMonth(sfTime::addMonth(time(), 5));
     *   // equivalent
     *   $dt = new sfDate();
     *   $ts = $dt->addMonth(5)->firstDayOfMonth()->get();
     * </code>
     *
     * @return    sfDate    the modified object, for chainability
     */
    public function __call( $method, $arguments )
    {
        $callable = array(
            'sfTime',
            $method,
        );
        
        if ( !is_callable( $callable ) ) {
            throw new HTTPResponse_Exception( sprintf( 'Call to undefined function: %s::%s', 'sfDate', $method ) );
        }
        
        array_unshift( $arguments, $this->ts );
        
        $this->ts = call_user_func_array( $callable, $arguments );
        
        return $this;
    }
    
    
    public static function isValid( $dateString )
    {
        if ( self::isDateValid( $dateString ) ) {
            return date( "Y-m-d", \strtotime( $dateString ) );
        }
        
        return "-";
    }
    
    private static function isDateValid( $dateString )
    {
        $intTime = \strtotime( $dateString );
        
        //Valid since PHP 5.1.0. Previous versions return -1 on failure.
        return $intTime !== false;
    }
    
    static function currentTime()
    {
        return mktime();
    }
    
    static function currentDateTime()
    {
        $intFromDateToTimeStamp = mktime();
        
        return mktime( 0, 0, 0, date( 'n', $intFromDateToTimeStamp ), date( 'j', $intFromDateToTimeStamp ), date( 'Y', $intFromDateToTimeStamp ) );
    }
    
    static function phpTimeToMySQLDateTime( $dtTime )
    {
        return date( 'Y-m-d H:i:s', $dtTime );
    }
    
    static function phpTimeToMySQLTime( $dtTime )
    {
        return date( 'Y-m-d', $dtTime );
    }
    
    static function phpTimeToMySQLTimeQuoted( $dtTime )
    {
        return "'" . self::phpTimeToMySQLTime( $dtTime ) . "'";
    }
    
    static function calendarDateToPHPDate( $strDate )
    {
        $iYear  = (int) substr( $strDate, 6, 4 );
        $iMonth = (int) substr( $strDate, 3, 2 );
        $iDay   = (int) substr( $strDate, 0, 2 );
        
        return mktime( 0, 0, 0, $iMonth, $iDay, $iYear );
    }
    
    static function calendarDateToMySQLDate( $strDate )
    {
        $dtDate = self::calendarDateToPHPDate( $strDate );
        
        return self::phpTimeToMySQLTime( $dtDate );
    }
    
    static function calendarDateToMySQLDateQuoted( $strDate )
    {
        $dtDate = self::calendarDateToPHPDate( $strDate );
        
        return self::phpTimeToMySQLTimeQuoted( $dtDate );
    }
    
    static function addDaysToDate( $dtDate, $iDays )
    {
        $arrDate           = getdate( $dtDate );
        $arrDate[ 'mday' ] += $iDays;
        
        return mktime( $arrDate[ 'hours' ], $arrDate[ 'minutes' ], $arrDate[ 'seconds' ], $arrDate[ 'mon' ], $arrDate[ 'mday' ], $arrDate[ 'year' ] );
    }
    
    static function mysqlDateToPHPDate( $strDate )
    {
        $intFromDateToTimeStamp = strtotime( $strDate );
        
        return mktime( 0, 0, 0, date( 'n', $intFromDateToTimeStamp ), date( 'j', $intFromDateToTimeStamp ), date( 'Y', $intFromDateToTimeStamp ) );
    }
    
    static function mysqlDateToCalendarDate( $strDate )
    {
        $dtTime = self::mysqlDateToPHPDate( $strDate );
        
        return date( 'd/m/Y', $dtTime );
    }
    
    static function SSDateToBritishShortDate( $strDate )
    {
        $dtTime = self::mysqlDateToPHPDate( $strDate );
        
        return date( 'd/m/y', $dtTime );
    }
    
    static function mysqlDateToNiceDate( $strDate )
    {
        $dtTime = self::mysqlDateToPHPDate( $strDate );
        
        return date( 'jS F Y H:ia', $dtTime );
    }
    
    static function mysqlDateToNiceDateOnly( $strDate )
    {
        $dtTime = self::mysqlDateToPHPDate( $strDate );
        
        return date( 'jS F Y', $dtTime );
    }
    
    static function mysqlDateToNiceDateWithFullTextOnly( $strDate )
    {
        $dtTime = self::mysqlDateToPHPDate( $strDate );
        
        return date( 'l jS F Y', $dtTime );
    }
    
    static function phpTimeToNiceDateFormat( $dtTime )
    {
        return date( 'l, j-m-Y', $dtTime );
    }
    
    static function monthFromTime( $dtTime )
    {
        return date( "m", $dtTime );
    }
    
    static function mysqlTimeToNiceTime( $strTime )
    {
        $iLen    = strlen( $strTime );
        $strAMPM = strtoupper( substr( $strTime, $iLen - 3 ) );
        $strTime = substr( $strTime, 0, -3 );
        
        if ( $strAMPM == 'PM' ) {
            $arrParts = explode( ':', $strTime );
            $iHour    = (int) $arrParts[ 0 ];
            $iHour    += 12;
            $strTime  = $iHour . ':' . $arrParts[ 1 ];
        }
        
        return $strTime;
    }
    
    static function getSSTimeStr( $dtDate )
    {
        return date( 'H:i:s', $dtDate );
    }
    
    public static function getTimeStr( $dtDate )
    {
        return date( 'H:i', $dtDate );
    }
    
    public static function ssDateToPHPDate( $strDate )
    {
        $intFromDateToTimeStamp = strtotime( $strDate );
        
        return mktime( 0, 0, 0, date( 'n', $intFromDateToTimeStamp ), date( 'j', $intFromDateToTimeStamp ), date( 'Y', $intFromDateToTimeStamp ) );
    }
    
    public static function getDayStr( $dtDate )
    {
        return strftime( '%A', $dtDate );
    }
    
    static function geDateRange( $start_date, $range = 10 )
    {
        $begin     = new \DateTime( $start_date );
        $ts        = strtotime( $start_date );
        $fortnight = date( self::MySqlDateFormat, CalendarTimeManager::add( $ts, $range ) );
        $end_date  = new \DateTime( $fortnight );
        $interval  = new \DateInterval( 'P1D' );
        $dateRange = new \DatePeriod( $begin, $interval, $end_date );
        $days      = array();
        foreach ( $dateRange as $date ) {
            $days[] = $date->format( "Y-m-d" );
        }
        
        return $days;
    }
    
    function isFirstDayOfTheMonth( $date )
    {
        if ( date( 'j', strtotime( $date ) ) === '1' ) {
            return 'FirstDay';
        }
        
        return false;
    }
    
    static function isLastDayOfTheMonth( $date )
    {
        $last_day = date( 't', strtotime( $date ) );
        if ( date( 'j', strtotime( $date ) ) === $last_day ) {
            return 'LastDay';
        }
        
        return false;
    }
    
    static function isWeekend( $date )
    {
        $date = date( 'D', strtotime( $date ) );
        if ( in_array( $date, array(
            "Sat",
            "Sun",
        ) ) ) {
            return 'weekend';
        }
        
        return false;
    }
    
    static function isDateOnWeekend( $date )
    {
        return ( date( 'N', strtotime( $date ) ) >= 6 );
    }
    
    static function formatedDate( $date )
    {
        $time = strtotime( $date );
        
        return sprintf( "<span class='day'>%s</span><span class=\"date\">%s</span><span>%s</span>", date( 'D', $time ), date( 'd', $time ), date( 'M', $time ) );
    }
    
}
