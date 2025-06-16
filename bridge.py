import serial
import requests
import time

# --- CONFIGURATION ---
SERIAL_PORT = 'COM12'  # <-- À MODIFIER !
BAUD_RATE = 9600
API_UPDATE_URL = 'http://localhost/projet-parking/api/update-spot-status'
API_GET_URL = 'http://localhost/projet-parking/api/get-spot-status'
# ---------------------

print("--- Pont Serie vers API (Bidirectionnel) ---")
print(f"Ecoute sur le port {SERIAL_PORT}...")
print("Appuyez sur Ctrl+C pour arreter.")

def send_update_to_server(spot_number, status):
    """Envoie une mise à jour de statut au serveur (POST)."""
    payload = {'spot_number': spot_number, 'status': status}
    try:
        response = requests.post(API_UPDATE_URL, data=payload, timeout=5)
        if response.status_code == 200:
            print(f"-> SUCCES (POST): Place {spot_number} mise a jour a '{status}'.")
        else:
            print(f"-> ERREUR (POST): Le serveur a repondu {response.status_code}. Details: {response.text}")
    except requests.exceptions.RequestException as e:
        print(f"-> ERREUR DE CONNEXION (POST): {e}")

def get_status_from_server(spot_number, ser_conn):
    """Récupère le statut du serveur (GET) et l'envoie à la Tiva."""
    params = {'spot_number': spot_number}
    try:
        response = requests.get(API_GET_URL, params=params, timeout=5)
        if response.status_code == 200:
            data = response.json()
            if data.get('success'):
                status_from_server = data['status']
                # Envoi de la réponse à la carte Tiva
                message_to_tiva = f"SERVER_STATUS:{status_from_server}\n"
                ser_conn.write(message_to_tiva.encode('utf-8'))
                print(f"-> INFO (GET): Statut pour {spot_number} est '{status_from_server}'. Reponse envoyee a la Tiva.")
        else:
             print(f"-> ERREUR (GET): Le serveur a repondu {response.status_code}. Details: {response.text}")
    except requests.exceptions.RequestException as e:
        print(f"-> ERREUR DE CONNEXION (GET): {e}")


try:
    ser = serial.Serial(SERIAL_PORT, BAUD_RATE, timeout=1)
    time.sleep(2)

    while True:
        line = ser.readline().decode('utf-8').strip()

        if line:
            print(f"Recu de la Tiva: '{line}'")
            
            # Message de la Tiva pour METTRE A JOUR le serveur
            if line.startswith("SENSOR_STATUS:"):
                parts = line.split(':')
                if len(parts) == 3:
                    send_update_to_server(spot_number=parts[1], status=parts[2])
                
            # Message de la Tiva pour DEMANDER l'état du serveur
            elif line.startswith("GET_SERVER_STATUS:"):
                parts = line.split(':')
                if len(parts) == 2:
                    get_status_from_server(spot_number=parts[1], ser_conn=ser)

except serial.SerialException as e:
    print(f"\nERREUR CRITIQUE: Impossible d'ouvrir le port serie '{SERIAL_PORT}'.")
    print(f"Details: {e}")
except KeyboardInterrupt:
    print("\n--- Programme arrete. ---")
finally:
    if 'ser' in locals() and ser.is_open:
        ser.close()
        print("Port serie ferme.")