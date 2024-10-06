<?php

// Copyright (C) 2005 - 2015 Open Source Matters
// Copyright (C) 2015 miniOrange
// Copyright (C) 2024 fidoriel

// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.

// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.

// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>

// @license		http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE


defined('_JEXEC') or die('Restricted access');
JHtml::_('jquery.framework');
/**
 * Account Setup View
 *
 * @since  0.0.1
 */
class miniorangeoauthViewAccountSetup extends JViewLegacy
{
    public function display($tpl = null)
    {
        // Get data from the model
        $this->lists        = $this->get('List');
        //$this->pagination = $this->get('Pagination');

        // Check for errors.
        if (count($errors = $this->get('Errors'))) {
            JFactory::getApplication()->enqueueMessage(500, implode('<br />', $errors));

            return false;
        }
        $this->setLayout('accountsetup');
        // Set the toolbar
        $this->addToolBar();

        // Display the template
        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @return  void
     *
     * @since   1.6
     */
    protected function addToolBar()
    {
        JToolBarHelper::title(JText::_('COM_MINIORANGE_OAUTH_PLUGIN_TITLE'), 'mo_oauth_logo mo_oauth_icon');
    }
}
