=== Holray Units ===
Contributors: you
Requires at least: 6.0
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.3.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: holray-units
Domain Path: /languages

Catalogue plugin that registers a "holray_unit" CPT, a "unit_location" taxonomy, ships archive & single templates (theme-overridable), a Sync & Settings admin page, a [holray_catalog] shortcode, and availability [holray_search]/[holray_results] shortcodes.

== Installation ==
1. Upload the ZIP via Plugins → Add New → Upload Plugin.
2. Activate "Holray Units".
3. In Units → Sync & Settings, set your Holray API Base (api.php URL) and API Key. Optionally set Checkout URL.
4. Use [holray_search] and [holray_results] as described below.

== Shortcodes ==
- [holray_catalog location="slug" per_page="12"]
- [holray_search layout="topbar|sidebar" results_page="/availability-results"]
- [holray_results]

== Settings ==
- Only show “Book now” when online (avonline): Hides CTA for offline results when enabled.
- Price formatting: currency symbol, position, thousand/decimal separators, decimals.
- Legacy Units JSON URL: if set, sync imports from it; otherwise sync uses Holray API “units”.

== Changelog ==
= 1.3.1 =
- Fix: full plugin files included (admin menu, settings, sync). No placeholders.
- Based on 1.3.0 API-based sync; keeps legacy JSON fallback.
