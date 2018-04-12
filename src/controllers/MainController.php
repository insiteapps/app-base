<?php

namespace InsiteApps\Control;

use InsiteApps\Data\Curl\CurlManager;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Group;
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
        if (!$oMember) {
            return self::redirect(Security::login_url());
        }
    }
    
    
    protected function getPostData()
    {
        $data = filter_input_array( INPUT_POST);
        if ( count( $data ) ) {
            return RecordController::cleanREQUEST( $data );
        }
        
        return [];
    }
    
    protected function getRequestData()
    {
        $data = filter_input_array( INPUT_GET);
        if ( count( $data ) ) {
            return RecordController::cleanREQUEST( $data );
        }
        
        return [];
    }
    
    function Member()
    {
        return Security::getCurrentUser();
    }
    
    public static function find_or_make_members_group()
    {
        $group = DataObject::get_one("Group", "Code='members'");
        if (!$group) {
            $group = new Group();
            $group->Code = 'members';
            $group->Title = 'Members';
            $group->write();
            Permission::grant($group->ID, 'SITE_MEMBER');
        }
        
        return $group;
    }
    
    static public function AddProtocol($url)
    {
        if (strtolower(substr($url, 0, 8)) !== 'https://' && strtolower(substr($url, 0, 7)) !== 'http://') {
            return 'http://' . $url;
        }
        
        return $url;
    }
    
    /**
     * @param array $request
     * @param array $Unset
     *
     * @return array|string
     */
    public static function cleanREQUEST(array $request = array(), array $Unset = array())
    {
        $request = Convert::raw2sql($request);
        $aUnset = array('url', 'SecurityID');
        $arrUnset = array_merge($aUnset, $Unset);
        foreach ( $arrUnset as $value ) {
            unset($request[ $value ]);
        }
        
        return $request;
    }
    
    public function get_listing()
    {
        $url = Convert::raw2sql($this->urlParams["Action"]);
        $listing = DataObject::get_one("Listing", sprintf("URLSegment = '%s'", $url));
        
        return $listing;
    }
    
    /**
     * @param $template
     *
     * @return mixed
     */
    public function setRenderWithPageTemplate($template)
    {
        if (Director::is_ajax()) {
            Requirements::clear();
            
            return $this->owner->renderWith(array($template));
        }
        
        return $this->owner->renderWith(array($template, 'Page'));
    }
    
    function generate_page_controller($title = "Page")
    {
        $tmpPage = new Page();
        $tmpPage->Title = $title;
        $tmpPage->URLSegment = strtolower(str_replace(' ', '-', $title));
        // Disable ID-based caching  of the log-in page by making it a random number
        $tmpPage->ID = -1 * rand(1, 10000000);
        
        $controller = PageController::create($tmpPage);
        //$controller->setDataModel($this->model);
        $controller->init();
        
        return $controller;
    }
    
    function urlParamsID()
    {
        return Convert::raw2sql($this->urlParams['ID']);
    }
    
    function urlParamsOtherID()
    {
        return Convert::raw2sql($this->urlParams['OtherID']);
    }
    
    function urlParamsAction()
    {
        return Convert::raw2sql($this->urlParams['Action']);
    }
    
    function urlParamsParts()
    {
        return Convert::raw2sql($this->urlParams);
    }
    
    /**
     * @return array|bool
     */
    public static function get_fonts_library_names()
    {
        $url = "https://cdn.insiteapps.co.za/fonts/names/";
        $oManager = CurlManager::create();
        $results = $oManager->processCurlWithHeaders($url);
        
        return $results;
        
    }
    
    public static function IsAdmin()
    {
        return Permission::check( "ADMIN" );
    }
    
    
}
