<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Script file of openjoomla_oauth_plugin.
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
class pkg_oauthclientInstallerScript
{
    /**
     * This method is called after a component is installed.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function install($parent)
    {

            
    }

    /**
     * This method is called after a component is uninstalled.
     *
     * @param  \stdClass $parent - Parent object calling this method.
     *
     * @return void
     */
    public function uninstall($parent)
    {
        //echo '<p>' . JText::_('COM_HELLOWORLD_UNINSTALL_TEXT') . '</p>';
    }

    /**
     * This method is called after a component is updated.
     *
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function update($parent)
    {
        //echo '<p>' . JText::sprintf('COM_HELLOWORLD_UPDATE_TEXT', $parent->get('manifest')->version) . '</p>';
    }

    /**
     * Runs just before any installation action is performed on the component.
     * Verifications and pre-requisites should run in this function.
     *
     * @param  string    $type   - Type of PreFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function preflight($type, $parent)
    {
        //echo '<p>' . JText::_('COM_HELLOWORLD_PREFLIGHT_' . $type . '_TEXT') . '</p>';
    }

    /**
     * Runs right after any installation action is performed on the component.
     *
     * @param  string    $type   - Type of PostFlight action. Possible values are:
     *                           - * install
     *                           - * update
     *                           - * discover_install
     * @param  \stdClass $parent - Parent object calling object.
     *
     * @return void
     */
    public function postflight($type, $parent)
    {
        // echo '<p>' . JText::_('COM_HELLOWORLD_POSTFLIGHT_' . $type . '_TEXT') . '</p>';
        if ($type == 'uninstall') {
            return true;
        }
        $this->showInstallMessage('');
    }

    protected function showInstallMessage($messages=array())
    {
        ?>
        <style>
        
	.oj-row {
		width: 100%;
		display: block;
		margin-bottom: 2%;
	}

	.oj-row:after {
		clear: both;
		display: block;
		content: "";
	}

	.oj-column-2 {
		width: 19%;
		margin-right: 1%;
		float: left;
	}

	.oj-column-10 {
		width: 80%;
		float: left;
	}
    </style>
   
    <h3>Steps to use the OAuth Client plugin.</h3>
    <ul>
    <li>Click on <b>Components</b></li>
    <li>Click on <b>OpenJoomlaOAuth Client</b> and select <b>Configure OAuth</b> tab</li>
    <li>You can start configuring.</li>
    </ul>
        <?php
    }

}
