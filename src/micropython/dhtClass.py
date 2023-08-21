from machine import Pin
import dht

# Simple-ish DHT class to simplify working with the sensor
class DHT:
    def __init__(self, pin: int):
        self.pin = self.setPin(pin)
        self.sensor = self.setDht(self.pin)

    def setDht(self, pin: Pin):
        return dht.DHT11(pin)

    def setPin(self, pin: int):
        return Pin(pin)

    def measure(self):
        self.sensor.measure()

    def getTemperature(self):
        self.measure()
        return self.sensor.temperature()

    def getHumidity(self):
        self.measure()
        return self.sensor.humidity()