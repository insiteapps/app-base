<?php
/**
 *
 * @copyright (c) 2016 - 2017 Insite Apps - http://www.insiteapps.co.za
 * @package       insiteapps
 * @author        Patrick Chitovoro  <patrick@insiteapps.co.za>
 * All rights reserved. No warranty, explicit or implicit, provided.
 *
 * NOTICE:  All information contained herein is, and remains the property of Insite Apps and its suppliers,  if any.
 * The intellectual and technical concepts contained herein are proprietary to Insite Apps and its suppliers and may be
 * covered by South African. and Foreign Patents, patents in process, and are protected by trade secret or copyright
 * law. Dissemination of this information or reproduction of this material is strictly forbidden unless prior written
 * permission is obtained from Insite Apps.
 *
 * There is no freedom to use, share or change this file.
 *
 *
 */

namespace InsiteApps\Vend\Extensions;

use DirectApp\Managers\AppManager;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\ReadonlyField;

class GuidExtension extends DataExtension
{
    
    private static $db = [
        'Guid' => 'Varchar(255)',
    ];
    
    public function updateCMSFields( FieldList $fields )
    {
        
        $fields->removeByName( [ 'Guid' ] );
        
    }
    
    public function onBeforeWrite()
    {
        
        parent::onBeforeWrite();
        
        
        if ( empty( $this->owner->Guid ) ) {
            $guid              = AppManager::Guid();
            $this->owner->Guid = $guid;
        }
        
    }
    
    
}
