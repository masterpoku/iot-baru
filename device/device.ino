#include <WiFi.h>
#include <HTTPClient.h>
#include <FS.h>                  
#include <WiFiManager.h>        
#ifdef ESP32
  #include <SPIFFS.h>
#endif
#include <ArduinoJson.h>

char email[40];
char password[40];
char host_ip[16] = "192.168.1.1";
char device_id[34] = "YOUR_DEVICE_ID";

bool shouldSaveConfig = false;
const int LED_GREEN = 14;
const int LED_RED = 12;
const int LED_YELLOW = 13;

void saveConfigCallback() {
  Serial.println("Should save config");
  shouldSaveConfig = true;
}

void loginDevice() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    String url = "http://" + String(host_ip) + ":80/example-app/public/api/login";
    http.begin(url);
    
    http.addHeader("Content-Type", "application/json");
    StaticJsonDocument<200> doc;
    doc["email"] = email;
    doc["password"] = password;
    
    String requestBody;
    serializeJson(doc, requestBody); 
    int httpResponseCode = http.POST(requestBody);
    
    if (httpResponseCode > 0) {
      String response = http.getString();  
      
      Serial.println("HTTP Response code: " + String(httpResponseCode));
      Serial.println("Response from server: " + response);
      
      StaticJsonDocument<512> jsonResponse;
      DeserializationError error = deserializeJson(jsonResponse, response);
      
      if (!error) {
        const char* token = jsonResponse["token"]; 
        Serial.println("Token: " + String(token));
        
        // Login berhasil, nyalakan LED hijau
        digitalWrite(LED_GREEN, HIGH);
        digitalWrite(LED_RED, LOW);
        digitalWrite(LED_YELLOW, LOW);
        
      } else {
        Serial.println("Failed to parse JSON response");
        
        // Login gagal, nyalakan LED merah
        digitalWrite(LED_GREEN, LOW);
        digitalWrite(LED_RED, HIGH);
        digitalWrite(LED_YELLOW, LOW);
      }
    } else {
      Serial.println("Error on sending POST: " + String(httpResponseCode));
      
      // Gagal koneksi ke server, nyalakan LED kuning
      digitalWrite(LED_GREEN, LOW);
      digitalWrite(LED_RED, LOW);
      digitalWrite(LED_YELLOW, HIGH);
    }
    
    http.end();
  } else {
    Serial.println("Error: Not connected to WiFi");
    
    // Tidak terkoneksi ke WiFi, nyalakan LED kuning
    digitalWrite(LED_GREEN, LOW);
    digitalWrite(LED_RED, LOW);
    digitalWrite(LED_YELLOW, HIGH);
  }
}

void setup() {
  Serial.begin(115200);
  Serial.println();

  // Inisialisasi pin LED
  pinMode(LED_GREEN, OUTPUT);
  pinMode(LED_RED, OUTPUT);
  pinMode(LED_YELLOW, OUTPUT);
  
  // Pastikan semua LED dimatikan pada awal
  digitalWrite(LED_GREEN, LOW);
  digitalWrite(LED_RED, LOW);
  digitalWrite(LED_YELLOW, LOW);

  if (SPIFFS.begin()) {
    Serial.println("mounted file system");
    if (SPIFFS.exists("/config.json")) {  
      Serial.println("reading config file");
      File configFile = SPIFFS.open("/config.json", "r");
      if (configFile) {
        Serial.println("opened config file");
        size_t size = configFile.size();
        std::unique_ptr<char[]> buf(new char[size]);

        configFile.readBytes(buf.get(), size);

        DynamicJsonDocument json(1024);
        auto deserializeError = deserializeJson(json, buf.get());
        if (!deserializeError) {
          Serial.println("\nparsed json");
          strcpy(email, json["email"]);
          strcpy(password, json["password"]);
          strcpy(host_ip, json["host_ip"]);
          strcpy(device_id, json["device_id"]);
        } else {
          Serial.println("failed to load json config");
        }
        configFile.close();
      }
    }
  } else {
    Serial.println("failed to mount FS");
  }

  WiFiManagerParameter custom_email("email", "Email", email, 40);
  WiFiManagerParameter custom_password("password", "Password", password, 40);
  WiFiManagerParameter custom_host_ip("host_ip", "Host IP", host_ip, 16);
  WiFiManagerParameter custom_device_id("device_id", "Device ID", device_id, 34);
  WiFiManager wifiManager;

  wifiManager.setSaveConfigCallback(saveConfigCallback); 
  wifiManager.setSTAStaticIPConfig(IPAddress(10, 0, 1, 99), IPAddress(10, 0, 1, 1), IPAddress(255, 255, 255, 0));

  wifiManager.addParameter(&custom_email);
  wifiManager.addParameter(&custom_password);
  wifiManager.addParameter(&custom_host_ip);
  wifiManager.addParameter(&custom_device_id);

  if (!wifiManager.autoConnect("AutoConnectAP", "password")) {
    Serial.println("failed to connect and hit timeout");
    delay(3000);
    ESP.restart();
    delay(5000);
  }
  
  Serial.println("connected...yeey :)");

  strcpy(email, custom_email.getValue());
  strcpy(password, custom_password.getValue());
  strcpy(host_ip, custom_host_ip.getValue());
  strcpy(device_id, custom_device_id.getValue());

  Serial.println("The values in the file are: ");
  Serial.println("\temail : " + String(email));
  Serial.println("\tpassword : " + String(password));
  Serial.println("\thost_ip : " + String(host_ip));
  Serial.println("\tdevice_id : " + String(device_id));


  if (shouldSaveConfig) {
    Serial.println("saving config");
    DynamicJsonDocument json(1024);
    json["email"] = email;
    json["password"] = password;
    json["host_ip"] = host_ip;
    json["device_id"] = device_id;

    File configFile = SPIFFS.open("/config.json", "w");
    if (!configFile) {
      Serial.println("failed to open config file for writing");
    }

    serializeJson(json, configFile);
    configFile.close();
  }
  Serial.println("local ip");
  Serial.println(WiFi.localIP());
  loginDevice();  
}

void loop() {
}
