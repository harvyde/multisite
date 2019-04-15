a library for detecting sites / hosts and environments. This library can be useful to build a multisite. Multiple domains pointing to the same application. You can use this class anywhere in your application to serve content based on domain and environment site be being accessed.

# usage
```php
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    
    ## get current environment
    Multisite::env();
    
    ## check environment
    Multisite::is_env('prod');
    Multisite::is_env(['prod', 'new']);
```

# Support and Feedback
If you find a bug, please submit the issue in Github directly. [harvyde/multisite](https://github.com/harvyde/multisite/issues)  Issues



