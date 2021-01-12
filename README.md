# diveraVehiclePropertyInput


Schafft eine WEB-GUI zur Eingabe von Fahrzeugeigenschaften für [**DIVERA 24/7**](https://www.divera247.com).

Ruft die API ab, verarbeitet das JSON und spiegelt getätigte Änderungen zurück.

Es werden automatisch alle Fahrzeuge der Einheit geladen.

Bei der Auswahl eines Fahrzeugs werden automatisch alle² Eigenschaften geladen.


## Datenquelle
### DIVERA 24/7 RESTful Webservice
[https://api.divera247.com](https://api.divera247.com/?urls.primaryName=api%2Fv2%2Fpull#/Daten%2F%20Informationen/get_api_v2_pull_vehicle_status)

![DIVERA247 Logo](https://www.divera247.com/images/divera247.svg)

---

## Dinge, die **du** anpassen musst:
##### DIVERA 24/7 Einstellungen
Anlegen der Fahrzeug-Eigenschaften:

Siehe [Verwaltung > Setup > Fahrzeuge](https://app.divera247.com/localmanagement/index-settings-basic.html?tab=vehicle)

Hier werden die Fahrzeug-Eigenschaften nach folgendem Schema angelegt:

* **Title** kann frei gewählt werden und wird hier nicht weiter verwendet.
* **Key** nach Schema: **dash_TYP_NAME** - siehe nächster Absatz
* **Einheit** kann frei gewählt werden und wird hier nicht weiter verwendet.
* **Typ/Validator** - weglassen :grin: 

**Key**
**dash_TYP_NAME**

Die **Unterstriche** zwischen den Werten sind Pflicht.

***dash*** ist ein fester Prefix und ist Pflicht. ²Ohne werden die Werte nicht angezeigt, z.B. Für Werte die nur durch andere Schnittstellen administriert werden.

**TYP** gibt den Typ des Eingabefelds vor (s.u.).

**NAME** Beschreibt die Eigenschaft und dient als Beschriftung des Eingabefelds. 

:exclamation: Der Name darf **keine Sonderzeichen, Umlaute, Leerzeichen** enthalten; Bindestriche als Trennzeichen sind gestattet, siehe Beispiele! :exclamation:


unterstütze **Typen**:
* **number** - für numerische Werte
* **range** - für numerische Werte (mit Schieberegler zur Eingabe)
* **range(min-max)** - für numerische Werte in einem Bereich (mit Schieberegler zur Eingabe)
* **month** - für Monatsangaben
* **week** - für Wochenangaben
* **check** - für Ankreuzfelder
* ~~**text** - für textuelle Werte~~ **(noch) nicht unterstützt**
* ~~**date** - für Datumsangaben~~ **(noch) nicht unterstützt**
* ~~**radio** - für Auswahlfelder~~ **(noch) nicht unterstützt**

Beispiele:
* dash_number_Kilometerstand
* dash_range(0-100)_Tankinhalt-in-Prozent
* dash_month_naechste-Hauptuntersuchung
* dash_week_naechste-Seilwindenpruefung
* dash_check_Winterdiesel

Nach dem Anlegen der Werte müssen diese den gewünschten Fahrzeugen zugewiesen werden.


## Aufruf der WEB-GUI
Die drei Dateien müssen in ein Verzeichnes deines Webservers kopiert werden.
index.php
vehicle-property.php
vehicle-property.css

Der Aufruf erfolgt über die Adresse mit angehängtem Access-Key:

z.B.: http://meine-adresse.de/meinVerzeichnis/index.php?accesskey=mein-geheimer-accesskey1234567890
