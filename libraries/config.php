<?php
/* Require Library */
require("TeamSpeak3/TeamSpeak3.php");
/* Require Library End */

/* Config Start with Array */
$config = array();

/* Give the Array Information */
$config['Username'] = "serveradmin"; // Login name for the Query
$config['Password'] = "Opossum25lol"; // Password for the Query
$config['serverIP'] = "opossumts.net"; // Server IP or Domain Name
$config['sPort'] = "9987"; // Server Port for the Query | Default: 9987
$config['qPort'] = "10011"; // Query Port for the Query | Default: 10011
$config['BotName'] = rawurlencode("3-Channel-Creator"); // url encoded bot name
$config['CheckDelay'] = 3; // Amount of Seconds between each Check. Only use Number greater then 1. Faster Checks are useful for bigger TeamSpeaks, in smaller TeamSpeaks, this can be higher
/* Bot Config End */

/* TeamSpeak Settings */
$config['PublicChannels'] = array(3263, 3264, 3265); // Put the Public Channels here | Note: The Last Channel in this List should always be the last Channel in the TeamSpeak Order as well
$config['TempChannelName'] = "Dreier-Channel "; // Temporary Public Channel Name
$config['TempMaxClients'] = 3; // Set the Max Clients for new Temp Channels
/* TeamSpeak Setting End */
?>