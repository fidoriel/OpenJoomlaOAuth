# joomla-openid

This is adapted from the [miniOrange OAuth Client](https://extensions.joomla.org/extension/access-a-security/miniorange-oauth-client/) at time of forking licensed GLPv2 or later.

## Create Login Button

The redirect after login url is set in the cookie `returnurl`.

Create a new `Custom` Site Module with a button link. You can copy your link from Step 4 of the wizard.

This is a example for Keycloak:

```html
<a href="?morequest=oauthredirect&app_name=keycloak"> <button>Log In with SSO</button> </a>
```

You can add a `redirect_after_login` parameter to the link to set the redirect after login url. Make sure to url encode the value.

For Joomla this could be:

```html
<p><a href="?morequest=oauthredirect&app_name=keycloak&redirect_after_login=https://example.com/index.php/news-intern/aktuelles"> <button>Login Mitgliederbereich mit SSO</button> </a></p>
```
