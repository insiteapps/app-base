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

use InsiteApps\Common\Manager;

class CalendarTimeManager extends Manager
{
    
    /**
     * Units of time
     */
    const SECOND = 0;
    const MINUTE = 1;
    const HOUR = 2;
    const DAY = 3;
    const WEEK = 4;
    const MONTH = 5;
    const QUARTER = 6;
    const YEAR = 7;
    const DECADE = 8;
    const CENTURY = 9;
    const MILLENIUM = 10;
    
    /**
     * Days of the week
     */
    const SUNDAY = 0;
    const MONDAY = 1;
    const TUESDAY = 2;
    const WEDNESDAY = 3;
    const THURSDAY = 4;
    const FRIDAY = 5;
    const SATURDAY = 6;
    
    /**
     * Months of the year
     */
    const JANUARY = 1;
    const FEBRUARY = 2;
    const MARCH = 3;
    const APRIL = 4;
    const MAY = 5;
    const JUNE = 6;
    const JULY = 7;
    const AUGUST = 8;
    const SEPTEMBER = 9;
    const OCTOBER = 10;
    const NOVEMBER = 11;
    const DECEMBER = 12;
    
    /**
     * Adds the specified number of given units of time to the given date.
     *
     * <b>Example:</b>
     * <code>
     *   // tomorrow
     *   $dt = CalendarTimeManager::add();
     *   // day after
     *   $dt = CalendarTimeManager::add($mydate);
     *   // 5 days after
     *   $dt = CalendarTimeManager::add($mydate, 5);
     *   // 2 months after
     *   $dt = CalendarTimeManager::add($mydate, 2, CalendarTimeManager::MONTH);
     *   // 4 weeks after
     *   $dt = CalendarTimeManager::add($mydate, 4, CalendarTimeManager::WEEK);
     * </code>
     *
     * @param    timestamp      a timestamp for the calculation
     * @param    int            the number of units to add to the given date
     * @param    int            the unit to add by
     *
     * @return    timestamp    the timestamp result of the calculation
     *
     * @throws    sfDateTimeException
     */
    public static function add($ts = null, $num = 1, $unit = self::DAY)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        // gather individual variables for readability and maintainability
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        // determine which unit of time to add by
        switch ( $unit ) {
            case CalendarTimeManager::SECOND:
                return mktime($H, $i, $s + $num, $m, $d, $Y);
            case CalendarTimeManager::MINUTE:
                return mktime($H, $i + $num, $s, $m, $d, $Y);
            case CalendarTimeManager::HOUR:
                return mktime($H + $num, $i, $s, $m, $d, $Y);
            case CalendarTimeManager::DAY:
                return mktime($H, $i, $s, $m, $d + $num, $Y);
            case CalendarTimeManager::WEEK:
                return mktime($H, $i, $s, $m, $d + (7 * $num), $Y);
            case CalendarTimeManager::MONTH:
                return mktime($H, $i, $s, $m + $num, $d, $Y);
            case CalendarTimeManager::QUARTER:
                return mktime($H, $i, $s, $m + (3 * $num), $d, $Y);
            case CalendarTimeManager::YEAR:
                return mktime($H, $i, $s, $m, $d, $Y + $num);
            case CalendarTimeManager::DECADE:
                return mktime($H, $i, $s, $m, $d, $Y + (10 * $num));
            case CalendarTimeManager::CENTURY:
                return mktime($H, $i, $s, $m, $d, $Y + (100 * $num));
            case CalendarTimeManager::MILLENIUM:
                return mktime($H, $i, $s, $m, $d, $Y + (1000 * $num));
            default:
                throw new sfDateTimeException(sprintf('The unit of time provided is not valid: %s', $unit));
        }
    }
    
    /**
     * Subtracts the specified number of given units of time from the given date.
     *
     * <b>Example:</b>
     * <code>
     *   // yesterday
     *   $dt = CalendarTimeManager::subtract();
     *   // day before
     *   $dt = CalendarTimeManager::subtract($mydate);
     *   // 5 days before
     *   $dt = CalendarTimeManager::subtract($mydate, 5);
     *   // 2 months before
     *   $dt = CalendarTimeManager::subtract($mydate, 2, CalendarTimeManager::MONTH);
     *   // 4 weeks before
     *   $dt = CalendarTimeManager::subtract($mydate, 4, CalendarTimeManager::WEEK);
     * </code>
     *
     * @param    timestamp      a timestamp for the calculation
     * @param    int            the number of units to add to the given date
     * @param    int            the unit to add by
     *
     * @return    timestamp    the timestamp result of the calculation
     *
     * @see        add
     */
    public static function subtract($ts = null, $num = 1, $unit = CalendarTimeManager::DAY)
    {
        return CalendarTimeManager::add($ts, $num * -1, $unit);
    }
    
    /**
     * Returns the timestamp with the date but without the time of day.
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function clearTime($ts = null)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime(0, 0, 0, $m, $d, $Y);
    }
    
    /**
     * Returns the timestamp with the time of day but without the date.
     *
     * @deprecated This is a deprecated function. Do not use!
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function clearDate($ts = null)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime($H, $i, $s, 0, 0, 0);
    }
    
    /**
     * Clear the second value of this timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function clearSecond($ts = null)
    {
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime($H, $i, 0, $m, $d, $Y);
    }
    
    /**
     * Clear the minute value of this timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function clearMinute($ts = null)
    {
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime($H, 0, $s, $m, $d, $Y);
    }
    
    /**
     * Clear the hour value of this timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function clearHour($ts = null)
    {
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime(0, $i, $s, $m, $d, $Y);
    }
    
    /**
     * Set the second value of this timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function setSecond($ts = null, $second = 0)
    {
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime($H, $i, $second, $m, $d, $Y);
    }
    
    /**
     * Set the minute value of this timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function setMinute($ts = null, $minute = 0)
    {
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime($H, $minute, $s, $m, $d, $Y);
    }
    
    /**
     * Set the hour value of this timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function setHour($ts = null, $hour = 0)
    {
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime($hour, $i, $s, $m, $d, $Y);
    }
    
    /**
     * Set the day value of this timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function setDay($ts = null, $day = 1)
    {
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime($H, $i, $s, $m, $day, $Y);
    }
    
    /**
     * Set the month value of this timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function setMonth($ts = null, $month = 1)
    {
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime($H, $i, $s, $month, $d, $Y);
    }
    
    /**
     * Set the year value of this timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function setYear($ts = null, $year = 1970)
    {
        list($H, $i, $s, $m, $d, $Y) = CalendarManagerToolkit::breakdown($ts);
        
        return mktime($H, $i, $s, $m, $d, $year);
    }
    
    /**
     * Returns the timestamp for tomorrow.
     *
     * Alias for CalendarTimeManager::addDay
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function tomorrow($ts = null)
    {
        return CalendarTimeManager::add($ts);
    }
    
    /**
     * Returns the timestamp for yesterday.
     *
     * Alias for CalendarTimeManager::subtractDay
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function yesterday($ts = null)
    {
        return CalendarTimeManager::subtract($ts);
    }
    
    /**
     * Adds the specified number of seconds to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addSecond($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::SECOND);
    }
    
    /**
     * Subtracts the specified number of seconds from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractSecond($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::SECOND);
    }
    
    /**
     * Adds the specified number of minutes to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addMinute($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::MINUTE);
    }
    
    /**
     * Subtracts the specified number of minutes from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractMinute($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::MINUTE);
    }
    
    /**
     * Adds the specified number of hours to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addHour($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::HOUR);
    }
    
    /**
     * Subtracts the specified number of hours from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractHour($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::HOUR);
    }
    
    /**
     * Adds the specified number of days to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addDay($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::DAY);
    }
    
    /**
     * Subtracts the specified number of days from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractDay($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::DAY);
    }
    
    /**
     * Adds the specified number of weeks to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addWeek($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::WEEK);
    }
    
    /**
     * Subtracts the specified number of weeks from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractWeek($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::WEEK);
    }
    
    /**
     * Adds the specified number of months to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addMonth($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::MONTH);
    }
    
    /**
     * Subtracts the specified number of months from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractMonth($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::MONTH);
    }
    
    /**
     * Adds the specified number of quarters to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addQuarter($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::QUARTER);
    }
    
    /**
     * Subtracts the specified number of quarters from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractQuarter($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::QUARTER);
    }
    
    /**
     * Adds the specified number of years to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addYear($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::YEAR);
    }
    
    /**
     * Subtracts the specified number of years from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractYear($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::YEAR);
    }
    
    /**
     * Adds the specified number of decades to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addDecade($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::DECADE);
    }
    
    /**
     * Subtracts the specified number of decades from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractDecade($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::DECADE);
    }
    
    /**
     * Adds the specified number of centuries to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addCentury($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::CENTURY);
    }
    
    /**
     * Subtracts the specified number of centuries from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractCentury($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::CENTURY);
    }
    
    /**
     * Adds the specified number of millenia to the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function addMillenium($ts = null, $num = 1)
    {
        return CalendarTimeManager::add($ts, $num, CalendarTimeManager::MILLENIUM);
    }
    
    /**
     * Subtracts the specified number of millenia from the timestamp.
     *
     * @param    timestamp
     * @param    int
     *
     * @return    timestamp
     */
    public static function subtractMillenium($ts = null, $num = 1)
    {
        return CalendarTimeManager::subtract($ts, $num, CalendarTimeManager::MILLENIUM);
    }
    
    /**
     * Returns the timestamp for first day of the week for the given date.
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function firstDayOfWeek($ts = null)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        return CalendarTimeManager::subtractDay($ts, date('w', $ts));
    }
    
    /**
     * Returns the timestamp for last day of the week for the given date.
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function finalDayOfWeek($ts = null)
    {
        return CalendarTimeManager::subtractDay(CalendarTimeManager::firstDayOfWeek(CalendarTimeManager::addWeek($ts)));
    }
    
    /**
     * Returns the timestamp for first day of the month for the given date.
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function firstDayOfMonth($ts = null)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        return CalendarTimeManager::subtractDay($ts, date('d', $ts) - 1);
    }
    
    /**
     * Returns the timestamp for last day of the month for the given date.
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function finalDayOfMonth($ts = null)
    {
        return CalendarTimeManager::subtractDay(CalendarTimeManager::firstDayOfMonth(CalendarTimeManager::addMonth($ts)));
    }
    
    /**
     * Returns the timestamp for first day of thequarter for the given date.
     *
     * NOTE: Computes the quarter as:
     * <code>
     *   $quarter = ceil(date('m', $ts) / 3); // 1 - 4
     * </code>
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function firstDayOfQuarter($ts = null)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        // variables for computation
        $month = date('m', $ts);
        $quarter = ceil($month / 3) - 1; // zero based quarter
        
        return CalendarTimeManager::subtractMonth(CalendarTimeManager::firstDayOfMonth($ts), $month - ($quarter * 3) - 1);
    }
    
    /**
     * Returns the timestamp for last day of the quarter for the given date.
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function finalDayOfQuarter($ts = null)
    {
        return CalendarTimeManager::subtractDay(CalendarTimeManager::firstDayOfQuarter(CalendarTimeManager::addQuarter($ts)));
    }
    
    /**
     * Returns the timestamp for first day of the year for the given date.
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function firstDayOfYear($ts = null)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        return CalendarTimeManager::subtractMonth(CalendarTimeManager::firstDayOfMonth($ts), date('m', $ts) - 1);
    }
    
    /**
     * Returns the timestamp for last day of the year for the given date.
     *
     * @param    timestamp
     *
     * @return    timestamp
     */
    public static function finalDayOfYear($ts = null)
    {
        return CalendarTimeManager::subtractDay(CalendarTimeManager::firstDayOfYear(CalendarTimeManager::addYear($ts)));
    }
    
    /**
     * Returns the timestamp for the next occurance of [day].
     *
     * @param    timestamp
     * @param    int            the day of week
     *
     * @return    timestamp
     */
    public static function nextDay($ts = null, $day = CalendarTimeManager::SUNDAY)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        // get offsets from sunday
        $offset1 = date('w', $ts);
        $offset2 = $day;
        
        // adjust if date wraps into next week
        $offset2 += $offset2 > $offset1 ? 0 : 7;
        
        return CalendarTimeManager::addDay($ts, $offset2 - $offset1);
    }
    
    /**
     * Returns the timestamp for the most recent (previous) occurance of [day].
     *
     * @param    timestamp
     * @param    int            the day of week
     *
     * @return    timestamp
     */
    public static function previousDay($ts = null, $day = CalendarTimeManager::SUNDAY)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        // get offsets from sunday
        $offset1 = date('w', $ts);
        $offset2 = $day;
        
        // adjust if date wraps into last week
        $offset1 += $offset1 > $offset2 ? 0 : 7;
        
        return CalendarTimeManager::subtractDay($ts, $offset1 - $offset2);
    }
    
    /**
     * Returns the timestamp for the next occurance of [month].
     *
     * @param    timestamp
     * @param    int            the month of year
     *
     * @return    timestamp
     */
    public static function nextMonth($ts = null, $month = CalendarTimeManager::JANUARY)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        // get offsets from january
        $offset1 = date('m', $ts);
        $offset2 = $month;
        
        // adjust if date wraps into next year
        $offset2 += $offset2 > $offset1 ? 0 : 12;
        
        return CalendarTimeManager::addMonth($ts, $offset2 - $offset1);
    }
    
    /**
     * Returns the timestamp for the most recent (previous) occurance of [month].
     *
     * @param    timestamp
     * @param    int            the month of year
     *
     * @return    timestamp
     */
    public static function previousMonth($ts = null, $month = CalendarTimeManager::JANUARY)
    {
        // default to now
        if ($ts === null) $ts = CalendarManagerToolkit::now();
        
        // get offsets from january
        $offset1 = date('m', $ts);
        $offset2 = $month;
        
        // adjust if date wraps into last year
        $offset1 += $offset1 > $offset2 ? 0 : 12;
        
        return CalendarTimeManager::subtractMonth($ts, $offset1 - $offset2);
    }
}
