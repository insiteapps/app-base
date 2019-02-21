<?php
/**
 *
 * @copyright (c) 2018 Insite Apps - http://www.insiteapps.co.za
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

namespace InsiteApps\Control;

use InsiteApps\Data\Curl\CurlManager;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\Dev\Debug;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Group;
use SilverStripe\Security\Member;
use SilverStripe\Security\Permission;
use SilverStripe\Security\Security;
use SilverStripe\View\Requirements;
use Page;
use PageController;

class MainController extends Controller
{
    
    public function HasToLoginFirst()
    {
        
        $oMember = $this->Member();
        if ( !$oMember ) {
            
            return $this->redirect( Security::login_url() );
            
        }
    }
    
    /**
     * @param null $query
     *
     * @return array|string
     */
    protected function getPostData( $query = null )
    {
        
        $data = filter_input_array( INPUT_POST );
        
        return $this->processRequestData( $data, $query );
    }
    
    /**
     * @param null $query
     *
     * @return array|string
     */
    protected function getRequestData( $query = null )
    {
        
        $data  = $this->request->requestVars();
        $aData = RecordController::cleanREQUEST( $data );
        if ( $query ) {
            
            
            if ( isset( $aData[ $query ] ) ) {
                return $aData[ $query ];
            }
            
            return false;
        }
        
        return $aData;
        
    }
    
    /**
     * @param      $data
     * @param null $query
     *
     * @return array|string
     */
    protected function processRequestData( $data, $query = null )
    {
        
        
        if ( $data ) {
            $aData = RecordController::cleanREQUEST( $data );
            if ( $query && isset( $aData[ $query ] ) ) {
                return $aData[ $query ];
            }
            
            return $aData;
            
        }
        
        return [];
    }
    
    
    public function Member()
    {
        
        return Security::getCurrentUser();
    }
    
    public static function find_or_make_members_group()
    {
        
        $group = DataObject::get_one( "Group", "Code='members'" );
        if ( !$group ) {
            $group        = new Group();
            $group->Code  = 'members';
            $group->Title = 'Members';
            $group->write();
            Permission::grant( $group->ID, 'SITE_MEMBER' );
        }
        
        return $group;
    }
    
    
    public static function AddProtocol( $url )
    {
        
        if ( strtolower( substr( $url, 0, 8 ) ) !== 'https://' && strtolower( substr( $url, 0, 7 ) ) !== 'http://' ) {
            return 'http://' . $url;
        }
        
        return $url;
    }
    
    public static function RemoveProtocol( $url )
    {
        return preg_replace( '(^https?://)', '', $url );
        
    }
    
    /**
     * @param array|null $request
     * @param array      $Unset
     *
     * @return array|string
     */
    public static function cleanREQUEST( array $request = [], array $Unset = array() )
    {
        
        $request = Convert::raw2sql( $request );
        if ( count( $request ) ) {
            
            
            $aUnset   = array(
                'url',
                'SecurityID',
            );
            $arrUnset = array_merge( $aUnset, $Unset );
            foreach ( $arrUnset as $value ) {
                unset( $request[ $value ] );
            }
        }
        
        return $request;
    }
    
    public static function SimpleCleanREQUEST( array $request = [], array $Unset = array() )
    {
        
        $request = Convert::raw2xml( $request );
        if ( count( $request ) ) {
            
            
            $aUnset   = array(
                'url',
                'SecurityID',
            );
            $arrUnset = array_merge( $aUnset, $Unset );
            foreach ( $arrUnset as $value ) {
                unset( $request[ $value ] );
            }
        }
        
        return $request;
    }
    
    
    public function get_db_datetime()
    {
        
        return DBDatetime::now()->Rfc2822();
    }
    
    public function get_listing()
    {
        
        $url     = Convert::raw2sql( $this->urlParams[ "Action" ] );
        $listing = DataObject::get_one( "Listing", sprintf( "URLSegment = '%s'", $url ) );
        
        return $listing;
    }
    
    /**
     * @param $template
     *
     * @return mixed
     */
    public function setRenderWithPageTemplate( $template )
    {
        
        if ( Director::is_ajax() ) {
            Requirements::clear();
            
            return $this->owner->renderWith( array( $template ) );
        }
        
        return $this->owner->renderWith( array(
            $template,
            'Page',
        ) );
    }
    
    public function generate_page_controller( $title = "Page" )
    {
        
        $tmpPage             = new Page();
        $tmpPage->Title      = $title;
        $tmpPage->URLSegment = strtolower( str_replace( ' ', '-', $title ) );
        // Disable ID-based caching  of the log-in page by making it a random number
        $tmpPage->ID = -1 * rand( 1, 10000000 );
        
        $controller = PageController::create( $tmpPage );
        //$controller->setDataModel($this->model);
        $controller->init();
        
        return $controller;
    }
    
    public function urlParamsID()
    {
        
        return Convert::raw2sql( $this->urlParams[ 'ID' ] );
    }
    
    public function urlParamsOtherID()
    {
        
        return Convert::raw2sql( $this->urlParams[ 'OtherID' ] );
    }
    
    public function urlParamsAction()
    {
        
        return Convert::raw2sql( $this->urlParams[ 'Action' ] );
    }
    
    public function urlParamsParts()
    {
        
        return Convert::raw2sql( $this->urlParams );
    }
    
    /**
     * @return array|bool
     */
    public static function get_fonts_library_names()
    {
        
        $url      = "https://cdn.insiteapps.co.za/fonts/names/";
        $oManager = CurlManager::create();
        $results  = $oManager->processCurlWithHeaders( $url );
        
        return $results;
        
    }
    
    
    /**
     * @param \SilverStripe\Security\Member|null $member
     *
     * @return bool|int
     */
    public static function IsAdmin( Member $member = null )
    {
        
        if ( !$member ) {
            $member = Security::getCurrentUser();
        }
        
        return Permission::checkMember( $member, "ADMIN" );
    }
    
    
}
