from machine import Pin, PWM
from utime import sleep


# Class to simplify working with LED's.

class LED:
    def __init__(self, pin: int):
        self.pin = self.setPin(pin)
        self.pwm = self.setPWM()

    def setPin(self, pin: int):
        return Pin(pin, Pin.OUT)

    def setPWM(self):
        pwm = PWM(self.pin)
        pwm.freq(1000)
        return pwm

    def flash(self, waitTime: int):
        self.pin.toggle()
        sleep(waitTime)
        self.pin.toggle()

    def toggle(self):
        self.pin.toggle()

    def fadeInOut(self, speed: int = 65535):
        for duty in range(speed):
            self.pwm.duty_u16(duty)

        for duty in range(speed, 0, -3):
            self.pwm.duty_u16(duty)
