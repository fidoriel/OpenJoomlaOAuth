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

class miniorangeoauthControllerAccountSetup extends JControllerForm
{
    public function __construct()
    {
        $this->view_list = 'accountsetup';
        parent::__construct();
    }

    public function saveConfig()
    {
        $post =	JFactory::getApplication()->input->post->getArray();
        $appD = new MoOauthCustomer();
        if (count($post)==0) {
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup');
            return;
        } elseif (isset($post['oauth_config_form_step1'])) {
            if (isset($post['callbackurl'])) {
                $callbackurlhttp           = isset($post['callbackurlhttp'])?$post['callbackurlhttp'] : 'http';
                $redirectUri               = isset($post['callbackurl'])? $post['callbackurl'] : '';
                $redirectUri               = $callbackurlhttp."".$redirectUri ;
                $appname                   = isset($post['mo_oauth_app_name'])? $post['mo_oauth_app_name'] : '';
                $db     = JFactory::getDbo();
                $query  = $db->getQuery(true);
                $fields = array(
                    $db->quoteName('appname') . ' = '.$db->quote($appname),
                    $db->quoteName('redirecturi') . ' = '.$db->quote($redirectUri),
                );

                $conditions = array(
                    $db->quoteName('id') . ' = 1'
                );

                $query->update($db->quoteName('#__openjoomlaoauth_config'))->set($fields)->where($conditions);
                $db->setQuery($query);
                $result = $db->execute();
                $returnURL  = 'index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp='.$post['mo_oauth_app_name'].'&progress=step2';
                $errMessage = 'Redirect URI configuration completed successfully! Now, proceed to Step 2 to set up the client ID and client secret details';
            } else {
                $returnURL  = 'index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp='.$post['mo_oauth_app_name'];
                $errMessage = 'Please enter the redirect URI correctly';
                $this->setRedirect($returnURL, $errMessage, 'error');
                return;
            }
            
        } elseif (isset($post['oauth_config_form_step2'])) {
            $clientid                = isset($post['mo_oauth_client_id'])? $post['mo_oauth_client_id'] : '';
            $clientsecret            = isset($post['mo_oauth_client_secret'])? $post['mo_oauth_client_secret'] : '';
            $scope                   = isset($post['mo_oauth_scope'])? $post['mo_oauth_scope'] : '';
            $appname                 = isset($post['mo_oauth_app_name'])? $post['mo_oauth_app_name'] : '';
            $customappname           = isset($post['mo_oauth_custom_app_name'])? $post['mo_oauth_custom_app_name'] : '';
            $appEndpoints            = json_decode($appD->getAppJson(), true);
            $appEndpoints            = $appEndpoints[$appname];
            $authorizeurl            = isset($post['mo_oauth_authorizeurl'])? $post['mo_oauth_authorizeurl'] : '';
            $accesstokenurl          = isset($post['mo_oauth_accesstokenurl'])? $post['mo_oauth_accesstokenurl'] : '';
            $resourceownerdetailsurl = isset($post['mo_oauth_resourceownerdetailsurl'])? $post['mo_oauth_resourceownerdetailsurl'] : '';
            $current = "";
            if ($authorizeurl =="" && $accesstokenurl=="" && $resourceownerdetailsurl == "") {
                $authorizeurl            = isset($appEndpoints['authorize'])? $appEndpoints['authorize'] : '';
                $accesstokenurl          = isset($appEndpoints['token'])? $appEndpoints['token'] : '';
                $resourceownerdetailsurl = isset($appEndpoints['userinfo'])? $appEndpoints['userinfo'] : '';
                $appData                 = json_decode($appD->getAppData(), true);
                $appData                 = explode(",", $appData[$appname]['1']);
                $scope                   = isset($appEndpoints['scope'])? $appEndpoints['scope'] : 'email';

                $https_prefix = "https://";
                $http_prefix = "http://";

                    
    
                foreach ($appData as $key=>$val) {
                    $raw_url = $post[$val];

                    if (str_starts_with($raw_url, $https_prefix)) {
                        $current = substr($raw_url, strlen($https_prefix));
                    } elseif (str_starts_with($raw_url, $http_prefix)) {
                        $current = substr($raw_url, strlen($http_prefix));
                    } else {
                        $current = $raw_url;
                    }
                    
                    $authorizeurl            = str_replace("{".strtolower($val)."}", $current, $authorizeurl);
                    $accesstokenurl          = str_replace("{".strtolower($val)."}", $current, $accesstokenurl);
                    $resourceownerdetailsurl = str_replace("{".strtolower($val)."}", $current, $resourceownerdetailsurl);
                }

            }
    
            $in_header               = isset($post['mo_oauth_in_header'])?$post['mo_oauth_in_header']:'';
            $enableOAuthLoginButton  = isset($post['login_link_check']) ? $post['login_link_check'] : '0';
            $in_body                 = isset($post['mo_oauth_body'])?$post['mo_oauth_body']:'';
            $in_header_or_body       = "inHeader" ;
            if ($in_header=='1' && $in_body=='1') {
                $in_header_or_body = "both";
            } elseif ($in_body=='1') {
                $in_header_or_body ="inBody";
            }
    
            $db     = JFactory::getDbo();
            $query  = $db->getQuery(true);
            $fields = array(
                $db->quoteName('appname') . ' = '.$db->quote($appname),
                $db->quoteName('custom_app') . ' = '.$db->quote($customappname),
                $db->quoteName('client_id') . ' = '.$db->quote(trim($clientid)),
                $db->quoteName('client_secret') . ' = '.$db->quote(trim($clientsecret)),
                $db->quoteName('app_scope') . ' = '.$db->quote($scope),
                $db->quoteName('authorize_endpoint') . ' = '.$db->quote(trim($authorizeurl)),
                $db->quoteName('access_token_endpoint') . ' = '.$db->quote(trim($accesstokenurl)),
                $db->quoteName('user_info_endpoint') . ' = '.$db->quote(trim($resourceownerdetailsurl)),
                $db->quoteName('in_header_or_body').'='.$db->quote($in_header_or_body),
                $db->quoteName('login_link_check') . ' = '.$db->quote($enableOAuthLoginButton)
    
            );
            $conditions = array(
                $db->quoteName('id') . ' = 1'
            );
    
            $query->update($db->quoteName('#__openjoomlaoauth_config'))->set($fields)->where($conditions);
            $db->setQuery($query);
            $result = $db->execute();
            $returnURL  = 'index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp='.$post['mo_oauth_app_name'].'&progress=step3';
            $errMessage = 'Your configuration completed successfully! Now, proceed to Step 3 to configure the basic attribute mapping';
        }

        $this->setRedirect($returnURL, $errMessage);
    }

    public function saveMapping()
    {
        $post=	JFactory::getApplication()->input->post->getArray();

        $email_attr = isset($post['mo_oauth_email_attr'])? $post['mo_oauth_email_attr'] : '';
        $full_name_attr = isset($post['mo_oauth_full_name_attr'])? $post['mo_oauth_full_name_attr'] : '';
        $user_name_attr = isset($post['mo_oauth_user_name_attr'])? $post['mo_oauth_user_name_attr'] : '';

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('email_attr') . ' = '.$db->quote($email_attr),
            $db->quoteName('full_name_attr') . ' = '.$db->quote($full_name_attr),
            $db->quoteName('user_name_attr') . ' = '.$db->quote($user_name_attr),
        );

        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__openjoomlaoauth_config'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();

        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=configuration&progress=step4', JText::_('COM_MINIORANGE_OAUTH_ATTRIBUTE_MAPPING_SAVED_SUCCESSFULLY'));
    }

    public function clearConfig()
    {
        $post=	JFactory::getApplication()->input->post->getArray();

        $clientid = "";
        $clientsecret = "";
        $scope = "";
        $appname = "";
        $customappname = "";
        $authorizeurl = "";
        $accesstokenurl = "";
        $resourceownerdetailsurl = "";
        $email_attr="";
        $full_name_attr="";
        $user_name_attr="";
        $test_attribute_name = "";

        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('appname') . ' = '.$db->quote($appname),
            $db->quoteName('custom_app') . ' = '.$db->quote($customappname),
            $db->quoteName('client_id') . ' = '.$db->quote($clientid),
            $db->quoteName('client_secret') . ' = '.$db->quote($clientsecret),
            $db->quoteName('app_scope') . ' = '.$db->quote($scope),
            $db->quoteName('authorize_endpoint') . ' = '.$db->quote($authorizeurl),
            $db->quoteName('access_token_endpoint') . ' = '.$db->quote($accesstokenurl),
            $db->quoteName('user_info_endpoint') . ' = '.$db->quote($resourceownerdetailsurl),
            $db->quoteName('redirecturi') . ' = '.$db->quote(''),
            $db->quoteName('email_attr') . ' = '.$db->quote($email_attr),
            $db->quoteName('full_name_attr') . ' = '.$db->quote($full_name_attr),
            $db->quoteName('user_name_attr') . ' = '.$db->quote($user_name_attr),
            $db->quoteName('test_attribute_name') . ' = '.$db->quote($test_attribute_name),
        );

        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );

        $query->update($db->quoteName('#__openjoomlaoauth_config'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();

        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=configuration', JText::_('COM_MINIORANGE_OAUTH_APP_CONFIGURATION_RESET'));
    }


    public function updateDatabaseQuery($database_name, $updatefieldsarray)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        foreach ($updatefieldsarray as $key => $value) {
            $database_fileds[] = $db->quoteName($key) . ' = ' . $db->quote($value);
        }
        $query->update($db->quoteName($database_name))->set($database_fileds)->where($db->quoteName('id')." = 1");
        $db->setQuery($query);
        $db->execute();
    }
    
    public function exportConfiguration()
    {
        $appDetails = $this->retrieveAttributes('#__openjoomlaoauth_config');
        $clientid = $appDetails['client_id'];

        if ($clientid =='' && $clientsecret =='') {
            $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', JText::_('COM_MINIORANGE_OAUTH_ENTER_CLIENT_ID_BEFORE_DOWNLOADING'), 'error');
            return;
        }

        $plugin_configuration = array();
        array_push($plugin_configuration, $appDetails);
        
        
        $filecontentd = json_encode($plugin_configuration, JSON_PRETTY_PRINT);
        
        header('Content-Disposition: attachment; filename=oauth-client.json');
        header('Content-Type: application/json');
        print_r($filecontentd);

        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup', JText::_('COM_MINIORANGE_OAUTH_PLUGIN_CONFIGURATION_DOWNLOADED_SUCCESSFULLY'));
        exit;
    }

    public function retrieveAttributes($tablename)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName($tablename));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        return $db->loadAssoc();
    }

    public function moOAuthProxyConfigReset()
    {
        $nameOfDatabase= '#__openjoomlaoauth_config';
        $updateFieldsArray = array('proxy_server_url' => '', 'proxy_server_port' => '80', 'proxy_username' => '', 'proxy_password' => '', 'proxy_set' => '');
        
        $this->updateDatabaseQuery($nameOfDatabase, $updateFieldsArray);
        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', JText::_('COM_MINIORANGE_OAUTH_PROXY_SETTING_RESET'));
    }

    public function moOAuthProxyServer()
    {

        $post=	JFactory::getApplication()->input->post->getArray();
        $proxy_server_url = isset($post['proxy_server_url'])? $post['proxy_server_url'] : '';
        $proxy_server_port = isset($post['proxy_server_port'])? $post['proxy_server_port'] : '';
        $proxy_username = isset($post['proxy_username'])? $post['proxy_username'] : '';
        $proxy_password = isset($post['proxy_password'])? $post['proxy_password'] : '';

        $nameOfDatabase = '#__openjoomlaoauth_config';
        $updateFieldsArray = array(
            'proxy_server_url' 	  	  => $proxy_server_url,
            'proxy_server_port' 	  => $proxy_server_port,
            'proxy_username'          => $proxy_username,
            'proxy_password'          => $proxy_password,
            'proxy_set'               => 'yes',
        );

        $this->updateDatabaseQuery($nameOfDatabase, $updateFieldsArray);
        $this->setRedirect('index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=account', JText::_('COM_MINIORANGE_OAUTH_PROXY_SERVER_SAVED_SUCCESSFULLY'));
    }
}
