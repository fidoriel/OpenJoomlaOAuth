# OpenJoomlaOAuth

This is adapted from the [miniOrange OAuth Client](https://extensions.joomla.org/extension/access-a-security/miniorange-oauth-client/) at time of forking licensed GLPv2 or later.
If not stated otherwise in file all code is licensed under GLPv3 or later.
I created my own fork to have a more lightweight and customizable and free solution.
The whole plugin was debloated, all feature limitations removed and the code was cleaned up to some degree and some bugs were fixed.
It is able to do everything I need but still lacks some features you may expect from a full fledged OAuth client - feel free to contribute, it is free and open source.
There is currently no role mapping and no way to configure a custom attribute mapping.

## Create Login Button

The redirect after login url is set in the cookie `returnurl`.

Create a new `Custom` Site Module with a button link. You can copy your link from Step 4 of the wizard.

This is a example for Keycloak:

```html
<a href="?openjoomlarequest=oauthredirect&app_name=keycloak"> <button>Log In with SSO</button> </a>
```

You can add a `redirect_after_login` parameter to the link to set the redirect after login url. Make sure to url encode the value.

For Joomla this could be:

```html
<p><a href="?openjoomlarequest=oauthredirect&app_name=keycloak&redirect_after_login=https://example.com/index.php/news-intern/aktuelles"> <button>Login Mitgliederbereich mit SSO</button> </a></p>
```

## Development

Format with 
```bash
php-cs-fixer fix --rules=@PSR2 plugin/
```
Build a zip file with joomla plugin structure with
```bash
bash package.sh
```

You can enable debug mode in joomla settings:
Global Settings -> System -> Debug System -> Yes
Enable error reporting in joomla settings:
Global Settings -> Server -> Error Reporting -> Maximum
