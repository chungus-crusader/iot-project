<?php
  require 'db.php';
  $dht_sql = "SELECT temperature, humidity FROM dht_data ORDER BY datetime DESC LIMIT 1;";
  $tilt_sql = "SELECT tilted FROM tilt_data ORDER BY datetime DESC LIMIT 1;";

  $dht_res = $conn->query($dht_sql);
  $tilt_res = $conn->query($tilt_sql);
  $temperature = "Unknown";
  $humidity = "Unknown";
  $tilted = "Unknown";

  if ($dht_res->num_rows == 1) {
    // Fetch the single row
    $row = $dht_res->fetch_assoc();

    $temperature = $row['temperature'];
    $humidity = $row['humidity'];
  }

  if ($tilt_res->num_rows == 1) {
    // Fetch the single row
    $row = $tilt_res->fetch_assoc();

    $tilted = $row['tilted'] == 1 ? "Tilted!" : "Not titled.";
  }


?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]>      <html class="no-js"> <!--<![endif]-->
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>IOT dashboard</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
  </head>
  <body style="font-family: Helvetica; margin: 40px auto;
               width: fit-content;
               border-style: solid;
               border-color: #C5BEBE;
               border-width: 1.5px;
               border-radius: 5px;
               padding: 30px;">
    <!-- <form action="index.php" method = "POST">
      <div>
        <label for="username">Username: </label>
        <input type="text" name="username">
      </div>
      <div>
        <label for="username">Password: </label>
        <input type="password" name="password">
      </div>
      <input type="submit" value="Submit" name="submit">
    </form> -->
    <div class="header">
      <h2 style="color: #C5BEBE;
                 font-weight: lighter;
                 border-width: 2px;
                 border-radius: 10px 0;
                 border-style: none none solid none; 
                 padding: 0; 
                 margin: 10px auto; 
                 height:45px; 
                 font-size: 2.25em; 
                 width:350px;">
      Pico controller (TM)</h2>
    </div>
    <div class="body" style="width: 400px; height: 420px; position: relative; margin: 30px 0 0 10px;">
      <div class="bodyGreeter" style="margin: 30px auto auto 10px;
                                      border-style: solid;
                                      width: 370px;
                                      height: 80px;
                                      border-radius: 5px;
                                      border-width: 1.5px;
                                      border-color: #C5BEBE;">
        <h3 style="margin: 10px 10px 10px 10px;
                  font-weight: lighter; 
                  letter-spacing: 0.5px;
                  color: #C5BEBE;">
        
        A simple pico dashboard to fetch sensor data and toggle LED's.</h3>
      </div>
      <div style="color: #C5BEBE;
                  display: flex;
                  flex-direction: row;" 
           class="currentStatus">
        <div class="params" style="border-style: solid; 
                                   border-width: 1.5px;
                                   border-radius: 5px;
                                   border-color:#C5BEBE;
                                   width: 160px;
                                   margin: 30px 0 0 8px;">
          <h3 style="height: 25px; text-align: center; margin: 10px 0 0 0; border-width: 1.5px; border-style: none none solid none; font-weight: lighter; color: #C5BEBE;">LED toggler</h3>
          <div class="paramList" style="height: 130px; color: #D05D5D; display: flex; flex-direction: column; margin: 0 0 0 10px; justify-content: space-around;">
            <button id="led-1" class="ledTogglerEnabled ledHover ledToggler">> Toggle LED 1</button>
            <button id="led-2" class="ledTogglerEnabled ledHover ledToggler">> Toggle LED 2</button>
            <button id="led-3" class="ledTogglerEnabled ledHover ledToggler">> Toggle LED 3</button>
          </div>
        </div>
        <div style="flex-direction: column">
          <div style="border-style: solid;
                      border-radius:5px;
                      border-width: 1.5px;
                      margin: 30px 0 0 30px;
                      height: 115px;
                      width: 177px;
                      overflow: visible">
            <h3 style="border-style: none none solid none;
                      border-width: 1.5px;
                      font-weight: lighter;
                      text-align: center;
                      margin: 10px 0 0;
                      height: 25px;">
            DHT11 data:</h3>

            <div style="display: flex;
                        flex-direction: row;
                        margin: 15px;">
              <p style="font-weight: bold;
                        margin: 0 0 0 -5px;
                        width: 120px;
                        overflow: visible;">
              > Humidity:</p>
              <p id="hum" style="font-size: 0.8em; margin: 0;"><?php echo $humidity?></p>
            </div>
            <div style="display: flex;
                        flex-direction: row;
                        margin: 5px;
                        overflow: visible">
              <p style="font-weight: bold;
                      margin: 0 0 0 5px;
                      width: 120px">
              > Temperature:</p>
              <p id="temp" style="font-size: 0.8em; margin: 0 0 0 0;">
                <?php echo $temperature?>
              </p>
            </div>
          </div>
          <button style="margin: 10px 0 0 30px;
                         text-align: center;
                         width: 180px;
                         border-style: solid;
                         border-width: 1.5px;
                         text-align: center;
                         cursor: pointer;
                         outline: inherit;
                         max-width: 180px;
                         height: 50px;
                         min-height: 50px;"
                  class="button-transition"
                  id="dht">
            Update DHT11 data
          </button>
        </div>
      </div>
      <div class="lowerContainer">
        <div class="tiltSensorData">
          <h3 class="tiltHeader">Tilt sensor data</h3>
          <p class="tilt-status" id="tilt-status"><?php echo $tilted?></p>
          <button class="update-tilt" id="tilt">Update tilt status</button>
        </div>
        <button class="garlandMenu">Toggle LED garland</button>
      </div>
      <div class="hidden garlandCount">
        <!-- <div class="selection"> -->
          <form id="garlandForm">
            <div class="selection">
              <label class="countLabel" for="count">Please choose your iteration count:</label>
              <div style="display: flex; flex-direction: column; width: fit-content;" class="">
              
                <select name="count" id="count">
                  <?php for ($i = 1; $i <= 10; $i++):?>
                    <option value="<?php echo $i?>"><?php echo $i?></option>
                  <?php endfor;?>
                </select>
                <input class="garlandSubmit" type="submit" value="Go!">
              </div>
            </div>
          </form>
        <!-- </div> -->
      </div>
    </div>
    <!-- <h1><?php // echo "{$susStatus} was the {$name}";?></h1> -->
    <!--[if lt IE 7]>
      <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="#">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->
    
    <script async defer>
      
      const hum = document.querySelector("#hum")
      const temp = document.querySelector("#temp")
      const tilt = document.querySelector("#tilt-status")
      const LEDbuttons = document.querySelectorAll(".ledToggler")

      const submitGarland = (e) => {
        e.preventDefault()
        const data = {
          type: "garland",
          count: document.querySelector("#count").value
        }
        console.log(JSON.stringify(data))
        document.querySelector(".garlandCount").classList.add("hidden");
        document.querySelector(".garlandMenu").disabled = true;
        fetch('http://localhost:8000/src/php/server.php',  {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json; charset=utf-8'
          },
          body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
          console.log(data)
          LEDbuttons.forEach(led => {
            document.querySelector(".garlandMenu").disabled = true;
            led.disabled = false
            led.classList.add("ledHover")
            led.classList.add("ledTogglerEnabled")
            led.classList.remove("ledTogglerDisabled")
        })
        })
      }
      
      const toggleGarland = (e) => {
        // const data = {
        //   type: "garland"
        // }
        // console.log(JSON.stringify(data))
        
        LEDbuttons.forEach(led => {
          // e.target.disabled = true
          led.disabled = !led.disabled
          led.classList.toggle("ledHover")
          led.classList.toggle("ledTogglerEnabled")
          led.classList.toggle("ledTogglerDisabled")
        })
        document.querySelector(".garlandCount").classList.toggle("hidden");
        // console.log(document.querySelector(".garlandCount").style.display);
        // fetch('http://localhost:8000/src/php/server.php',  {
        //   method: 'POST',
        //   headers: {
        //     'Content-Type': 'application/json; charset=utf-8'
        //   },
        //   body: JSON.stringify(data)
        // })
        // .then(res => res.json())
        // .then(data => {
        //   console.log(data)
        //   LEDbuttons.forEach(led => {
        //   e.target.disabled = false
        //   led.disabled = false
        //   led.classList.add("ledHover")
        //   led.classList.add("ledTogglerEnabled")
        //   led.classList.remove("ledTogglerDisabled")
        // })
        // })
      }

      const fetchSensorData = (e) => {
        const id = e.target.id
        const data = {
            type: id
          }

        fetch('http://localhost:8000/src/php/server.php',  {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json; charset=utf-8'
          },
          body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
          console.log(data)

          if (id == "dht") {
            hum.textContent = data["humidity"]
            temp.textContent = data["temperature"]
          } else {
            tilt.textContent = data["tilted"]
          }
        })
      }

      const toggleLED = (e) => {
        console.log(e.target.id)
        const data = {
          type: e.target.id
        }

        e.target.classList.add("onButtonClick")
        e.target.classList.remove("ledHover")
        setTimeout(() => {e.target.classList.remove("onButtonClick");
                          e.target.classList.add("ledHover")}, 50)

        fetch('http://localhost:8000/src/php/server.php',  {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json; charset=utf-8'
          },
          body: JSON.stringify(data)
        })
      }

      LEDbuttons.forEach(led => led.addEventListener("click", e => {
        e.preventDefault()
        toggleLED(e)
      }))

      document.querySelector("#dht").addEventListener("click", e => {
        e.preventDefault()
        fetchSensorData(e)
      })

      document.querySelector("#tilt").addEventListener("click", e => {
        e.preventDefault()
        fetchSensorData(e)
      })

      document.querySelector(".garlandMenu").addEventListener("click", e => {
        e.preventDefault()
        toggleGarland(e)
      })

      document.querySelector("#garlandForm").addEventListener("submit", e => {
        submitGarland(e)
      })
    </script>
  </body>
</html>