<?php

namespace Drush\Commands\dev_modules;

/**
 * Implements hook_drush_help().
 *
 * {@inheritdoc}
 */
function dev_modules_drush_help($section) {
  switch ($section) {
    case 'drush:dev-modules':
      return dt('Enable/disable development and production modules all at once.');
  }
  return NULL;
}

/**
 * Implements hook_drush_command().
 */
function dev_modules_drush_command() {
  $items = array();

  $items['dev-modules'] = array(
    'description' => 'Enable/disable development and production modules all at once.',
    'callback' => 'drush_dev_modules',
    'bootstrap' => DRUSH_BOOTSTRAP_DRUPAL_FULL,
    'arguments' => array(
      'flag' => 'On or off, yes or no, True or False, 1 or 0',
    ),
    'options' => array(
      'list' => 'Only determine the list of modules and print that list to the console',
    ),
    'aliases' => array('dev'),
  );

  return $items;
}

/**
 * Drush callback to execute the drush dev-modules command.
 *
 * @param string $flag
 *   Flag to enable (on|yes|true|1) or disable (off|no|false|0) dev modules.
 *
 * @throws \Exception
 */
function drush_dev_modules($flag) {
  require_once __DIR__ . '/DevModulesInterface.php';
  require_once __DIR__ . '/DevModules.php';

  if (drush_drupal_major_version() < 8) {
    require_once __DIR__ . '/DevModules7.php';
    $devModules = new DevModules7();
  }
  else {
    require_once __DIR__ . '/DevModules8.php';
    $devModules = new DevModules8();
  }

  // Get the setting for this option.
  $listOnly = drush_get_option('list');

  $list = $devModules
    ->setEnableMode($flag)
    ->prepareModules()
    ->processModules($listOnly);

  // Output the effective list.
  if ($listOnly) {
    drush_print('Modules to disable: ' . implode(', ', $list['disable']));
    drush_print('Modules to enable: ' . implode(', ', $list['enable']));
  }
}
