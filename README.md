Resell Phone API
===============

[ Still in development - 14/02/17 ]

### To install app:

The application requires PHP >= 5.4.

Run:

    git clone https://github.com/NicolasCayet/resell-phone-api.git  
    cd resell-phone-api/
    composer install
It will install external libraries, including the framework Symfony (actual 2.8). Also generates `app/config/parameters.yml` file

Launch unit testing to check every thing is ready:

    phpunit -c app/

### To run app:


#### Global info

Be sure the following directories are writeable:

  - `/app/cache/`
  - `/app/logs/`

Also, the document root of the API is located in /web/.
Please use the /web/app_dev.php as default index file while it is in development.

The first POC does not require use of SQL database. Every data are saved in JSON files.

The storage is implemented by the App/StorageBundle; But migration to Doctrine 2 may occure in next phases.

#### Using Apache

Config example for an Apache VirtualHost:

    <VirtualHost *>
        DocumentRoot "/path-to-resell-phone-project/web"
        ServerName resell-phone.local
        
        <Directory /path-to-resell-phone-project/web>
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

  - Run the command line console
  - Go to the project root directory
  - Execute: 
  
        app/console server:run
  - (Default port is 8000. You can modify the port as this example : app/console server:run 127.0.0.1:8001)
  
### To test the API

  - Run the command console
  - From project root directory, run
  
        app/console debug:router --env=prod

The command will issue available URLs like:

  - GET /api/brands/{id}.{_format}
  - POST /api/brands.{_format}
  - GET /api/brands/{brandId}/phones.{_format} 
  
> Replace {_format} by the expected response format (json, xml).

> Replace {id} by an integer.

> Set request header "Content-Type: application/json" when performing a creation/modification.

> POST and PUT methods are currently not available using XML format (works only for getting resources)

#### Swagger

Swagger UI with sandbox enabled is available under URL:

    http://domain-name-serving.app/swagger

> For example: http://127.0.0.1:8000/swagger
