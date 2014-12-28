<?php

echo "<div>Updating table `".$config_db['prefix']."_configuration`</div>\n";
$query = array();
$query[] = "UPDATE `".$config_db['prefix']."_configuration` SET  `value` =  '5.0.1.0' WHERE  `label` =  'engine_version';";
$query[] = "INSERT INTO `".$config_db['prefix']."_configuration` (`label`, `value`) VALUES ('terms_of_use_dialog',0);";
$query[] = "REPLACE INTO `".$config_db['prefix']."_configuration` (`label`,`value`) VALUES ('maintenance_whiteip','".inet_pton('127.0.0.1')."')";

foreach ($query as $q) mysqli_query($conn,$q);

?>