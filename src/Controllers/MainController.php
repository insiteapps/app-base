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
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
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
    public static function cleanREQUEST(   $request  , array $Unset = array() )
    {
        
        $request = Convert::raw2sql( $request );
        if ($request) {
            
            
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

    public static function truncate($text, $length = "50", $ending = '...', $exact = false, $considerHtml = true)
    {


        if ($considerHtml) {
            // if the plain text is shorter than the maximum length, return the whole text
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                $html = DBField::create_field(DBHTMLText::class, $text);
                return $html->value;

            }
            // splits all html-tags to scanable lines
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $total_length = strlen($ending);
            $open_tags = array();
            $truncate = '';
            foreach ($lines as $line_matchings) {
                // if there is any html-tag in this line, handle it and add it (uncounted) to the output
                if (!empty($line_matchings[1])) {
                    // if it's an "empty element" with or without xhtml-conform closing slash
                    if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
                        // do nothing
                        // if tag is a closing tag
                    } elseif (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
                        // delete tag from $open_tags list
                        $pos = array_search($tag_matchings[1], $open_tags);
                        if ($pos !== false) {
                            unset($open_tags[$pos]);
                        }
                        // if tag is an opening tag
                    } elseif (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
                        // add tag to the beginning of $open_tags list
                        array_unshift($open_tags, strtolower($tag_matchings[1]));
                    }
                    // add html-tag to $truncate'd text
                    $truncate .= $line_matchings[1];
                }
                // calculate the length of the plain text part of the line; handle entities as one character
                $content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
                if ($total_length + $content_length > $length) {
                    // the number of characters which are left
                    $left = $length - $total_length;
                    $entities_length = 0;
                    // search for html entities
                    if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entities_length <= $left) {
                                $left--;
                                $entities_length += strlen($entity[0]);
                            } else {
                                // no more characters left
                                break;
                            }
                        }
                    }
                    $truncate .= substr($line_matchings[2], 0, $left + $entities_length);
                    // maximum lenght is reached, so get off the loop
                    break;
                } else {
                    $truncate .= $line_matchings[2];
                    $total_length += $content_length;
                }
                // if the maximum length is reached, get off the loop
                if ($total_length >= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        // if the words shouldn't be cut in the middle...
        if (!$exact) {
            // ...search the last occurance of a space...
            $spacepos = strrpos($truncate, ' ');
            if (isset($spacepos)) {
                // ...and cut the text in this position
                $truncate = substr($truncate, 0, $spacepos);
            }
        }
        // add the defined ending to the text
        $truncate .= $ending;
        if ($considerHtml) {
            // close all unclosed html-tags
            foreach ($open_tags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }


        $html = DBField::create_field(DBHTMLText::class, $truncate);

        return $html->value;

    }


}
