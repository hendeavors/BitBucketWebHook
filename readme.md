A small bitbucket webhook package with minimal setup for Laravel 5.2

Publish configuration:
```
php artisan config:publish endeavors/bit-bucket-web-hook
```

Add to providers array in app/config/app/php:
```
'Endeavors\BitBucketWebHook\BitBucketWebHookServiceProvider'
```

create a secret key
```
php artisan bitbucketwebhook:generate.key
```

Add the new secret key in your config.php to the end of the url. So if your url is: /my/url and your key is:1234
the end result will be /my/url/1234

Known issue:
The command to generate a key publishes to the package directory. You will need to generate first, then publish.
