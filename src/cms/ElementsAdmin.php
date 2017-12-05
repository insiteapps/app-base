<?php

    /**
     *
     * @copyright (c) 2017 Insite Apps - http://www.insiteapps.co.za
     * @package       insiteapps
     * @author        Patrick Chitovoro  <patrick@insiteapps.co.za>
     * All rights reserved. No warranty, explicit or implicit, provided.
     *
     * NOTICE:  All information contained herein is, and remains the property of Insite Apps and its suppliers,  if
     * any.
     * The intellectual and technical concepts contained herein are proprietary to Insite Apps and its suppliers and
     * may be covered by South African. and Foreign Patents, patents in process, and are protected by trade secret or
     * copyright law. Dissemination of this information or reproduction of this material is strictly forbidden unless
     * prior written permission is obtained from Insite Apps.
     *
     * There is no freedom to use, share or change this file.
     *
     *
     */

    namespace InsiteApps\AppBase;

    use InsiteApps\AppBase\Mailer\EmailRecipient;
    use SilverStripe\Admin\ModelAdmin;

    class ElementsAdmin extends ModelAdmin
    {

        private static $url_segment = 'elements';

        private static $menu_title = 'Elements';

        private static $menu_priority = 5;

        private static $page_length = 100;

        private static $model_importers = array();

        private static $managed_models = array(
            EmailRecipient::class,
        );

    }
