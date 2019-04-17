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

namespace InsiteApps\Forms {
    
    
    use InsiteApps\AppBase\Mailer\EmailRecipient;
    use InsiteApps\Secure\Directory\BusinessListingPage;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
    use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
 
    use SilverStripe\Forms\TextField;
    use SilverStripe\ORM\DataExtension;
    
    class FormPageExtension extends DataExtension
    {
        
        private static $db = array(
            "OnCompleteTitle" => 'Varchar(255)',
            "OnComplete"      => "HTMLText",
        );
        
        private static $has_one = array();
        
        private static $has_many = array(
            "EmailRecipients" => EmailRecipient::class
        );
        
        public function updateCMSFields( FieldList $fields )
        {
            
            $GridFieldConfig = GridFieldConfig_RecordEditor::create();
            $Gridfield       = new GridField( 'EmailRecipients', 'EmailRecipients', $this->owner->EmailRecipients(), $GridFieldConfig );
            $fields->addFieldsToTab( 'Root.Setup', [
                TextField::create( 'OnCompleteTitle', 'Title' ),
                HTMLEditorField::create( 'OnComplete', 'On Complete Content' )->setRows( 15 ),
                $Gridfield,
            ] );
        }
    }
    
}