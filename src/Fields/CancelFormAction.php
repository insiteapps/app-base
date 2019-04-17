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

/**
 * Action that takes the user back to a given link rather than submitting
 * the form.
 *
 * @package cancelformaction
 */

namespace InsiteApps\Forms;

use SilverStripe\Forms\FormAction;
use SilverStripe\View\HTML;

class CancelFormAction extends FormAction
{
    
    /**
     * @var string
     */
    private $link;
    
    function __construct( $link = "", $title = "", $form = null, $extraData = null, $extraClass = '' )
    {
        if ( !$title ) {
            $title = _t( 'CancelFormAction.CANCEL', 'Cancel' );
        }
        
        $this->setLink( $link );
        
        parent::__construct( 'CancelFormAction', $title, $form, $extraData, $extraClass );
    }
    
    function setLink( $link )
    {
        $this->link = $link;
    }
    
    function getLink()
    {
        return $this->link;
    }
    
    public function Field( $properties = array() )
    {
        $attributes = array(
            'class'    => 'CancelFormAction btn btn-danger cancel ' . ( $this->extraClass() ? $this->extraClass() : '' ),
            'id'       => $this->id(),
            'name'     => $this->action,
            'tabindex' => $this->getAttribute( 'tabindex' ),
            'href'     => $this->getLink(),
        );
        
        if ( $this->isReadonly() ) {
            $attributes[ 'disabled' ] = 'disabled';
            $attributes[ 'class' ]    = $attributes[ 'class' ] . ' disabled';
        }
        
        return HTML::createTag( 'a', $attributes, $this->buttonContent ? $this->buttonContent : $this->Title() );
    }
    
}
