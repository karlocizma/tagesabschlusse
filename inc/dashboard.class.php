<?php

class PluginTagesabschlusseDashboard extends CommonGLPI {

   static function getTypeName($nb = 0) {
      return 'Tagesabschlusse';
   }

   static function canView() {
      return Session::haveRight('ticket', READ);
   }

   static function display() {
      global $DB;

      // Default to today
      $date = date('Y-m-d');
      if (isset($_GET['date'])) {
         $date = $_GET['date'];
      }

      $users_id = Session::getLoginUserID();
      if (isset($_GET['users_id'])) {
         $users_id = $_GET['users_id'];
      }

      // Filter Form
      echo "<div class='card mb-4'>";
      echo "<div class='card-body'>";
      echo "<form method='GET' action='dashboard.php'>";
      echo "<table class='tab_cadre_fixe'>";
      echo "<tr>";
      echo "<td><label for='date' class='form-label'>" . __('Date') . ": </label></td>";
      echo "<td>";
      Html::showDateField('date', ['value' => $date]);
      echo "</td>";
      echo "<td><label for='users_id' class='form-label'>" . __('Technician') . ": </label></td>";
      echo "<td>";
      User::dropdown(['name' => 'users_id', 'value' => $users_id, 'right' => 'interface', 'all' => 1]);
      echo "</td>";
      echo "<td><input type='submit' class='btn btn-primary' value='" . __('Search') . "'></td>";
      echo "</tr>";
      echo "</table>";
      echo "</form>";
      echo "</div></div>";

      // Build Query
      $where = [
         'glpi_tickettasks.date' => ['LIKE', "$date%"]
      ];
      
      if ($users_id > 0) {
         $where['glpi_tickettasks.users_id_tech'] = $users_id;
      }

      // Query glpi_tickettasks
      $iterator = $DB->request([
         'SELECT' => [
            'glpi_tickettasks.id',
            'glpi_tickettasks.date',
            'glpi_tickettasks.actiontime',
            'glpi_tickettasks.content',
            'glpi_tickets.id AS ticket_id',
            'glpi_tickets.name AS ticket_name',
            'glpi_users.id AS user_id',
            'glpi_users.name AS user_name',
            'glpi_users.realname',
            'glpi_users.firstname'
         ],
         'FROM' => 'glpi_tickettasks',
         'LEFT JOIN' => [
            'glpi_tickets' => [
               'ON' => [
                  'glpi_tickettasks' => 'tickets_id',
                  'glpi_tickets' => 'id'
               ]
            ],
            'glpi_users' => [
               'ON' => [
                  'glpi_tickettasks' => 'users_id_tech',
                  'glpi_users' => 'id'
               ]
            ]
         ],
         'WHERE' => $where,
         'ORDER' => 'glpi_tickettasks.date DESC'
      ]);

      echo "<div class='card'>";
      echo "<div class='card-header'><h3 class='card-title'>Tagesabschlusse - " . Html::convDate($date) . "</h3></div>";
      echo "<div class='card-body'>";
      
      echo "<table class='table table-hover'>";
      echo "<thead><tr>";
      echo "<th>" . __('Technician') . "</th>";
      echo "<th>" . __('Ticket') . "</th>";
      echo "<th>" . __('Description') . "</th>";
      echo "<th>" . __('Duration') . "</th>";
      echo "</tr></thead>";
      echo "<tbody>";

      $total_time = 0;

      foreach ($iterator as $data) {
         $tech_name = formatUserName($data['user_id'], $data['user_name'], $data['realname'], $data['firstname']);
         $ticket_link = "<a href='" . Ticket::getFormURLWithID($data['ticket_id']) . "'>" . $data['ticket_id'] . " - " . $data['ticket_name'] . "</a>";
         $duration = Html::timestampToString($data['actiontime'], false);
         $total_time += $data['actiontime'];

         echo "<tr>";
         echo "<td>" . $tech_name . "</td>";
         echo "<td>" . $ticket_link . "</td>";
         echo "<td>" . $data['content'] . "</td>";
         echo "<td>" . $duration . "</td>";
         echo "</tr>";
      }

      echo "</tbody>";
      echo "<tfoot><tr>";
      echo "<td colspan='3' style='text-align:right; font-weight:bold'>" . __('Total') . "</td>";
      echo "<td style='font-weight:bold'>" . Html::timestampToString($total_time, false) . "</td>";
      echo "</tr></tfoot>";
      echo "</table>";
      
      echo "</div></div>";
   }
}
