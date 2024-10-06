<?php

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

class MoOAuthUtility
{

    public static function check_empty_or_null($value)
    {
        if (!isset($value) || empty($value)) {
            return true;
        }
        return false;
    }

    public static function is_curl_installed()
    {
        if (in_array('curl', get_loaded_extensions())) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function GetPluginVersion()
    {
        $db = JFactory::getDbo();
        $dbQuery = $db->getQuery(true)
            ->select('manifest_cache')
            ->from($db->quoteName('#__extensions'))
            ->where($db->quoteName('element') . " = " . $db->quote('com_openjoomla_oauth'));
        $db->setQuery($dbQuery);
        $manifest = json_decode($db->loadResult());
        return ($manifest->version);
    }
}
