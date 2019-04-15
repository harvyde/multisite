<?php
namespace Harvyde;

use Exception;

final class Multisite {

  /**
   * @var $host
   * @example current host as env.hostname.com
   */
  private static $host;

  /**
   * @var $env
   * @example env from env.hostname.com
   */
  private static $env;

  /**
   * @var string
   * '' if accessed by HTTP_HOST <YourDomain>.com
   * 'www' if accessed by HTTP_HOST <subdomain>.<YourDomain>.com
   * set by detectEnv
   */
  private static $subDomain;

  /**
   * @var string
   * set by detectEnv
   */
  private static $domain;

  /**
   * multiple domains supported by site
   */
  private static $domains;

  /**
   * multiple env supported by site
   */
  const SUB_DOMAINS = [
    'prod' => ['www'],
    'dev' => ['dev', 'new'],
    'loc' => ['loc', 'croogo'],
  ];

  const DEBUG_LEVELS = [
    'prod' => 0,
    'dev' => 0,
    'loc' => 2,
  ];

  /**
   * @param array $_domains
   */
  public static function setup($_domains = []) {
    self::$domains = $_domains;
    if (isset($_SERVER['HTTP_HOST'])) {
      static::detectHost();
      static::detectEnv();
    } else {
      trigger_error('$_SERVER[\'HTTP_HOST\'] is not defined');
    }
  }

  public static function detectHost() {
//     @todo whiteListDomains
//    if(!in_array($_SERVER['HTTP_HOST'], self::$domains)){
//      throw new Exception("Invalid domain");
//    }
    static::$host = strtolower($_SERVER['HTTP_HOST']);
  }

  /**
   * @param null $host
   * for cron jobs
   */
  public static function setupForCron($host = null) {
    if($host){
      static::$host = strtolower($host);
      static::setEnv();
    }
  }

  public static function host() {
    return static::$host;
  }

  public static function isDomain($alias) {
    $DOMAINS = static::$domains;
    if (array_key_exists($alias, static::$domains)) {
      return preg_match("/" . $DOMAINS[$alias] . "$/", static::$host);
    } else {
      trigger_error('invalid domain alias');
    }
    return static::$host;
  }

  public static function detectEnv() {
    $hostParts = explode(".", $_SERVER['HTTP_HOST']);
    if (!empty($hostParts)) {
      /**
       * this may set <provalues> as $env if <provalues>.com is passed as HTTP_HOST
       */
      static::$subDomain = '';
      $e = 'prod';
      if (isset($hostParts[0])) {
        foreach (static::SUB_DOMAINS as $envName => $envHosts) {
          /**
           * if prefix exists in SUB_DOMAINS
           */
          if (in_array($hostParts[0], $envHosts)) {
            static::$subDomain = $hostParts[0];
            $e = $envName;
            break;
          }
        }
      }

      static::$env = $e;
    }


  }

  public static function setEnv() {
    $hostParts = explode(".",static::$host);
    //@todo duplicate onwards with detectEnv() fix later
    if (!empty($hostParts)) {
      /**
       * this may set <provalues> as $env if <provalues>.com is passed as HTTP_HOST
       */
      static::$subDomain = '';
      $e = 'prod';
      if (isset($hostParts[0])) {
        foreach (static::SUB_DOMAINS as $envName => $envHosts) {
          /**
           * if prefix exists in SUB_DOMAINS
           */
          if (in_array($hostParts[0], $envHosts)) {
            static::$subDomain = $hostParts[0];
            $e = $envName;
            break;
          }
        }
      }

      static::$env = $e;
    }


  }

  public static function debug_level() {
    $env = static::env();
    $DL = static::DEBUG_LEVELS;
    if (isset($DL[$env])) {
      return $DL[$env];
    }
    return 0;
  }

  /**
   * @param null $envAlias
   * @return bool
   */
  public static function availableEnvironments() {
    return array_keys(static::SUB_DOMAINS);

  }

  public static function is_env($envAlias) {
    $ENV = static::$env;

    if (is_array($envAlias)) {
      $allEnvs = static::availableEnvironments();

      /**
       * array_intersect is used to remove invalid values passed in $envAlias
       */
      $matches = array_intersect($envAlias, $allEnvs);
      if (count($matches)) {
        return in_array($ENV, $matches);
      }
    } elseif (in_array($envAlias, ['prod', 'dev', 'loc'])) {
      return $envAlias === static::env();
    }
    trigger_error("Provide a valid env " . implode('|', static::availableEnvironments()));
  }

  /**
   * @return int|null|string
   * @deprecated use Sc::env
   */
  public static function which_env() {
    return static::env();
  }

  public static function env() {
    return static::$env;
  }

  /**
   * @param string $alias
   * @return string
   * this function is written to return the requested domain with the same subdomain of current domain
   * to improve testing for YourDomain.com
   *
   * HTTP_HOST SubDomain.YourDomain.com
   * return SubDomain.YourDomain.com
   */
  public static function domain($alias = null) {
    $DOMAINS = static::$domains;
    if (!isset($DOMAINS[$alias])) {
      trigger_error('Invalid domain alias ' . $alias);
    }
    if (static::subDomain()) {
      return static::subDomain() . "." . $DOMAINS[$alias];
    }
    return $DOMAINS[$alias];
  }

  public static function subDomain() {
    return (isset(static::$subDomain)) ? static::$subDomain : '';
  }


}