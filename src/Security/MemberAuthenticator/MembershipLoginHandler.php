<?php

namespace InsiteApps\Security\MemberAuthenticator;

use SilverStripe\Control\Director;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Dev\Debug;
use SilverStripe\Security\Group;
use SilverStripe\Security\MemberAuthenticator\LoginHandler;
use SilverStripe\Security\Security;

class MembershipLoginHandler extends LoginHandler
{
    
    private static $allowed_actions = [
    
    ];
    
    
    /**
     * Send user to the right location after login
     *
     * @return HTTPResponse
     */
    protected function redirectAfterSuccessfulLogin()
    {
        
        
        // Check password expiry
        if ( Security::getCurrentUser()->isPasswordExpired() ) {
            // Redirect the user to the external password change form if necessary
            return $this->redirectToChangePassword();
        }
        
        
        $oMember = Security::getCurrentUser();
        
        $oGroups = Group::get();
        if ( $oMember ) {
            foreach ( $oGroups as $oGroup ) {
                if ( $oMember->inGroup( $oGroup->ID ) && $oGroup->GoToAdmin == 1 ) {
                    $this->redirect( Director::baseURL() . 'admin' );
                    
                    return true;
                } elseif ( $oMember->inGroup( $oGroup->ID ) && $oGroup->LinkPageID != 0 ) {
                    $pageLink = $oGroup->LinkPage()->Link();
                    $this->redirect( $pageLink );
                    
                    return true;
                }
            }
        }
        
       // Debug::show( $oMember );
       
     //   return;
        
        if ( isset( $_REQUEST[ 'BackURL' ] ) && $_REQUEST[ 'BackURL' ] && Director::is_site_url( $_REQUEST[ 'BackURL' ] ) ) {
            //$BackURL = $_REQUEST[ 'BackURL' ];
            
            //return $this->redirect( $BackURL );
        } else {
            return $this->redirect( '/' );
        }
        return $this->redirect( '/' );
    }
}
