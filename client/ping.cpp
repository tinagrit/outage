#include <WiFi.h>
#include <HTTPClient.h>

const char* SSID = "SSID";
const char* PASSWORD = "PASSWORD";
const char* ENDPOINT = "http://localhost/ping.php?id=123";

const unsigned long PING_INTERVAL_MS = 3*60*1000;

unsigned long lastPing = 0;

void setup() {
    Serial.begin(115200);
    WiFi.begin(SSID, PASSWORD);
    WiFi.setAutoReconnect(true);
    WiFi.persistent(true);
}

void loop() {
    unsigned long current = millis();
    if (current - lastPing >= PING_INTERVAL_MS) {
        if (WiFi.status() == WL_CONNECTED) {
            HTTPClient http;
            http.begin(ENDPOINT);
            int httpResponseCode = http.GET();
            http.end();
            lastPing = current;
        }
    }
    delay(PING_INTERVAL_MS / 3);
}