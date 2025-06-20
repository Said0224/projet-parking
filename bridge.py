import serial
import requests
import time
import psycopg2

# --- CONFIGURATION ---
SERIAL_PORT = 'COM12'
BAUD_RATE = 9600
PARKING_SPOT_NUMBER = "101" # Le numéro de la place gérée par ce pont
# L'ID de la ligne à METTRE À JOUR dans la table capteurproximite de la BDD commune
COMMUNAL_DB_SENSOR_ID = 1     
LED_ID_IN_DB = 1
IOT_POLL_INTERVAL = 2

# --- URL API (BDD Locale) ---
API_BASE_URL = 'http://localhost/projet-parking'
API_UPDATE_URL = f'{API_BASE_URL}/api/update-spot-status'
API_GET_URL = f'{API_BASE_URL}/api/get-spot-status'

# --- Configuration BDD commune (PostgreSQL) ---
DB_IOT_CONFIG = {
    "host": "app.garageisep.com", "port": "5409", "dbname": "app_db",
    "user": "app_user", "password": "appg9"
}

# --- Variables globales ---
last_led_state = {'etat': None, 'couleur': None, 'intensite': None}
last_iot_poll_time = 0

print("--- Pont Serie vers API (Bidirectionnel) ---")
print(f"Ecoute sur le port {SERIAL_PORT}...")
print(f"Gestion de la place '{PARKING_SPOT_NUMBER}' et mise à jour du capteur ID '{COMMUNAL_DB_SENSOR_ID}'.")
print("Appuyez sur Ctrl+C pour arreter.")

# ==================== FONCTION MISE À JOUR (UPDATE au lieu de INSERT) ====================
def update_proximity_in_iot_db(sensor_id_to_update, is_available):
    """
    Met à jour l'état d'un capteur de proximité spécifique dans la BDD commune.
    :param sensor_id_to_update: L'ID de la ligne à mettre à jour (ex: 1)
    :param is_available: Un booléen (True si disponible, False si occupée)
    """
    conn = None
    try:
        conn = psycopg2.connect(**DB_IOT_CONFIG)
        cur = conn.cursor()
        # La requête met à jour la valeur et les timestamps de la ligne spécifiée
        sql = "UPDATE public.capteurproximite SET valeur = %s, date = CURRENT_DATE, heure = CURRENT_TIME WHERE id = %s"
        cur.execute(sql, (is_available, sensor_id_to_update))
        conn.commit()
        
        # Vérifie si une ligne a bien été modifiée pour confirmer que l'ID existe
        if cur.rowcount == 0:
            print(f"-> AVERTISSEMENT (BDD IoT): Aucune ligne trouvée avec l'id {sensor_id_to_update}. Aucune mise à jour.")
        else:
            print(f"-> SUCCÈS (BDD IoT): Capteur ID {sensor_id_to_update} mis à jour à '{'Disponible' if is_available else 'Occupé'}'.")
            
    except psycopg2.Error as e:
        print(f"-> ERREUR BDD IoT (update_proximity_in_iot_db): {e}")
    finally:
        if conn:
            conn.close()
# ======================================================================================

def set_led_to_auto_mode(status):
    """Force la LED à son état automatique. Écrase tout override manuel."""
    etat = True
    intensite = 100 
    command = 'auto_status'
    couleur = '#00FF00' if status == 'disponible' else '#FF0000'
    
    conn = None
    try:
        conn = psycopg2.connect(**DB_IOT_CONFIG)
        cur = conn.cursor()
        cur.execute(
            "UPDATE public.led SET etat = %s, couleur = %s, intensite = %s, last_command = %s WHERE id = %s",
            (etat, couleur, intensite, command, LED_ID_IN_DB)
        )
        conn.commit()
        print(f"-> INFO (BDD IoT): LED {LED_ID_IN_DB} REINITIALISEE en mode auto (statut: '{status}').")
    except psycopg2.Error as e:
        print(f"-> ERREUR BDD IoT (set_led_to_auto_mode): {e}")
    finally:
        if conn:
            conn.close()

def process_sensor_update(spot_number, status):
    """
    Traite une mise à jour de capteur en l'envoyant à l'API locale
    ET en mettant à jour la base de données commune IoT.
    """
    # 1. Mise à jour de la base de données locale via l'API PHP
    payload = {'spot_number': spot_number, 'status': status}
    try:
        response = requests.post(API_UPDATE_URL, data=payload, timeout=5)
        if response.status_code == 200:
            print(f"-> SUCCES (POST API Locale): Place {spot_number} mise a jour a '{status}'.")
            set_led_to_auto_mode(status)
    except requests.exceptions.RequestException as e:
        print(f"-> ERREUR DE CONNEXION (POST API Locale): {e}")

    # 2. Mise à jour dans la base de données commune IoT (PostgreSQL)
    if spot_number == PARKING_SPOT_NUMBER:
        # Conversion du statut en booléen selon votre demande :
        # 'disponible' -> True
        # 'occupée'    -> False
        is_available_bool = (status == 'disponible')
        update_proximity_in_iot_db(COMMUNAL_DB_SENSOR_ID, is_available_bool)

def get_status_from_server(spot_number, ser_conn):
    """Récupère le statut du serveur (GET) et l'envoie à la Tiva."""
    try:
        response = requests.get(API_GET_URL, params={'spot_number': spot_number}, timeout=5)
        if response.status_code == 200 and response.json().get('success'):
            status = response.json()['status']
            ser_conn.write(f"SERVER_STATUS:{status}\n".encode('utf-8'))
            print(f"<- INFO (GET): Statut pour {spot_number} est '{status}'.")
    except requests.exceptions.RequestException as e:
        print(f"-> ERREUR DE CONNEXION (GET): {e}")

def poll_led_from_iot_db(ser_conn):
    """Interroge la BDD IoT et envoie une commande à la Tiva si l'état a changé."""
    global last_led_state
    conn = None
    try:
        conn = psycopg2.connect(**DB_IOT_CONFIG)
        cur = conn.cursor()
        cur.execute("SELECT etat, couleur, intensite FROM public.led WHERE id = %s", (LED_ID_IN_DB,))
        new_state = cur.fetchone()
        cur.close()
        if new_state:
            current_state = {'etat': new_state[0], 'couleur': new_state[1], 'intensite': new_state[2]}
            if current_state != last_led_state:
                print(f"--- Changement d'etat LED detecte: {current_state}")
                last_led_state = current_state
                etat_str = "ON" if current_state['etat'] else "OFF"
                couleur_str = current_state['couleur'] or "#000000"
                intensite_str = str(current_state['intensite'] or 0)
                command_to_tiva = f"SET_LED:{etat_str}:{couleur_str}:{intensite_str}\n"
                ser_conn.write(command_to_tiva.encode('utf-8'))
                print(f"<- COMMANDE (LED): Envoyé '{command_to_tiva.strip()}' a la Tiva.")
    except psycopg2.Error as e:
        print(f"-> ERREUR BDD IoT: {e}")
    finally:
        if conn:
            conn.close()

try:
    ser = serial.Serial(SERIAL_PORT, BAUD_RATE, timeout=1)
    time.sleep(2)
    while True:
        current_time = time.time()
        line = ser.readline().decode('utf-8').strip()
        if line:
            print(f"Recu de la Tiva: '{line}'")
            if line.startswith("SENSOR_STATUS:"):
                process_sensor_update(line.split(':')[1], line.split(':')[2])
            elif line.startswith("GET_SERVER_STATUS:"):
                get_status_from_server(line.split(':')[1], ser)
        if current_time - last_iot_poll_time >= IOT_POLL_INTERVAL:
            last_iot_poll_time = current_time
            poll_led_from_iot_db(ser)
except serial.SerialException as e:
    print(f"\nERREUR CRITIQUE: Impossible d'ouvrir le port serie '{SERIAL_PORT}'.\nDetails: {e}")
except KeyboardInterrupt:
    print("\n--- Programme arrete. ---")
finally:
    if 'ser' in locals() and ser.is_open:
        ser.close()
        print("Port serie ferme.")