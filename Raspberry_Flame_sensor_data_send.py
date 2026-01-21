import requests
import smbus
import time

apikey = "123456789"
SLAVE_ADDRESS = 0x08
bus = smbus.SMBus(1)
time.sleep(1) # Hagyjunk időt a busz inicializálására

def send_flame_to_api(status):
    # Itt az URL-t módosítsd, ha kivetted az API kulcs ellenőrzést a PHP-ból!
    url = f'http://192.168.108.168/flame/insert_flame.php?status={status}'
    
    try:
        response = requests.get(url, timeout=5)
        print(f"API Válasz: {response.text}")
    except Exception as e:
        print(f"Hálózati hiba: {e}")

print("Lángérzékelő monitorozása...")

while True:
    try:
        # Próbáljuk megolvasni az adatot
        data = bus.read_byte(SLAVE_ADDRESS)
        status_char = chr(data)
        
        if status_char in ['0', '1']:
            print(f"Szenzor állapot: {status_char}")
            send_flame_to_api(status_char)
        
    except OSError as e:
        print(f"I2C hiba (I/O Error): Ellenőrizd a kábeleket és az Arduinót! {e}")
    except Exception as e:
        print(f"Egyéb hiba: {e}")
    
    time.sleep(5)
