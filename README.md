# Contact Form Logs plugin for Craft CMS

Plugin to extend the use of the [Contact Form](https://github.com/craftcms/contact-form) plugin by logging records of all emails sent from the CMS

## Installation

To install Contact Form Logs, follow these steps:

1. Download & unzip the file and place the `contactformlogs` directory into your `craft/plugins` directory
2.  -OR- do a `git clone ???` directly into your `craft/plugins` folder.  You can then update it with `git pull`
3.  -OR- install with Composer via `composer require /contactformlogs`
4. Install plugin in the Craft Control Panel under Settings > Plugins
5. The plugin folder should be named `contactformlogs` for Craft to see it.  GitHub recently started appending `-master` (the branch name) to the name of the folder for zip file downloads.

Contact Form Logs works on Craft 2.4.x and Craft 2.5.x.

## Contact Form Logs Overview

This plugin is used to log submissions from the Craft Contact Form.  
It also creates an area in the CMS for viewing sent messages and provides variables for browsing messages in the front-end.  
Contact Form Logs provides the ability to log multiple forms on a site by adding an optional `form` field. 

## Configuring Contact Form Logs

You can change the name/label for Contact Form Logs within the Plugin settings by updating the `Known As` field.

## Using Contact Form Logs

Visting the Contact Form Logs area of the CMS will provide a list of messages posted through the Contact Form plugin.  
These can be filtered by clicking on the form name shown in the Form column.  
Message details can be viewed by clicking on the email subject.

### Extending Contact Form

There are two additional functions that can be used.

1. You can add a 'form' input to your forms which means you can differentiate the logs available for different forms on your site
2. There are two new fields available: `firstName` and `lastName` if you need to capture these separately (rather than simply `fromName`). Contact Form Logs will concatenate these for the purposes of the Contact Form plugin but store these as separate fields.

### Variables available

You can use `{% set logs = craft.contactformlogs.logs( ) %}` to fetch all message logs available or filter these by defining a criteria to filter against, for example `{% set vars = { form: 'Contact Form } %}` then `{% set logs = craft.contactformlogs.logs( vars ) %}`. For each log entry the following variables are available:

* `log.form` String
*  `log.fromName` String
*  `log.fromEmail` String
*  `log.toEmail` String
*  `log.subject` String
*  `log.message` String
*  `log.htmlMessage` HTML String
*  `log.messageFields` JSON object
*  `log.status` String (either `holding` or `sent`)
*  `log.timestamp` - a DateTime object

## How it works

The Contact Form plugin doesn't have a post send hook so the logs are recorded in a two-step process.  
On the `contactForm.beforeSend` event the plugin detects a submisssion from the Contact Form plugin, modifies any fields (eg merges firstName and lastName into a single fullName field) and stores the message with a status of holding.  
Then, on the `email.onSendEmail` event the plugin checks to see if a message has been logged in the last ten minutes that matches the status and sender email and changes any records to `sent` as well as update the record for the subject of the message (this is to deal with the Contact Form dynamically generates subject if variables are present).

## Contact Form Logs Roadmap

* Logs sorting
* Abstract `contactForm.beforeSend` and `email.onSendEmail` hooks into a separate service

Brought to you by [@cole007](http://ournameismud.co.uk/)  
Log Icon via Noun Project from [Ben Davis, RO](https://thenounproject.com/search/?q=logs&i=828711)
