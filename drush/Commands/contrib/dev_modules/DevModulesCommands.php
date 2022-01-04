<?php

namespace Drush\Commands\dev_modules;

use Drupal;
use Drush\Commands\DrushCommands;

/**
 * Global Drush command to enable/disable all development modules at once.
 */
class DevModulesCommands extends DrushCommands {

  /**
   * DevModulesCommands constructor.
   */
  public function __construct() {
    parent::__construct();
    require_once __DIR__ . '/DevModulesInterface.php';
    require_once __DIR__ . '/DevModules.php';
    require_once __DIR__ . '/DevModules8.php';
  }

  /**
   * Enable/disable all development modules at once.
   *
   * @command site:dev
   * @aliases dev-modules, dev
   * @bootstrap full
   *
   * @param mixed $flag
   *   On or off, yes or no, True or False, 1 or 0.
   * @param array $options
   *
   * @throws \Exception
   * @option list Only determine the list of modules and print that list to the console
   *
   */
  public function devMode($flag, $options = ['list' => FALSE]): void {
    $devModules = new DevModules8();
    $list = $devModules
      ->setEnableMode($flag)
      ->prepareModules()
      ->processModules($options['list']);
    if ($options['list']) {
      $this->io()->section('Enable');
      $this->io()->listing($list['enable']);
      $this->io()->section('Disable');
      $this->io()->listing($list['disable']);
    }
  }

  /**
   * Init site by following instructions in given file.
   *
   * @command site:init
   * @aliases init-site
   * @bootstrap full
   * @param string $initFile
   */
  public function initSite($initFile): void {
    $instructions = json_decode(file_get_contents($initFile), TRUE);
    foreach ($instructions['config'] as $configName => $keys) {
      $config = Drupal::configFactory()->getEditable($configName);
      foreach ($keys as $key => $value) {
        $config->set($key, $value);
      }
      $config->save();
    }
  }

}
