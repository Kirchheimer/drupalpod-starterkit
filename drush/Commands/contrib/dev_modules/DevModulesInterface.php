<?php

namespace Drush\Commands\dev_modules;

/**
 * Interface for DevModules.
 */
interface DevModulesInterface {

  /**
   * Callback to set the flag for subsequent operations.
   *
   * @param string $flag
   *   Flag to enable (on|yes|true|1) or disable (off|no|false|0) dev modules.
   *
   * @return $this
   *
   * @throws \Exception
   */
  public function setEnableMode($flag): self;

  /**
   * Callback to prepare dev and prod modules.
   *
   * @return $this
   */
  public function prepareModules(): self;

  /**
   * Callback to process the prepared lists of dev and prod modules.
   *
   * @param bool $listOnly
   *   Whether to only output the list of modules (TRUE) or really enable and
   *   disable them.
   *
   * @return array
   */
  public function processModules($listOnly): array;

}
