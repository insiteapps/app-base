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

namespace InsiteApps\Security {
    
    use InsiteApps\Security\MemberAuthenticator\MembershipAuthenticator;
    use SilverStripe\Core\Injector\Injector;
    use SilverStripe\ORM\DataExtension;
    
    
    class SecurityExtension extends DataExtension
    {
        
        private static $allowed_actions = array(
            'MembershipLoginForm',
        );
        
        /**
         * @return mixed
         * @throws \Psr\Container\NotFoundExceptionInterface
         */
        public function MembershipLoginForm()
        {
            return Injector::inst()->get( MembershipAuthenticator::class )->getLoginHandler( $this->owner->Link() )
                           ->MembershipLoginForm();
        }
    }
}