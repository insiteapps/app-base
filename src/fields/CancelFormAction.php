<?php

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
