<?php

namespace Drush\Commands\dev_modules;

/**
 * Common code for Drupal before version 8.
 */
class DevModules7 extends DevModules {

  /**
   * {@inheritdoc}
   */
  protected function alterModuleLists(): DevModules {
    $this->developmentModules += module_invoke_all('dev_modules_dev');
    $this->productionModules += module_invoke_all('dev_modules_production');
    drupal_alter('dev_modules_dev', $this->developmentModules);
    drupal_alter('dev_modules_production', $this->productionModules);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function rebuildModuleData(): array {
    return system_rebuild_module_data();
  }

  /**
   * {@inheritdoc}
   */
  protected function moduleExists($module): bool {
    return module_exists($module);
  }

  /**
   * {@inheritdoc}
   */
  protected function doEnableModules(): DevModules {
    module_enable($this->enableModules);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function doDisableModules(): DevModules {
    module_disable($this->disableModules);
    return $this;
  }

}
