<?php

/**
 * @package     Joomla.System
 * @subpackage  plg_system_miniorangeoauth
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.plugin.plugin');
jimport('joomla.user.helper');
jimport('joomla.access.access');
require_once JPATH_ADMINISTRATOR.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'com_miniorange_oauth'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'mo_customer_setup.php';

class plgSystemMiniorangeoauth extends JPlugin
{
    private $attributesNames = "";
    public function onAfterRender()
    {
        $app            = JFactory::getApplication();
        $body           = $app->getBody();
        $tab = 0;
        $tables = JFactory::getDbo()->getTableList();
        foreach ($tables as $table)
        {
            if (strpos($table, "miniorange_oauth_config")!==FALSE)
                $tab = $table;
        }
        if($tab===0)
            return;
        
        $customerResult =$this->miniOauthFetchDb('#__miniorange_oauth_config',array('id'=>'1'));
        $applicationName=$customerResult['appname'];
        $linkCheck      =$customerResult['login_link_check'];
        if($linkCheck==1 && $app->isClient('site'))
        {
            $linkCondition = <<<EOD
            <button type="submit" tabindex="0" name="Submit" class="btn btn-primary login-button">
            EOD;
            if(stristr($body,$linkCondition))
            {
                if(stristr($body,"user.login"))
                {
                    $linkAddPlace="</button><br><a href = ".JURI::root()."?morequest=oauthredirect&app_name=".$applicationName."> Click Here For SSO ";
                    $body = str_replace('</button>', $linkAddPlace . '</a>', $body);
                    $app->setBody($body);           
                }
            }
        }
    }

    public function onAfterInitialise()
    {
        $app = JFactory::getApplication();
        $post = $app->input->post->getArray();

        if (isset($post['mojsp_feedback']))
        {
            $radio = $post['deactivate_plugin'];
            $data = $post['query_feedback'];
            $current_user = JFactory::getUser();
            $feedback_email = isset($post['feedback_email']) ? $post['feedback_email'] : $current_user->email;
            $fields = array(
                'uninstall_feedback'=>1
            );
            $conditions = array(
                'id'=>'1'
            );

            $this->miniOauthUpdateDb('#__miniorange_oauth_customer',$fields,$conditions);
            $customerResult=$this->miniOauthFetchDb('#__miniorange_oauth_customer',array('id'=>'1'));
            $admin_email = (isset($customerResult['email']) && !empty($customerResult['email'])) ? $customerResult['email'] : $feedback_email;
            $admin_phone = $customerResult['admin_phone'];
            $data1 = $radio . ' : ' . $data;
            require_once JPATH_BASE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_oauth' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'mo_customer_setup.php';
            require_once JPATH_SITE . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Installer' . DIRECTORY_SEPARATOR . 'Installer.php';
            
            foreach ($post['result'] as $fbkey) 
            {
                $result = $this->miniOauthFetchDb('#__extensions',array('extension_id'=>$fbkey),'loadColumn','type');
                $type = 0;
                foreach ($result as $results) 
                {
                    $type = $results;
                }
                if ($type) 
                {
                    $cid = 0;
                    $installer = new JInstaller();
                    $installer->uninstall($type, $fbkey, $cid);
                }
            }
        }
        /*----------------------Oauth Flow-------------------------------------------*/
        $get = $app->input->get->getArray();
        $session = JFactory::getSession();

        /*----------------------Test Configuration Handling----------------------------*/
        if (isset($get['morequest']) and $get['morequest'] == 'testattrmappingconfig')
        {
            $mo_oauth_app_name = $get['app'];
            $result=$app->redirect(JRoute::_(JURI::root() . '?morequest=oauthredirect&app_name=' . urlencode($mo_oauth_app_name) . '&test=true'));
        }

        /*-------------------------OAuth SSO starts with this if-----------*/
        /*            Opening of OAuth server dialog box
                     Step 1 of Oauth/OpenID flow
        */
        else if (isset($get['morequest']) and $get['morequest'] == 'oauthredirect') 
        {
            $appname = $get['app_name'];
            if (isset($get['test']))
                setcookie("mo_oauth_test", true);
            else
                setcookie("mo_oauth_test", false);

            // save the referrer in cookie so that we can come back to origin after SSO
            if (isset($get['redirect_after_login']))
                $loginredirurl = urldecode($get['redirect_after_login']);
            else if (isset($_SERVER['HTTP_REFERER']))
                $loginredirurl = $_SERVER['HTTP_REFERER'];

            if (!empty($loginredirurl)) {
                setcookie("returnurl", $loginredirurl);
            }
            
            // get Ouath configuration from database
            
            $appdata = $this->miniOauthFetchDb('#__miniorange_oauth_config', array('custom_app'=>$appname));
            $session->set('appname', $appname);
            if(is_null($appdata))
                $appdata = $this->miniOauthFetchDb('#__miniorange_oauth_config', array('appname'=>$appname));
            
            if(empty($appdata['client_id']) || empty($appdata['app_scope'])){
                echo "<center><h3 style='color:indianred;border:1px dotted black;'>Sorry! client ID or Scope is empty.</h3></center>";
                exit;
            }

            $state = base64_encode($appname);
            $authorizationUrl = $appdata['authorize_endpoint'];

            if (strpos($authorizationUrl, '?') !== false)
                $authorizationUrl = $authorizationUrl . "&client_id=" . $appdata['client_id'] . "&scope=" . $appdata['app_scope'] . "&redirect_uri=" . JURI::root() . "&response_type=code&state=" . $state;
            else
                $authorizationUrl = $authorizationUrl . "?client_id=" . $appdata['client_id'] . "&scope=" . $appdata['app_scope'] . "&redirect_uri=" . JURI::root() . "&response_type=code&state=" . $state;
            
            if (session_id() == '' || !isset($session))
                session_start();
            $session->set('oauth2state', $state);
            header('Location: ' . $authorizationUrl);
            exit;
        } 
        /*
        *   Step 2 of OAuth Flow starts. We got the code
        *
        */
        else if (isset($get['code'])) 
        {
            if (session_id() == '' || !isset($session))
                session_start();
            try {
                // get the app name from session or by decoding state
                $currentappname = "";
                $session_var = $session->get('appname');
                if (isset($session_var) && !empty($session_var))
                    $currentappname = $session->get('appname');
                else if (isset($get['state']) && !empty($get['state']))
                    $currentappname = base64_decode($get['state']);
                if (empty($currentappname)) {
                    exit('No request found for this application.');
                }
                // get OAuth configuration
                $appname = $session->get('appname');
                $name_attr = "";
                $email_attr = "";
                $appdata = $this->miniOauthFetchDb('#__miniorange_oauth_config', array('custom_app'=>$appname));
                if(is_null($appdata))
                    $appdata = $this->miniOauthFetchDb('#__miniorange_oauth_config', array('appname'=>$appname));
                $currentapp = $appdata;
                if (isset($appdata['email_attr']))
                    $email_attr = $appdata['email_attr'];
                if (isset($appdata['first_name_attr']))
                    $name_attr = $appdata['first_name_attr'];
                if (!$currentapp)
                    exit('Application not configured.');
                $authBase = JPATH_BASE . DIRECTORY_SEPARATOR . 'administrator' . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_oauth';
                include_once $authBase . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'oauth_handler.php';
                $mo_oauth_handler = new Mo_OAuth_Hanlder();
                /*
                 * make a back channel request for access token
                 * we may also get an ID token in openid flow
                 *
                 * */
                list($accessToken,$idToken) = $mo_oauth_handler->getAccessToken
                ($currentapp['access_token_endpoint'], 'authorization_code',
                    $currentapp['client_id'], $currentapp['client_secret'], $get['code'], JURI::root(),$currentapp['in_header_or_body']);
                $mo_oauth_handler->printError();
                /*
                * if access token is valid then call userInfo endpoint to get user info or resource  owner details or extract from Id-token
                */
                $resourceownerdetailsurl = $currentapp['user_info_endpoint'];
                if (substr($resourceownerdetailsurl, -1) == "=") {
                    $resourceownerdetailsurl .= $accessToken;
                }
                $resourceOwner = $mo_oauth_handler->getResourceOwner($resourceownerdetailsurl, $accessToken,$idToken);
                $mo_oauth_handler->printError();
                list($email,$name)=$this->getEmailAndName($resourceOwner,$email_attr,$name_attr);
                $checkUser = $this->get_user_from_joomla($email);

                if ($checkUser) {
                    $this->loginCurrentUser($checkUser, $name, $email);
                } 
                else 
                {
					require_once JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_miniorange_oauth' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'mo_customer_setup.php';

                    $user = new JUser;
                    $data = array();
                    
                    $data['name'] = $name;
                    $data['username'] = $email;
                    $data['email'] = $email;

                    $data['groups'] = $this->getRegisteredGroups();

                    $data['password'] = JUserHelper::genRandomPassword();
                    $data['password2'] = $data['password'];
                    $data['sendEmail'] = 1;
                    $data['block'] = 0;
                
                    if (!$user->bind($data)) {
                        echo 'Could not bind data. Error: ' . $user->getError();
                        exit;
                    }
                    
                    if (!$user->save()) {
                        echo 'Could not save user. Error: ' . $user->getError();
                        exit;
                    }
                    $this->loginCurrentUser($user, $name, $email);
                } 

            }catch (Exception $e) 
            {
                exit($e->getMessage());
            }
        }
    }

    function getRegisteredGroups()
    {
        $names = ["Registriert", "Registered"];
        $db = JFactory::getDbo();
        $groupIds = array();
    
        foreach ($names as $name) {
            $query = $db->getQuery(true)
                ->select($db->quoteName('id'))
                ->from($db->quoteName('#__usergroups'))
                ->where($db->quoteName('title') . ' = ' . $db->quote($name));
            $db->setQuery($query);
            $groupId = $db->loadResult();
            
            if ($groupId !== null) {
                $groupIds[] = $groupId;
            }
        }

        if (empty($groupIds)) {
            throw new Exception('No registered groups found for: ' . implode(', ', $names));
        }

        return $groupIds;
    }

    function onExtensionBeforeUninstall($id)
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('extension_id');
        $query->from('#__extensions');
        $query->where($db->quoteName('name') . " = " . $db->quote('OPEN_OPENID_OAUTH'));
        $db->setQuery($query);
        $result = $db->loadColumn();
        $tables = JFactory::getDbo()->getTableList();
        $tab = 0;
        foreach ($tables as $table) {
            if (strpos($table, "miniorange_oauth_customer"))
                $tab = $table;
        }
    }

    function getEmailAndName($resourceOwner,$email_attr,$name_attr)
    {
        //TEST Configuration
        $session = JFactory::getSession();
        $siteUrl=JURI::root();
        $siteUrl = $siteUrl . '/administrator/components/com_miniorange_oauth/assets/images/';
        if(isset($resourceOwner['email']))
        {
            $email =$resourceOwner['email'];
        }
        else
        {
            $email="there";
        }
        echo '<div style="font-family:Calibri;padding:0 3%;">';
        $test_cookie = JFactory::getApplication()->input->cookie->get('mo_oauth_test');
        if (isset($test_cookie) && !empty($test_cookie))
        {
            $attributesName = "";          
            echo '<div style="color: #3c763d;
                background-color: #dff0d8; padding:2%;margin-bottom:20px;text-align:center; border:1px solid #AEDB9A; font-size:18pt;">TEST SUCCESSFUL</div>
                <div style="display:block;text-align:center;margin-bottom:4%;"><img style="width:15%;"src="' . $siteUrl . 'green_check.png"></div><br>
                <span style="font-size:14pt;"><b>Hello, '.$email.'</b>,<br/> </span><br/>
                <table style="border-collapse:collapse;border-spacing:0; display:table;width:100%; font-size:14pt;background-color:#EDEDED;">
                <tr style="text-align:center;"><td style="font-weight:bold;border:2px solid #949090;padding:2%;">ATTRIBUTE NAME</td><td style="font-weight:bold;padding:2%;border:2px solid #949090; word-wrap:break-word;">ATTRIBUTE VALUE</td></tr>';
            
            echo '<div style="background:#EDEDED;padding:5px;">
                <p style="color:red;"><b><u>Next Steps :</u></b></p>
                <p>In Order to perform SSO successfully you need to atleast map the attribute containing Email-id recieved from the OAuth Provider with default joomla Email attribute in the Step 3. </p>
                </div>
                <p style="font-weight:bold;font-size:14pt;margin-left:1%;">ATTRIBUTES RECEIVED:</p><br>';
            self::testattrmappingconfig("",$resourceOwner);             
            echo "</table> <br><br>";
            $user_attributes = $this->attributesNames;
            $this->miniOauthUpdateDb('#__miniorange_oauth_config',array('test_attribute_name'=>$user_attributes),array("id"=>1));
            exit();
        }
        if(!empty($email_attr))
        {
            $email = $this->getnestedattribute($resourceOwner, $email_attr);
        }
        else
        {
            $session->set('mo_reason','Login not Allowed.Attibute Mapping is empty. Please configure it');
            echo '<div style="font-family:Calibri;padding:0 3%;">';
            echo '<div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
            <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong> Login not Allowed</p>
            <p><strong>Causes</strong>: Attibute Mapping is empty. Please configure it.</p>
            </div>';
            $base_url = JURI::root();
            echo '<p align="center"><a href="' . $base_url . '" style="text-decoration: none; padding:1%;width:100px;background: #0091CD none repeat scroll 0% 0%;cursor: pointer;font-size:15px;border-width: 1px;border-style: solid;border-radius: 3px;white-space: nowrap;box-sizing: border-box;border-color: #0073AA;box-shadow: 0px 1px 0px rgba(120, 200, 230, 0.6) inset;color: #FFF;"type="button">Done</a></p>';
            exit;
        }
        if (!empty($name_attr))
            $name = $this->getnestedattribute($resourceOwner, $name_attr);

        if (empty($email)) 
        {
            $home_link = JURI::root();
            $session->set('mo_reason','Email address not received. Check your Attribute Mapping configuration.');
            echo '<div style="font-family:Calibri;padding:0 3%;"><div style="color: #a94442;background-color: #f2dede;padding: 15px;margin-bottom: 20px;text-align:center;border:1px solid #E6B3B2;font-size:18pt;"> ERROR</div>
                    <div style="color: #a94442;font-size:14pt; margin-bottom:20px;"><p><strong>Error: </strong>Email Address not recived.</p>
                    <p><strong>Cause:</strong>Email Id not received for the email attribute name you configured. Check your <b>Attribute Mapping</b> configuration.</p></div></div><br>';
            $home_link = JURI::root();
            echo '<p align="center"><a href=' . $home_link . ' type="button" style="color: white; background: #185b91; padding: 10px 20px;">Back to Website</a><p>';
            exit;
        }
        return array($email,$name);
    }

    function testattrmappingconfig($nestedprefix, $resourceOwnerDetails)
    {
        if (!empty($nestedprefix))
            $nestedprefix .= ".";
            
        foreach ($resourceOwnerDetails as $key => $resource) 
        {
            if (is_array($resource) || is_object($resource)) 
            {
                $this->testattrmappingconfig($nestedprefix . $key, $resource);
            } 
            else 
            {
                echo "<tr><td style='font-weight:bold;border:2px solid #949090;padding:2%;'>";
                if (!empty($nestedprefix))
                    echo $nestedprefix;
                echo $key."</td><td style='padding:2%;border:2px solid #949090; word-wrap:break-word;'>" . $resource . "</td></tr>";
               $this->attributesNames.= $nestedprefix.$key.',';
            }
        }
    }

    function getnestedattribute($resource, $key)
    {
        if(trim($key)=="")
            return "";

        $keys = explode(".",$key);
        if(sizeof($keys)>1)
        {
            $current_key = $keys[0];
            if(isset($resource[$current_key]))
                return $this->getnestedattribute($resource[$current_key], str_replace($current_key.".","",$key));
        } 
        else
        {
            $current_key = $keys[0];
            if(isset($resource[$current_key]))
            {
                return $resource[$current_key];
            }
        }
        return "";
    }

    function get_user_from_joomla($email)
    {
        //Check if email exist in database
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->select('id')
            ->from('#__users')
            ->where('email=' . $db->quote($email));
        $db->setQuery($query);
        $checkUser = $db->loadObject();
        return $checkUser;
    }

    function loginCurrentUser($checkUser, $name, $email)
    {
        $app = JFactory::getApplication();
        $user = JUser::getInstance($checkUser->id);
        $this->updateCurrentUser($user->id, $name);
        $session = JFactory::getSession(); #Get current session vars
        // Register the needed session variables
        $session->set('user', $user);
        //$app->checkSession();
        $sessionId = $session->getId();
        $this->updateUsernameToSessionId($user->id, $user->username, $sessionId);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query->select('*');
        $query->from($db->quoteName('#__miniorange_oauth_customer'));
        $query->where($db->quoteName('id') . " = 1");
        $db->setQuery($query);
        $result = $db->loadAssoc();
        $test = base64_decode(empty($result['sso_test'])?base64_encode(0):$result['sso_test']);
        $sso_test = base64_encode($sso_test);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('sso_test') . ' = ' . $db->quote($sso_test),
        );
        $conditions = array(
            $db->quoteName('id') . ' = 1'
        );
        $query->update($db->quoteName('#__miniorange_oauth_customer'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $results = $db->execute();
        $user->setLastVisit();
        $db = JFactory::getDbo();
        
        $returnurl = JFactory::getApplication()->input->cookie->getArray();
        if (isset($returnurl['returnurl'])) 
        {
            $redirectloginuri = $returnurl['returnurl'];
        }
        else 
        {
            $redirectloginuri = JURI::root() . 'index.php?';
        }

        $app->redirect($redirectloginuri);
    }

    function updateCurrentUser($id, $name)
    {
        // Username
        if (empty($name)) {
            return;
        }
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('name') . ' = ' . $db->quote($name),
        );
        $conditions = array(
            $db->quoteName('id') . ' = ' . $db->quote($id),
        );
        $query->update($db->quoteName('#__users'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();

        // Prefetch the groups the user is already in
        $query = $db->getQuery(true);
        $query->select($db->quoteName('group_id'))
            ->from($db->quoteName('#__user_usergroup_map'))
            ->where($db->quoteName('user_id') . ' = ' . $db->quote($id));
        $db->setQuery($query);
        $currentGroupIds = $db->loadColumn();

        // Group
        $groupIds = $this->getRegisteredGroups();

        foreach ($groupIds as $groupId) {
            if (in_array($groupId, $currentGroupIds)) {
                continue;
            }

            $query = $db->getQuery(true);
            $columns = array('user_id', 'group_id');
            $values = array($db->quote($id), $db->quote($groupId));
            $query->insert($db->quoteName('#__user_usergroup_map'))
                ->columns($db->quoteName($columns))
                ->values(implode(',', $values));
            $db->setQuery($query);
        }
        $result = $db->execute();

        return $result;
    }

    function updateUsernameToSessionId($userID, $username, $sessionId)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $fields = array(
            $db->quoteName('username') . ' = ' . $db->quote($username),
            $db->quoteName('guest') . ' = ' . $db->quote('0'),
            $db->quoteName('userid') . ' = ' . $db->quote($userID),
        );

        $conditions = array(
            $db->quoteName('session_id') . ' = ' . $db->quote($sessionId),
        );

        $query->update($db->quoteName('#__session'))->set($fields)->where($conditions);
        $db->setQuery($query);
        $result = $db->execute();
    }

    function miniOauthFetchDb($tableName,$condition,$method='loadAssoc',$columns='*')
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $columns = is_array($columns)?$db->quoteName($columns):$columns;
        $query->select($columns);
        $query->from($db->quoteName($tableName));
        foreach ($condition as $key=>$value)
            $query->where($db->quoteName($key) . " = " . $db->quote($value));

        $db->setQuery($query);
        if ($method=='loadColumn')
            return $db->loadColumn();
        else if($method == 'loadObjectList')
            return $db->loadObjectList();
        else if($method== 'loadResult')
            return $db->loadResult();
        else if($method == 'loadRow')
            return $db->loadRow();
        else
            return $db->loadAssoc();
    }

    function miniOauthUpdateDb($tableName,$fields,$conditions)
    {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        // Fields to update.
        $sanFields=array();
        foreach ($fields as $key=>$value)
        {
            array_push($sanFields,$db->quoteName($key) . ' = ' . $db->quote($value));
        }
        // Conditions for which records should be updated.
        $sanConditions=array();
        foreach ($conditions as $key=>$value)
        {
            array_push($sanConditions,$db->quoteName($key) . ' = ' . $db->quote($value));
        }
        $query->update($db->quoteName($tableName))->set($sanFields)->where($sanConditions);
        $db->setQuery($query);
        $db->execute();
    }
}
