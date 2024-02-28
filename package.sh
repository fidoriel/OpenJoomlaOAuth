rm OpenJoomlaOAuth.zip

cd plugin

rm com_miniorange_oauth.zip
rm plg_system_miniorangeoauth.zip

zip -r com_miniorange_oauth.zip com_miniorange_oauth
zip -r plg_system_miniorangeoauth.zip plg_system_miniorangeoauth

cd ..

zip -r OpenJoomlaOAuth.zip \
    plugin/language \
    plugin/pkg_oauthclient.xml \
    plugin/pkg_script.php \
    plugin/com_miniorange_oauth.zip \
    plugin/plg_system_miniorangeoauth.zip
