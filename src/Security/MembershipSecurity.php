<?php

namespace InsiteApps\Security;

use SilverStripe\Admin\AdminRootController;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Session;
use SilverStripe\Core\Convert;
use SilverStripe\Core\Manifest\ModuleLoader;
use SilverStripe\ORM\FieldType\DBField;
use SilverStripe\ORM\FieldType\DBHTMLText;
use SilverStripe\Security\Authenticator;
use SilverStripe\Security\Security;
use SilverStripe\View\Requirements;
use SilverStripe\View\SSViewer;

/**
 * Provides a security interface functionality within the cms
 */
class MembershipSecurity extends Security
{
    private static $allowed_actions = array(
        'login',
        'MembershipLoginForm',
        'success',
    );
    
    /**
     * Enable in-cms reauthentication
     *
     * @var boolean
     * @config
     */
    private static $reauth_enabled = true;
    
    protected function init()
    {
        parent::init();
        
        
    }
    
    public function login( $request = null, $service = Authenticator::CMS_LOGIN )
    {
        return parent::login( $request, Authenticator::CMS_LOGIN );
    }
    
    public function Link( $action = null )
    {
        /** @skipUpgrade */
        return Controller::join_links( Director::baseURL(), __CLASS__, $action );
    }
    
    protected function getAuthenticator( $name = 'cms' )
    {
        return parent::getAuthenticator( $name );
    }
    
    public function getApplicableAuthenticators( $service = Authenticator::CMS_LOGIN )
    {
        return parent::getApplicableAuthenticators( $service );
    }
    
    /**
     * Get known logged out member
     *
     * @return Member
     */
    public function getTargetMember()
    {
        $tempid = $this->getRequest()->requestVar( 'tempid' );
        if ( $tempid ) {
            return Member::member_from_tempid( $tempid );
        }
        
        return null;
    }
    
    public function getResponseController( $title )
    {
        // Use $this to prevent use of Page to render underlying templates
        return $this;
    }
    
    protected function getSessionMessage( &$messageType = null )
    {
        $message = parent::getSessionMessage( $messageType );
        if ( $message ) {
            return $message;
        }
        
        // Format
        return _t( __CLASS__ . '.LOGIN_MESSAGE', '<p>Your session has timed out due to inactivity</p>' );
    }
    
    /**
     * Check if there is a logged in member
     *
     * @return bool
     */
    public function getIsloggedIn()
    {
        return !!Security::getCurrentUser();
    }
    
    
    /**
     * Determine if CMSSecurity is enabled
     *
     * @return bool
     */
    public function enabled()
    {
        // Disable shortcut
        if ( !static::config()->get( 'reauth_enabled' ) ) {
            return false;
        }
        
        return count( $this->getApplicableAuthenticators( Authenticator::CMS_LOGIN ) ) > 0;
    }
    
    /**
     * Given a successful login, tell the parent frame to close the dialog
     *
     * @return HTTPResponse|DBField
     */
    public function success()
    {
        
    }
}
