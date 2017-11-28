<?php

    /**
     *
     * @copyright (c) 2017 Insite Apps - http://www.insiteapps.co.za
     * @package       insiteapps
     * @author        Patrick Chitovoro  <patrick@insiteapps.co.za>
     * All rights reserved. No warranty, explicit or implicit, provided.
     *
     * NOTICE:  All information contained herein is, and remains the property of Insite Apps and its suppliers,  if
     *     any.
     * The intellectual and technical concepts contained herein are proprietary to Insite Apps and its suppliers and
     *     may be covered by South African. and Foreign Patents, patents in process, and are protected by trade secret
     *     or copyright law. Dissemination of this information or reproduction of this material is strictly forbidden
     *     unless prior written permission is obtained from Insite Apps.
     *
     * There is no freedom to use, share or change this file.
     *
     *
     */

    namespace InsiteApps\AppBase;

    use SilverStripe\ORM\DataObject;

    class ContactDetail extends DataObject
    {
        private static $table_name = 'ContactDetail';
        private static $default_sort = 'SortOrder';

        private static $db = array(
            'Name'      => 'Varchar(255)',
            'Content'   => 'Text',
            'Link'      => 'Varchar(255)',
            'Icon'      => 'Varchar(255)',
            'SortOrder' => 'Int',
        );

        private static $has_one = array(
            "Page" => "Page",
        );

        public function getCMSFields()
        {
            $f = parent::getCMSFields();
            $f->removeByName(array( 'SortOrder', 'PageID' ));

            return $f;
        }

        private static $summary_fields = array(
            'Name',
            'Content',
            'Icon',
        );

    }
