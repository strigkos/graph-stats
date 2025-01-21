=== Divi-Modules – Table Maker ===
Tags: Divi-Modules, Table Maker, Divi, Divi Theme, Extra Theme, Divi Builder
Donate link: https://divi-modules.com/
Requires at least: 5.0
Tested up to: 6.6.2
Requires PHP: 7.0
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Brings beautiful responsive tables to the Divi Builder.

== Description ==

= About: =

[Divi-Modules – Table Maker](https://divi-modules.com/products/table-maker "Divi-Modules – Table Maker") brings beautiful responsive tables to the Divi Builder. With features including: multiple headers and footers; column and row spanning; icon, button and image cells, accordion view for mobile; scrolling with sticky headers; and much more.

Style every part of the table from content to columns, headers to footers, right down to individual cells without the need for third-party plugins and shortcodes.

For more information, please see the [Divi-Modules – Table Maker](https://divi-modules.com/products/table-maker "Divi-Modules – Table Maker") product page and download the [Table Maker – Documentation](https://divi-modules.com/docs/table-maker/ "Divi-Modules – Table Maker – Documentation").

= Requirements: =

* **WordPress:** Version 5.0 (or higher)
* **PHP:** Version 7.0 (or higher)
* **MySQL:** Version 5.0 (or higher)
* **dom:** Enabled
* **libxml:** Enabled
* **mbstring:** Enabled
* **CSS Grid:** Enabled

= Important: =

[Divi-Modules](https://divi-modules.com "Divi-Modules") plugins require the `Divi Theme`, `Extra Theme`, or `Divi Builder Plugin` to be installed and activated. [Divi-Modules](https://divi-modules.com "Divi-Modules") plugins are created and tested using the latest Divi Theme and Builder versions. Backwards compatibility is not guaranteed, however, plugins should function normally with at least the following versions:

* **Divi Theme:** Version 4.10 (or higher)
* **Extra Theme:** Version 4.10 (or higher)
* **Divi Builder:** Version 4.10 (or higher)

This module may not function correctly under the `Classic Editor`. It's highly recommended that choose `Switch To The New Divi Builder`.

== Installation ==

= To Install: =
* Use the WordPress built-in `plugin installer`, or
* Extract the zip file and upload it to the `wp-content/plugins/` directory of your WordPress installation.
* Activate the plugin in the WordPress admin `Plugins` menu.
* Navigate to the `Divi-Modules > My Modules` submenu.
* Enter your `License Key` in the product widget and activate.

== Changelog ==

= 3.1.2 =
* 18 September, 2024.
* FIXED: Dynamic property deprecation warning for module icon in PHP v8.2.
* FIXED: Divi-Modules customers should no longer need to re-check license status after plugin updates.
* FIXED: Divi Marketplace customers should no longer need to re-check subscription status after plugin updates.
* FIXED: Divi Marketplace API reporting incorrect subscription status after purchase or renewal.
* FIXED: Divi Builder type check for compatibility with Divi 5.
* UPDATED: Removed dependency on mb_convert_encoding which will be deprecated in PHP v8.2.
* UPDATED: Subscription purchase and renewal notification for greater clarity.
* UPDATED: Increased admin text contrast for improved accessibility.
* UPDATED: Other miscellaneous admin improvements.

= 3.1.1 =
* 18 May, 2024.
* UPDATED: All scripts now loaded into footer.
* UPDATED: Added subscription renewal notification to admin.
* UPDATED: General admin improvements.

= 3.1.0 =
* 8 April, 2024.
* FIXED: Issue with inline onclick attributes.
* UPDATED: Plugin admin in preparation for Divi 5.
* UPDATED: Improved escaping and code sanitization.

= 3.0.3 =
* 24 June, 2023.
* FIXED: Some PHP 8.1 and 8.2 deprecation warnings.
* FIXED: Table Stripes and Hover 'color-alpha' warning.
* FIXED: Potential javascript conflict with some plugins.
* FIXED: Spelling of Description in Settings > Design > Table Description field labels.
* UPDATED: Added a filter to provide potential support for the WP & Divi Icons extension.

= 3.0.2 =
* 27 September, 2022.
* FIXED: Critical error when row data contained only a space or other invisible character.
* FIXED: Critical error when used with the All In One SEO plugin.

= 3.0.1 =
* 20 September, 2022.
* UPDATED: Plugin now clears static resources after updating.
* FIXED: Front-end issues with nested HTML elements in cell content.

= 3.0.0 =
* 13 September, 2022.
* IMPORTANT CHANGE: Previously, in Responsive > Display As > Blocks mode, module Background, Border, Spacing and Box-Shadow settings were applied to individual blocks at Tablet and Phone view, this is no longer the case. These styles are now applied to the main module element at all times. This change has been made to accomodate the new Title and Description settings.
* IMPORTANT CHANGE: Previously, in tables with multiple headers, accordion toggles showed only the first header when closed. Additional headers were displayed only when the accordion was open. All headers are now shown whether accordions are closed or opened. This change has been made to accomodate the new Table Corners > Top/Left: Mode setting.
* IMPORTANT CHANGE: Accordion toggles are now all closed by default at responsive size. Users can now choose to have an open toggle if they wish by using the new Toggle State and Toggle Opened settings.
* IMPORTANT CHANGE: Previously, Table Stripes Hue, Saturation, and Brightness effects were applied to the whole table cell, including cell content. This was extremely limiting. Table Stripes settings have now been completely recoded and stripe effects are applied only to the cell background.
* IMPORTANT CHANGE: Column and row headers now have their heading-level (ie. H1-H6 tags) set to Off by default. Users are recommended to leave this setting Off because enabling it can prevent screen readers from reading the table.
* NEW: Tables can now have a title and description positioned above or below the table.
* NEW: Table top/left corner cell visibility can now be toggled on and off.
* NEW: Added Table Corner settings for controlling styling of top/left, top/right, bottom/left, and bottom/right cells.
* NEW: Table Responsive settings can now be enabled at desktop size.
* NEW: Users can now select which accordion toggle should be opened at responsive sizes.
* NEW: Users can now select to have all accordion toggles closed at responsive sizes.
* NEW: Accordion toggles can now be navigated, opened, and closed with keyboard commands for improved accessibility.
* NEW: All new Table Stripes settings including: Stripes: Mode, Tint, Blend, and Color.
* NEW: All new Table Hover settings including: Hover: Mode, Tint, Blend, and Color.
* NEW: User can now disable H1-H6 tags on column header and row header text for improved accessibility.
* NEW: Added a custom Image: Size setting for table images.
* NEW: Added a placeholder image for missing or unspecified table images.
* NEW: Added a Custom CSS field for Table Blocks for styling table blocks and accordions.
* NEW: Added table cell attributes for easier styling of individual cell backgrounds, borders and text color.
* NEW: Added table icon attributes for easier styling of individual icon size and color.
* NEW: Icons can now be made into links by adding an href attribute to the <icon> tag.
* NEW: Images can now be made into links by adding an href attribute to the <image> tag.
* UPDATED: Main module element now includes a class showing the plugin version number.
* UPDATED: Added various ARIA attributes for improved accessibility.
* UPDATED: Title and alt attributes added to table images for improved accessibility.
* UPDATED: Title attribute now added to clickable cells for improved accessibility.
* UPDATED: Added responsive and hover settings wherever possible.
* UPDATED: Better labelling for repetitive settings.
* UPDATED: Some Custom CSS fields and table and column settings toggles have been reordered.
* UPDATED: Removed unnecessary visibility and transitions toggles from table column settings.
* UPDATED: Table Frame > Lines setting now includes all standard border styles.
* UPDATED: Image: Alignment settings have been renamed Image: Position X and Image: Position Y.
* UPDATED: Image: Alignment settings are now a continuous range (ie. no longer limited to only Left | Center | Right).
* UPDATED: Table blocks classes now include an element index class.
* UPDATED: Added support for plugin text-domains.
* UPDATED: Global module content now bypasses wpautop.
* FIXED: Critical error 'call to undefined function et_builder_should_wrap_styles()' in Divi 4.10.0 to 4.10.6.
* FIXED: Added an 'Update URI' header to avoid update conflicts with plugins sold on the Divi Marketplace.
* FIXED: Accordion icon positioning was misaligned when aligned right.
* FIXED: The blocked/listed elegant icon was not displaying.
* FIXED: Multiple Visual Builder display issues in Divi Builder (Builder Plugin version).
* FIXED: Multiple front-end display issues in Divi Builder (Builder Plugin version).
* FIXED: No more PHP notices when table images are missing from the media library.

= 2.0.4 =
* 18 November, 2021.
* FIXED: Added default table icon size (1em).

= 2.0.3 =
* 15 November, 2021.
* NEW: Added support for Divi extended icons to the Table and Column Icons toggles.
* NEW: Added support for Divi extended icons to the Table and Column Buttons toggles.
* NEW: Added support for Divi extended icons to the table Accordion Toggle toggle.
* NOTE: FontAwesome icons are not supported as icon names in the `<icon>ICON_NAME</icon>` tag.
* UPDATED: Product licensing and updating code. (Divi-Modules products only).
* UPDATED: Plugin now checks for correct PHP version, MBSTRING, DOM and LIBXML extensions on activation.
* UPDATED: Minimum requirements now recommend WordPress 5.0, PHP 7.0 and Divi 4.10.

= 2.0.2 =
* 1 September, 2021.
* UPDATED: Product licensing and updating code. (Divi-Modules products only)
* FIXED: Error due to deprecated code in PHP 8.
* FIXED: Button and cell links which included query string parameters were incorrectly escaped.

= 2.0.1 =
* 17 March, 2020.
* UPDATED: Code quality improvements.
* UPDATED: Changed column width increments. (.1fr)
* UPDATED: Removed Libxml 2.7.7 dependency.
* UPDATED: Module no longer bypasses wpautop.

= 2.0.0 =
* 21 February, 2020.
* NEW: Added Visual Builder support.
* NEW: Added support for shortcodes in rows.
* NEW: Added support for custom html escape characters.
* NEW: Added combined Block/Accordion Gap setting.
* NEW: Added Inset/Outset line styles for Frame.
* UPDATED: Now parsing rows as HTML not XML.
* UPDATED: Changed min/max fields from text to range fields.
* UPDATED: Plugin now loads as Divi Extension.
* UPDATED: No longer minifying module code.

= 1.0.0 =
* 9 December, 2019.
* NEW: Official public release.
