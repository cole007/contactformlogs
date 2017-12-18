<?php
/**
 * Contact Form Logs plugin for Craft CMS
 *
 * Plugin to extend the use of contact forms by logging records of all emails sent from the CMS
 *
 * @author    @cole007
 * @copyright Copyright (c) 2017 @cole007
 * @link      http://ournameismud.co.uk/
 * @package   ContactFormLogs
 * @since     1.0.0
 */

namespace Craft;

class ContactFormLogsPlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init()
    {
        parent::init();

        craft()->on('contactForm.beforeSend', function(Event $event) {
            
            // get message parameters from Event model
            $src = $event->params['message'];
            
            // get Contact Form settings
            $contactForm = craft()->plugins->getPlugin('contactform')->getSettings();
            
            // get POST variables from form
            $form = craft()->request->getParam('form');
            $firstName = craft()->request->getParam('firstName');
            $lastName = craft()->request->getParam('lastName');
            $toEmail = craft()->request->getParam('toEmail');

            // if first and second name variables exist concatenate to create fromName
            if (!empty($firstName) && !empty($lastName)) $fromName = $firstName . ' ' . $lastName;
            else $fromName = $src->fromName;

            // create new Contact Form Log model
            $message = new ContactFormLogsModel();
            // populate model
            $message->form = craft()->request->getParam('form');
            $message->fromName = $fromName;
            $message->fromEmail = $src->fromEmail;

            // do we need security here for toEmail if send via post?
            if (!empty($toEmail)) $message->toEmail = $toEmail;
            else $message->toEmail = $contactForm->toEmail;
        
            $message->subject = $src->subject;
            $message->message = $src->message;
            $message->htmlMessage = $src->htmlMessage;
            $message->messageFields = $src->messageFields;
            $message->attachment = null; 
            $message->status = 'pending';            

            // create and populate Contact Form Log record
            $messageRecord = new ContactFormLogsRecord();
            foreach ($message AS $key => $value) {
                $messageRecord->$key = $value;                
            }
            // save record
            $messageRecord->timestamp = time();
            $messageRecord->save();

            // upate Contact Form fromName (useful if custom defined by first/last name)
            $event->params['message']->fromName = $fromName;
            ContactFormLogsPlugin::log('contactForm.beforeSend: ' . time());
        });

        craft()->on('email.onSendEmail', function(Event $event) {
            ContactFormLogsPlugin::log('email.onSendEmail: ' . date('Y-m-d H:i:s')); 
            // get Email model
            $email = $event->params['emailModel'];
            // get custom Email variables
            $vars = $event->params['variables'];

            // get subject
            $subject = $vars['emailSubject'];

            
            $now = new DateTime();
            $from = $now->modify('-10 minutes');
            
            // get stored record with shared email address, subject and more recently than 10 minutes old
            
            $messageRecord = ContactFormLogsRecord::model()->findAllByAttributes(array('status'=>'pending','fromEmail'=>$email['replyTo']),'timestamp >= :timestamp',array(':timestamp'=>$from));                
            foreach ($messageRecord AS $record) {
                $record->setAttribute('subject',$subject);
                $record->setAttribute('status','sent');
                $record->save();
            }

            // do we want to clear out emails (status:holding) here or elsewhere?
            
        });
        
    }

    /**
     * @return mixed
     */
    public function getName()
    {
         return Craft::t('Contact Form Logs');
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return Craft::t('Plugin to extend the use of contact forms by logging records of all emails sent from the CMS');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl()
    {
        return '???';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return '???';
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getDeveloper()
    {
        return '@cole007';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'http://ournameismud.co.uk/';
    }

    /**
     * @return bool
     */
    public function hasCpSection()
    {
        return false;
    }

    /**
     */
    public function onBeforeInstall()
    {
    }

    /**
     */
    public function onAfterInstall()
    {
    }

    /**
     */
    public function onBeforeUninstall()
    {
    }

    /**
     */
    public function onAfterUninstall()
    {
    }
}