<?php
require 'db.php';

if(isset($_POST)) {
  $data = file_get_contents("php://input");
  $decodedData = json_decode($data, true);
  
  // echo $ledId["ledNumber"];
  $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
  $result = socket_connect($socket, 'put.your.ip.here', 8001);

  if ($result === false) {
      echo "Failed to connect to the server: " . socket_strerror(socket_last_error()) . "\n";
      exit;
  }

  // $data = socket_read($socket, 1024);
  $result = socket_write($socket, $data, 1024);
  if ($result === false) {
    echo "Failed to send data to the MicroPython device.\n";
    exit;
  }

  if ($decodedData["type"] == "dht" || 
      $decodedData["type"] == "tilt" || 
      $decodedData["type"] == "garland") {
        $dht_data = socket_read($socket, 2048);
        $php_data = json_decode($dht_data);
        $sql = null;

        if ($decodedData["type"] == "dht") {
          $temperature = $php_data->temperature;
          $humidity = $php_data->humidity;

          $sql = "INSERT INTO dht_data (temperature, humidity) 
              VALUES ('$temperature', '$humidity')";
        } else if ($decodedData["type"] == "tilt") {
          $tilted = $php_data->tilted;
          $sql = "INSERT INTO tilt_data (tilted) 
              VALUES (" . ($tilted == "Tilted!" ? "1" : "0") . ")";
      
      }
      if (isset($sql)) {
        if ($conn->query($sql) === FALSE) {
          echo "Error: " . $sql . "<br>" . $conn->error;
        }
        $conn->close();
      }
    header('Content-Type: application/json');
    sleep(1);
    echo $dht_data;
  }
  socket_close($socket);
}
?>