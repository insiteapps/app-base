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

namespace InsiteApps\Security\MemberAuthenticator {
    
    
    use SilverStripe\Security\Authenticator;
    use SilverStripe\Security\MemberAuthenticator\MemberAuthenticator;
    
    /**
     * Provides authentication for the user within the CMS
     */
    class MembershipAuthenticator extends MemberAuthenticator
    {
        
        public function supportedServices()
        {
            // Bitwise-OR of all the supported services in this Authenticator, to make a bitmask
            return Authenticator::LOGIN | Authenticator::LOGOUT | Authenticator::CHANGE_PASSWORD | Authenticator::RESET_PASSWORD | Authenticator::CHECK_PASSWORD;
        }
        
        
        /**
         * @param string $link
         *
         * @return \SilverStripe\Security\MemberAuthenticator\LoginHandler|static
         */
        public function getLoginHandler( $link )
        {
            return MembershipLoginHandler::create( $link, $this );
        }
    }
}