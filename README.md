# CS360-project

PLEASE READ TO GET PROJECT TO WORK AFTER DOWNLOADING XAMPP

1)
apache->config->httpd-xampp.conf and add this exception near the start above the others

<FilesMatch "\.php$">
SetHandler application/x-httpd-php
</FilesMatch>

2)
move the index.html file from this directory to xampp\htdocs\dashboard and replace the index.html file there.
