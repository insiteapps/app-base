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

namespace InsiteApps\Assets {
    
    use Colymba\BulkUpload\BulkUploader;
    use SilverStripe\Forms\FieldList;
    use SilverStripe\Forms\GridField\GridField;
    use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
    use SilverStripe\ORM\DataExtension;
    use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;
    
    class MultimediaExtension extends DataExtension
    {
        
        private static $has_many = array(
            'Images' => Multimedia::class,
        );
        private static $casting  = [
            'RawClassName' => 'Varchar',
        ];
        
        public function updateCMSFields( FieldList $fields )
        {
            $ImagesGridFieldConfig = GridFieldConfig_RecordEditor::create();
            $ImagesGridFieldConfig->addComponent( new  BulkUploader() );
            $ImagesGridFieldConfig->addComponent( new GridFieldSortableRows( 'SortOrder' ) );
            
            $ImagesGridFieldConfig->getComponentByType( BulkUploader::class )
                                  ->setUfSetup( 'setFolderName', 'multimedia' );
            
            $fields->addFieldToTab( 'Root.Images', new GridField( 'Images', 'Images', $this->owner->Images(), $ImagesGridFieldConfig ) );
            
            
        }
    
        public function HeroImage()
        {
        
            if ( count( $this->owner->Images() ) ) {
                $image = $this->owner->Images()->sort( 'Rand()' )->first();
            
                return $image->Image();
            }
        
            return false;
        }
        
        public function Image()
        {
            if ( method_exists( $this->owner, 'HeroImage' ) ) {
                return $this->owner->HeroImage();
            }
            if ( count( $this->owner->Images() ) ) {
                $image = $this->owner->Images()->first();
                
                return $image->Image();
            }
            
            
            return false;
        }
        
        public function getRawClassName()
        {
            $reflect = new \ReflectionClass( $this->owner->ClassName );
            return $reflect->getShortName();
        }
        
        public function GetImages()
        {
            
            $aImages = [];
            
            if ( count( $this->owner->Images() ) ) {
                $images = $this->owner->Images()->limit( 8 );
                foreach ( $images as $multimedia ) {
                    $multimedia->write();
                    $aMultimedia = array(
                        'ID'                              => $multimedia->Guid,
                        $this->owner->RawClassName . 'ID' => $this->owner->ID,
                    );
                    if ( $multimedia->getImageLink() ) {
                        $aMultimedia[ 'ImageFilename' ] = $multimedia->getImageLink();
                    } else {
                        $aMultimedia[ 'ImageFilename' ] = $multimedia->Image()->FillMax( 800, 600 )->URL;
                    }
                    
                    
                    $aImages[] = $aMultimedia;
                    
                }
                
            }
            
            return $aImages;
            
        }
    }
}
