<?php

include ('../../../inc/includes.php');

PluginTagesabschlusseDashboard::checkRights();

Html::header('Tagesabschlusse', $_SERVER['PHP_SELF'], "tools", "plugin_tagesabschlusse_dashboard");

PluginTagesabschlusseDashboard::display();

Html::footer();
