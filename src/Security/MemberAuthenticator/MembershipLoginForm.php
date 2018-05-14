<?php

namespace InsiteApps\Security\MemberAuthenticator;

use SilverStripe\Control\Controller;
use SilverStripe\Control\RequestHandler;

use SilverStripe\Forms\FieldList;

use SilverStripe\Security\MemberAuthenticator\MemberLoginForm;


/**
 * Provides the in-cms session re-authentication form for the "member" authenticator
 */
class MembershipLoginForm extends MemberLoginForm
{
    
    /**
     * CMSMemberLoginForm constructor.
     *
     * @param RequestHandler $controller
     * @param string         $authenticatorClass
     * @param FieldList      $name
     */
    public function __construct( RequestHandler $controller, $authenticatorClass, $name )
    {
        $this->controller = $controller;
        
        $this->authenticator_class = $authenticatorClass;
        
        $fields = $this->getFormFields();
        
        $actions = $this->getFormActions();
        
        parent::__construct( $controller, $authenticatorClass, $name, $fields, $actions );
        
        $this->addExtraClass( 'form--no-dividers' );
    }
    
    
}
