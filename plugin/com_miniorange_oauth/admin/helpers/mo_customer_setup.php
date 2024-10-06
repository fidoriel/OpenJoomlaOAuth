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

class MoOauthCustomer
{

    public function getAppJson()
    {
        return '{
			"azure": {
				"label": "Azure AD",
				"type": "oauth",
				"scope": "openid email profile",
				"authorize": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/authorize",
				"token": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/token",
				"userinfo": "https://graph.microsoft.com/beta/me",
				"logo_class": "fa fa-windowslive"
			},
			"azureb2c": {
				"label": "Azure B2C",
				"type": "openidconnect",
				"scope": "openid email",
				"authorize": "https://{tenant}.b2clogin.com/{tenant}.onmicrosoft.com/{policy}/oauth2/v2.0/authorize",
				"token": "https://{tenant}.b2clogin.com/{tenant}.onmicrosoft.com/{policy}/oauth2/v2.0/token",
				"userinfo": "",
				"logo_class": "fa fa-windowslive"
			},
			"cognito": {
				"label": "AWS Cognito",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://{domain}/oauth2/authorize",
				"token": "https://{domain}/oauth2/token",
				"userinfo": "https://{domain}/oauth2/userInfo",
				"logo_class": "fa fa-amazon"
			},
			"adfs": {
				"label": "ADFS",
				"type": "openidconnect",
				"scope": "openid",
				"authorize": "https://{domain}/adfs/oauth2/authorize/",
				"token": "https://{domain}/adfs/oauth2/token/",
				"userinfo": "",
				"logo_class": "fa fa-windowslive"
			},
			"whmcs": {
				"label": "WHMCS",
				"type": "oauth",
				"scope": "openid profile email",
				"authorize": "https://{domain}/oauth/authorize.php",
				"token": "https://{domain}/oauth/token.php",
				"userinfo": "https://{domain}/oauth/userinfo.php?access_token=",
				"logo_class": "fa fa-lock"
			},
			"keycloak": {
				"label": "keycloak",
				"type": "openidconnect",
				"scope": "openid",
				"authorize": "https://{domain}/realms/{realm}/protocol/openid-connect/auth",
				"token": "https://{domain}/realms/{realm}/protocol/openid-connect/token",
				"userinfo": "{domain}/realms/{realm}/protocol/openid-connect/userinfo",
				"logo_class": "fa fa-lock"
			},
			"slack": {
				"label": "Slack",
				"type": "oauth",
				"scope": "users.profile:read",
				"authorize": "https://slack.com/oauth/authorize",
				"token": "https://slack.com/api/oauth.access",
				"userinfo": "https://slack.com/api/users.profile.get",
				"logo_class": "fa fa-slack"
			},
			"discord": {
				"label": "Discord",
				"type": "oauth",
				"scope": "identify email",
				"authorize": "https://discordapp.com/api/oauth2/authorize",
				"token": "https://discordapp.com/api/oauth2/token",
				"userinfo": "https://discordapp.com/api/users/@me",
				"logo_class": "fa fa-lock"
			},
			"invisioncommunity": {
				"label": "Invision Community",
				"type": "oauth",
				"scope": "email",
				"authorize": "{domain}/oauth/authorize/",
				"token": "https://{domain}/oauth/token/",
				"userinfo": "https://{domain}/oauth/me",
				"logo_class": "fa fa-lock"
			},
			"bitrix24": {
				"label": "Bitrix24",
				"type": "oauth",
				"scope": "user",
				"authorize": "https://{accountid}.bitrix24.com/oauth/authorize",
				"token": "https://{accountid}.bitrix24.com/oauth/token",
				"userinfo": "https://{accountid}.bitrix24.com/rest/user.current.json?auth=",
				"logo_class": "fa fa-clock-o"
			},
			"wso2": {
				"label": "WSO2",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://{domain}/wso2/oauth2/authorize",
				"token": "https://{domain}/wso2/oauth2/token",
				"userinfo": "https://{domain}/wso2/oauth2/userinfo",
				"logo_class": "fa fa-lock"
			},
			"okta": {
				"label": "Okta",
				"type": "openidconnect",
				"scope": "openid email profile",
				"authorize": "https://{domain}/oauth2/default/v1/authorize",
				"token": "https://{domain}/oauth2/default/v1/token",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"onelogin": {
				"label": "OneLogin",
				"type": "openidconnect",
				"scope": "openid",
				"authorize": "https://{domain}/oidc/auth",
				"token": "https://{domain}/oidc/token",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"gapps": {
				"label": "Google",
				"type": "oauth",
				"scope": "email",
				"authorize": "https://accounts.google.com/o/oauth2/auth",
				"token": "https://www.googleapis.com/oauth2/v4/token",
				"userinfo": "https://www.googleapis.com/oauth2/v1/userinfo",
				"logo_class": "fa fa-google-plus"
			},
			"fbapps": {
				"label": "Facebook",
				"type": "oauth",
				"scope": "public_profile email",
				"authorize": "https://www.facebook.com/dialog/oauth",
				"token": "https://graph.facebook.com/v2.8/oauth/access_token",
				"userinfo": "https://graph.facebook.com/me/?fields=id,name,email,age_range,first_name,gender,last_name,link",
				"logo_class": "fa fa-facebook"
			},
			"gluu": {
				"label": "Gluu Server",
				"type": "oauth",
				"scope": "openid",
				"authorize": "http://{domain}/oxauth/restv1/authorize",
				"token": "http://{domain}/oxauth/restv1/token",
				"userinfo": "http:///{domain}/oxauth/restv1/userinfo",
				"logo_class": "fa fa-lock"
			},
			"linkedin": {
				"label": "LinkedIn",
				"type": "oauth",
				"scope": "openid email profile",
				"authorize": "https://www.linkedin.com/oauth/v2/authorization",
				"token": "https://www.linkedin.com/oauth/v2/accessToken",
				"userinfo": "https://api.linkedin.com/v2/me",
				"logo_class": "fa fa-linkedin-square"
			},
			"strava": {
				"label": "Strava",
				"type": "oauth",
				"scope": "public",
				"authorize": "https://www.strava.com/oauth/authorize",
				"token": "https://www.strava.com/oauth/token",
				"userinfo": "https://www.strava.com/api/v3/athlete",
				"logo_class": "fa fa-lock"
			},
			"fitbit": {
				"label": "FitBit",
				"type": "oauth",
				"scope": "profile",
				"authorize": "https://www.fitbit.com/oauth2/authorize",
				"token": "https://api.fitbit.com/oauth2/token",
				"userinfo": "https://www.fitbit.com/1/user",
				"logo_class": "fa fa-lock"
			},
			"box": {
				"label": "Box",
				"type": "oauth",
				"scope": "root_readwrite",
				"authorize": "https://account.box.com/api/oauth2/authorize",
				"token": "https://api.box.com/oauth2/token",
				"userinfo": "https://api.box.com/2.0/users/me",
				"logo_class": "fa fa-lock"
			},
			"github": {
				"label": "GitHub",
				"type": "oauth",
				"scope": "user repo",
				"authorize": "https://github.com/login/oauth/authorize",
				"token": "https://github.com/login/oauth/access_token",
				"userinfo": "https://api.github.com/user",
				"logo_class": "fa fa-github"
			},
			"gitlab": {
				"label": "GitLab",
				"type": "oauth",
				"scope": "read_user",
				"authorize": "https://gitlab.com/oauth/authorize",
				"token": "http://gitlab.com/oauth/token",
				"userinfo": "https://gitlab.com/api/v4/user",
				"logo_class": "fa fa-gitlab"
			},
			"clever": {
				"label": "Clever",
				"type": "oauth",
				"scope": "read:students read:teachers read:user_id",
				"authorize": "https://clever.com/oauth/authorize",
				"token": "https://clever.com/oauth/tokens",
				"userinfo": "https://api.clever.com/v1.1/me",
				"logo_class": "fa fa-lock"
			},
			"salesforce": {
				"label": "Salesforce",
				"type": "oauth",
				"scope": "email",
				"authorize": "https://login.salesforce.com/services/oauth2/authorize",
				"token": "https://login.salesforce.com/services/oauth2/token",
				"userinfo": "https://login.salesforce.com/services/oauth2/userinfo",
				"logo_class": "fa fa-lock"
			},
			"reddit": {
				"label": "Reddit",
				"type": "oauth",
				"scope": "identity",
				"authorize": "https://www.reddit.com/api/v1/authorize",
				"token": "https://www.reddit.com/api/v1/access_token",
				"userinfo": "https://www.reddit.com/api/v1/me",
				"logo_class": "fa fa-reddit"
			},
			"paypal": {
				"label": "PayPal",
				"type": "openidconnect",
				"scope": "openid",
				"authorize": "https://www.paypal.com/signin/authorize",
				"token": "https://api.paypal.com/v1/oauth2/token",
				"userinfo": "",
				"logo_class": "fa fa-paypal"
			},
			"swiss-rx-login": {
				"label": "Swiss RX Login",
				"type": "openidconnect",
				"scope": "anonymous",
				"authorize": "https://www.swiss-rx-login.ch/oauth/authorize",
				"token": "https://swiss-rx-login.ch/oauth/token",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"yahoo": {
				"label": "Yahoo",
				"type": "openidconnect",
				"scope": "openid",
				"authorize": "https://api.login.yahoo.com/oauth2/request_auth",
				"token": "https://api.login.yahoo.com/oauth2/get_token",
				"userinfo": "",
				"logo_class": "fa fa-yahoo"
			},
			"spotify": {
				"label": "Spotify",
				"type": "oauth",
				"scope": "user-read-private user-read-email",
				"authorize": "https://accounts.spotify.com/authorize",
				"token": "https://accounts.spotify.com/api/token",
				"userinfo": "https://api.spotify.com/v1/me",
				"logo_class": "fa fa-spotify"
			},
			"eveonlinenew": {
				"label": "Eve Online",
				"type": "oauth",
				"scope": "publicData",
				"authorize": "https://login.eveonline.com/oauth/authorize",
				"token": "https://login.eveonline.com/oauth/token",
				"userinfo": "https://esi.evetech.net/verify",
				"logo_class": "fa fa-lock"
			},
			"vkontakte": {
				"label": "VKontakte",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://oauth.vk.com/authorize",
				"token": "https://oauth.vk.com/access_token",
				"userinfo": "https://api.vk.com/method/users.get?fields=id,name,email,age_range,first_name,gender,last_name,link&access_token=",
				"logo_class": "fa fa-vk"
			},
			"pinterest": {
				"label": "Pinterest",
				"type": "oauth",
				"scope": "read_public",
				"authorize": "https://api.pinterest.com/oauth/",
				"token": "https://api.pinterest.com/v1/oauth/token",
				"userinfo": "https://api.pinterest.com/v1/me/",
				"logo_class": "fa fa-pinterest"
			},
			"vimeo": {
				"label": "Vimeo",
				"type": "oauth",
				"scope": "public",
				"authorize": "https://api.vimeo.com/oauth/authorize",
				"token": "https://api.vimeo.com/oauth/access_token",
				"userinfo": "https://api.vimeo.com/me",
				"logo_class": "fa fa-vimeo"
			},
			"deviantart": {
				"label": "DeviantArt",
				"type": "oauth",
				"scope": "browse",
				"authorize": "https://www.deviantart.com/oauth2/authorize",
				"token": "https://www.deviantart.com/oauth2/token",
				"userinfo": "https://www.deviantart.com/api/v1/oauth2/user/profile",
				"logo_class": "fa fa-deviantart"
			},
			"dailymotion": {
				"label": "Dailymotion",
				"type": "oauth",
				"scope": "email",
				"authorize": "https://www.dailymotion.com/oauth/authorize",
				"token": "https://api.dailymotion.com/oauth/token",
				"userinfo": "https://api.dailymotion.com/user/me?fields=id,username,email,first_name,last_name",
				"logo_class": "fa fa-lock"
			},
			"meetup": {
				"label": "Meetup",
				"type": "oauth",
				"scope": "basic",
				"authorize": "https://secure.meetup.com/oauth2/authorize",
				"token": "https://secure.meetup.com/oauth2/access",
				"userinfo": "https://api.meetup.com/members/self",
				"logo_class": "fa fa-lock"
			},
			"autodesk": {
				"label": "Autodesk",
				"type": "oauth",
				"scope": "user:read user-profile:read",
				"authorize": "https://developer.api.autodesk.com/authentication/v1/authorize",
				"token": "https://developer.api.autodesk.com/authentication/v1/gettoken",
				"userinfo": "https://developer.api.autodesk.com/userprofile/v1/users/@me",
				"logo_class": "fa fa-lock"
			},
			"zendesk": {
				"label": "Zendesk",
				"type": "oauth",
				"scope": "read write",
				"authorize": "https://{domain}/oauth/authorizations/new",
				"token": "https://{domain}/oauth/tokens",
				"userinfo": "https://{domain}/api/v2/users",
				"logo_class": "fa fa-lock"
			},
			"laravel": {
				"label": "Laravel",
				"type": "oauth",
				"scope": "",
				"authorize": "http://{domain}/oauth/authorize",
				"token": "http://{domain}/oauth/token",
				"userinfo": "http://{domain}}/api/user/get",
				"logo_class": "fa fa-lock"
			},
			"identityserver": {
				"label": "Identity Server",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://{domain}/connect/authorize",
				"token": "https://{domain}/connect/token",
				"userinfo": "https://{domain}/connect/introspect",
				"logo_class": "fa fa-lock"
			},
			"nextcloud": {
				"label": "Nextcloud",
				"type": "oauth",
				"scope": "user:read:email",
				"authorize": "https://{domain}/index.php/apps/oauth2/authorize",
				"token": "https://{domain}/index.php/apps/oauth2/api/v1/token",
				"userinfo": "https://{domain}/ocs/v2.php/cloud/user?format=json",
				"logo_class": "fa fa-lock"
			},
			"twitch": {
				"label": "Twitch",
				"type": "oauth",
				"scope": "Analytics:read:extensions",
				"authorize": "https://id.twitch.tv/oauth2/authorize",
				"token": "https://id.twitch.tv/oauth2/token",
				"userinfo": "https://id.twitch.tv/oauth2/userinfo",
				"logo_class": "fa fa-lock"
			},
			"wildApricot": {
				"label": "Wild Apricot",
				"type": "oauth",
				"scope": "auto",
				"authorize": "https://{domain}/sys/login/OAuthLogin",
				"token": "https://oauth.wildapricot.org/auth/token",
				"userinfo": "https://api.wildapricot.org/v2.1/accounts/{accountid}/contacts/me",
				"logo_class": "fa fa-lock"
			},
			"connect2id": {
				"label": "Connect2id",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://c2id.com/login",
				"token": "https://{domain}/token",
				"userinfo": "https://{domain}/userinfo",
				"logo_class": "fa fa-lock"
			},
			"miniorange": {
				"label": "miniOrange",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://login.xecurify.com/moas/idp/openidsso",
				"token": "https://login.xecurify.com/moas/rest/oauth/token",
				"userinfo": "https://logins.xecurify.com/moas/rest/oauth/getuserinfo",
				"logo_class": "fa fa-lock"
			},
			"orcid": {
				"label": "ORCID",
				"type": "openidconnect",
				"scope": "openid",
				"authorize": "https://orcid.org/oauth/authorize",
				"token": "https://orcid.org/oauth/token",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"diaspora": {
				"label": "Diaspora",
				"type": "openidconnect",
				"scope": "openid",
				"authorize": "https://{domain}/api/openid_connect/authorizations/new",
				"token": "https://{domain}/api/openid_connect/access_tokens",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"timezynk": {
				"label": "Timezynk",
				"type": "oauth",
				"scope": "read:user",
				"authorize": "https://api.timezynk.com/api/oauth2/v1/auth",
				"token": "https://api.timezynk.com/api/oauth2/v1/token",
				"userinfo": "https://api.timezynk.com/api/oauth2/v1/userinfo",
				"logo_class": "fa fa-lock"
			},
			"Amazon": {
				"label": "Amazon",
				"type": "oauth",
				"scope": "profile",
				"authorize": "https://www.amazon.com/ap/oa",
				"token": "https://api.amazon.com/auth/o2/token",
				"userinfo": "https://api.amazon.com/user/profile",
				"logo_class": "fa fa-lock"
			},
			"Office 365": {
				"label": "Office 365",
				"type": "oauth",
				"scope": "openid email profile",
				"authorize": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/authorize",
				"token": "https://login.microsoftonline.com/{tenant}/oauth2/v2.0/token",
				"userinfo": "https://graph.microsoft.com/beta/me",
				"logo_class": "fa fa-lock"
			},
			"Instagram": {
				"label": "Instagram",
				"type": "oauth",
				"scope": "user_profile user_media",
				"authorize": "https://api.instagram.com/oauth/authorize",
				"token": "https://api.instagram.com/oauth/access_token",
				"userinfo": "https://graph.instagram.com/me?fields=id,username&access_token=",
				"logo_class": "fa fa-lock"
			},
			"Line": {
				"label": "Line",
				"type": "oauth",
				"scope": "profile openid email",
				"authorize": "https://access.line.me/oauth2/v2.1/authorize",
				"token": "https://api.line.me/oauth2/v2.1/token",
				"userinfo": "https://api.line.me/v2/profile",
				"logo_class": "fa fa-lock"
			},
			"PingFederate": {
				"label": "PingFederate",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://{domain}/as/authorization.oauth2",
				"token": "https://{domain}/as/token.oauth2",
				"userinfo": "https://{domain}/idp/userinfo.oauth2",
				"logo_class": "fa fa-lock"
			},
			"OpenAthens": {
				"label": "OpenAthens",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://sp.openathens.net/oauth2/authorize",
				"token": "https://sp.openathens.net/oauth2/token",
				"userinfo": "https://sp.openathens.net/oauth2/userInfo",
				"logo_class": "fa fa-lock"
			},
			"Intuit": {
				"label": "Intuit",
				"type": "oauth",
				"scope": "openid email profile",
				"authorize": "https://appcenter.intuit.com/connect/oauth2",
				"token": "https://oauth.platform.intuit.com/oauth2/v1/tokens/bearer",
				"userinfo": "https://accounts.platform.intuit.com/v1/openid_connect/userinfo",
				"logo_class": "fa fa-lock"
			},
			"Twitter": {
				"label": "Twitter",
				"type": "oauth",
				"scope": "email",
				"authorize": "https://api.twitter.com/oauth/authorize",
				"token": "https://api.twitter.com/oauth2/token",
				"userinfo": "https://api.twitter.com/1.1/users/show.json?screen_name=here-comes-twitter-screen-name",
				"logo_class": "fa fa-lock"
			},
			"WordPress": {
				"label": "WordPress",
				"type": "oauth",
				"scope": "profile openid email custom",
				"authorize": "http://{site_base_url}/wp-json/moserver/authorize",
				"token": "http://{site_base_url}/wp-json/moserver/token",
				"userinfo": "http://{site_base_url}/wp-json/moserver/resource",
				"logo_class": "fa fa-lock"
			},
			"Subscribestar": {
				"label": "Subscribestar",
				"type": "oauth",
				"scope": "user.read user.email.read",
				"authorize": "https://www.subscribestar.com/oauth2/authorize",
				"token": "https://www.subscribestar.com/oauth2/token",
				"userinfo": "https://www.subscribestar.com/api/graphql/v1?query={user{name,email}}",
				"logo_class": "fa fa-lock"
			},
			"Classlink": {
				"label": "Classlink",
				"type": "oauth",
				"scope": "email profile oneroster full",
				"authorize": "https://launchpad.classlink.com/oauth2/v2/auth",
				"token": "https://launchpad.classlink.com/oauth2/v2/token",
				"userinfo": "https://nodeapi.classlink.com/v2/my/info",
				"logo_class": "fa fa-lock"
			},
			"HP": {
				"label": "HP",
				"type": "oauth",
				"scope": "read",
				"authorize": "https://{hp_domain}/v1/oauth/authorize",
				"token": "https://{hp_domain}/v1/oauth/token",
				"userinfo": "https://{hp_domain}/v1/userinfo",
				"logo_class": "fa fa-lock"
			},
			"Basecamp": {
				"label": "Basecamp",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://launchpad.37signals.com/authorization/new?type=web_server",
				"token": "https://launchpad.37signals.com/authorization/token?type=web_server",
				"userinfo": "https://launchpad.37signals.com/authorization.json",
				"logo_class": "fa fa-lock"
			},
			"Feide": {
				"label": "Feide",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://auth.dataporten.no/oauth/authorization",
				"token": "https://auth.dataporten.no/oauth/token",
				"userinfo": "https://auth.dataporten.no/openid/userinfo",
				"logo_class": "fa fa-lock"
			},
			"Freja EID": {
				"label": "Freja EID",
				"type": "openidconnect",
				"scope": "openid profile email",
				"authorize": "https://oidc.prod.frejaeid.com/oidc/authorize",
				"token": "https://oidc.prod.frejaeid.com/oidc/token",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"ServiceNow": {
				"label": "ServiceNow",
				"type": "oauth",
				"scope": "email profile",
				"authorize": "https://{your-servicenow-domain}/oauth_auth.do",
				"token": "https://{your-servicenow-domain}/oauth_token.do",
				"userinfo": "https://{your-servicenow-domain}/{base-api-path}?access_token=",
				"logo_class": "fa fa-lock"
			},
			"IMIS": {
				"label": "IMIS",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://{your-imis-domain}/sso-pages/Aurora-SSO-Redirect.aspx",
				"token": "https://{your-imis-domain}/token",
				"userinfo": "https://{your-imis-domain}/api/iqa?queryname=$/Bearer_Info_Aurora",
				"logo_class": "fa fa-lock"
			},
			"OpenedX": {
				"label": "OpenedX",
				"type": "oauth",
				"scope": "email profile",
				"authorize": "https://{your-domain}/oauth2/authorize",
				"token": "https://{your-domain}/oauth2/access_token",
				"userinfo": "https://{your-domain}/api/mobile/v1/my_user_info",
				"logo_class": "fa fa-lock"
			},
			"Elvanto": {
				"label": "Elvanto",
				"type": "openidconnect",
				"scope": "ManagePeople",
				"authorize": "https://api.elvanto.com/oauth?",
				"token": "https://api.elvanto.com/oauth/token",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"DigitalOcean": {
				"label": "DigitalOcean",
				"type": "oauth",
				"scope": "read",
				"authorize": "https://cloud.digitalocean.com/v1/oauth/authorize",
				"token": "https://cloud.digitalocean.com/v1/oauth/token",
				"userinfo": "https://api.digitalocean.com/v2/account",
				"logo_class": "fa fa-lock"
			},
			"UNA": {
				"label": "UNA",
				"type": "openidconnect",
				"scope": "basic",
				"authorize": "https://{site-url}.una.io/oauth2/authorize?",
				"token": "https://{site-url}.una.io/oauth2/access_token",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"MemberClicks": {
				"label": "MemberClicks",
				"type": "oauth",
				"scope": "read write",
				"authorize": "https://{orgId}.memberclicks.net/oauth/v1/authorize",
				"token": "https://{orgId}.memberclicks.net/oauth/v1/token",
				"userinfo": "https://{orgId}.memberclicks.net/api/v1/profile/me",
				"logo_class": "fa fa-lock"
			},
			"MineCraft": {
				"label": "MineCraft",
				"type": "openidconnect",
				"scope": "openid",
				"authorize": "https://login.live.com/oauth20_authorize.srf",
				"token": "https://login.live.com/oauth20_token.srf",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"Neon CRM": {
				"label": "Neon CRM",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://{your Neon CRM organization id}.z2systems.com/np/oauth/auth",
				"token": "https://{your Neon CRM organization id}.z2systems.com/np/oauth/token",
				"userinfo": "https://api.neoncrm.com/neonws/services/api/account/retrieveIndividualAccount?accountId=",
				"logo_class": "fa fa-lock"
			},
			"Canvas": {
				"label": "Canvas",
				"type": "oauth",
				"scope": "openid profile",
				"authorize": "https://{your-site-url}/login/oauth2/auth",
				"token": "https://{your-site-url}/login/oauth2/token",
				"userinfo": "https://{your-site-url}/login/v2.1/users/self",
				"logo_class": "fa fa-lock"
			},
			"Ticketmaster": {
				"label": "Ticketmaster",
				"type": "openidconnect",
				"scope": "openid email",
				"authorize": "https://auth.ticketmaster.com/as/authorization.oauth2",
				"token": "https://auth.ticketmaster.com/as/token.oauth2",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"Mindbody": {
				"label": "Mindbody",
				"type": "openidconnect",
				"scope": "email profile openid",
				"authorize": "https://signin.mindbodyonline.com/connect/authorize",
				"token": "https://signin.mindbodyonline.com/connect/token",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"iGov": {
				"label": "iGov",
				"type": "openidconnect",
				"scope": "openid profile",
				"authorize": "https://idp.government.gov/oidc/authorization",
				"token": "https://idp.government.gov/token",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"LearnWorlds": {
				"label": "LearnWorlds",
				"type": "openidconnect",
				"scope": "openid profile",
				"authorize": "https://api.learnworlds.com/oauth",
				"token": "https://api.learnworlds.com/oauth2/access_token",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"Otoy": {
				"label": "Otoy",
				"type": "oauth",
				"scope": "openid",
				"authorize": "https://account.otoy.com/oauth/authorize",
				"token": "https://account.otoy.com/oauth/token",
				"userinfo": "https://account.otoy.com/api/v1/user.json",
				"logo_class": "fa fa-lock"
			},
			"other": {
				"label": "Custom OAuth",
				"type": "oauth",
				"scope": "",
				"authorize": "",
				"token": "",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			},
			"openidconnect": {
				"label": "Custom OpenID Connect App",
				"type": "openidconnect",
				"scope": "",
				"authorize": "",
				"token": "",
				"userinfo": "",
				"logo_class": "fa fa-lock"
			}
		}';
    }

    public function getAppData()
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
}
