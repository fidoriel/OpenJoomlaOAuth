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

class Mo_OAuth_Hanlder
{
    public $error;
    public function __construct($error='')
    {
        $this->error=$error;
    }

    public function getAccessToken($tokenendpoint, $grant_type, $clientid, $clientsecret, $code, $redirect_url, $in_header_or_body)
    {
        $session = JFactory::getSession();
        $ch = curl_init($tokenendpoint);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        
        if ($in_header_or_body=='both') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($clientid . ":" . $clientsecret)
            ));
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'redirect_uri='.urlencode($redirect_url).'&grant_type='.$grant_type.'&client_id='.urlencode($clientid).'&client_secret='.urlencode($clientsecret).'&code='.$code);

        } elseif ($in_header_or_body=='inHeader') {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($clientid . ":" . $clientsecret)
            ));
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'redirect_uri='.urlencode($redirect_url).'&grant_type='.$grant_type.'&code='.$code);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Accept: application/json'
            ));
            curl_setopt($ch, CURLOPT_POSTFIELDS, 'redirect_uri='.urlencode($redirect_url).'&grant_type='.$grant_type.'&client_id='.$clientid.'&client_secret='.$clientsecret.'&code='.$code);
        }
        
        $content = curl_exec($ch);

        if (curl_error($ch)) {

            $this->setError(curl_error($ch));
            $session->set('mo_reason', curl_error($ch));
        }

        $content =json_decode($content, true);
        if (!is_array($content)) {
            $this->setError("Invalid response received.");
            $session->set('mo_reason', 'Invalid response received.');
        }

        // first check if any error received
        if (isset($content["error_description"])) {
            $this->setError($content["error_description"]);
            $session->set('mo_reason', $content["error_description"]);

        } elseif (isset($content["error"])) {
            $this->setError($content["error"]);
            $session->set('mo_reason', $content["error"]);
        }
        // extract access_token and id_token
        $idToken=isset($content["id_token"])?$content["id_token"]:'';
        $access_token=isset($content["access_token"])?$content["access_token"]:'';
        if (empty($idToken) && empty($access_token)) {
            $this->setError('Invalid response received from OAuth Provider. Contact your administrator for more details.');
            $session->set('mo_reason', 'Invalid response received from OAuth Provider. Contact your administrator for more details.');
        }
        
        return array($access_token,$idToken);
    }
    public function getResourceOwnerFromIdToken($id_token)
    {
        $session = JFactory::getSession();
        $id_array = explode(".", $id_token);
        if (isset($id_array[1])) {
            $id_body = base64_decode($id_array[1]);
            if (is_array(json_decode($id_body, true))) {
                return json_decode($id_body, true);
            }
        }
        $this->setError('Invalid response received.<br><b>Id_token : </b>'.$id_token);
        $session->set('mo_reason', 'Invalid response received.<br><b>Id_token : </b>'.$id_token);
        return false;
    }

    public function getResourceOwner($resourceownerdetailsurl, $access_token, $idToken)
    {
        $session = JFactory::getSession();
        if (!empty($idToken) && !is_null($idToken)) {
            return $this->getResourceOwnerFromIdToken($idToken);
        }
        $ch = curl_init($resourceownerdetailsurl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_ENCODING, "");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$access_token,
                'User-Agent:web'
                ));
        $content = curl_exec($ch);
        if (curl_error($ch)) {
            $this->setError(curl_error($ch));
            $session->set('mo_reason', curl_error($ch));
            return false;
        }
        $content = json_decode($content, true);
        if (!is_array($content)) {
            $this->setError("Invalid response received.");
            $session->set('mo_reason', "Invalid response received.");
            return false;
        }
        
        if (isset($content["error_description"])) {
            $this->setError($content["error_description"]);
            $session->set('mo_reason', $content["error_description"]);
            return false;
        } elseif (isset($content["error"])) {
            $this->setError($content["error"]);
            $session->set('mo_reason', $content["error"]);
            return false;
        }
        return $content;
    }

    public function setError($error)
    {
        $this->error=$error;
    }
    
    public function isError()
    {
        if (empty($this->error)) {
            return false;
        }
        return true;
    }
    public function printError()
    {
        if (!$this->isError()) {
            return;
        }

        if (is_array($this->error)) {
            print_r($this->error);
        } else {
            echo($this->error);
        }
        exit;
    }
}
