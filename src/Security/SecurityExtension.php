<?php

namespace InsiteApps\Security;

use InsiteApps\Security\MemberAuthenticator\MembershipAuthenticator;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\ORM\DataExtension;


class SecurityExtension extends DataExtension
{
    
    private static $allowed_actions = array(
        'MembershipLoginForm',
    );
    
    public function MembershipLoginForm()
    {
        return Injector::inst()->get(MembershipAuthenticator::class)->getLoginHandler($this->owner->Link())->MembershipLoginForm();
    }
}