#include <WiFi.h>
#include <HTTPClient.h>

const char* SSIDNAME = "SSID";
const char* PASSWORD = "PASSWORD";
const char* ENDPOINT = "http://localhost/ping.php?id=123";

const unsigned long INTERVAL_SECONDS = 180;

const unsigned long PING_INTERVAL_MS = INTERVAL_SECONDS * 1000;
unsigned long lastPing = 0;
const int BLUE_LED_PIN = 2;

void setup() {
    pinMode(BLUE_LED_PIN, OUTPUT);
    Serial.begin(115200);
    WiFi.begin(SSIDNAME, PASSWORD);
    WiFi.setAutoReconnect(true);
    WiFi.persistent(true);

    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
    }
    digitalWrite(BLUE_LED_PIN, HIGH);
    Serial.print("\nIP Address: ");
    Serial.println(WiFi.localIP());

    unsigned long current = millis();
    ping();
    lastPing = current;
}

void loop() {
    unsigned long current = millis();
    if (WiFi.status() == WL_CONNECTED) {
        digitalWrite(BLUE_LED_PIN, HIGH);
        if (current - lastPing >= PING_INTERVAL_MS) {
            ping();
            lastPing = current;
        }
    } else {
        digitalWrite(BLUE_LED_PIN, LOW);
    }
    delay(1000);
}

void ping() {
    HTTPClient http;
    http.begin(ENDPOINT);
    int httpResponseCode = http.GET();
    http.end();           
}
