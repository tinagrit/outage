#include <WiFi.h>
#include <HTTPClient.h>

const char* SSIDNAME = "SSID";
const char* PASSWORD = "PASSWORD";
const char* ENDPOINT = "http://localhost/ping.php?id=123";

const unsigned long PING_INTERVAL_MS = 3*60*1000;

unsigned long lastPing = 0;

void setup() {
    pinMode(2, OUTPUT);
    Serial.begin(115200);
    WiFi.begin(SSIDNAME, PASSWORD);
    WiFi.setAutoReconnect(true);
    WiFi.persistent(true);

    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
    }
    digitalWrite(2, HIGH);
    Serial.print("\nIP Address: ");
    Serial.println(WiFi.localIP());

    unsigned long current = millis();
    ping();
    lastPing = current;
}

void loop() {
    unsigned long current = millis();
    if (WiFi.status() == WL_CONNECTED) {
        digitalWrite(2, HIGH);
        if (current - lastPing >= PING_INTERVAL_MS) {
            ping();
            lastPing = current;
        }
    } else {
        digitalWrite(2, LOW);
    }
    delay(1000);
}

void ping() {
    HTTPClient http;
    http.begin(ENDPOINT);
    int httpResponseCode = http.GET();
    http.end();           
}
