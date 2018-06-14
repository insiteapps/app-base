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

namespace InsiteApps\Common;

use InsiteApps\Data\Curl\CurlManager;
use SilverStripe\Core\Injector\Injectable;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Member;
use SilverStripe\Security\Security;

class Manager
{
    
    use Injectable;
    
    
    protected static  function get_db_datetime()
    {
        return DBDatetime::now()->Rfc2822();
    }
    
    /**
     * @param int $x
     * @param int $max
     *
     * @return array
     */
    public function getNumericValues( $x = 0, $max = 4 )
    {
        
        $arrValues = array();
        for ( $i = $x; $i <= $max; $i++ ) {
            $arrValues[ $i ] = $i;
        }
        
        return $arrValues;
    }
    
    /**
     * Get list of all members you have the "Full administrative right" permission
     *
     * @return \DataList
     */
    public static function get_admin_list()
    {
        
        $oMembers = Member::get()->leftJoin( "Group_Members", "Group_Members.MemberID = Member.ID" )
                          ->leftJoin( "Permission", "Permission.GroupID = Group_Members.GroupID" )
                          ->filter( [ "Permission.Code" => 'ADMIN' ] );
        
        return $oMembers;
    }
    
    /**
     * @return array|bool
     */
    public static function get_fonts_library_names()
    {
        
        $url      = "https://cdn.insiteapps.co.za/fonts/names/";
        $oManager = new CurlManager();
        $results  = $oManager->processCurlWithHeaders( $url );
        
        return $results;
        
    }
    
    function Member()
    {
        
        return Security::getCurrentUser();
    }
    
    
    /**
     * @param $pure_string
     *
     * @return string
     */
    public static function encrypt( $pure_string )
    {
        return openssl_encrypt( $pure_string, "AES-128-ECB", BASE_ENCODE_KEY );
    }
    
    /**
     * @param $encrypted_string
     *
     * @return string
     */
    public static function decrypt( $encrypted_string )
    {
        return openssl_decrypt( $encrypted_string, "AES-128-ECB", BASE_ENCODE_KEY );
    }
}
