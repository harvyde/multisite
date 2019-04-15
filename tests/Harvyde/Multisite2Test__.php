<?php
include "/Users/harvinder/sites/gitgub_projects/vendor/autoload.php";
use Harvyde\Multisite;

    $_SERVER['HTTP_HOST'] = 'xxwww.yourdomain.com';


    Multisite::setup(
      [
        'site1' => 'www.ddyourdomain.com',
        'site2' => 'www.yourdomain2.com',
      ]
    );

   Multisite::setup(
      [
        'site1' => 'www.ccyourdomain.com',
        'site2' => 'www.yourdomain2.com',
      ]
    );


    print PHP_EOL . "========================";
    print PHP_EOL . Multisite::host();
    print PHP_EOL . Multisite::env();
    print PHP_EOL . Multisite::is_env('prod');
    print PHP_EOL . Multisite::is_env(['prod', 'new']);
    print PHP_EOL . Multisite::debug_level();
    print PHP_EOL . "========================";
    print PHP_EOL;
    print PHP_EOL;