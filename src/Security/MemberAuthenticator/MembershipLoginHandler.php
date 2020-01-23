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
        'doLogin',
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
                if ( $oMember->inGroup($oGroup->ID) && $oGroup->GoToAdmin == 1 ) {
                    return $this->redirect(Director::baseURL() . 'admin');

                } elseif ( $oMember->inGroup($oGroup->ID) && $oGroup->LinkPageID != 0 ) {
                    $pageLink = $oGroup->LinkPage()->Link();

                    return $this->redirect($pageLink);

                }
            }
        }

        // Debug::show( $oMember );

        //   return;

        if ( isset($_REQUEST[ 'BackURL' ]) && $_REQUEST[ 'BackURL' ] && Director::is_site_url($_REQUEST[ 'BackURL' ]) ) {
            $BackURL = $_REQUEST[ 'BackURL' ];

            return $this->redirect($BackURL);
        } else {
            return $this->redirect('/');
        }

        return $this->redirect('/');
    }
}
