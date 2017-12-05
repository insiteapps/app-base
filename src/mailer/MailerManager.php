<?php

    namespace InsiteApps\AppBase\Mailer;

    use SilverStripe\Control\Controller;
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
         * @param $from
         * @param $to
         * @param $Cc
         * @param $Bcc
         * @param $subject
         * @param $template
         * @param $data
         * @param array $aAttachments
         */
        protected function send_email( $from, $to, $Cc, $Bcc, $subject, $template, $data, $aAttachments = [] )
        {
            $to_name = "";
            $to_email = $to;
            if (is_array($to)) {
                $to_email = $to[ "email" ];
                $to_name = $to[ "name" ];
            }

            $email = Email::create();
            $email->setFrom($from);
            $email->setTo($to_email, $to_name);
            $email->setCc($Cc);
            $email->setBcc($Bcc);
            $email->setSubject($subject);
            $email->setHTMLTemplate($template);
            $email->setData($data);
            if (count($aAttachments)) {
                foreach ($aAttachments as $attachment) {
                    $email->addAttachment($attachment[ "tmp_name" ], $attachment[ "name" ]);
                }
            }
            try {
                $email->send();

              //  Debug::show($email);
            } catch (\Exception $e) {
                Debug::show($e->getMessage());
            }

        }


        public function sendSubmission( array $data, $subject = 'New website enquiry', $template = 'Email\SendEnquiryForm' )
        {
            $oRecipients = $this->getEmailRecipients();
            if (count($oRecipients)) {
                foreach ($oRecipients as $oRecipient) {
                    $this->send_email($data[ "Email" ], [ "email" => $oRecipient->Email, "name" => $oRecipient->Name ], null, null, $subject, $template, $data);
                }
            }

            return $this->AutoResponder($data);
        }

        /**
         *
         * @param array $data
         */
        function AutoResponder( array $data )
        {
            $this->send_email('no-reply@activesouthafrica.co.za', "patrick@insitesolutions.co.za" /*"patrick@activesouthafrica.co.za" $data[ "Email" ]*/, null, null, "Thank You for your Submission", 'Email\AutoResponderMailer', $data);

            //$this->send_email('no-reply@carlislehomes.com.au', $data[ "Email" ], null, null, "Thank You", 'AutoResponderMailer', $data);
        }

    }
