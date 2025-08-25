<?php

use function Cake\Core\env;

/*
 * Local configuration file to provide any overrides to your app.php configuration.
 * Copy and save this file as app_local.php and make changes as required.
 * Note: It is not recommended to commit files with credentials such as app_local.php
 * into source code version control.
 */

return [
    /*
     * Debug Level:
     *
     * Production Mode:
     * false: No error messages, errors, or warnings shown.
     *
     * Development Mode:
     * true: Errors and warnings shown.
     */
    'debug' => filter_var(env('DEBUG', true), FILTER_VALIDATE_BOOLEAN),

    /*
     * Security and encryption configuration
     *
     * - salt - A random string used in security hashing methods.
     *   The salt value is also used as the encryption key.
     *   You should treat it as extremely sensitive data.
     */
    'Security' => [
        'salt' => env('SECURITY_SALT', '2c5cd1acaebac96961d2f778b244c7a75903afe91d9a9f21cf97e95b6d14aee6'),
    ],

    /*
     * Connection information used by the ORM to connect
     * to your application's datastores.
     *
     * See app.php for more configuration options.
     */
    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => env('DB_HOST', 'gateway01.us-east-1.prod.aws.tidbcloud.com'),
            'port' => env('DB_PORT', 4000),
            'username' => env('DB_USER', '3bHozqb49gZMc7R.root'),
            'password' => env('DB_PASS', '8UgM2xq7Hj8CXEHI'),
            'database' => env('DB_NAME', 'test'),
            'encoding' => 'utf8mb4',
            'timezone' => 'UTC',
            'flags' => [
                \PDO::MYSQL_ATTR_SSL_CA => '/var/www/html/certs/isrgrootx1.pem',
                \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true,
                \PDO::MYSQL_ATTR_SSL_KEY => null,
                \PDO::MYSQL_ATTR_SSL_CERT => null,
            ],
            'cacheMetadata' => true,
        ],
    ],


    /*
     * Email configuration.
     *
     * Host and credential configuration in case you are using SmtpTransport
     *
     * See app.php for more configuration options.
     */
    'EmailTransport' => [
        'default' => [
            'host' => 'localhost',
            'port' => 25,
            'username' => null,
            'password' => null,
            'client' => null,
            'url' => env('EMAIL_TRANSPORT_DEFAULT_URL', null),
        ],
    ],
];
