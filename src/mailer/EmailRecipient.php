<?php

    namespace InsiteApps\AppBase\Mailer;

    use SilverStripe\ORM\DataObject;

    class EmailRecipient extends DataObject
    {
        private static $table_name = 'EmailRecipient';
        private static $db = array(
            'Name'      => 'Varchar(200)',
            'Email' => 'Varchar(200)',
            //"Level"=>"Enum('One','Two')"
        );
        private static $has_one = array();
        private static $summary_fields = array(
            'Name',
            'Email',
        );

        function getCMSFields()
        {
            $f = parent::getCMSFields();

            return $f;
        }

    }
