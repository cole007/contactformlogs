<?php
/**
 * Contact Form Logs plugin for Craft CMS
 *
 * ContactFormLogs Controller
 *
 * @author    @cole007
 * @copyright Copyright (c) 2017 @cole007
 * @link      http://ournameismud.co.uk/
 * @package   ContactFormLogs
 * @since     1.0.0
 */

namespace Craft;

class ContactFormLogsController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = array('actionIndex',
        );

    /**
     */
    public function actionIndex()
    {
    }
}