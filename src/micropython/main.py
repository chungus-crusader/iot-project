from machine import Pin, Timer, PWM, ADC, reset
from utime import sleep
from network import WLAN, STA_IF  # handles connecting to WiFi
import usocket
import urequests # handles making and servicing network requests
import dht
import errno
import json
import socket
import select
from dhtClass import DHT
from ledClass import LED

wlan = WLAN(STA_IF)
ip_address = wlan.ifconfig()[0]
print("IP Address:", ip_address)

def handle_client(client_sock, data):
    try:      
        print(data)
        client_sock.sendall(data.encode("utf-8"))
    except OSError as e:
        print("Sensor reading error:", e)
    client_sock.close()

# Connect to network
wlan = WLAN(STA_IF) 
wlan.active(True)

# Fill in your network name (ssid) and password here:
ssid = 'placeholder'
password = 'placeholder'
wlan.connect(ssid, password)
print(wlan.ifconfig()[0])
print(wlan.isconnected())

sock = usocket.socket(usocket.AF_INET, usocket.SOCK_STREAM)
try:
    sock.bind(('put.your.ip.here', 8001))
except OSError as e:
    print(e)
sock.listen(1)

yellow_led = LED(16)
green_led = LED(17)
red_led = LED(19)
dht_sensor = DHT(28)
tilt_sensor = Pin(18, Pin.IN, Pin.PULL_UP)

while True:
    (client_sock, client_addr) = sock.accept()
    print("Client connected:", client_addr)
    data = client_sock.recv(1024).decode("utf-8")
    print(data)

    if data:
        print("Received data from PHP server:", data)
        # Process the received data as needed
        info = json.loads(data)
        id = info["type"]
        if id == "led-1":
            print("red led received")
            green_led.fadeInOut()
        elif id == "led-2":
            yellow_led.fadeInOut()
            print("green led received")
        elif id == "led-3":
            red_led.fadeInOut()
            print("yellow led received")
        else: 
          
            if id == "dht":
                print("dht caught")
                dht_sensor.measure()
                humidity = dht_sensor.getHumidity()
                temperature = dht_sensor.getTemperature()

                data = json.dumps({
                "temperature": f"{temperature}º C",
                "humidity": f"{humidity}%"
                })
                handle_client(client_sock, data)
                print(f"Temperature: {temperature}%\nHumidity: {humidity}° C")
            elif id == "tilt":
                print("tilt caught")
                data = json.dumps({
                "tilted": f"{'Tilted!' if tilt_sensor.value() == 1 else 'Not tilted.'}"
                })
                handle_client(client_sock, data)
            elif id == "garland":
                print("Garland mode activated")
                for i in range(int(info["count"])):
                  print("accepting data")
                  green_led.fadeInOut()
                  yellow_led.fadeInOut()
                  red_led.fadeInOut()
                data = json.dumps({
                    "type": f"finished"
                })
                handle_client(client_sock, data)
            else:
              print("No readable data sent by server.")
    else:
      print("No data sent by user.")
    client_sock.setblocking(True)
    client_sock.close()
