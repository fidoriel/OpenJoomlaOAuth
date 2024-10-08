<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_openjoomla_oauth
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * General Controller of openjoomla_oauth component
 *
 * @package     Joomla.Administrator
 * @subpackage  com_openjoomla_oauth
 * @since       0.0.7
 */
class OpenJoomlaOAuthController extends JControllerLegacy
{
    /**
     * The default view for the display method.
     *
     * @var string
     * @since 12.2
     */
    protected $default_view = 'accountsetup';
}
