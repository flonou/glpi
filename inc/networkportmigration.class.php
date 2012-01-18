<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2011 by the INDEPNET Development Team.

 http://indepnet.net/   http://glpi-project.org
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

// ----------------------------------------------------------------------
// Original Author of file: Damien Touraine
// Purpose of file:
// ----------------------------------------------------------------------

if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

/// NetworkPortMigration class : class of unknown objects defined inside the NetworkPort before 0.84
/// @since 0.84
class NetworkPortMigration extends NetworkPortInstantiation {

   static function getMotives() {
      return array( 'unknown_interface_type' => __('undefined interface'),
                    'invalid_network'        => __('invalid network (already defined or with invalid addresses'),
                    'invalid_gateway'        => __('gateway not include inside the network'),
                    'invalid_address'        => __('invalid IP address') );
   }

   static function getTypeName($nb=0) {
     return __('Not defined. Inherit from migration');
   }


   static function getShowForItemNumberColums() {
      return 3;
   }

   // mac, networkinterfaces_id, netpoints_id


   static function showForItemHeader() {

      echo "<th>" . __('MAC') . "</th>\n";
      echo "<th>" . __('Network outlet') . "</th>\n";
      echo "<th>" . NetworkInterface::getTypeName(1) . "</th>\n";
  }


   function showForItem(NetworkPort $netport, CommonDBTM $item, $canedit, $withtemplate='') {

      echo "<td>".$this->fields["mac"]."</td>\n";
      echo "<td>".Dropdown::getDropdownName("glpi_netpoints",
                                            $this->fields["netpoints_id"])."</td>\n";
      echo "<td>".Dropdown::getDropdownName("glpi_networkinterfaces",
                                            $this->fields["networkinterfaces_id"])."</td>\n";
  }


   function showInstantiationForm(NetworkPort $netport, $options=array(), $recursiveItems) {

      if (!$options['several']) {
         echo "<tr class='tab_bg_1'>";
         $this->showNetpointField($netport, $options, $recursiveItems);

         echo "<td>" . NetworkInterface::getTypeName(1) . "</td>\n";
         echo "<td>";
         Dropdown::show('NetworkInterface',
                        array('value' => $this->fields["networkinterfaces_id"]));
         echo "</td>";
         echo "</tr>\n";
      }

      echo "<tr class='tab_bg_1'>";
      $this->showMacField($netport, $options);
      echo "</tr>";
   }

   function getSearchOptions() {
      global $CFG_GLPI;

      $tab = parent::getSearchOptions();


      $optionIndex = 10;
      foreach (self::getMotives() as $motive => $name) {

         $tab[$optionIndex]['table']         = $this->getTable();
         $tab[$optionIndex]['field']         = $motive;
         $tab[$optionIndex]['name']          = $name;
         $tab[$optionIndex]['datatype']      = 'bool';

         $optionIndex ++;
      }

      $tab[$optionIndex]['table']         = $this->getTable();
      $tab[$optionIndex]['field']         = 'ip';
      $tab[$optionIndex]['name']          = IPAddress::getTypeName(1);
      $optionIndex ++;

      $tab[$optionIndex]['table']         = $this->getTable();
      $tab[$optionIndex]['field']         = 'netmask';
      $tab[$optionIndex]['name']          = IPNetmask::getTypeName(1);
      $optionIndex ++;

      $tab[$optionIndex]['table']         = $this->getTable();
      $tab[$optionIndex]['field']         = 'subnet';
      $tab[$optionIndex]['name']          = IPNetwork::getTypeName(1);
      $optionIndex ++;

      $tab[$optionIndex]['table']         = $this->getTable();
      $tab[$optionIndex]['field']         = 'gateway';
      $tab[$optionIndex]['name']          = IPAddress::getTypeName(1);
      $optionIndex ++;

      $tab[$optionIndex]['table']         = 'glpi_networkinterfaces';
      $tab[$optionIndex]['field']         = 'name';
      $tab[$optionIndex]['name']          = NetworkInterface::getTypeName(1);
      $optionIndex ++;

      return $tab;
   }
}
?>
