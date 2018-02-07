<?php

/**
 * @file
 * Teamspeak 3 Dynamische Slots
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   TeamSpeak3
 * @version   1.0
 * @author    Felix Gerberding
 * @copyright Copyright (c) 2018 Felix Gerberding - All rights reserved.
 */

$config = array(
	'1' =>  array(
        'Nickname' => "Dynamic-Slots", // Sichtbarer Nutzername des Bots
        'Abstand' => 5, // Abstand zwischen den Slots
        'Login' => "serveradmin", // Query-Loginname z.B. Serveradmin
        'Passwort' => "123456", // Query-Passwort
        'Server_Adresse' => "127.0.0.1", // Adresse des Servers
        'Query_Port' => "10011", // Query-Port des Servers (Standard ist 10011)
        'Mindestwert' => 10, // Mindestwert an Slots der nicht unterschritten wird
        'Port' => 9987, // Port zur Verbindung
	'ID' => 1 // Fortlaufene Nummer (einfach immer um 1 erhöhen)
    )
);

// ----------------------------------------------------
// Ab hier nur Bearbeiten, wenn Sie wissen was Sie tun!
// ----------------------------------------------------

// Lädt Teamspeak-Framework
require_once ("libraries/TeamSpeak3/TeamSpeak3.php");

foreach($config as $config) {

try {

	// Verbindet sich zum Server
	$ts3_VirtualServer = TeamSpeak3::factory("serverquery://" . $config['Login'] . ":" . $config['Passwort'] . "@" . $config['Server_Adresse'] . ":" . $config['Query_Port'] . "/?nickname=" . $config['Nickname'] . "&server_port=" . $config['Port']);

}

catch(Exception $e) {

	// Gibt eventuelle Verbindungs-Fehler aus
	echo "Fehler: " . $e . "<br>";
}

$pfad = "speicher/" . $config['ID'] . ".txt";

if (!file_exists($pfad)) {
    echo "Die Datei $pfad existiert nicht <br>";
		file_put_contents($pfad, "");
}

// Berechnet Slots
$slots = $ts3_VirtualServer["virtualserver_clientsonline"]-$ts3_VirtualServer["virtualserver_queryclientsonline"];
$freie_slots = $ts3_VirtualServer['virtualserver_maxclients'] - $slots;

if ($slots == $ts3_VirtualServer['virtualserver_maxclients']) {

    	$neue_slots = $slots + $config['Abstand'];

	$ts3_VirtualServer->modify(array(
		"virtualserver_maxclients" => $neue_slots,
	));
	echo "Slots gesetzt auf " . $neue_slots . " für " . $config['Server_Adresse'] . ":" . $config['Port'];

	$time = time();
	$datei = fopen($pfad, "w");
	fwrite($datei, $time);
	fclose($datei);
	echo "<br>Schreibe Zeit in Datei: " . $time . "<br>";

} elseif ($freie_slots > $config['Abstand']) {

    if ($ts3_VirtualServer['virtualserver_maxclients'] != $config['Mindestwert']){

	$datei = fopen($pfad, "r");
	$alte_zeit  = fgets($datei, 1000);
	$time = time();
	$dif = $time - $alte_zeit;
	fclose($datei);

	if($dif > 86400){
		echo "Mehr als 24 Stunden vergangen <br>";
		$neue_slots = $ts3_VirtualServer['virtualserver_maxclients'] - $config['Abstand'];
		$ts3_VirtualServer->modify(array(
			"virtualserver_maxclients" => $neue_slots,
		));
		echo "Slots gesetzt auf " . $neue_slots . " für " . $config['Server_Adresse'] . ":" . $config['Port'];
	} else {
		echo "Weniger als 24 Stunden vergangen <br>";
	}
    }
  }
}
?>
