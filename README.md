# 🔌 WordPress Plugin External DB Boilerplate

**Eine saubere Boilerplate-Struktur für WordPress-Plugins mit externer Datenbankanbindung.**  
Ideal für große oder sensible Datenmengen wie Produktdaten, CRM-Informationen, Formulare, Logs, etc.  
Die Core-Datenbank bleibt dabei sauber und performant – das Plugin nutzt eine eigene Datenbankstruktur (DB2).

---

## 🧠 Idee

In großen WordPress-Projekten überladen viele Plugins die Hauptdatenbank (DB1) mit unzähligen Einträgen in `wp_options`, `wp_postmeta`, etc.  
Dieses Boilerplate-Projekt bietet eine Möglichkeit, Plugin-Daten vollständig in einer separaten Datenbank (DB2) zu halten – ohne Kompatibilitätsprobleme mit dem WordPress-Core.

**Die Core-Datenbank weiß dennoch über die externe Datenbank Bescheid** (Registrierung in einer eigenen Tabelle), um Monitoring und Statusverwaltung zu ermöglichen.

---

## 🔧 Features

- 📦 Eigene Datenbankverbindung pro Plugin
- 🔁 Core-Integration: Registrierung und Status der externen DB in DB1
- ⚙️ Automatische Initialisierung (Tabellen, Migrationen, etc.)
- 📡 Health Monitoring / Logging optional integrierbar
- 🧪 Ideal für große Datenmengen und skalierbare Architektur

---

## 📐 Architektur

```text
┌────────────┐        PluginLoader        ┌────────────┐
│  WP Core   │ ─────────────────────────▶ │  Plugin DB │
│   DB1      │         (Boilerplate)      │    DB2     │
│ (leicht)   │ ◀── PluginMeta (Status) ───│ (schwer)   │
└────────────┘                            └────────────┘
````

---

## 🚀 Einsatzszenarien

* Shop-Plugins mit Millionen Produktvarianten
* Eigene CRM-Module
* Logging-Systeme
* Statistik-Plugins
* Eigenständige Anwendungen im WordPress-Frontend

---

## 📘 Beispiel: Erste Nutzung

```php
$db = PluginDBFactory::get_for('mein-plugin');
$results = $db->get_results("SELECT * FROM crm_kontakte");
```

Registrierung beim Aktivieren des Plugins:

```sql
INSERT INTO wp_plugin_db_registry (plugin_slug, db_host, db_name, status)
VALUES ('mein-plugin', '127.0.0.1', 'external_plugin_db', 'active');
```

---

## 🛡️ Vorteile

* Kein Core-Hacking nötig
* Volle Kontrolle über eigene DB-Struktur
* Zukunftssicher durch entkoppelte Architektur
* Erweiterbar mit Load-Balancing, Pooling, uvm.

---

## 🧱 TODO / Roadmap

* [x] Plugin-DB-Fabrik (Factory-Klasse für DB2)
* [x] Core-Registrierungstabelle (`wp_plugin_db_registry`)
* [ ] Health-Check UI im WP-Backend
* [ ] CLI-Unterstützung für Einrichtung / Migration
* [ ] Optional: Unterstützung für Multisite + getrennte DBs pro Site

---

## 🤖 Autor

**Idee & Konzept:** [VolkanSah \:D](https://github.com/volkansah)
📍 „Fauler Entwickler mit Hirn – der Core bleibt clean!“

---

## 🧩 Lizenz

MIT – Nutze es, verbessere es, teile es. Aber bitte: kein Core-Müll mehr. 😉

