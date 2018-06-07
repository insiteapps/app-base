<?php
/**
 *
 * @copyright (c) 2018 Insite Apps - http://www.insiteapps.co.za
 * @package insiteapps
 * @author Patrick Chitovoro  <patrick@insiteapps.co.za>
 * All rights reserved. No warranty, explicit or implicit, provided.
 *
 * NOTICE:  All information contained herein is, and remains the property of Insite Apps and its suppliers,  if any.
 * The intellectual and technical concepts contained herein are proprietary to Insite Apps and its suppliers and may be covered by South African. and Foreign Patents, patents in process, and are protected by trade secret or copyright laws.
 * Dissemination of this information or reproduction of this material is strictly forbidden unless prior written permission is obtained from Insite Apps.
 * Proprietary and confidential.
 * There is no freedom to use, share or change this file.
 *
 *
 */

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
