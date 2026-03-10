Holray WordPress Plugin Documentation
=====================================

Support
-------

For any issues, questions, or support, contact:  
**Email:** support@holray.co.uk

* * *

Installation
------------

1.  Download the plugin ZIP file from the latest release:  
    [Holray WordPress Plugin Releases](https://github.com/Holray-Systems-Limited/WP-Plugin/releases)
    
2.  Upload the ZIP via your WordPress admin:
    
    *   Go to **Plugins → Add New → Upload Plugin**
        
    *   Select the downloaded ZIP file
        
    *   Click **Install Now**, then **Activate Plugin**
        

* * *

Updates
------------

Updates can be handled automatically through your WordPress dashboard. Please see below for a list of upgrade guides if you are updating. If you are making a jump eg. from 1.0.0 to 1.5.0, you will need to follow the upgrade guides in order from 1.0.0 to 1.5.0. 

- [Version 1.2.0](https://holray-systems-limited.github.io/WP-Plugin/upgrades/1.2.0)

* * *

Setup
-----

After activating the plugin:

1.  Navigate to the **Holray Settings** in the WordPress admin.
    
2.  Enter your Holray credentials:
    
    *   **Holray API URL** – Provided by Holray
        
    *   **Holray API Key** – Provided by Holray
        
3.  Click **Save Settings**.
    

### Sync Units

*   Once credentials are saved, click **Sync Units**.
    
*   This imports:
    
    *   Unit class, name, image, and capacity
        
    *   Layout, max pets, min nights
        
    *   All online-available locations from Holray
        

### Price Display Settings

You can customise how prices appear:

*   **Currency Symbol** – e.g., £, $, €
    
*   **Symbol Position** – Before or after the price
    
*   **Thousand Separator** – e.g., `,` or `.`
    
*   **Decimal Separator** – e.g., `.` or `,`
    
*   **Decimals** – Number of decimal places to display
    

### Search Settings

*   Set the **Search Results Page** (you will need to create this and it should just typically be a blank page)
    
*   Exclude specific locations from appearing online if required
    

* * *

Holray Unit Post Type
---------------------

*   **Holray Units** are a custom post type containing imported unit data.
    
*   You can edit these posts manually, but **name** and **Unit details** will be overwritten on the next sync with Holray.
    
*   **Locations** are used to categorise units for search filtering but do not have public-facing pages.
    

* * *

Shortcodes
----------

### 1. `[holray_search]`

Displays the search form anywhere on your site.

**Arguments:**

| Argument       | Default  | Description                                                               |
| -------------- | -------- | ------------------------------------------------------------------------- |
| `placement`    | topbar   | Layout style of the form (topbar or sidebar)                              |
| `results_page` | Page ID  | Page to display search results (default: settings page)                   |
| `partysize`    | 4        | Default number of guests                                                  |
| `nights`       | 7        | Default number of nights                                                  |
| `fromDate`     | tomorrow | Start date for search (format: `Y-m-d` or string e.g., `2 days from now`) |
| `flex`         | 3        | Flexibility in nights for search                                          |


**Example:**  
`[holray_search placement="sidebar" partysize="2" nights="5"]`

* * *

### 2. `[holray_calendar]`

Displays a fully customisable Holray calendar for a specific unit.

**Required Argument:**

*   `holray_calendar` – The unit class name from Holray (must match exactly)
    

**Example:**  
`[holray_calendar holray_calendar="LuxuryCottage2026"]`

* * *

Templates
---------

You can override plugin templates by copying them into your theme folder under `/holray/`.

**Available templates:**

| Template File               | Description                 |
| --------------------------- | --------------------------- |
| `archive-holray_unit.php`   | Archive of all Holray units |
| `single-holray_units.php`   | Single unit page            |
| `holray-search-results.php` | Search results page         |


**Important:**

*   If you override templates, check the **Upgrade Guide** for each version update to make necessary changes.
    
*   We aim to minimize breaking changes but cannot guarantee them.
    


Contact & Support
-----------------

If you encounter issues, bugs, or need assistance:  
**Email:** support@holray.co.uk

