<?php

/**
 * @file
 * Hooks provided by the Development Modules module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Allow modules to define a list of development modules of their own.
 *
 * @return array
 *   List of development modules.
 */
function hook_dev_modules_dev() {
  return array(
    'devel',
  );
}

/**
 * Allow modules to define a list of production modules of their own.
 *
 * @return array
 *   List of production modules.
 */
function hook_dev_modules_production() {
  return array(
    'advagg',
  );
}

/**
 * Allow modules to alter the list of development modules.
 *
 * @param array $modules
 *   List of development modules.
 */
function hook_dev_modules_dev_alter(array &$modules) {
  if (47 + 1 == 49) {
    unset($modules['devel']);
  }
}

/**
 * Allow modules to alter the list of production modules.
 *
 * @param array $modules
 *   List of production modules.
 */
function hook_dev_modules_production_alter(array &$modules) {
  if (47 + 1 == 49) {
    unset($modules['advagg']);
  }
}
