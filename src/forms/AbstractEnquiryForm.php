<?php
    /**
     *
     * @copyright (c) 2017 Insite Apps - http://www.insiteapps.co.za
     * @package insiteapps
     * @author Patrick Chitovoro  <patrick@insiteapps.co.za>
     * All rights reserved. No warranty, explicit or implicit, provided.
     *
     * NOTICE:  All information contained herein is, and remains the property of Insite Apps and its suppliers,  if
     *     any.
     * The intellectual and technical concepts contained herein are proprietary to Insite Apps and its suppliers and
     *     may be covered by South African. and Foreign Patents, patents in process, and are protected by trade secret
     *     or copyright laws. Dissemination of this information or reproduction of this material is strictly forbidden
     *     unless prior written permission is obtained from Insite Apps. Proprietary and confidential. There is no
     *     freedom to use, share or change this file.
     *
     *
     */

    namespace InsiteApps\Listings\Activity;

    use InsiteApps\AppBase\Mailer\MailerManager;
    use InsiteApps\AppBase\Utli\UtilityManager;
    use SilverStripe\Forms\DropdownField;
    use SilverStripe\Forms\EmailField;
    use SilverStripe\Forms\Form;
    use SilverStripe\Control\Controller;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\FormAction;
    use SilverStripe\Forms\RequiredFields;
    use SilverStripe\Forms\TextareaField;
    use SilverStripe\Forms\TextField;

    abstract class AbstractEnquiryForm extends Form
    {
        private static $allowed_actions = array(
            'doSubmit',
        );

        /**
         * @var string
         */
        private static $empty_string = "-Select-";

        /**
         * EnquiryForm constructor.
         *
         * @param Controller $controller
         * @param string $name
         */
        function __construct( Controller $controller, $name )
        {

            $f = $this->getFormFields();

            $actions = new FieldList(
                $btn = new FormAction('doSubmit', 'Submit')
            );
            $btn->addExtraClass("btn btn-primary hide");

            $aRequiredFields = array();
            $aRequiredFields[] = "Name";
            $aRequiredFields[] = "Email";

            $requiredFields = new RequiredFields();

            parent::__construct($controller, $name, $f, $actions, $requiredFields);
            $this->addExtraClass('EnquiryForm');

        }

        /**
         * @return \SilverStripe\Forms\FieldList
         */
        protected function getFormFields()
        {
            $f = new FieldList(
                TextField::create('Name', 'Full name'),
                EmailField::create('Email', 'Email Address'),
                TextField::create('Telephone'),
                DropdownField::create('Country', 'Country of origin')
                    ->setSource(UtilityManager::CountyList())
                    ->setEmptyString("-- Select Country --")
                    ->setAttribute('placeholder', 'Country of origin'),
                TextareaField::create('Message', 'Message')
            );

            return $f;
        }

        /**
         * @param array $raw_data
         * @param \SilverStripe\Forms\Form $form
         *
         * @return mixed
         */
        abstract function doSubmit( array $raw_data, Form $form );

        protected function onAfterSubmission( array $data )
        {
            $this->extend(__FUNCTION__, $data);


        }

    }