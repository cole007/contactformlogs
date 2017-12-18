<?php
/**
 * Contact Form Logs plugin for Craft CMS
 *
 * ContactFormLogs Model
 *
 * @author    @cole007
 * @copyright Copyright (c) 2017 @cole007
 * @link      http://ournameismud.co.uk/
 * @package   ContactFormLogs
 * @since     1.0.0
 */

namespace Craft;

class ContactFormLogsModel extends BaseModel
{
    /**
     * @return array
     */
    protected function defineAttributes()
    {
        return array_merge(parent::defineAttributes(), array(
            'form'     => array(AttributeType::String),
            'fromName'     => array(AttributeType::String),
            'fromEmail'     => array(AttributeType::Email),
            'toEmail'     => array(AttributeType::Email),
            'subject'     => array(AttributeType::String, 'maxLength' => 1000),
            'message'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'htmlMessage'     => array(AttributeType::String, 'column' => ColumnType::Text),
            'messageFields'     => array(AttributeType::Mixed),
            'attachment'     => array(AttributeType::Mixed),
            'status'     => array(AttributeType::String, 'default' => 'holding'),
        ));
    }

}