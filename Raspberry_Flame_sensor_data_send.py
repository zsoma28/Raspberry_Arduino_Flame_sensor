import requests
import smbus
import time

apikey = "123456789"
SLAVE_ADDRESS = 0x04
bus = smbus.SMBus(1)

def send_flame_to_api(status):
    # status: "1" (tűz) vagy "0" (nincs tűz)
    url = f'http://192.168.0.181/sensor/insert_flame.php?api_key={apikey}&status={status}'
    
    try:
        response = requests.get(url, timeout=5)
        if response.status_code == 200:
            print(f"Sikeres küldés! Állapot: {'TŰZ' if status == '1' else 'OK'} | Válasz: {response.text}")
        else:
            print(f"API hiba: {response.status_code}")
    except Exception as e:
        print(f"Hálózati hiba: {e}")

print("Lángérzékelő monitorozása I2C-n keresztül...")

while True:
    try:
        # 1 bájt beolvasása az Arduinótól
        data = bus.read_byte(SLAVE_ADDRESS)
        
        # Karakterré alakítjuk (ASCII '0' vagy '1')
        status_char = chr(data)
        
        # Csak akkor küldünk adatot, ha értelmezhető (0 vagy 1)
        if status_char in ['0', '1']:
            send_flame_to_api(status_char)
        else:
            print(f"Hibás adat érkezett: {status_char}")

    except Exception as e:
        print(f"I2C hiba: {e}")
    
    # 5 másodpercenkénti frissítés
    time.sleep(5)
