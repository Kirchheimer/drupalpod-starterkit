<?php

namespace Drush\Commands\dev_modules;

use InvalidArgumentException;

/**
 * Common code for Drush 9 and older.
 */
abstract class DevModules implements DevModulesInterface {

  protected $enable = TRUE;
  protected $developmentModules;
  protected $productionModules;
  protected $enableModules;
  protected $disableModules;

  /**
   * Constructs a DevModules object.
   */
  public function __construct() {
    $this->developmentModules = $this->developmentDefaults();
    $this->productionModules = $this->productionDefaults();
    $this->alterModuleLists();
  }

  /**
   * Invokes alter hooks to allow all modules to contribute to module lists.
   *
   * @return $this
   */
  abstract protected function alterModuleLists(): self;

  /**
   * Rebuild module list.
   *
   * @return array
   */
  abstract protected function rebuildModuleData(): array;

  /**
   * Determines if a given module exists.
   *
   * @param string $module
   *   Machine name of the module.
   *
   * @return bool
   *   TRUE if the module exists and is enabled, FALSE otherwise.
   */
  abstract protected function moduleExists($module): bool;

  /**
   * Enables all the prepared modules.
   *
   * @return $this
   */
  abstract protected function doEnableModules(): self;

  /**
   * Disables all the prepared modules.
   *
   * @return $this
   */
  abstract protected function doDisableModules(): self;

  /**
   * {@inheritdoc}
   */
  public function setEnableMode($flag): DevModulesInterface {
    $this->enable = $this->flag($flag);
    return $this;
  }

  /**
   * Sanitizes the list of modules that need to be enabled.
   *
   * @param array $modules
   *   List of to be enabled modules.
   *
   * @return array
   *   Sanitized list of to be enabled modules.
   */
  protected function prepareEnableModule(array $modules): array {
    $module_data = $this->rebuildModuleData();
    $list = array();
    foreach ($modules as $module) {
      if (isset($module_data[$module]) && !$this->moduleExists($module)) {
        $list[] = $module;
      }
    }
    return $list;
  }

  /**
   * Sanitizes the list of modules that need to be disabled.
   *
   * @param array $modules
   *   List of to be disabled modules.
   *
   * @return array
   *   Sanitized list of to be disabled modules.
   */
  protected function prepareDisableModule(array $modules): array {
    $list = array();
    foreach ($modules as $module) {
      if ($this->moduleExists($module)) {
        $list[] = $module;
      }
    }
    return $list;
  }

  /**
   * {@inheritdoc}
   */
  public function prepareModules(): DevModulesInterface {
    if ($this->enable) {
      $this->enableModules = $this->prepareEnableModule($this->developmentModules);
      $this->disableModules = $this->prepareDisableModule($this->productionModules);
    }
    else {
      $this->enableModules = $this->prepareEnableModule($this->productionModules);
      $this->disableModules = $this->prepareDisableModule($this->developmentModules);
    }
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function processModules($listOnly): array {
    if (!$listOnly) {
      $this
        ->doEnableModules()
        ->doDisableModules();
    }
    return array(
      'enable' => $this->enableModules,
      'disable' => $this->disableModules,
    );
  }

  /**
   * Returns default list of development modules.
   *
   * @return array
   *   List of development modules.
   */
  protected function developmentDefaults(): array {
    $modules = array(
      'browser_refresh',
      'coffee',
      'devel',
      'hacked',
      'kint',
      'link_css',
      'rules_ui',
      'stage_file_proxy',
    );
    if (!$this->moduleExists('layout_builder')) {
      $modules[] = 'field_ui';
    }
    return $modules;
  }

  /**
   * Returns default list of production modules.
   *
   * @return array
   *   List of production modules.
   */
  protected function productionDefaults(): array {
    return array(
      'advagg',
      'bigpipe',
      'cdn',
      'cloudflare',
      'cloudflarepurger',
      'dynamic_page_cache',
      'page_cache',
      'purge',
      'varnish_purger',
    );
  }

  /**
   * Validates the flag returns its boolean equivalent.
   *
   * @param string $flag
   *   Flag to enable (on|yes|true|1) or disable (off|no|false|0) dev modules.
   *
   * @return bool
   *   TRUE for valid positive flags, FALSE for valid negative flags.
   *
   * @throws InvalidArgumentException
   */
  protected function flag($flag): bool {
    switch (strtolower($flag)) {
      case 'on':
      case 'yes':
      case 'true':
      case '1':
        return TRUE;

      case 'off':
      case 'no':
      case 'false':
      case '0':
        return FALSE;

      default:
        throw new InvalidArgumentException('Unsupported flag.');
    }
  }

}
