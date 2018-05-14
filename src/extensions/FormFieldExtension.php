<?php

namespace InsiteApps\Forms;

use SilverStripe\Dev\Debug;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataExtension;

class FormFieldExtension extends DataExtension
{
    function onBeforeRender( $field )
    {
        $_a   = array();
        $_a[] = TextField::class;
        $_a[] = EmailField::class;
        $_a[] = TextareaField::class;
        $_a[] = DropdownField::class;
        $_a[] = NumericField::class;
        
        
        if ( in_array( get_class( $field ), $_a ) ) {
            $field->addExtraClass( 'form-control' );
        }
        
    }
}