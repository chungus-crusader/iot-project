<?php
$host = "172.27.129.180";
$port = 3306;
$db_name = "sensor_data";
// replace these credentials with yours 
$username = "root";
$password = "root";

$conn = new mysqli($host, $username, $password, $db_name, $port);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql_dht = "CREATE TABLE IF NOT EXISTS dht_data
(temperature VARCHAR(250) NOT NULL, 
 humidity VARCHAR(250) NOT NULL, 
 datetime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 id INT NOT NULL AUTO_INCREMENT,
 PRIMARY KEY(id));";

$sql_tilt = "CREATE TABLE IF NOT EXISTS dht_data
(tilted TINYINT(1) NOT NULL,
 datetime timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 id INT NOT NULL AUTO_INCREMENT,
 PRIMARY KEY(id));";

$result_dht = $conn->query($sql_dht);
$result_tilt = $conn->query($sql_tilt);

if ($result_dht === FALSE || $result_tilt === FALSE) {
  echo "Error creating table: " . $conn->error;
}


// echo "Connected successfully";
?>