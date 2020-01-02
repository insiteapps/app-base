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

namespace InsiteApps\AppBase\Mailer;

use SilverStripe\Control\Controller;
use SilverStripe\Control\Director;
use SilverStripe\Control\Email\Email;
use SilverStripe\Dev\Debug;

class MailerManager extends Controller
{
    
    
    /**
     * @return \SilverStripe\ORM\DataList
     */
    private function getEmailRecipients()
    {
        
        return EmailRecipient::get();
    }
    
    /**
     * @return string
     */
    protected static function getFromAddress()
    {
        $url   = Director::absoluteURL( Director::absoluteBaseURL() );
        $parse = parse_url( $url );
        $host  = $parse[ 'host' ]; // prints 'google.com'
        
        return 'no-reply' . $host;
    }
    
    /**
     * @param       $from
     * @param       $to
     * @param       $Cc
     * @param       $Bcc
     * @param       $subject
     * @param       $template
     * @param       $data
     * @param array $aAttachments
     */
    protected function send_email( $from, $to, $Cc, $Bcc, $subject, $template, $data, $aAttachments = [] )
    {
        $to_name  = '';
        $to_email = $to;
        if ( is_array( $to ) ) {
            $to_email = $to[ 'email' ];
            $to_name  = $to[ 'name' ];
        }
        
        $email = Email::create();
        $email->setFrom( static::getFromAddress() );
        if ( $from !== null ) {
            $email->setReplyTo( $from );
        }
        
        $email->setTo( $to_email, $to_name );
        $email->setCc( $Cc );
        $email->setBcc( $Bcc );
        $email->setSubject( $subject );
        $email->setHTMLTemplate( $template );
        $email->setData( $data );
        if ( count( $aAttachments ) ) {
            foreach ( $aAttachments as $attachment ) {
                $email->addAttachment( $attachment[ 'tmp_name' ], $attachment[ 'name' ] );
            }
        }
        try {
            $email->send();
            //  Debug::show($email);
        } catch ( \Exception $e ) {
            Debug::show( $e->getMessage() );
        }
        
    }
    
    
    public function sendSubmission( array $data, $subject = 'New website enquiry', $template = 'Email\SendEnquiryForm' )
    {
        $oRecipients = $this->getEmailRecipients();
        if ( count( $oRecipients ) ) {
            foreach ( $oRecipients as $oRecipient ) {
                //  $from =  isset($data[ 'Email' ]) ? $data[ 'Email' ] : Director::absoluteBaseURL();
                $this->send_email( $data[ 'Email' ], [
                    'email' => $oRecipient->Email,
                    'name'  => $oRecipient->Name,
                ], null, null, $subject, $template, $data );
            }
        }
        
        return $this->AutoResponder( $data );
    }
    
    /**
     *
     * @param array $data
     */
    function AutoResponder( array $data )
    {
        $this->send_email( null, $data[ 'Email' ], null, null, 'Thank You for your Submission', 'Email\AutoResponderMailer', $data );
    }
    
}
