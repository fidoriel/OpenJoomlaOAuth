rm OpenJoomlaOAuth.zip

cd plugin

rm com_openjoomla_oauth.zip
rm plg_system_openjoomlaoauth.zip

zip -r com_openjoomla_oauth.zip com_openjoomla_oauth
zip -r plg_system_openjoomlaoauth.zip plg_system_openjoomlaoauth

cd ..

zip -r OpenJoomlaOAuth.zip \
    plugin/language \
    plugin/pkg_oauthclient.xml \
    plugin/pkg_script.php \
    plugin/com_openjoomla_oauth.zip \
    plugin/plg_system_openjoomlaoauth.zip
