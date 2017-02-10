recommerce_test
===============

[ Still in development ]

### To run app:

#### Global info

Be sure the following directories are writeable:
  - /app/cache/
  - /app/logs/

Also, the document root of the API is located in /web/.
Please use the /web/app_dev.php as default index file while it is in development.

#### Using Apache

Config example for an Apache VirtualHost:

  <VirtualHost *>
    DocumentRoot "/path-to-recommerce_test-project/web"
    ServerName recommerce_test.local

    <Directory /path-to-recommerce_test-project/web>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride None
        Order allow,deny
        allow from all
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteRule ^(.*)$ /app_dev.php [QSA,L]
        </IfModule>
    </Directory>
  </VirtualHost>

#### Using PHP's built-in Web Server

  - Run the command console
  - Go to the project root directory
  - Execute: app/console server:run
  - (Default port is 8000. You can modify the port as this example : app/console server:run 127.0.0.1:8001)
  
### To test the API

For now, only the Brand resource methods are implemented.

They are available under the URLs:

  - GET /api/brands.{_format}
  - GET /api/brands/{id}.{_format}
  - POST /api/brands.{_format}
  - PUT /api/brands/{id}.{_format}
  - DELETE /api/brands/{id}.{_format}

> Replace {_format} by the expected response format (json, xml).

> Replace {id} by an integer.