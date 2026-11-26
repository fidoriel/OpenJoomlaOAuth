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

    public static function getSecureCookieOptions($expires = 0)
    {
        return array(
            'expires'  => $expires,
            'path'     => '/',
            'domain'   => '',
            'secure'   => JUri::getInstance()->isSsl(),
            'httponly' => true,
            'samesite' => 'Lax',
        );
    }

    public static function clearReturnUrlCookie()
    {
        setcookie('returnurl', '', self::getSecureCookieOptions(time() - 300));
    }

    // The test popup is opened from the admin backend but runs in the site session,
    // so the admin authorises it with an HMAC over the app name and an expiry.
    public static function buildOAuthTestSignature($appName, $expires)
    {
        $secret = JFactory::getConfig()->get('secret');
        if (empty($secret) || $appName === '' || (int) $expires <= 0) {
            return '';
        }
        return hash_hmac('sha256', $appName . '|' . (int) $expires . '|oauth-test', $secret);
    }

    public static function verifyOAuthTestSignature($appName, $expires, $signature)
    {
        if ($appName === '' || (int) $expires < time() || $signature === '') {
            return false;
        }
        $expected = self::buildOAuthTestSignature($appName, $expires);
        return $expected !== '' && hash_equals($expected, (string) $signature);
    }

    public static function getDefaultPostLoginRedirectUrl()
    {
        return JUri::root() . 'index.php?';
    }

    // Only allow relative paths or absolute http(s) URLs pointing to this site.
    public static function getSafePostLoginRedirectUrl($candidate, $fallback = null)
    {
        if ($fallback === null) {
            $fallback = self::getDefaultPostLoginRedirectUrl();
        }
        if (!is_string($candidate)) {
            return $fallback;
        }
        $candidate = trim($candidate);
        // browsers treat backslashes like slashes, which can change the host
        if ($candidate === '' || preg_match('#[\\\\\x00-\x20]#', $candidate)) {
            return $fallback;
        }

        if (preg_match('#^([a-z][a-z0-9+.\-]*):#i', $candidate, $schemeMatch)) {
            if (!in_array(strtolower($schemeMatch[1]), array('http', 'https'), true)) {
                return $fallback;
            }
        } elseif (strpos($candidate, '//') === 0) {
            return $fallback;
        }

        if ($candidate[0] === '/') {
            return $candidate;
        }

        $host = JUri::getInstance($candidate)->getHost();
        if ($host === '' || !in_array(self::normalizeHost($host), self::getAllowedRedirectHosts(), true)) {
            return $fallback;
        }
        return JUri::getInstance($candidate)->toString();
    }

    private static function normalizeHost($host)
    {
        $host = strtolower((string) $host);
        return strpos($host, 'www.') === 0 ? substr($host, 4) : $host;
    }

    private static function getAllowedRedirectHosts()
    {
        $hosts = array(
            JUri::getInstance(JUri::root())->getHost(),
            JUri::getInstance()->getHost(),
        );
        $liveSite = (string) JFactory::getConfig()->get('live_site', '');
        if ($liveSite !== '') {
            $hosts[] = JUri::getInstance($liveSite)->getHost();
        }
        return array_values(array_unique(array_filter(array_map(array(__CLASS__, 'normalizeHost'), $hosts))));
    }
}
