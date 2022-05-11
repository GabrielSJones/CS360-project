# CS360-project

PLEASE READ TO GET PROJECT TO WORK AFTER DOWNLOADING XAMPP

1) contents of this repo should be placed at the location of "xampp\htdocs\CS360-project"

2)
apache->config->httpd-xampp.conf and add this exception near the start above the others

<FilesMatch "\.php$">
SetHandler application/x-httpd-php
</FilesMatch>

3)
move the index.html file from this directory to xampp\htdocs\dashboard and replace the index.html file there.
