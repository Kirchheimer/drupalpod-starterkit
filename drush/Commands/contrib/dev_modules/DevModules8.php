<?php

namespace Drush\Commands\dev_modules;

use Drupal;

/**
 * Common code for Drush 9 and newer with Drupal 8.
 */
class DevModules8 extends DevModules {

  /**
   * {@inheritdoc}
   */
  protected function alterModuleLists(): DevModules {
    /** @noinspection AdditionOperationOnArraysInspection */
    $this->developmentModules += Drupal::moduleHandler()->invokeAll('dev_modules_dev');
    /** @noinspection AdditionOperationOnArraysInspection */
    $this->productionModules += Drupal::moduleHandler()->invokeAll('dev_modules_production');
    Drupal::moduleHandler()->alter('dev_modules_dev', $this->developmentModules);
    Drupal::moduleHandler()->alter('dev_modules_production', $this->productionModules);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function rebuildModuleData(): array {
    /** @var \Drupal\Core\Extension\ModuleExtensionList $service */
    $service = Drupal::service('extension.list.module');
    $service->reset();
    return $service->getList();
  }

  /**
   * {@inheritdoc}
   */
  protected function moduleExists($module): bool {
    return Drupal::moduleHandler()->moduleExists($module);
  }

  /**
   * {@inheritdoc}
   */
  protected function doEnableModules(): DevModules {
    Drupal::service('module_installer')->install($this->enableModules, TRUE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  protected function doDisableModules(): DevModules {
    Drupal::service('module_installer')->uninstall($this->disableModules);
    return $this;
  }

}
