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

defined('_JEXEC') or die('Restricted Access');
JHtml::_('jquery.framework');
JHtml::_('script', JURI::base() . 'components/com_miniorange_oauth/assets/js/bootstrap.js');
JHtml::_('stylesheet', JURI::base() . 'components/com_miniorange_oauth/assets/css/miniorange_oauth.css');
JHtml::_('stylesheet', JURI::base() . 'components/com_miniorange_oauth/assets/css/miniorange_boot.css');
JHtml::_('script', JURI::base() . 'components/com_miniorange_oauth/assets/js/myscript.js');
JHtml::_('stylesheet', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css');
?>  
<?php
if (MoOAuthUtility::is_curl_installed() == 0) { ?>
    <p style="color:red;">(Warning: <a href="http://php.net/manual/en/curl.installation.php" target="_blank">PHP CURL
            extension</a> is not installed or disabled) Please go to Troubleshooting for steps to enable curl.</p>
    <?php
}
$active_tab = JFactory::getApplication()->input->get->getArray();
$oauth_active_tab = isset($active_tab['tab-panel']) && !empty($active_tab['tab-panel']) ? $active_tab['tab-panel'] : 'configuration';
global $license_tab_link;
$license_tab_link="index.php?option=com_miniorange_oauth&view=accountsetup&tab-panel=license";
$current_user = JFactory::getUser();
if (!JPluginHelper::isEnabled('system', 'miniorangeoauth')) {
    ?>
    <div id="system-message-container">
        <button type="button" class="close" data-dismiss="alert">×</button>
        <div class="alert alert-error">
            <h4 class="alert-heading">Warning!</h4>
            <div class="alert-message">
                <h4>
                    This component requires System Plugin to be activated. Please activate the following plugin
                    to proceed further: System - OpenJoomlaOAuth Client
                </h4>
                <h4>Steps to activate the plugins:</h4>
                <ul>
                    <li>In the top menu, click on Extensions and select Plugins.</li>
                    <li>Search for miniOrange in the search box and press 'Search' to display the plugins.</li>
                    <li>Now enable the System plugin.</li>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>
<style>
    .close {
        color: red;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }   
    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>
 
<script>
    function MyClose(){
        jQuery(".TC_modal").css("display","none");
    }
    function show_TC_modal(){
        jQuery(".TC_modal").css("display","block");
    }
</script>

<div class="mo_boot_container-fluid p-0">
    <div class="mo_boot_row p-0 mo_boot_mx-2" style="background-color:white;">
        <div id="mo_oauth_nav_parent" class="mo_boot_col-sm-12 p-0 m-0" style="display:flex;">
            <a id="configtab" class="p-3  mo_nav-tab mo_nav_tab_<?php echo $oauth_active_tab == 'configuration' ? 'active' : ''; ?>" href="#configuration" onclick="add_css_tab('#configtab');" data-toggle="tab">
                <?php echo JText::_('COM_MINIORANGE_OAUTH_TAB1_CONFIGURE_OAUTH');?>
            </a>
            <a id="attributetab" class="p-3 mo_nav-tab mo_nav_tab_<?php echo $oauth_active_tab == 'attrrolemapping' ? 'active' : ''; ?>" href="#attrrolemapping" onclick="add_css_tab('#attributetab');" data-toggle="tab">
                User Attribute Mapping
            </a>
        </div>
    </div>
</div>
<div class="tab-content mo_boot_mx-2 mo_boot_my-2 mo_container" id="myTabContent">
        <div id="configuration" class="tab-pane <?php echo $oauth_active_tab == 'configuration' ? 'active' : ''; ?>">
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-12">
                    <?php
                        moOAuthConfiguration();
?>
                </div>
            </div>
        </div>
        <div id="attrrolemapping" class="tab-pane <?php echo $oauth_active_tab == 'attrrolemapping' ? 'active' : ''; ?>">
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-12">
                    <?php attributerole(); ?>
                </div>
            </div>
        </div>
<?php
function getAppJson()
{
    return '{	
        "azure": {
            "label":"Azure AD", "type":"oauth", "image":"azure.png", "scope": "openid email profile", "authorize": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/authorize", "token": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/token", "userinfo":"https://graph.microsoft.com/beta/me", "guide":"https://plugins.miniorange.com/azure-ad-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-windowslive"
        },
        "azureb2c": {
            "label":"Azure B2C", "type":"openidconnect", "image":"azure.png", "scope": "openid email", "authorize": "https://{tenant}.b2clogin.com/{tenant}.onmicrosoft.com/{policy}/oauth2/v2.0/authorize", "token": "https://{tenant}.b2clogin.com/{tenant}.onmicrosoft.com/{policy}/oauth2/v2.0/token", "userinfo": "", "guide":"https://plugins.miniorange.com/azure-ad-b2c-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-windowslive"
        },
        "cognito": {
            "label":"AWS Cognito", "type":"oauth", "image":"cognito.png", "scope": "openid", "authorize": "https://{domain}/oauth2/authorize", "token": "https://{domain}/oauth2/token", "userinfo": "https://{domain}/oauth2/userInfo", "guide":"https://plugins.miniorange.com/aws-cognito-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-amazon"
        },
        "adfs": {
            "label":"ADFS", "type":"openidconnect", "image":"adfs.png", "scope": "openid", "authorize": "https://{domain}/adfs/oauth2/authorize/", "token": "https://{domain}/adfs/oauth2/token/", "userinfo": "", "guide":"", "logo_class":"fa fa-windowslive"
        },
        "whmcs": {
            "label":"WHMCS", "type":"oauth", "image":"whmcs.png", "scope": "openid profile email", "authorize": "https://{domain}/oauth/authorize.php", "token": "https://{domain}/oauth/token.php", "userinfo": "https://{domain}/oauth/userinfo.php?access_token=", "guide":"https://plugins.miniorange.com/whmcs-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "keycloak": {
            "label":"keycloak", "type":"openidconnect", "image":"keycloak.png", "scope": "openid", "authorize": "https://{domain}/realms/{realm}/protocol/openid-connect/auth", "token": "https://{domain}/realms/{realm}/protocol/openid-connect/token", "userinfo": "{domain}/realms/{realm}/protocol/openid-connect/userinfo", "guide":"https://plugins.miniorange.com/keycloak-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "slack": {
            "label":"Slack", "type":"oauth", "image":"slack.png", "scope": "users.profile:read", "authorize": "https://slack.com/oauth/authorize", "token": "https://slack.com/api/oauth.access", "userinfo": "https://slack.com/api/users.profile.get", "guide":"https://plugins.miniorange.com/slack-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-slack"
        },
        "discord": {
            "label":"Discord", "type":"oauth", "image":"discord.png", "scope": "identify email", "authorize": "https://discordapp.com/api/oauth2/authorize", "token": "https://discordapp.com/api/oauth2/token", "userinfo": "https://discordapp.com/api/users/@me", "guide":"https://plugins.miniorange.com/discord-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "invisioncommunity": {
            "label":"Invision Community", "type":"oauth", "image":"invis.png", "scope": "email", "authorize": "{domain}/oauth/authorize/", "token": "https://{domain}/oauth/token/", "userinfo": "https://{domain}/oauth/me", "guide":"https://plugins.miniorange.com/joomla-oauth-sign-on-sso-using-invision-community", "logo_class":"fa fa-lock"
        },
        "bitrix24": {
            "label":"Bitrix24", "type":"oauth", "image":"bitrix24.png", "scope": "user", "authorize": "https://{accountid}.bitrix24.com/oauth/authorize", "token": "https://{accountid}.bitrix24.com/oauth/token", "userinfo": "https://{accountid}.bitrix24.com/rest/user.current.json?auth=", "guide":"https://plugins.miniorange.com/bitrix24-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-clock-o"
        },
        "wso2": {
            "label":"WSO2", "type":"oauth", "image":"wso2.png", "scope": "openid", "authorize": "https://{domain}/wso2/oauth2/authorize", "token": "https://{domain}/wso2/oauth2/token", "userinfo": "https://{domain}/wso2/oauth2/userinfo", "guide":"https://plugins.miniorange.com/wso2-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "okta": {
            "label":"Okta", "type":"openidconnect", "image":"okta.png", "scope": "openid email profile", "authorize": "https://{domain}/oauth2/default/v1/authorize", "token": "https://{domain}/oauth2/default/v1/token", "userinfo": "", "guide":"https://plugins.miniorange.com/login-with-okta-using-joomla", "logo_class":"fa fa-lock"
        },
        "onelogin": {
            "label":"OneLogin", "type":"openidconnect", "image":"onelogin.png", "scope": "openid", "authorize": "https://{domain}/oidc/auth", "token": "https://{domain}/oidc/token", "userinfo": "", "guide":"https://plugins.miniorange.com/onelogin-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "gapps": {
            "label":"Google", "type":"oauth", "image":"google.png", "scope": "email", "authorize": "https://accounts.google.com/o/oauth2/auth", "token": "https://www.googleapis.com/oauth2/v4/token", "userinfo": "https://www.googleapis.com/oauth2/v1/userinfo", "guide":"https://plugins.miniorange.com/google-apps-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-google-plus"
        },
        "fbapps": {
            "label":"Facebook", "type":"oauth", "image":"facebook.png", "scope": "public_profile email", "authorize": "https://www.facebook.com/dialog/oauth", "token": "https://graph.facebook.com/v2.8/oauth/access_token", "userinfo": "https://graph.facebook.com/me/?fields=id,name,email,age_range,first_name,gender,last_name,link", "guide":"https://plugins.miniorange.com/facebook-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-facebook"
        },
        "gluu": {
            "label":"Gluu Server", "type":"oauth", "image":"gluu.png", "scope": "openid", "authorize": "http://{domain}/oxauth/restv1/authorize", "token": "http://{domain}/oxauth/restv1/token", "userinfo": "http:///{domain}/oxauth/restv1/userinfo", "guide":"https://plugins.miniorange.com/gluu-server-single-sign-on-sso-joomla-login-using-gluu", "logo_class":"fa fa-lock"
        },
        "linkedin": {
            "label":"LinkedIn", "type":"oauth", "image":"linkedin.png", "scope": "openid email profile", "authorize": "https://www.linkedin.com/oauth/v2/authorization", "token": "https://www.linkedin.com/oauth/v2/accessToken", "userinfo": "https://api.linkedin.com/v2/me", "guide":"https://plugins.miniorange.com/linkedin-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-linkedin-square"
        },
        "strava": {
            "label":"Strava", "type":"oauth", "image":"strava.png", "scope": "public", "authorize": "https://www.strava.com/oauth/authorize", "token": "https://www.strava.com/oauth/token", "userinfo": "https://www.strava.com/api/v3/athlete", "guide":"https://plugins.miniorange.com/strava-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "fitbit": {
            "label":"FitBit", "type":"oauth", "image":"fitbit.png", "scope": "profile", "authorize": "https://www.fitbit.com/oauth2/authorize", "token": "https://api.fitbit.com/oauth2/token", "userinfo": "https://www.fitbit.com/1/user", "guide":"https://plugins.miniorange.com/fitbit-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "box": {
            "label":"Box", "type":"oauth", "image":"box.png", "scope": "root_readwrite", "authorize": "https://account.box.com/api/oauth2/authorize", "token": "https://api.box.com/oauth2/token", "userinfo": "https://api.box.com/2.0/users/me", "guide":"https://plugins.miniorange.com/box-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "github": {
            "label":"GitHub", "type":"oauth", "image":"github.png", "scope": "user repo", "authorize": "https://github.com/login/oauth/authorize", "token": "https://github.com/login/oauth/access_token", "userinfo": "https://api.github.com/user", "guide":"https://plugins.miniorange.com/github-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-github"
        },
        "gitlab": {
            "label":"GitLab", "type":"oauth", "image":"gitlab.png", "scope": "read_user", "authorize": "https://gitlab.com/oauth/authorize", "token": "http://gitlab.com/oauth/token", "userinfo": "https://gitlab.com/api/v4/user", "guide":"https://plugins.miniorange.com/gitlab-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-gitlab"
        },
        "clever": {
            "label":"Clever", "type":"oauth", "image":"clever.png", "scope": "read:students read:teachers read:user_id", "authorize": "https://clever.com/oauth/authorize", "token": "https://clever.com/oauth/tokens", "userinfo": "https://api.clever.com/v1.1/me", "guide":"https://plugins.miniorange.com/clever-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "salesforce": {
            "label":"Salesforce", "type":"oauth", "image":"salesforce.png", "scope": "email", "authorize": "https://login.salesforce.com/services/oauth2/authorize", "token": "https://login.salesforce.com/services/oauth2/token", "userinfo": "https://login.salesforce.com/services/oauth2/userinfo", "guide":"https://plugins.miniorange.com/salesforce-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "reddit": {
            "label":"Reddit", "type":"oauth", "image":"reddit.png", "scope": "identity", "authorize": "https://www.reddit.com/api/v1/authorize", "token": "https://www.reddit.com/api/v1/access_token", "userinfo": "https://www.reddit.com/api/v1/me", "guide":"https://plugins.miniorange.com/reddit-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-reddit"
        },
        "paypal": {
            "label":"PayPal", "type":"openidconnect", "image":"paypal.png", "scope": "openid", "authorize": "https://www.paypal.com/signin/authorize", "token": "https://api.paypal.com/v1/oauth2/token", "userinfo": "", "guide":"https://plugins.miniorange.com/paypal-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-paypal"
        },
        "swiss-rx-login": {
            "label":"Swiss RX Login", "type":"openidconnect", "image":"swiss-rx-login.png", "scope": "anonymous", "authorize": "https://www.swiss-rx-login.ch/oauth/authorize", "token": "https://swiss-rx-login.ch/oauth/token", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
        },
        "yahoo": {
            "label":"Yahoo", "type":"openidconnect", "image":"yahoo.png", "scope": "openid", "authorize": "https://api.login.yahoo.com/oauth2/request_auth", "token": "https://api.login.yahoo.com/oauth2/get_token", "userinfo": "", "guide":"https://plugins.miniorange.com/yahoo-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-yahoo"
        },
        "spotify": {
            "label":"Spotify", "type":"oauth", "image":"spotify.png", "scope": "user-read-private user-read-email", "authorize": "https://accounts.spotify.com/authorize", "token": "https://accounts.spotify.com/api/token", "userinfo": "https://api.spotify.com/v1/me", "guide":"https://plugins.miniorange.com/spotify-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-spotify"
        },
        "eveonlinenew": {
            "label":"Eve Online", "type":"oauth", "image":"eveonline.png", "scope": "publicData", "authorize": "https://login.eveonline.com/oauth/authorize", "token": "https://login.eveonline.com/oauth/token", "userinfo": "https://esi.evetech.net/verify", "guide":"https://plugins.miniorange.com/oauth-openid-connect-single-sign-on-sso-into-joomla-using-eve-online", "logo_class":"fa fa-lock"
        },
        "vkontakte": {
            "label":"VKontakte", "type":"oauth", "image":"vk.png", "scope": "openid", "authorize": "https://oauth.vk.com/authorize", "token": "https://oauth.vk.com/access_token", "userinfo": "https://api.vk.com/method/users.get?fields=id,name,email,age_range,first_name,gender,last_name,link&access_token=", "guide":"https://plugins.miniorange.com/vkontakte-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-vk"
        },
        "pinterest": {
            "label":"Pinterest", "type":"oauth", "image":"pinterest.png", "scope": "read_public", "authorize": "https://api.pinterest.com/oauth/", "token": "https://api.pinterest.com/v1/oauth/token", "userinfo": "https://api.pinterest.com/v1/me/", "guide":"https://plugins.miniorange.com/pinterest-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-pinterest"
        },
        "vimeo": {
            "label":"Vimeo", "type":"oauth", "image":"vimeo.png", "scope": "public", "authorize": "https://api.vimeo.com/oauth/authorize", "token": "https://api.vimeo.com/oauth/access_token", "userinfo": "https://api.vimeo.com/me", "guide":"https://plugins.miniorange.com/vimeo-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-vimeo"
        },
        "deviantart": {
            "label":"DeviantArt", "type":"oauth", "image":"devart.png", "scope": "browse", "authorize": "https://www.deviantart.com/oauth2/authorize", "token": "https://www.deviantart.com/oauth2/token", "userinfo": "https://www.deviantart.com/api/v1/oauth2/user/profile", "guide":"https://plugins.miniorange.com/deviantart-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-deviantart"
        },
        "dailymotion": {
            "label":"Dailymotion", "type":"oauth", "image":"dailymotion.png", "scope": "email", "authorize": "https://www.dailymotion.com/oauth/authorize", "token": "https://api.dailymotion.com/oauth/token", "userinfo": "https://api.dailymotion.com/user/me?fields=id,username,email,first_name,last_name", "guide":"https://plugins.miniorange.com/dailymotion-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "meetup": {
            "label":"Meetup", "type":"oauth", "image":"meetup.png", "scope": "basic", "authorize": "https://secure.meetup.com/oauth2/authorize", "token": "https://secure.meetup.com/oauth2/access", "userinfo": "https://api.meetup.com/members/self", "guide":"", "logo_class":"fa fa-lock"
        },
        "autodesk": {
            "label":"Autodesk", "type":"oauth", "image":"autodesk.png", "scope": "user:read user-profile:read", "authorize": "https://developer.api.autodesk.com/authentication/v1/authorize", "token": "https://developer.api.autodesk.com/authentication/v1/gettoken", "userinfo": "https://developer.api.autodesk.com/userprofile/v1/users/@me", "guide":"https://plugins.miniorange.com/autodesk-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "zendesk": {
            "label":"Zendesk", "type":"oauth", "image":"zendesk.png", "scope": "read write", "authorize": "https://{domain}/oauth/authorizations/new", "token": "https://{domain}/oauth/tokens", "userinfo": "https://{domain}/api/v2/users", "guide":"https://plugins.miniorange.com/login-with-zendesk-as-an-oauth-openid-connect-server", "logo_class":"fa fa-lock"
        },
        "laravel": {
            "label":"Laravel", "type":"oauth", "image":"laravel.png", "scope": "", "authorize": "http://{domain}/oauth/authorize", "token": "http://{domain}/oauth/token", "userinfo": "http://{domain}}/api/user/get", "guide":"https://plugins.miniorange.com/login-with-joomla-oauth-sign-on-sso-using-laravel-passport", "logo_class":"fa fa-lock"
        },
        "identityserver": {
            "label":"Identity Server", "type":"oauth", "image":"identityserver.png", "scope": "openid", "authorize": "https://{domain}/connect/authorize", "token": "https://{domain}/connect/token", "userinfo": "https://{domain}/connect/introspect", "guide":"https://plugins.miniorange.com/identityserver3-oauth-openid-connect-single-sign-on-sso-into-joomla-identityserver3-sso-login", "logo_class":"fa fa-lock"
        },
        "nextcloud": {
            "label":"Nextcloud", "type":"oauth", "image":"nextcloud.png", "scope": "user:read:email", "authorize": "https://{domain}/index.php/apps/oauth2/authorize", "token": "https://{domain}/index.php/apps/oauth2/api/v1/token", "userinfo": "https://{domain}/ocs/v2.php/cloud/user?format=json", "guide":"https://plugins.miniorange.com/joomla-oauth-sign-on-sso-using-nextcloud", "logo_class":"fa fa-lock"
        },
        "twitch": {
            "label":"Twitch", "type":"oauth", "image":"twitch.png", "scope": "Analytics:read:extensions", "authorize": "https://id.twitch.tv/oauth2/authorize", "token": "https://id.twitch.tv/oauth2/token", "userinfo": "https://id.twitch.tv/oauth2/userinfo", "guide":"https://plugins.miniorange.com/twitch-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "wildApricot": {
            "label":"Wild Apricot", "type":"oauth", "image":"wildApricot.png", "scope": "auto", "authorize": "https://{domain}/sys/login/OAuthLogin", "token": "https://oauth.wildapricot.org/auth/token", "userinfo": "https://api.wildapricot.org/v2.1/accounts/{accountid}/contacts/me", "guide":"https://plugins.miniorange.com/wildapricot-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "connect2id": {
            "label":"Connect2id", "type":"oauth", "image":"connect2id.png", "scope": "openid", "authorize": "https://c2id.com/login", "token": "https://{domain}/token", "userinfo": "https://{domain}/userinfo", "guide":"https://plugins.miniorange.com/connect2id-single-sign-on-sso-joomla-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "miniorange": {
            "label":"miniOrange", "type":"oauth", "image":"miniorange.png", "scope": "openid", "authorize": "https://login.xecurify.com/moas/idp/openidsso", "token": "https://login.xecurify.com/moas/rest/oauth/token", "userinfo": "https://logins.xecurify.com/moas/rest/oauth/getuserinfo", "guide":"https://plugins.miniorange.com/miniorange-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "orcid": {
            "label":"ORCID", "type":"openidconnect", "image":"orcid.png", "scope": "openid", "authorize": "https://orcid.org/oauth/authorize", "token": "https://orcid.org/oauth/token", "userinfo": "", "guide":"https://plugins.miniorange.com/orcid-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "diaspora": {
            "label":"Diaspora", "type":"openidconnect", "image":"diaspora.png", "scope": "openid", "authorize": "https://{domain}/api/openid_connect/authorizations/new", "token": "https://{domain}/api/openid_connect/access_tokens", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
        },
        "timezynk": {
            "label":"Timezynk", "type":"oauth", "image":"timezynk.png", "scope": "read:user", "authorize": "https://api.timezynk.com/api/oauth2/v1/auth", "token": "https://api.timezynk.com/api/oauth2/v1/token", "userinfo": "https://api.timezynk.com/api/oauth2/v1/userinfo", "guide":"", "logo_class":"fa fa-lock"
        },
        "Amazon": {
            "label":"Amazon", "type":"oauth", "image":"cognito.png", "scope": "profile", "authorize": "https://www.amazon.com/ap/oa", "token": "https://api.amazon.com/auth/o2/token", "userinfo": "https://api.amazon.com/user/profile", "guide":"https://plugins.miniorange.com/amazon-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "Office 365": {
            "label":"Office 365", "type":"oauth", "image":"microsoft.webp", "scope": "openid email profile", "authorize": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/authorize", "token": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/token", "userinfo": "https://graph.microsoft.com/beta/me", "guide":"https://plugins.miniorange.com/joomla-oauth-single-sign-on-sso-using-office365", "logo_class":"fa fa-lock"
        },
        "Instagram": {
            "label":"Instagram", "type":"oauth", "image":"instagram.png", "scope": "user_profile user_media", "authorize": "https://api.instagram.com/oauth/authorize", "token": "https://api.instagram.com/oauth/access_token", "userinfo": "https://graph.instagram.com/me?fields=id,username&access_token=", "guide":"https://plugins.miniorange.com/instagram-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "Line":{
            "label":"Line", "type":"oauth", "image":"line.webp", "scope": "profile openid email", "authorize": "https://access.line.me/oauth2/v2.1/authorize", "token": "https://api.line.me/oauth2/v2.1/token", "userinfo": "https://api.line.me/v2/profile", "guide":"https://plugins.miniorange.com/line-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "PingFederate": {
            "label":"PingFederate", "type":"oauth", "image":"ping.webp", "scope": "openid", "authorize": "https://{domain}/as/authorization.oauth2", "token": "https://{domain}/as/token.oauth2", "userinfo": "https://{domain}/idp/userinfo.oauth2", "guide":"https://plugins.miniorange.com/ping-federate-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "OpenAthens": {
            "label":"OpenAthens", "type":"oauth", "image":"openathens.webp", "scope": "openid", "authorize": "https://sp.openathens.net/oauth2/authorize", "token": "https://sp.openathens.net/oauth2/token", "userinfo": "https://sp.openathens.net/oauth2/userInfo", "guide":"https://plugins.miniorange.com/openathens-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "Intuit": {
            "label":"Intuit", "type":"oauth", "image":"intuit.webp", "scope": "openid email profile", "authorize": "https://appcenter.intuit.com/connect/oauth2", "token": "https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer", "userinfo": "https://accounts.platform.intuit.com/v1/openid_connect/userinfo", "guide":"https://plugins.miniorange.com/oauth-openid-connect-single-sign-on-sso-into-joomla-using-intuit", "logo_class":"fa fa-lock"
        },
        "Twitter": {
            "label":"Twitter", "type":"oauth", "image":"twitter-logo.webp", "scope": "email", "authorize": "https://api.twitter.com/oauth/authorize", "token": "https://api.twitter.com/oauth2/token", "userinfo": "https://api.twitter.com/1.1/users/show.json?screen_name=here-comes-twitter-screen-name", "guide":"https://plugins.miniorange.com/twitter-sso-single-sign-on-joomla-using-oauth-client-openid-connect", "logo_class":"fa fa-lock"
        },
        "WordPress": {
            "label":"WordPress", "type":"oauth", "image":"intuit.webp", "scope": "profile openid email custom", "authorize": "http://{site_base_url}/wp-json/moserver/authorize", "token": "http://{site_base_url}/wp-json/moserver/token", "userinfo": "http://{site_base_url}/wp-json/moserver/resource", "guide":"https://plugins.miniorange.com/oauth-openid-connect-single-sign-on-sso-into-joomla-using-wordpress", "logo_class":"fa fa-lock"
        },
        "Subscribestar": {
            "label":"Subscribestar", "type":"oauth", "image":"Subscriberstar-logo.png", "scope": "user.read user.email.read", "authorize": "https://www.subscribestar.com/oauth2/authorize", "token": "https://www.subscribestar.com/oauth2/token", "userinfo": "https://www.subscribestar.com/api/graphql/v1?query={user{name,email}}", "guide":"https://plugins.miniorange.com/subscribestar-oauth-openid-connect-single-sign-on-sso-into-joomla-subscribestar-sso-login", "logo_class":"fa fa-lock"
        },
        "Classlink": {
            "label":"Classlink", "type":"oauth", "image":"classlink.webp", "scope": "email profile oneroster full", "authorize": "https://launchpad.classlink.com/oauth2/v2/auth", "token": "https://launchpad.classlink.com/oauth2/v2/token", "userinfo": "https://nodeapi.classlink.com/v2/my/info", "guide":"https://plugins.miniorange.com/classlink-oauth-sso-openid-connect-single-sign-on-in-joomla-classlink-sso-login", "logo_class":"fa fa-lock"
        },
        "HP": {
            "label":"HP", "type":"oauth", "image":"hp-logo.webp", "scope": "read", "authorize": "https://{hp_domain}/v1/oauth/authorize", "token": "https://{hp_domain}/v1/oauth/token", "userinfo": "https://{hp_domain}/v1/userinfo", "guide":"https://plugins.miniorange.com/hp-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "Basecamp": {
            "label":"Basecamp", "type":"oauth", "image":"basecamp-logo.webp", "scope": "openid", "authorize": "https://launchpad.37signals.com/authorization/new?type=web_server", "token": "https://launchpad.37signals.com/authorization/token?type=web_server", "userinfo": "https://launchpad.37signals.com/authorization.json", "guide":"https://plugins.miniorange.com/basecamp-oauth-and-openid-connect-single-sign-on-sso-login", "logo_class":"fa fa-lock"
        },
        "Feide": {
            "label":"Feide", "type":"oauth", "image":"feide-logo.webp", "scope": "openid", "authorize": "https://auth.dataporten.no/oauth/authorization", "token": "https://auth.dataporten.no/oauth/token", "userinfo": "https://auth.dataporten.no/openid/userinfo", "guide":"https://plugins.miniorange.com/feide-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "Freja EID": {
            "label":"Freja EID", "type":"openidconnect", "image":"frejaeid-logo.webp", "scope": "openid profile email", "authorize": "https://oidc.prod.frejaeid.com/oidc/authorize", "token": "https://oidc.prod.frejaeid.com/oidc/token", "userinfo": "", "guide":"https://plugins.miniorange.com/freja-eid-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "ServiceNow": {
            "label":"ServiceNow", "type":"oauth", "image":"servicenow-logo.webp", "scope": "email profile", "authorize": "https://{your-servicenow-domain}/oauth_auth.do", "token": "https://{your-servicenow-domain}/oauth_token.do", "userinfo": "https://{your-servicenow-domain}/{base-api-path}?access_token=", "guide":"https://plugins.miniorange.com/servicenow-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "IMIS": {
            "label":"IMIS", "type":"oauth", "image":"imis-logo.webp", "scope": "openid", "authorize": "https://{your-imis-domain}/sso-pages/Aurora-SSO-Redirect.aspx", "token": "https://{your-imis-domain}/token", "userinfo": "https://{your-imis-domain}/api/iqa?queryname=$/Bearer_Info_Aurora", "guide":"https://plugins.miniorange.com/imis-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "OpenedX": {
            "label":"OpenedX", "type":"oauth", "image":"openedx-logo.webp", "scope": "email profile", "authorize": "https://{your-domain}/oauth2/authorize", "token": "https://{your-domain}/oauth2/access_token", "userinfo": "https://{your-domain}/api/mobile/v1/my_user_info", "guide":"https://plugins.miniorange.com/open-edx-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "Elvanto": {
            "label":"Elvanto", "type":"openidconnect", "image":"elvanto-logo.webp", "scope": "ManagePeople", "authorize": "https://api.elvanto.com/oauth?", "token": "https://api.elvanto.com/oauth/token", "userinfo": "", "guide":"https://plugins.miniorange.com/elvanto-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "DigitalOcean": {
            "label":"DigitalOcean", "type":"oauth", "image":"digitalocean-logo.webp", "scope": "read", "authorize": "https://cloud.digitalocean.com/v1/oauth/authorize", "token": "https://cloud.digitalocean.com/v1/oauth/token", "userinfo": "https://api.digitalocean.com/v2/account", "guide":"https://plugins.miniorange.com/digital-ocean-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "UNA": {
            "label":"UNA", "type":"openidconnect", "image":"una-logo.webp", "scope": "basic", "authorize": "https://{site-url}.una.io/oauth2/authorize?", "token": "https://{site-url}.una.io/oauth2/access_token", "userinfo": "", "guide":"https://plugins.miniorange.com/una-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
        },
        "MemberClicks": {
			"label":"MemberClicks", "type":"oauth", "image":"memberclicks-logo.webp", "scope": "read write", "authorize": "https://{orgId}.memberclicks.net/oauth/v1/authorize", "token": "https://{orgId}.memberclicks.net/oauth/v1/token", "userinfo": "https://{orgId}.memberclicks.net/api/v1/profile/me", "guide":"https://plugins.miniorange.com/memberclicks-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"MineCraft": {
			"label":"MineCraft", "type":"openidconnect", "image":"minecraft-logo.webp", "scope": "openid", "authorize": "https://login.live.com/oauth20_authorize.srf", "token": "https://login.live.com/oauth20_token.srf", "userinfo": "", "guide":"https://plugins.miniorange.com/minecraft-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"Neon CRM": {
			"label":"Neon CRM", "type":"oauth", "image":"neon-logo.webp", "scope": "openid", "authorize": "https://{your Neon CRM organization id}.z2systems.com/np/oauth/auth", "token": "https://{your Neon CRM organization id}.z2systems.com/np/oauth/token", "userinfo": "https://api.neoncrm.com/neonws/services/api/account/retrieveIndividualAccount?accountId=", "guide":"https://plugins.miniorange.com/neoncrm-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"Canvas": {
			"label":"Canvas", "type":"oauth", "image":"canvas-logo.webp", "scope": "openid profile", "authorize": "https://{your-site-url}/login/oauth2/auth", "token": "https://{your-site-url}/login/oauth2/token", "userinfo": "https://{your-site-url}/login/v2.1/users/self", "guide":"https://plugins.miniorange.com/canvas-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"Ticketmaster": {
			"label":"Ticketmaster", "type":"openidconnect", "image":"ticketmaster-logo.webp", "scope": "openid email", "authorize": "https://auth.ticketmaster.com/as/authorization.oauth2", "token": "https://auth.ticketmaster.com/as/token.oauth2", "userinfo": "", "guide":"https://plugins.miniorange.com/ticketmaster-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"Mindbody": {
			"label":"Mindbody", "type":"openidconnect", "image":"mindbody-logo.webp", "scope": "email profile openid", "authorize": "https://signin.mindbodyonline.com/connect/authorize", "token": "https://signin.mindbodyonline.com/connect/token", "userinfo": "", "guide":"https://plugins.miniorange.com/mindbody-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"iGov": {
			"label":"iGov", "type":"openidconnect", "image":"iGov-logo.webp", "scope": "openid profile", "authorize": "https://idp.government.gov/oidc/authorization", "token": "https://idp.government.gov/token", "userinfo": "", "guide":"https://plugins.miniorange.com/igov-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"LearnWorlds": {
			"label":"LearnWorlds", "type":"openidconnect", "image":"learnworlds-logo.webp", "scope": "openid profile", "authorize": "https://api.learnworlds.com/oauth", "token": "https://api.learnworlds.com/oauth2/access_token", "userinfo": "", "guide":"https://plugins.miniorange.com/learnworlds-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
		"Otoy": {
			"label":"Otoy", "type":"oauth", "image":"otoy-logo.webp", "scope": "openid", "authorize": "https://account.otoy.com/oauth/authorize", "token": "https://account.otoy.com/oauth/token", "userinfo": "https://account.otoy.com/api/v1/user.json", "guide":"https://plugins.miniorange.com/otoy-sso-single-sign-on-into-joomla-using-oauth-openid-connect", "logo_class":"fa fa-lock"
		},
        "other": {
            "label":"Custom OAuth", "type":"oauth", "image":"customapp.png", "scope": "", "authorize": "", "token": "", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
        },
        "openidconnect": {
            "label":"Custom OpenID Connect App", "type":"openidconnect", "image":"customapp.png", "scope": "", "authorize": "", "token": "", "userinfo": "", "guide":"", "logo_class":"fa fa-lock"
        }
    }';
}
function getAppData()
{
    return '{
        "azure": {
            "0":"both","1":"Tenant"
        },
        "azureb2c": {
            "0":"both","1":"Tenant,Policy"
        },
        "cognito": {
            "0":"both","1": "Domain"
        },
        "adfs": {
            "0":"both","1":"Domain"
        },
        "whmcs": {
            "0":"both","1":"Domain"
        },
        "keycloak": {
            "0":"both","1":"Domain,Realm"
        },
        "invisioncommunity": {
            "0":"both","1":"Domain"
        },
        "bitrix24": {
            "0":"both","1":"Domain"
        },
        "wso2": {
            "0":"both","1":"Domain"
        },
        "okta": {
            "0":"header","1":"Domain"
        },
        "onelogin": {
            "0":"both","1":"Domain"
        },
        "gluu": {
            "0":"both","1": "Domain" 
        },
        "zendesk": {
            "0":"both","1":"Domain"
        },
        "laravel": {
            "0":"both","1":"Domain"
        },
        "identityserver": {
            "0":"both","1":"Domain"
        },
        "nextcloud": {
            "0":"both","1":"Domain"
        },
        "wildApricot": {
            "0":"both","1":"Domain,AccountId"
        },
        "connect2id": {
            "0":"both","1":"Domain"
        },
        "diaspora": {
            "0":"both","1":"Domain" 
        },
        "Office 365": {
            "0":"both","1":"Tenant" 
        },
        "PingFederate": {
            "0":"both","1":"Domain"
        },
        "HP": {
            "0":"both","1":"Domain"
        },
        "Neon CRM": {
            "0":"both","1":"Domain"
        },
        "Canvas": {
            "0":"both","1":"Domain"
        },
        "UNA": {
            "0":"both","1":"Domain"
        },
        "OpenedX": {
            "0":"both","1":"Domain"
        },
        "ServiceNow": {
            "0":"both","1":"Domain"
        },
        "WordPress": {
            "0":"both","1":"Domain"
        },
        "MemberClicks": {
            "0":"both","1":"Domain"
        },
        "IMIS": {
            "0":"both","1":"Domain"
        }
    }';
}
function selectAppByIcon()
{
    $appArray = json_decode(getAppJson(), true);
    $ImagePath=JURI::base().'components/com_miniorange_oauth/assets/images/';
    $imageTableHtml = "<table id='moAuthAppsTable'>";
    $i=1;
    $PreConfiguredApps = array_slice($appArray, 0, count($appArray)-2);
    foreach ($PreConfiguredApps as $key => $value) {
        $img=$ImagePath.$value['image'];
        if ($i%6==1) {
            $imageTableHtml.='<tr>';
        }
        $imageTableHtml=$imageTableHtml."<td class='mo_boot_border' moAuthAppSelector='".$value['label']."'><a class='mo_boot_select_app' href='".JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp='.$key)."''><div><img style='max-height:60px;max-width:60px;' src='".$img."'><br><p>".$value['label']."</p></div></a></td>";
        if ($i%6==0 || $i==count($appArray)) {
            $imageTableHtml.='</tr>';
        }
        $i++;
    }
    $imageTableHtml.='</table>';
    ?> 
    <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 ">
        <div class="mo_boot_col-sm-12 mo_boot_mt-4">
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-11 m-0 p-0">
                    <input type="text" class="mo_boot_form-control" name="appsearch" id="moAuthAppsearchInput" value="" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTH_SELECT_APP');?>">
                </div>
                <div class="mo_boot_col-sm-1 m-0 p-0 mo_boot_border mo_boot_btn-primary mo_boot_text-center mo_boot_align-middle">
                    <span class=""><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-4">
            <?php
                echo $imageTableHtml;
    ?>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-4">
            <div class="mo_boot_row">
                <div class="mo_boot_col-sm-12 mo_boot_my-2">
                    <h6><?php echo JText::_('COM_MINIORANGE_OAUTH_CUSTOM_APPLICATIONS');?></h6>
                    <br>
                    <span class="mo_boot_p-1 mo_boot_text-dark"><?php echo JText::_('COM_MINIORANGE_OAUTH_CUSTOM_APPLICATIONS_NOTE');?></span>
                </div>
                <div class="mo_boot_col-sm-6 mo_boot_my-5 mo_boot_text-center" moAuthAppSelector='moCustomOuth2App'>
                    <a class="mo_boot_select_app" href="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=other');?>">
                        <div class=" mo_boot_border" style="background:#fff;border: 1px solid #ddd;">
                            <img style='max-height:60px;max-width:60px;' alt="" src="<?php echo  $ImagePath.$appArray['other']['image']; ?>"><br><p><?php echo $appArray['other']['label'];?></p>
                        </div>
                    </a>
                </div>
                <div class="mo_boot_col-sm-6 mo_boot_my-5 mo_boot_text-center"  moAuthAppSelector='moCustomOpenIdConnectApp'>
                    <a class="mo_boot_select_app" href="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=openidconnect');?>">
                        <div>
                            <img style='max-height:60px;max-width:60px;' alt="" src="<?php echo  $ImagePath.$appArray['openidconnect']['image']; ?>"><br><p><?php echo $appArray['openidconnect']['label'];?></p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
function selectCustomApp()
{
    $appArray = json_decode(getAppJson(), true);
    $ImagePath=JURI::base().'components/com_miniorange_oauth/assets/images/';
    ?> 
    <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3">
        <div class="mo_boot_col-sm-12 mo_boot_my-2">
            <h6><?php echo JText::_('COM_MINIORANGE_OAUTH_CUSTOM_APPLICATIONS');?></h6>
            <br>
            <span class="mo_boot_p-1 mo_boot_text-dark"><?php echo JText::_('COM_MINIORANGE_OAUTH_CUSTOM_APPLICATIONS_NOTE');?></span>
        </div>
        <div class="mo_boot_col-sm-6 mo_boot_my-5 mo_boot_text-center" moAuthAppSelector='moCustomOuth2App'>
            <a class="mo_boot_select_app" href="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=other');?>">
                <div class=" mo_boot_border" style="background:#fff;border: 1px solid #ddd;">
                    <img style='max-height:60px;max-width:60px;' alt="" src="<?php echo  $ImagePath.$appArray['other']['image']; ?>"><br><p><?php echo $appArray['other']['label'];?></p>
                </div>
            </a>
        </div>
        <div class="mo_boot_col-sm-6 mo_boot_my-5 mo_boot_text-center"  moAuthAppSelector='moCustomOpenIdConnectApp'>
            <a class="mo_boot_select_app" href="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&moAuthAddApp=openidconnect');?>">
                <div>
                    <img style='max-height:60px;max-width:60px;' alt="" src="<?php echo  $ImagePath.$appArray['openidconnect']['image']; ?>"><br><p><?php echo $appArray['openidconnect']['label'];?></p>
                </div>
            </a>
        </div>
    </div>
    <?php
}
function getAppDetails()
{
    $db = JFactory::getDbo();
    $query = $db->getQuery(true);
    $query->select('*');
    $query->from($db->quoteName('#__openjoomlaoauth_config'));
    $query->where($db->quoteName('id') . " = 1");
    $db->setQuery($query);
    return $db->loadAssoc();
}
function configuration($OauthApp, $appLabel)
{
    global $license_tab_link;
    $attribute = getAppDetails();
    $appJson = json_decode(getAppJson(), true);
    $appData = json_decode(getAppData(), true);
    if ($appJson[$appLabel]["guide"]!="") {
        $guide=$appJson[$appLabel]["guide"];
    } else {
        $guide="https://plugins.miniorange.com/guide-to-enable-joomla-oauth-client";
    }
    $mo_oauth_app = $appLabel;
    $custom_app = "";
    $client_id = "";
    $client_secret = "";
    $redirecturi = JURI::root();
    $email_attr = "";
    $full_name_attr = "";
    $user_name_attr = "";
    $isAppConfigured = false;
    $mo_oauth_in_header = "checked=true";
    $mo_oauth_in_body   = "";
    $login_link_check="1";
    if (isset($attribute['in_header_or_body'])) {
        if ($attribute['in_header_or_body']=='inBody') {
            $mo_oauth_in_header = "";
            $mo_oauth_in_body   = "checked=true";
        } elseif ($attribute['in_header_or_body']=='inHeader') {
            $mo_oauth_in_header = "checked=true";
            $mo_oauth_in_body   = "";
        } elseif ($attribute['in_header_or_body']=='both') {
            $mo_oauth_in_header = "checked=true";
            $mo_oauth_in_body   = "checked=true";
        }
    } else {
        if (isset($appData[$appLabel]) && $appData[$appLabel][0]=='both') {
            $mo_oauth_in_header = "checked=true";
            $mo_oauth_in_body   = "checked=true";
        } elseif (isset($appData['appLabel']) && $appData['appLabel'][0]=='inBody') {
            $mo_oauth_in_header = "";
            $mo_oauth_in_body   = "checked=true";
        } elseif (isset($appData['appLabel']) && $appData['appLabel'][0]=='inHeader') {
            $mo_oauth_in_header = "checked=true";
            $mo_oauth_in_body   = "";
        }
    }
    if (isset($attribute['client_id'])) {
        $mo_oauth_app = empty($attribute['appname'])?$appLabel:$attribute['appname'];
        $custom_app = $attribute['custom_app'];
        $client_id = $attribute['client_id'];
        $client_secret = $attribute['client_secret'];
        $isAppConfigured = empty($client_id) || empty($client_secret) || empty($custom_app)||empty($attribute['redirecturi'])?false:true;
        $step1Check = empty($attribute['redirecturi'])?false:true;
        $step2Check = empty($client_id) || empty($client_secret) || empty($custom_app)||empty($attribute['redirecturi'])?false:true;
        $app_scope = empty($attribute['app_scope'])?$OauthApp['scope']:$attribute['app_scope'];
        $authorize_endpoint = empty($attribute['authorize_endpoint'])?null:$attribute['authorize_endpoint'];
        $access_token_endpoint = empty($attribute['access_token_endpoint'])?null:$attribute['access_token_endpoint'];
        $user_info_endpoint = empty($attribute['user_info_endpoint'])?null:$attribute['user_info_endpoint'];
        $email_attr = $attribute['email_attr'];
        $full_name_attr = $attribute['full_name_attr'];
        $user_name_attr = $attribute['user_name_attr'];
        $attributesNames = $attribute['test_attribute_name'];
        $step3Check = empty($email_attr)?false:true;
        $redirecturi = explode('//', JURI::root())[1];
        $attributesNames = explode(",", $attributesNames);

    }
    $get =JFactory::getApplication()->input->get->getArray();
    $progress = isset($get['progress'])?$get['progress']:"step1";

    ?>
    <div class="mo_boot_row m-0 p-1" style="box-shadow: 0px 0px 15px 5px lightgray;">
        <div class="mo_boot_col-sm-2 m-0 p-0" style="border-right:1px solid #001b4c">
            <div class="mo_boot_row m-0 p-0">
                <div class="mo_boot_col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this , '#mo_redirectUrl_setting')" <?php echo(($progress=='step1')?'class="mo_sub_menu mo_sub_menu_active"':'class="mo_sub_menu"'); ?> >
                        <span>Step 1 <small>[Redirect URI]</small></span> <span class="mo_boot_float-right"><i class="mo_boot_text-success fa-solid fa-circle-check" <?php echo($step1Check?'style="display:block"':'style="display:none"'); ?> ></i></span>
                    </div>
                </div>
            </div>
            <div class="mo_boot_row m-0 p-0">
                <div class="mo_boot_col-sm-12 m-0 p-0">
                    
                    <div <?php if (1) {
                        echo "onclick = \"changeSubMenu(this,'#mo_client_setting')\" ";
                    } else {
                        echo "style='cursor:not-allowed;'";
                    }?> title="Configure the Step 1 First" <?php echo(($progress=='step2')?'class="mo_sub_menu mo_sub_menu_active"':'class="mo_sub_menu"'); ?>>
                        <span>Step 2 <small> [Client ID & Secret]</small></span></span> <span class="mo_boot_float-right"><i class=" mo_boot_text-success fa-solid fa-circle-check" <?php echo($step2Check?'style="display:block"':'style="display:none"'); ?>></i></span>
                    </div>
                </div>
            </div>
            <div class="mo_boot_row m-0 p-0">
                <div class="mo_boot_col-sm-12 m-0 p-0">
                    <div <?php if ($client_secret!="") {
                        echo "onclick = \"changeSubMenu(this,'#mo_attribute_setting')\" ";
                    } else {
                        echo "style='cursor:not-allowed'";
                    }?> title="Configure the Step 2 First" <?php echo(($progress=='step3')?'class="mo_sub_menu mo_sub_menu_active"':'class="mo_sub_menu"'); ?>>
                        <span>Step 3 <small>[Attribute Mapping]</small></span></span> <span class="mo_boot_float-right"><i class=" mo_boot_text-success fa-solid fa-circle-check" <?php echo($step3Check?'style="display:block"':'style="display:none"'); ?>></i></span>
                    </div>
                </div>
            </div>
            <div class="mo_boot_row m-0 p-0">
                <div  class="mo_boot_col-sm-12 m-0 p-0">
                    <div <?php if ($email_attr!="") {
                        echo "onclick = \"changeSubMenu(this,'#mo_sso_url')\" ";
                    } else {
                        echo "style='cursor:not-allowed'";
                    }?> title="Configure the Step 3 first" <?php echo(($progress=='step4')?'class="mo_sub_menu mo_sub_menu_active"':'class="mo_sub_menu"'); ?>>
                        <span>Step 4 <small>[SSO URL]</small></span></span>
                    </div>
                </div>
            </div>
            <hr style="background-color:black">
            <div class="mo_boot_row m-0 p-0">
                <div  class="mo_boot_col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this,'#mo_importexport_setting')" class="mo_sub_menu">
                        <span>Import / Export Configuration </span>
                    </div>
                </div>
            </div>
            <div class="mo_boot_row m-0 mt-3 p-0">
                <div  class="mo_boot_col-sm-12 m-0 p-0">
                    <div class="mo_boot_text-center">
                        <?php  echo "<a href='index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.clearConfig'
                                    class='mo_boot_btn mo_boot_pb-1 mo_boot_btn-danger' style='padding:2px 5px'>".JText::_('COM_MINIORANGE_OAUTH_DELETE_APPLICATION')."</a>";
    ?> 
                    </div>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-10">
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" <?php echo(($progress=='step1')?'style="display:block"':'style="display:none"'); ?> id="mo_redirectUrl_setting">
                <div class="mo_boot_col-sm-12" id="mo_oauth_attributemapping">
                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-12">
                            <div class="mo_boot_row mo_boot_mt-3" style="padding:10px;">
                                <div class="mo_boot_col-sm-12">
                                    <div class="mo_boot_row mo_boot_mt-3">
                                        <div class="mo_boot_col-sm-3">
                                            <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_APPLICATION');?></strong>
                                        </div>
                                        <div class="mo_boot_col-sm-8">
                                            <?php echo "<span style='background:#e9ecef;cursor:not-allowed;padding:2px; border:1px solid #e9ecef'>".$OauthApp['label']."</span>";?>
                                            <input type="hidden" name="mo_oauth_app_name" value="<?php echo $mo_oauth_app; ?>">
                                        </div>
                                    </div>
                                    <div class="mo_boot_row mo_boot_mt-3">
                                        <div class="mo_boot_col-sm-3">
                                            <strong><?php echo JText::_('COM_MINIORANGE_OAUTH_CALLBACK_URL');?></strong>
                                        </div>
                                        <div class="mo_boot_col-sm-7">
                                            <form id="oauth_config_form_step1" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveConfig'); ?>">  
                                                <input type="hidden" name="mo_oauth_app_name" value="<?php echo $mo_oauth_app; ?>">
                                                <input type="hidden" name="oauth_config_form_step1" value="true">
                                                <div class="mo_boot_row m-0 p-0">
                                                    <div class="mo_boot_col-sm-2 m-0 p-0">
                                                        <select class="d-inline-block mo_boot_form-control" name="callbackurlhttp" id="callbackurlhttp">
                                                            <option value="http://" selected>http</option>
                                                            <option value="https://">https</option>
                                                        </select>
                                                    </div>
                                                    <div class="mo_boot_col-sm-10 m-0 p-0">
                                                        <input class="mo_boot_form-control" id="callbackurl" name="callbackurl" type="text" readonly  value='<?php echo $redirecturi; ?>'>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="mo_boot_col-sm-1">
                                            <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard('#callbackurl','#callbackurlhttp');" style="color:red;background:#ccc;" ;>
                                                <span class="copytooltiptext">Copied!</span> 
                                            </em>
                                        </div>
                                        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
                                            <small><?php echo JText::_('COM_MINIORANGE_OAUTH_CALLBACK_URL_NOTE');?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mo_boot_col-sm-12">
                            <div class="mo_boot_row mo_boot_mt-4">
                                <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-right">
                                    <button name="send_query" onclick="step1Submit()" style="margin-bottom:3%;" class="mo_boot_btn mo_boot_btn-primary p-2 px-4">Save & Next <i class="fa-solid fa-arrow-right"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>  
                </div>
                <script>
                    function step1Submit()
                    {
                        jQuery("#oauth_config_form_step1").submit();
                    }
                </script>
            </div>
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" <?php echo(($progress=='step2')?'style="display:block"':'style="display:none"'); ?> id="mo_client_setting">
                <div class="mo_boot_col-sm-12 mo_boot_mt-5">
                    <form id="oauth_config_form_step2" name="" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveConfig'); ?>">  
                        <input type="hidden" name="oauth_config_form_step2" value="true">                   
                        <div class="mo_boot_row mo_boot_m-1 mo_boot_mt-3">
                            <div class="mo_boot_col-sm-12">
                                <div class="mo_boot_row">
                                    <div class="mo_boot_col-sm-12">
                                        <input type="hidden" id="mo_oauth_custom_app_name" name="mo_oauth_custom_app_name" value='<?php echo $OauthApp['label']; ?>' required>
                                        <input type="hidden" name="moOauthAppName" value="<?php echo $appLabel; ?>">
                                        <input type="hidden" name="mo_oauth_app_name" value="<?php echo $mo_oauth_app; ?>">
                                    </div>
                                </div>
                                <div class="mo_boot_row mo_boot_mt-3">
                                    <div class="mo_boot_col-sm-3">
                                        <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_CLIENT_ID'); ?></strong>
                                    </div>
                                    <div class="mo_boot_col-sm-7">
                                        <input placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTH_CLIENT_ID_PLACEHOLDER');?>" class="mo_boot_form-control" required="" type="text" name="mo_oauth_client_id" id="mo_oauth_client_id" value='<?php echo $client_id; ?>'>
                                    </div>
                                </div>
                                <div class="mo_boot_row mo_boot_mt-3">
                                    <div class="mo_boot_col-sm-3">
                                        <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_CLIENT_SECRET'); ?></strong>
                                    </div>
                                    <div class="mo_boot_col-sm-7">
                                        <input placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTH_CLIENT_SECRET_PLACEHOLDER');?>" class="mo_boot_form-control" type="text" id="mo_oauth_client_secret" name="mo_oauth_client_secret" value='<?php echo $client_secret; ?>'>
                                    </div>
                                </div>
                                <?php
        if ($authorize_endpoint==null) {
            if (isset($appData[$appLabel])) {
                $fields = explode(",", $appData[$appLabel]['1']);
                foreach ($fields as $key => $value) {
                    if ($value == 'Tenant') {
                        $placeholder = JText::_('COM_MINIORANGE_OAUTH_ENTER_THE_TENANT_ID');
                    } elseif ($value=='Domain') {
                        $placeholder = JText::_('COM_MINIORANGE_OAUTH_ENTER_THE_DOMAIN');
                    } else {
                        $placeholder = JText::_('COM_MINIORANGE_OAUTH_ENTER_THE_DETAILS').$value ;
                    }
                    echo '<div class="mo_boot_row mo_boot_mt-3"><div class="mo_boot_col-sm-3">
                                                <strong><span class="mo_oauth_highlight">*</span>'.$value.'</strong>
                                                </div>
                                                <div class="mo_boot_col-sm-7">
                                                    <input class="mo_boot_form-control" placeholder="'.$placeholder.'" type="text" id="" name="'.$value.'" value="" required>
                                                </div></div>';
                }
            } else { ?>
                                            <div class="mo_boot_row mo_boot_mt-3">
                                                <div class="mo_boot_col-sm-3">
                                                    <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_APP_SCOPE');?></strong>
                                                </div>
                                                <div class="mo_boot_col-sm-7">
                                                    <input class="mo_boot_form-control" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTH_APP_SCOPE_PLACEHOLDER');?>" type="text" id="mo_oauth_scope" name="mo_oauth_scope" value='<?php echo $app_scope ?>' required>
                                                </div>
                                            </div>
                                            <div class="mo_boot_row mo_boot_mt-3">
                                                <div class="mo_boot_col-sm-3">
                                                    <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_AUTHORIZE_ENDPOINT');?></strong>
                                                </div>
                                                <div class="mo_boot_col-sm-7">
                                                    <input class="mo_boot_form-control" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTH_AUTHORIZE_ENDPOINT_PLACEHOLDER');?>" type="text" id="mo_oauth_authorizeurl" name="mo_oauth_authorizeurl" value='<?php echo $appJson[$appLabel]["authorize"] ?>' required>
                                                </div>
                                                <div class="mo_boot_col-sm-1">
                                                    <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" ; onclick="copyToClipboard('#mo_oauth_authorizeurl');" style="color:red;background:#ccc;" ;>
                                                        <span class="copytooltiptext">Copied!</span>
                                                    </em>
                                                </div>
                                            </div>
                                            <div class="mo_boot_row mo_boot_mt-3">
                                                <div class="mo_boot_col-sm-3">
                                                    <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_TOKEN_ENDPOINT'); ?></strong>
                                                </div>
                                                <div class="mo_boot_col-sm-7">
                                                    <input class="mo_boot_form-control" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTH_TOKEN_ENDPOINT_PLACEHOLDER');?>" type="text" id="mo_oauth_accesstokenurl" name="mo_oauth_accesstokenurl" value='<?php echo $appJson[$appLabel]['token']; ?>' required>
                                                </div>
                                                <div class="mo_boot_col-sm-1">
                                                    <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard('#mo_oauth_accesstokenurl');" style="color:red;background:#ccc;" ;>
                                                        <span class="copytooltiptext">Copied!</span>
                                                    </em>
                                                </div>
                                            </div>                           
                                            <?php
                    if (!isset($OauthApp['type']) || $OauthApp['type']=='oauth') {?>
                                                    <div class="mo_boot_row mo_boot_mt-3" id="mo_oauth_resourceownerdetailsurl_div">
                                                        <div class="mo_boot_col-sm-3">
                                                            <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_INFO_ENDPOINT'); ?></strong>
                                                        </div>
                                                        <div class="mo_boot_col-sm-7">
                                                            <input class="mo_boot_form-control" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTH_INFO_ENDPOINT_PLACEHOLDER');?>" type="text" id="mo_oauth_resourceownerdetailsurl" name="mo_oauth_resourceownerdetailsurl" value='<?php echo $appJson[$appLabel]['userinfo']; ?>' required>
                                                        </div>
                                                        <div class="mo_boot_col-sm-1">
                                                            <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard('#mo_oauth_resourceownerdetailsurl');" style="color:red;background:#ccc;" ;>
                                                                <span class="copytooltiptext">Copied!</span>
                                                            </em>
                                                        </div>
                                                    </div>
                                            <?php }
                    }
        } else { ?>
                                        <div class="mo_boot_row mo_boot_mt-3">
                                            <div class="mo_boot_col-sm-3">
                                                <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_APP_SCOPE');?></strong>
                                            </div>
                                            <div class="mo_boot_col-sm-7">
                                                <input class="mo_boot_form-control" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTH_APP_SCOPE_PLACEHOLDER');?>" type="text" id="mo_oauth_scope" name="mo_oauth_scope" value='<?php echo $app_scope ?>' required>
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-3">
                                            <div class="mo_boot_col-sm-3">
                                                <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_AUTHORIZE_ENDPOINT'); ?></strong>
                                            </div>
                                            <div class="mo_boot_col-sm-7">
                                                <input class="mo_boot_form-control" type="text" id="mo_oauth_authorizeurl" name="mo_oauth_authorizeurl" value='<?php echo $authorize_endpoint; ?>' required>
                                            </div>
                                            <div class="mo_boot_col-sm-1">
                                                <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" ; onclick="copyToClipboard('#mo_oauth_authorizeurl');" style="color:red;background:#ccc;" ;>
                                                    <span class="copytooltiptext">Copied!</span>
                                                </em>
                                            </div>
                                        </div>
                                        <div class="mo_boot_row mo_boot_mt-3">
                                            <div class="mo_boot_col-sm-3">
                                                <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_TOKEN_ENDPOINT'); ?></strong>
                                            </div>
                                            <div class="mo_boot_col-sm-7">
                                                <input class="mo_boot_form-control" type="text" id="mo_oauth_accesstokenurl" name="mo_oauth_accesstokenurl" value='<?php echo $access_token_endpoint; ?>' required>
                                            </div>
                                            <div class="mo_boot_col-sm-1">
                                                <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard('#mo_oauth_accesstokenurl');" style="color:red;background:#ccc;" ;>
                                                    <span class="copytooltiptext">Copied!</span>
                                                </em>
                                            </div>
                                        </div>
                                        <?php
                if (!isset($OauthApp['type']) || $OauthApp['type']=='oauth') {?>
                                                <div class="mo_boot_row mo_boot_mt-3" id="mo_oauth_resourceownerdetailsurl_div">
                                                    <div class="mo_boot_col-sm-3">
                                                        <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_INFO_ENDPOINT'); ?></strong>
                                                    </div>
                                                    <div class="mo_boot_col-sm-7">
                                                        <input class="mo_boot_form-control" type="text" id="mo_oauth_resourceownerdetailsurl" name="mo_oauth_resourceownerdetailsurl" value='<?php echo $user_info_endpoint; ?>' required>
                                                    </div>
                                                    <div class="mo_boot_col-sm-1">
                                                        <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard('#mo_oauth_resourceownerdetailsurl');" style="color:red;background:#ccc;" ;>
                                                            <span class="copytooltiptext">Copied!</span>
                                                        </em>
                                                    </div>
                                                </div>
                                        <?php }
                }
    ?>    
                                <div class="mo_boot_row mo_boot_mt-3">
                                    <div class="mo_boot_col-sm-3">
                                        <b><?php echo JText::_('COM_MINIORANGE_OAUTH_SET_CLIENT_CREDENTIALS');?></b>
                                    </div>
                                    <div class="mo_boot_col-sm-7">
                                        <input type="checkbox" style='vertical-align: -2px;' name="mo_oauth_in_header" value="1" <?php echo " ".$mo_oauth_in_header; ?>>&nbsp;<?php echo JText::_('COM_MINIORANGE_OAUTH_SET_CREDENTIAL_IN_HEADER');?>
                                        <input type="checkbox" style='vertical-align: -2px;' class="mo_table_textbox" name="mo_oauth_body" value="1" <?php echo " ".$mo_oauth_in_body; ?> >&nbsp; <?php echo JText::_('COM_MINIORANGE_OAUTH_SET_CREDENTIAL_IN_BODY');?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>        
                    <div class="mo_boot_row mo_boot_mt-2">
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-right">
                            <button style="margin-bottom:3%;" class="mo_boot_btn mo_boot_btn-primary p-2 px-4" onclick="step2Submit()">Save Configuration</button>
                        </div>
                    </div>
                    <script>
                        function step2Submit()
                        {
                            jQuery("#oauth_config_form_step2").submit();
                        }
                        
                    </script>
                </div>
            </div>
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" <?php echo(($progress=='step3')?'style="display:block"':'style="display:none"'); ?> id="mo_attribute_setting">
                <div class="mo_boot_col-sm-12 mo_boot_mt-5">
                   <div class="mo_boot_row mo_boot_mt-3">
                        <div class="mo_boot_col-sm-3">
                            <strong>Test Configuration</strong>
                        </div>
                        <div class="mo_boot_col-sm-7">
                            <button style="margin-bottom:3%;" class="mo_boot_btn mo_boot_btn-primary p-2 px-4" onclick="testConfiguration()">Test Configuration</button>
                        </div>
                        <div class="mo_boot_col-sm-12 mo_boot_mb-5">
                            <br>
                            <span>
                               <strong>Note : </strong> Click the "Test Configuration" button to confirm the attributes obtained from the OAuth Provider. Once the test configuration is successful, proceed to configure the attribute mapping below. This ensures that the mapping is based on accurate and validated data from the OAuth Provider.
                            </span>
                        </div>
                    </div>
                    <form id="oauth_mapping_form" name="oauth_config_form" method="post" action="<?php echo JRoute::_('index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.saveMapping'); ?>">
                        <div class="mo_boot_row mo_boot_mt-3">
                            <div class="mo_boot_col-sm-3">
                                <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_EMAIL_ATTR'); ?></strong>
                            </div>
                            <div class="mo_boot_col-sm-7">
                                <?php
        if (count($attributesNames) != 0 && count($attributesNames) != 1) {
            ?>
                                        <select required class="mo_boot_form-control mo_boot_h-100" name="mo_oauth_email_attr" id="mo_oauth_email_attr">
                                            <option value="none" selected><?php echo JText::_('COM_MINIORANGE_OAUTH_EMAIL_ATTR_NOTE');?></option>
                                            <?php
                    foreach ($attributesNames as $key => $value) {
                        if ($value == $email_attr) {
                            $checked = "selected";
                        } else {
                            $checked = "";
                        }
                        if ($value!="") {
                            echo"<option ".$checked." value='".$value."'>".$value."</option>";
                        }
                    }
            ?>
                                        </select>
                                        <?php
        } else {
            ?>
                                        <input type="text" name="" class="mo_boot_form-control" disabled placeholder="Click on Test Configuration button above in order to get the attributes" id="">
                                        <?php
        }
    ?>
                               
                            </div>
                        </div>
                        <div class="mo_boot_row mo_boot_mt-2">
                            <div class="mo_boot_col-sm-3">
                                <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_NAME_ATTR'); ?></strong>
                            </div>
                            <div class="mo_boot_col-sm-7">
                                <?php
        if (count($attributesNames) != 0 && count($attributesNames) != 1) {
            ?>
                                        <select required class="mo_boot_form-control mo_boot_h-100" name="mo_oauth_full_name_attr" id="mo_oauth_full_name_attr">
                                            <option value="none" selected><?php echo JText::_('COM_MINIORANGE_OAUTH_NAME_ATTR_NOTE');?></option>
                                            <?php
                    foreach ($attributesNames as $key => $value) {
                        if ($value == $full_name_attr) {
                            $checked = "selected";
                        } else {
                            $checked = "";
                        }
                        if ($value!="") {
                            echo"<option ".$checked." value='".$value."'>".$value."</option>";
                        }
                    }
            ?>
                                        </select>
                                        <?php
        } else {
            ?>
                                        <input type="text" name="" class="mo_boot_form-control" disabled placeholder="Click on Test Configuration button above in order to get the attributes" id="">
                                        <?php
        }
    ?>
                                
                            </div>
                        </div>
                        <div class="mo_boot_row mo_boot_mt-2">
                            <div class="mo_boot_col-sm-3">
                                <strong><span class="mo_oauth_highlight">*</span><?php echo JText::_('COM_MINIORANGE_OAUTH_USER_NAME_ATTR'); ?></strong>
                            </div>
                            <div class="mo_boot_col-sm-7">
                                <?php
        if (count($attributesNames) != 0 && count($attributesNames) != 1) {
            ?>
                                        <select required class="mo_boot_form-control mo_boot_h-100" name="mo_oauth_user_name_attr" id="mo_oauth_user_name_attr">
                                            <option value="none" selected><?php echo JText::_('COM_MINIORANGE_OAUTH_USER_NAME_ATTR_NOTE');?></option>
                                            <?php
                    foreach ($attributesNames as $key => $value) {
                        if ($value == $user_name_attr) {
                            $checked = "selected";
                        } else {
                            $checked = "";
                        }
                        if ($value!="") {
                            echo"<option ".$checked." value='".$value."'>".$value."</option>";
                        }
                    }
            ?>
                                        </select>
                                        <?php
        } else {
            ?>
                                        <input type="text" name="" class="mo_boot_form-control" disabled placeholder="Click on Test Configuration button above in order to get the attributes" id="">
                                        <?php
        }
    ?>
                                
                            </div>
                        </div>
                        <div class="mo_boot_row mo_boot_mt-4">
                            <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-right">
                                <input type="submit" name="send_query" style="margin-bottom:3%;" class="mo_boot_btn mo_boot_btn-primary p-2" value="Finish Configuration"> 
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 " <?php echo(($progress=='step4')?'style="display:block"':'style="display:none"'); ?> id="mo_sso_url">
                <div class=" mo_boot_col-sm-12 mo_boot_mt-5">
                    <div class="mo_boot_row mo_boot_mt-3 mo_boot_mb-5">
                        <div class="mo_boot_col-sm-12 mo_boot_mb-3">
                            <?php echo JText::_('COM_MINIORANGE_OAUTH_LOGIN_URL_NOTE');?>
                        </div>
                        <div class="mo_boot_col-sm-3">
                            <strong><?php echo JText::_('COM_MINIORANGE_OAUTH_LOGIN_URL');?></strong>
                        </div>
                        <div class="mo_boot_col-sm-8">
                            <input class="mo_boot_form-control" id="loginUrl" type="text" readonly="true" value='<?php echo JURI::root() . '?morequest=oauthredirect&app_name=' . $mo_oauth_app; ?>'>
                        </div>
                        <div class="mo_boot_col-sm-1">
                            <em class="fa fa-pull-right fa-lg fa-copy mo_copy copytooltip" onclick="copyToClipboard('#loginUrl');" style="color:red;background:#ccc;" ;>
                                <span class="copytooltiptext">Copied!</span>
                            </em>
                        </div>
                    </div>
                    <div class="mo_boot_row mo_boot_mt-3 mo_boot_mb-5">
                        <div class="mo_boot_col-sm-12">
                            <hr>
                            <h4><u>Steps to Create a Login button</u></h4>
                            <br>
                            <table class="mo_boot_table mo_boot_table-bordered mo_boot_table-striped">
                                <tr>
                                    <td class="w-15"><strong>STEP 1:</strong></td>
                                    <td>
                                        Navigate to Module Manager -Go to "Extensions" > "Site Modules" from the top menu in the administrator area.
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>STEP 2:</strong> </td>
                                    <td>
                                        Locate and Edit the Login Module-  Look for the "Login" module in the list of modules. Click on its title to edit it.
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>STEP 3:</strong>
                                    </td>
                                    <td>
                                        Adjust Module Position- Check the position where the login module is displayed. Note this position as it will help you understand where the button needs to be placed.
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>STEP 4:</strong>
                                    </td>
                                    <td>
                                        Add Custom HTML Module for the Button-In the Joomla admin, go to "Extensions" > "Modules" > "New" > "Custom HTML".
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>STEP 5:</strong>
                                    </td>
                                    <td>Configure the Custom HTML Module-In the "Custom HTML" module settings:</td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>STEP 6:</strong>
                                    </td>
                                    <td>
                                        Set the title to a relevant name-Add your button HTML code in the module's content section. 
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>STEP 7:</strong>
                                    </td>
                                    <td>Set the Module Position-  Place this Custom HTML module in the same position as the login module or adjacent to it. Choose the appropriate module position where you want the button to appear.</td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>STEP 8:</strong>
                                    </td>
                                    <td>
                                        Assign Module to Menu Items - Configure the module assignment settings if needed to display the button on specific pages or menu items.
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <strong>STEP 9:</strong> 
                                    </td>
                                    <td>
                                        Save Changes - Save the Custom HTML module settings.
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>STEP 10:</strong></td>
                                    <td>Check the Frontend - Visit the frontend of your Joomla website to verify that the button appears near the login button as intended.</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 " style="display:none" id="mo_premium_feature">
                <div class="mo_boot_col-sm-12"> 
                    <h5 class="element"> 
                        Additional Features
                        <a href="https://developers.miniorange.com/docs/oauth-joomla/overview-oauth" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="Know more about premium feature"></i></sup></a>
                    </h5>
                    <hr>
                </div>
                <div class="mo_boot_col-sm-12">
                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-5">
                            <strong>Enable PKCE</strong>:
                        </div>
                        <div class="mo_boot_col-sm-7">
                            <label class=" mo_oauth_switch" id=" mo_oauth_switch">
                                <input type="checkbox" disabled/>
                                <span id="mo_oauth_slider" class="mo_oauth_slider round"></span>
                            </label>
                        </div>
                    </div><br>
                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-5">
                            <strong>Enable JWT</strong>:
                        </div>
                        <div class="mo_boot_col-sm-7">
                            <label class=" mo_oauth_switch" id=" mo_oauth_switch">
                                <input type="checkbox" disabled/>
                                <span id="mo_oauth_slider" class="mo_oauth_slider round"></span>
                            </label>
                        </div>
                    </div><br>

                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-5">
                            <strong>JWT Signing Algorithm:</strong>
                        </div>
                        <div class="mo_boot_col-sm-7">
                            <select readonly class="mo_boot_form-control">
                                <option value="HSA">HSA</option>
                                <option value="RSA">RSA</option>
                            </select>
                        </div>
                    </div><br>
                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-5">
                            <strong>JWKS URI :</strong>
                        </div>
                        <div class="mo_boot_col-sm-7">
                            <input class="mo_security_textfield mo_boot_form-control " required type="text" placeholder="Enter the URI" disabled="disabled" value="" />
                        </div>
                    </div><br>
                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-5">
                            <strong>Grant Types:</strong>
                        </div>
                        <div class="mo_boot_col-sm-7">
                            <select readonly class="mo_boot_form-control">
                                <option value="code">Authorization Grant</option>
                                <option value="implicit">Implicit Grant</option>
                                <option value="password">Password Grant</option>
                                <option value="client">Client Credential Grant</option>
                                <option value="refresh">Refresh token Grant</option>
                            </select>
                        </div>
                    </div><br>
                </div>
            </div>
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 " style="display:none" id="mo_importexport_setting">
                <div class="mo_boot_col-sm-12"> 
                    <?php moImportAndExport()?>
                </div>
            </div>
        </div>
    </div>
    <script>
        function testConfiguration() {
            var appname = "<?php echo $appLabel; ?>";
            var winl = ( screen.width - 400 ) / 2,
            wint = ( screen.height - 800 ) / 2,
            winprops = 'height=' + 600 +
            ',width=' + 800 +
            ',top=' + wint +
            ',left=' + winl +
            ',scrollbars=1'+
            ',resizable';
            var myWindow = window.open('<?php echo JURI::root();?>' + '?morequest=testattrmappingconfig&app=' + appname, "Test Attribute Configuration", winprops);
            var timer = setInterval(function() {   
            if(myWindow.closed) {  
                clearInterval(timer);  
                location.reload();
            }  
            }, 1); 
        }
    </script>  
    <?php
}
function attributerole()
{
    global $license_tab_link;
    $attribute = getAppDetails();
    $email = isset($attribute['email_attr'])?$attribute['email_attr']:"";
    $fullname = isset($attribute['full_name_attr'])?$attribute['full_name_attr']:"";
    $username = isset($attribute['user_name_attr'])?$attribute['user_name_attr']:"";
    ?>
    <div class="mo_boot_row m-0 p-1" style="box-shadow: 0px 0px 15px 5px lightgray;">
        <div class="mo_boot_col-sm-2 m-0 p-0" style="border-right:1px solid #001b4c">
            <div class="mo_boot_row m-0 p-0">
                <div class="mo_boot_col-sm-12 m-0 p-0">
                    <div onclick = "changeSubMenu(this , '#mo_basic_mapping')" class="mo_sub_menu mo_sub_menu_active">
                        <span>Basic Attribute's</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="mo_boot_col-sm-10">
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" id="mo_basic_mapping">
                <div class="mo_boot_col-sm-12 mo_boot_mt-2" id="mo_oauth_attributemapping">
                    <div class="mo_boot_row mo_boot_mt-2">
                        <div class="mo_boot_col-sm-12">
                            <h5 class="element">
                                Map Basic User Attribute 
                            </h5>
                            <br>
                        </div>
                        <br><br>
                        <div class="mo_boot_col-sm-12">
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-12">
                                    <p> Configure the Basic attribute of joomla to the attribute coming from the OAuth Provider</p>
                                </div>
                            </div>
                        </div>
                        <div class="mo_boot_col-sm-12">
                            <div class="mo_boot_row">
                                <div class="mo_boot_col-sm-3">
                                    <label for=""><span class="mo_oauth_highlight">*</span>Username :</label>
                                </div>
                                <div class="mo_boot_col-sm-9">
                                    <input class="mo_boot_form-control" readonly type="text" id="mo_oauth_uname_attr" name="mo_oauth_uname_attr" value='<?php echo $username?>' placeholder="Enter the Username attribute name from oauth provider" required>
                                </div>
                            </div>
                            <div class="mo_boot_row mo_boot_mt-3">
                                <div class="mo_boot_col-sm-3">
                                    <label for=""><span class="mo_oauth_highlight">*</span>Email :</label>
                                </div>
                                <div class="mo_boot_col-sm-9">
                                    
                                    <input class="mo_boot_form-control" readonly type="text" name="mo_oauth_email_attr" value='<?php echo $email?>' placeholder="Enter the Email attribute name from oauth provider" required>
                                </div>
                            </div>
                            <div class="mo_boot_row mo_boot_mt-3">
                                <div class="mo_boot_col-sm-3">
                                    <label for="">
                                        <span class="mo_oauth_highlight">*</span>Display Name :
                                    </label>    
                                </div>
                                <div class="mo_boot_col-sm-9">
                                    
                                    <input class="mo_boot_form-control" disabled type="text"  id="mo_oauth_dname_attr" name="mo_oauth_dname_attr" placeholder="Enter the Username attribute name from oauth provider" value=''>
                                </div>
                            </div>
                            <div class="mo_boot_row mo_boot_mt-2">
                                <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-right">
                                    <input type="submit" disabled style="cursor:not-allowed" name="send_query" value='<?php echo JText::_('COM_MINIORANGE_OAUTH_SAVE_ATTRIBUTE_MAPPING');?>' style="margin-bottom:3%;" class="mo_boot_btn mo_boot_btn-primary p-2"/>
                                </div>
                            </div>
                        </div>
                        
                    </div>  
                </div>
            </div>
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" style="display:none" id="mo_profile_mapping">
                <div class="mo_boot_col-sm-12 mo_boot_mt-3"> 
                    <h5 class="element"> 
                        <?php echo JText::_('COM_MINIORANGE_OAUTH_MAP_JOOMLA_USER_PROFILE_ATTRIBUTES');?>
                        <a href="https://developers.miniorange.com/docs/oauth-joomla/attribute-mapping" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Profile attribute mapping?"></i></sup></a>
                    </h5>
                    <hr>
                </div>
                <div class="mo_boot_col-sm-12 mo_boot_mt-2">
                    <p class="alert alert-info" style="color: #151515;"><?php echo JText::_('COM_MINIORANGE_OAUTH_MAP_JOOMLA_USER_PROFILE_ATTRIBUTES_NOTE');?> <a href='<?php echo $license_tab_link;?>' class='mo_oauth_coming_soon_features premium'><strong>Premium </a> </strong>and <a href='<?php echo $license_tab_link;?>' class='mo_oauth_coming_soon_features premium'> <strong>Enterprise</strong></a> versions of plugin.</p>
                </div>
                <div class="mo_boot_col-sm-12 mo_boot_my-4">
                    <div class="mo_boot_row mo_boot_my-3">
                        <div class="mo_boot_col-sm-12">
                            <input type="button" class="mo_boot_btn mo_boot_float-right mo_boot_btn-primary px-3 mx-1" disabled="true"  value="+" />
                            <input type="button" class="mo_boot_btn mo_boot_float-right mo_boot_btn-danger px-5 mx-1" disabled="true" value="Clear All" />
                        </div>
                    </div>
                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-6">
                            <strong><?php echo JText::_('COM_MINIORANGE_OAUTH_USER_PROFILE_ATTRIBUTE');?></strong>
                        </div>
                        <div class="mo_boot_col-sm-6">
                            <strong><?php echo JText::_('COM_MINIORANGE_OAUTH_SERVER_ATTRIBUTE');?></strong>
                        </div>
                    </div>
                    <div class="mo_boot_row m-0 p-0 mo_boot_my-3">
                        <div class="mo_boot_col-sm-6">
                            <select class="mo_boot_form-control" readonly>
                                <option value="">Select User Profile Attribute</option>
                                <option value="">Address1</option>
                                <option value="">Address2</option>
                                <option value="">City</option>
                                <option value="">Region</option>
                                <option value="">Country</option>
                                <option value="">Postal/Zip Code</option>
                                <option value="">Phone</option>
                                <option value="">Website</option>
                                <option value="">Favourite Book</option>
                                <option value="">About Me</option>
                                <option value="">Date Of Birth</option>
                            </select>
                        </div>
                        <div class="mo_boot_col-sm-5">
                            <input type="text" placeholder="Enter the Attribute Name you want to map"  class="mo_boot_form-control" disabled="disabled"/>
                        </div>
                        <div class="mo_boot_col-sm-1">    
                           <input type="button" class="mo_boot_btn mo_boot_float-right mo_boot_btn-secondary px-3 mx-1" disabled="true" value="-" />
                        </div>
                    </div>
                    <div class="mo_boot_row m-0 p-0 mo_boot_my-3">
                        <div class="mo_boot_col-sm-6">
                            <select class="mo_boot_form-control" readonly>
                                <option value="">Select User Profile Attribute</option>
                                <option value="">Address1</option>
                                <option value="">Address2</option>
                                <option value="">City</option>
                                <option value="">Region</option>
                                <option value="">Country</option>
                                <option value="">Postal/Zip Code</option>
                                <option value="">Phone</option>
                                <option value="">Website</option>
                                <option value="">Favourite Book</option>
                                <option value="">About Me</option>
                                <option value="">Date Of Birth</option>
                            </select>
                        </div>
                        <div class="mo_boot_col-sm-5">
                            <input type="text" placeholder="Enter the Attribute Name you want to map"  class="mo_boot_form-control" disabled="disabled"/>
                        </div>
                        <div class="mo_boot_col-sm-1">    
                           <input type="button" class="mo_boot_btn mo_boot_float-right mo_boot_btn-secondary px-3 mx-1" disabled="true" value="-" />
                        </div>
                    </div>
                    <div class="mo_boot_row mo_boot_mt-2">
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-right">
                            <input type="submit" name="send_query" value='Save Profile Atrribute Mapping' style="margin-bottom:3%;cursor:not-allowed" disabled class="mo_boot_btn mo_boot_btn-primary p-2"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" style="display:none" id="mo_field_mapping">
                <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                    <h5 class="element">
                        <?php echo JText::_('COM_MINIORANGE_OAUTH_MAP_JOOMLA_USER_FIELD_ATTRIBUTES');?>
                        <a href="https://developers.miniorange.com/docs/oauth-joomla/attribute-mapping" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Field attribute mapping?"></i></sup></a>
                    </h3>
                    <hr>
                </div>
                <div class="mo_boot_col-sm-12 mo_boot_mt-2">
                    <p class="alert alert-info" style="color: #151515;"><?php echo JText::_('COM_MINIORANGE_OAUTH_MAP_JOOMLA_USER_FIELD_ATTRIBUTES_NOTE');?></p>
                </div>
                <div class="mo_boot_row">
                    <div class="mo_boot_col-sm-12">
                        <input type="button" class="mo_boot_btn mo_boot_float-right mo_boot_btn-primary mx-1"  value="+" disabled/>
                        <input type="button" class="mo_boot_btn mo_boot_float-right mo_boot_btn-danger mx-1" value="Clear All" disabled/>
                    </div>
                </div>
                <div class="mo_boot_col-sm-12 mo_boot_my-3">
                    <div class="mo_boot_row mo_boot_mt-2">
                        <div class="mo_boot_col-sm-6">
                            <strong><?php echo JText::_('COM_MINIORANGE_OAUTH_USER_FIELD_ATTRIBUTE');?></strong>
                        </div>
                        <div class="mo_boot_col-sm-6">
                            <strong><?php echo JText::_('COM_MINIORANGE_OAUTH_SERVER_ATTRIBUTE');?></strong>
                        </div>
                    </div>
                    <div class="mo_boot_row m-0 p-0 mo_boot_my-3">
                        <div class="mo_boot_col-sm-6">
                            <input class="mo_boot_form-control" type="text" placeholder="Enter the field Name from Joomla" disabled/>
                        </div>
                        <div class="mo_boot_col-sm-5">
                            <input class="mo_boot_form-control" type="text" disabled placeholder="Enter the attribute name you want to map"  />
                        </div>
                        <div class="mo_boot_col-sm-1">    
                           <input type="button" class="mo_boot_btn mo_boot_float-right mo_boot_btn-secondary px-3 mx-1" disabled="true" value="-" />
                        </div>
                    </div>
                    <div class="mo_boot_row m-0 p-0 mo_boot_my-3">
                        <div class="mo_boot_col-sm-6">
                            <input class="mo_boot_form-control" type="text" placeholder="Enter the field Name from Joomla" disabled/>
                        </div>
                        <div class="mo_boot_col-sm-5">
                            <input class="mo_boot_form-control" type="text" disabled placeholder="Enter the attribute name you want to map" />
                        </div>
                        <div class="mo_boot_col-sm-1">    
                           <input type="button" class="mo_boot_btn mo_boot_float-right mo_boot_btn-secondary px-3 mx-1" disabled="true" value="-" />
                        </div>
                    </div>
                    <div class="mo_boot_row mo_boot_mt-2">
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-right">
                            <input type="submit" name="send_query" value='Save Field Attribute Mapping' style="margin-bottom:3%;cursor:not-allowed" disabled class="mo_boot_btn mo_boot_btn-primary p-2"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 " style="display:none" id="mo_group_mapping">
                <div class="mo_boot_col-sm-12">
                    <div class="mo_boot_row mo_boot_my-3">
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                            <h5 class="element">
                                <?php echo JText::_('COM_MINIORANGE_OAUTH_GROUP_MAPPING');?>
                                <a href="https://developers.miniorange.com/docs/oauth-joomla/role-mapping" target="_blank" class="mo_handbook" ><sup><i class="fa-regular fa-circle-question" title="What is Group/Role mapping?"></i></sup></a>
                            </h5>
                            <hr>
                            <p><?php echo JText::_('COM_MINIORANGE_OAUTH_GROUP_MAPPING_NOTE');?></p>
                        </div>
                    </div>
                </div>
                <div class="mo_boot_col-sm-12 mo_boot_my-4">
                    <div class="mo_boot_row">
                        <div class="mo_boot_col-sm-4">
                            <p><?php echo JText::_('COM_MINIORANGE_OAUTH_SELECT_DEFAULT_GROUP_FOR_NEW_USER');?></p>
                        </div>
                        <div class="mo_boot_col-sm-8">
                            <?php
                                $db = JFactory::getDbo();
    $db->setQuery(
        $db->getQuery(true)
        ->select('*')
        ->from("#__usergroups")
    );
    $groups = $db->loadRowList();

    echo '<select class="mo_boot_form-control" style="cursor:pointer" readonly name="mapping_value_default" id="default_group_mapping">';

    foreach ($groups as $group) {
        if ($group[4] != 'Super Users'&&$group[4] != 'Public'&&$group[4] != 'Guest') {
            echo '<option selected="selected" value = "' . $group[0] . '">' . $group[4] . '</option>';
        }
    }
    ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="mo_boot_col-sm-12 mo_boot_mt-2">
                    <div class="mo_boot_row mo_boot_mt-2">
                        <div class="mo_boot_col-sm-4">
                            <p><?php echo JText::_('COM_MINIORANGE_OAUTH_GROUP_ATTRIBUTE_NAMES');?></p>
                        </div>
                        <div class="mo_boot_col-sm-8">
                            <input class="mo_boot_form-control" placeholder="<?php echo JText::_('COM_MINIORANGE_OAUTH_GROUP_ATTRIBUTE_NAMES_PLACEHOLDER');?>" type="text" id="mo_oauth_gname_attr" name="mo_oauth_gname_attr" value='' disabled>
                        </div>
                    </div>
                    <hr class="bg-dark">
                </div>
                <div class=" mo_boot_col-sm-12 mo_boot_my-2">
                    <div class="mo_boot_row mo_boot_mt-3">
                        <div class="mo_boot_col-sm-4">
                            <strong><?php echo JText::_('COM_MINIORANGE_OAUTH_GROUP_NAME_IN_JOOMLA');?></strong>
                        </div>
                        <div class="mo_boot_col-sm-8">
                            <strong><?php echo JText::_('COM_MINIORANGE_OAUTH_GROUP_ROLE_NAME_IN_CONFIGURED_APP');?></strong>
                        </div>
                    </div>
                    <div class="mo_boot_row mo_boot_mt-3">
                        <?php
    $user_role = array();
    if (empty($role_mapping_key_value)) {
        foreach ($groups as $group) {
            if ($group[4] != 'Super Users') {
                echo '<div class="mo_boot_col-sm-4 mo_boot_mt-2">' . $group[4] . '</div><div class="mo_boot_col-sm-8 mo_boot_mt-2"><input class="mo_boot_form-control"  disabled type="text" id="oauth_group_attr_values' . $group[0] . '" name="oauth_group_attr_values' . $group[0] . '" value= "" placeholder="'.JText::_('COM_MINIORANGE_OAUTH_GROUP_ROLE_NAME_IN_CONFIGURED_APP_PLACEHOLDER'). $group[4] . '" "' . ' /></div>';
            }
        }
    } else {
        foreach ($groups as $group) {
            if ($group[4] != 'Super Users') {
                $role_value = array_key_exists($group[0], $role_mapping_key_value) ? $role_mapping_key_value[$group[0]] : "";
                echo '<div class="mo_boot_col-sm-4 mo_boot_offset-sm-1"><strong>' . $group[4] . '</strong></div><div class="mo_boot_col-sm-6"><input  class="mo_boot_form-control"  disabled type="text" id="oauth_group_attr_values' . $group[0] . '" name="oauth_group_attr_values' . $group[0] . '" value= "' . $role_value . '" placeholder="'.JText::_('COM_MINIORANGE_OAUTH_GROUP_ROLE_NAME_IN_CONFIGURED_APP_PLACEHOLDER'). $group[4] . '" "' . ' /></div>';
            }
        }
    }
    ?>
                    </div>
                </div>
                <div class="mo_boot_col-sm-12">
                    <div class="mo_boot_row mo_boot_mt-4">
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-right">
                            <input type="submit" name="send_query" value='Save Group Mapping' disabled style="margin-bottom:3%;cursor:not-allowed" class="mo_boot_btn mo_boot_btn-primary p-2 px-4"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3 " style="display:none" id="mo_advance_mapping">
                <div class="mo_boot_col-sm-12">
                    <div class="mo_boot_row mo_boot_my-3">
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                            <h5 class="element">
                                Additional Settings
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="mo_boot_col-sm-12">
                    <div class="mo_boot_row mo_boot_my-3">
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                            <strong>Attribute Mapping</strong>
                            <hr class="bg-dark">
                        </div>
                    </div>
                </div>
                
                <div class="mo_boot_col-sm-12 mo_boot_mt-2">
                    <div class="mo_boot_row mo_boot_mt-3" style="padding:10px;">
                        <div class="mo_boot_col-sm-12">
                            <label class=" mo_oauth_switch" id=" mo_oauth_switch">
                                <input disabled type="checkbox" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                                <span id="mo_oauth_slider" class="mo_oauth_slider round"></span>
                            </label> Do Not update existing user attributes.
                            <br>
                        </div>
                    </div>
                </div>
                <div class="mo_boot_col-sm-12">
                    <div class="mo_boot_row mo_boot_my-3">
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
                            <strong>Group Mapping</strong>
                            <hr class="bg-dark">
                        </div>
                    </div>
                </div>
                <div class="mo_boot_col-sm-12 mo_boot_mt-2">
                    <div class="mo_boot_row mo_boot_mt-3" style="padding:10px;">
                        <div class="mo_boot_col-sm-12">
                            <label class=" mo_oauth_switch" id=" mo_oauth_switch">
                                <input disabled type="checkbox" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                                <span id="mo_oauth_slider" class="mo_oauth_slider round"></span>
                            </label> <?php echo JText::_('COM_MINIORANGE_OAUTH_DO_NOT_UPDATE_EXISTING_USER_GROUPS');?>
                            <br>
                            <label class=" mo_oauth_switch" id=" mo_oauth_switch">
                                <input disabled type="checkbox" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                                <span id="mo_oauth_slider" class="mo_oauth_slider round"></span>
                            </label> <?php echo JText::_('COM_MINIORANGE_OAUTH_DO_NOT_UPDATE_EXISTING_USER_GROUPS_AND_NEWLY_MAPPED_ROLES');?>
                            <br>
                            <label class=" mo_oauth_switch" id=" mo_oauth_switch">
                                <input disabled type="checkbox" name=" mo_oauth_custom_checkbox" id=" mo_oauth_check">
                                <span id="mo_oauth_slider" class="mo_oauth_slider round"></span>
                            </label> <?php echo JText::_('COM_MINIORANGE_OAUTH_DO_NOT_AUTO_CREATE_USERS_IF_ROLES_NOT_MAPPED');?>
                            <br>
                        </div>
                    </div>
                </div>
                <div class="mo_boot_col-sm-12">
                    <div class="mo_boot_row mo_boot_mt-4">
                        <div class="mo_boot_col-sm-12 mo_boot_mt-3 mo_boot_text-right">
                            <input type="submit" disabled name="send_query" value='Save Additional Settings' style="margin-bottom:3%;cursor:not-allowed" class="mo_boot_btn mo_boot_btn-primary p-2 px-4"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
       function changeSubMenu(element0,element1)
       {
            jQuery(".mo_sub_menu_active").removeClass("mo_sub_menu_active");
            jQuery(element0).addClass("mo_sub_menu_active");
            jQuery(element1).nextAll('div').css('display', 'none');
            jQuery(element1).prevAll().css('display', 'none');
            jQuery(element1).css("display", "block");
       }
    </script>
    <?php
}

function moOAuthConfiguration()
{
    global $license_tab_link;
    global $license_tab_link;
    $appArray = json_decode(getAppJson(), true);
    $app = JFactory::getApplication();
    $get = $app->input->get->getArray();
    $attribute = getAppDetails();
    $isAppConfigured = empty($attribute['client_secret']) || empty($attribute['client_id']) || empty($attribute['custom_app'])|| empty($attribute['redirecturi'])?false:true;
    if (isset($get['moAuthAddApp']) && !empty($get['moAuthAddApp'])) {
        configuration($appArray[$get['moAuthAddApp']], $get['moAuthAddApp']);
        return;
    } elseif ($isAppConfigured) {
        configuration($appArray[$attribute['appname']], $attribute['appname']);
        return;
    } else { ?>
        <div class="mo_boot_row m-0 p-1" style="box-shadow: 0px 0px 15px 5px lightgray;">
            <div class="mo_boot_col-sm-2 m-0 p-0" style="border-right:1px solid #001b4c">
                <div class="mo_boot_row m-0 p-0">
                    <div class="mo_boot_col-sm-12 m-0 p-0">
                        <div onclick = "changeSubMenu(this , '#mo_pre_configure_app')" class="mo_sub_menu mo_sub_menu_active">
                            <span>Pre-Configured Apps</span>
                        </div>
                    </div>
                </div>
                <div class="mo_boot_row m-0 p-0">
                    <div class="mo_boot_col-sm-12 m-0 p-0">
                        <div onclick = "changeSubMenu(this,'#mo_custom_app')" class="mo_sub_menu">
                            <span>Custom Application</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mo_boot_col-sm-10">
                <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" id="mo_pre_configure_app">
                    <?php selectAppByIcon() ;?>
                </div>
                <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" style="display:none" id="mo_custom_app">
                    <?php selectCustomApp(); ?>
                </div>
                <div class="mo_boot_row mo_boot_m-1 mo_boot_my-3" style="display:none" id="mo_multiple_provider">
                    <div class="mo_boot_col-sm-12 mo_boot_alert-info p-5 my-5">
                        This functionality is exclusive to our Enterprise plugin version. To access this feature, we recommend upgrading to our Enterprise version. Additionally, if you'd like a trial of this functionality before upgrading your license, please don't hesitate to contact us at <strong>joomlasupport@xecurify.com</strong>.
                    </div>
                </div>
            </div>
        </div>
        <script>
        function changeSubMenu(element0,element1)
        {
                jQuery(".mo_sub_menu_active").removeClass("mo_sub_menu_active");
                jQuery(element0).addClass("mo_sub_menu_active");
                jQuery(element1).nextAll('div').css('display', 'none');
                jQuery(element1).prevAll().css('display', 'none');
                jQuery(element1).css("display", "block");
        }
        </script>
        <?php
    }
}

function grant_type_settings()
{
    global $license_tab_link;
    ?>
    <div class="mo_boot_row mo_boot_mr-1 mo_boot_my-3 ">
        <div class="mo_boot_col-sm-12 mo_boot_mt-4">
            <h3 style="display: inline;">Grant Settings<sup><code><small><a href="<?php echo $license_tab_link;?>"  rel="noopener noreferrer">[PREMIUM,ENTERPRISE]</a></small></code></sup></h3>
            <hr>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <h4>Select Grant Type:</h4>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2 grant_types">
            <input checked disabled type="checkbox">&emsp;<strong>Authorization Code Grant</strong>&emsp;<code><small>[DEFAULT]</small></code>
            <blockquote>
                The Authorization Code grant type is used by web and mobile apps.<br/>
                It requires the client to exchange authorization code with access token from the server.
                <br/><small>(If you have doubt on which settings to use, you can leave this checked and disable all others.)</small>
            </blockquote>
            <input disabled type="checkbox">&emsp;<strong>Implicit Grant</strong>
            <blockquote>
                The Implicit grant type is a simplified version of the Authorization Code Grant flow.<br/>
                OAuth providers directly offer access token when using this grant type.
            </blockquote>
            <input disabled type="checkbox">&emsp;<strong>Password Grant</strong>
            <blockquote>
                Password grant is used by application to exchange user's credentials for access token.<br/>
                This, generally, should be used by internal applications.
            </blockquote>
            <input disabled type="checkbox">&emsp;<strong>Refresh Token Grant</strong>
            <blockquote>
                The Refresh Token grant type is used by clients.<br/>
                This can help in keeping user session persistent.
            </blockquote>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <hr>
            <h3 style="display: inline;">JWT Validation<sup><code><small><a href="<?php echo $license_tab_link;?>"  rel="noopener noreferrer">[PREMIUM,ENTERPRISE]</a></small></code></sup></h3>
            <hr>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <strong>Enable JWT Verification:</strong>
            <input type="checkbox" value="" disabled/>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-2">
            <strong>JWT Signing Algorithm:</strong>
            <select disabled>
                <option>HSA</option>
                <option>RSA</option>
            </select> 
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_my-2">
            <div class="notes">
                <hr />
                Grant Type Settings and JWT Validation are configurable in <a href="<?php echo $license_tab_link;?>" rel="noopener noreferrer">premium and enterprise</a> versions of the plugin.
            </div>
        </div>
    </div>
    <?php
}

function moImportAndExport()
{
    ?>
    <div class="mo_boot_row  mo_boot_mr-1  mo_boot_py-3 mo_boot_px-2 mo_tab_border" id="import_export_form">
        <div class="mo_boot_col-sm-12">
            <h3>
                Export Configuration
                <hr>
            </h3>
        </div>
        <div class="mo_boot_col-sm-12 mo_boot_mt-3">
            <div class="mo_boot_row">
                <div class="mo_boot_col-8">
                    <strong>Download Configuration: </strong>
                </div> 
                <div class="mo_boot_col-4">
                    <a href='index.php?option=com_miniorange_oauth&view=accountsetup&task=accountsetup.exportConfiguration' class="mo_boot_btn mo_boot_btn-primary mo_boot_float-right" style='padding:2px 5px'><?php echo JText::_('COM_MINIORANGE_OAUTH_EXPORT_CONFIGURATION');?></a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
