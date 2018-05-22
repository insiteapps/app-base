<?php

namespace InsiteApps\Security\MemberAuthenticator;


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
        return Authenticator::LOGIN | Authenticator::LOGOUT | Authenticator::CHANGE_PASSWORD
            | Authenticator::RESET_PASSWORD | Authenticator::CHECK_PASSWORD;
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
