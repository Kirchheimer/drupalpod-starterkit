Development Modules
-------------------

This is not a module, it's a Drush command only which is desiged to work with
Drupal versions 6, 7 and 8.

The module has two commands/purposes at this stage:

```shell script
drush site:init <filename>
```

This command can be used to initialise any new Drupal 8 site with instructions taken from the given file which needs to be provided in json format. Currently, only setting config values is supported and a sample file can be found in the example directory.

```shell script
drush site:dev <flag>
```

The purpose of this command is to easily switch between development and production
stages.

When you turn on develoment stage, this command enables a list of configurable
development modules and disables a list of configurable production modules. When
you turn off development stage, it just does the opposite.

This command comes with two pre-defined lists of development and production
modules:

A. Development modules

- browser_refresh
- coffee
- devel
- field_ui (if layout_builder is not available)
- hacked
- link_css
- rules_ui
- stage_file_proxy
- views_ui

B. Production modules

- advagg
- bigpipe
- dynamic_page_cache
- page_cache
- purge
- varnish_purger

To add your own modules to those lists or to alter the existing lists, there
are a few hooks available that let you do exactly that. For further details
please refer to the API documentation in dev_modules.api.inc

You have questions, issues or suggestions? Please come over to the issue queue
and let us know: https://www.drupal.org/project/issues/dev_modules - of course
your patches are welcome there too.
