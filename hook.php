<?php

/**
 * Install hook
 *
 * @return boolean
 */
function plugin_tagesabschlusse_install() {
   // No specific database tables needed for now, relying on core TicketTasks
   return true;
}

/**
 * Uninstall hook
 *
 * @return boolean
 */
function plugin_tagesabschlusse_uninstall() {
   // Cleanup if needed
   return true;
}
