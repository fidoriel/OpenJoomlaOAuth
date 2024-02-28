# joomla-openid

This is adapted from the [miniOrange OAuth Client](https://extensions.joomla.org/extension/access-a-security/miniorange-oauth-client/) at time of forking licensed GLPv2 or later.

## Create Login Button

The redirect after login url is set in the cookie `returnurl`.

Create a new Site Module with a button link. You can copy your link from Step 4 of the wizard.

This is a example for Keycloak:

```html
<p><a href="?morequest=oauthredirect&app_name=keycloak"> <button>Log In with SSO</button> </a></p>
```
