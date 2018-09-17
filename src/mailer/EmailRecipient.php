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

namespace InsiteApps\AppBase\Mailer {
    
    
    use SilverStripe\ORM\DataObject;
    
    class EmailRecipient extends DataObject
    {
        
        private static $table_name     = 'EmailRecipient';
        private static $db             = array(
            'Name'  => 'Varchar(200)',
            'Email' => 'Varchar(200)',
        );
        private static $has_one        = array();
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
    
}