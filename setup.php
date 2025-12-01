<?php

define('PLUGIN_TAGESABSCHLUSSE_VERSION', '1.0.0');

// Minimal GLPI version, inclusive
define('PLUGIN_TAGESABSCHLUSSE_MIN_GLPI', '10.0.0');
// Maximum GLPI version, exclusive
define('PLUGIN_TAGESABSCHLUSSE_MAX_GLPI', '11.0.2');

/**
 * Init the hooks of the plugin.
 *
 * @return void
 */
function plugin_init_tagesabschlusse() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['tagesabschlusse'] = true;

   // Add a menu entry in the "Tools" menu
   $PLUGIN_HOOKS['menu_toadd']['tagesabschlusse'] = ['tools' => 'PluginTagesabschlusseDashboard'];

   // Add JavaScript for Timer
   $PLUGIN_HOOKS['add_javascript']['tagesabschlusse'] = ['js/timer.js'];
}

/**
 * Get the name and the version of the plugin
 *
 * @return array
 */
function plugin_version_tagesabschlusse() {
   return [
      'name'           => 'Tagesabschlusse',
      'version'        => PLUGIN_TAGESABSCHLUSSE_VERSION,
      'author'         => 'Antigravity',
      'license'        => 'GPLv2+',
      'homepage'       => '',
      'requirements'   => [
         'glpi' => [
            'min' => PLUGIN_TAGESABSCHLUSSE_MIN_GLPI,
            'max' => PLUGIN_TAGESABSCHLUSSE_MAX_GLPI,
         ]
      ]
   ];
}

/**
 * Check if the plugin can be installed
 *
 * @return boolean
 */
function plugin_tagesabschlusse_check_prerequisites() {
   if (version_compare(GLPI_VERSION, PLUGIN_TAGESABSCHLUSSE_MIN_GLPI, '<') || version_compare(GLPI_VERSION, PLUGIN_TAGESABSCHLUSSE_MAX_GLPI, '>=')) {
      echo "This plugin requires GLPI >= " . PLUGIN_TAGESABSCHLUSSE_MIN_GLPI . " and < " . PLUGIN_TAGESABSCHLUSSE_MAX_GLPI;
      return false;
   }
   return true;
}

/**
 * Check if the plugin can be uninstalled
 *
 * @return boolean
 */
function plugin_tagesabschlusse_check_config() {
   return true;
}
