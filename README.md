# Berkeley College of Engineering Theme

Github project link: https://github.com/sillybean/berkeley-coetheme


## Changelog

* 2.1: Rename some functions to avoid collisions; remove obsolete a11y replacement for genesis_do_nav()
* 2.0: Move logical functions from theme to plugin in order to use them across other Genesis-based themes. Genesis 3 compatibility.
* 1.6.3: https for engineering.berkeley.edu links
* 1.6.2: Remove student_type taxonomy from post_meta and excerpt content filters
* 1.6.1: Remove extra archive-people loop for defunct student_type taxonomy
* 1.6.0: Use wp_kses_post() instead of wp_kses() to use default allowed HTML in customizer background upload
* 1.5.9: Remove file_exists check for color scheme stylesheet
* 1.5.8: Swap mobile menu button toggle IDs
* 1.5.7: Mobile menu button corrections
* 1.5.6: Remove extraneous student_type loop header; abort sticky loop when there are no sticky posts
* 1.5.5: Student_type loops within people_type loops
* 1.5.4: People_type loops on people archive page
* 1.5.3: Genesis 2.4.x compatibility
* 1.5.2: Fix duplicate menu labels; fix font URL encoding; fix pub date format; fix header upload sanitizer
* 1.5.1: Enqueue maps files in functions.php
* 1.5: esc/sanitize/prefixes; maps->plugin; theme option defaults
* 1.4.6: Added button text and for accessibility
* 1.4.5: Updated help link for shortcodes on theme options screen
* 1.4.4: New Whitepaper sidebar
* 1.4.3: Fix menu toggle padding
* 1.4.2: Focus styles; hide search toggle when Header Right sidebar is empty
* 1.4.1: Custom background support for header
* 1.4: Filter all custom field & taxonomy list prefixes and labels. New whitepaper heading.
* 1.3.9: Replace genesis_do_nav to avoid unnecessary skip link header
* 1.3.8: Topics prefix in people excerpts; ARIA support for accordion headings; loop table THs; search result titles
* 1.3.7: Styles for Display Posts Shortcode plugin
* 1.3.6: Style consolidation; post author comment styles; comment numbering & whitespace cleanup
* 1.3.5: Select/option colors; Cleanup & inline documentation
* 1.3.4: Disable Genesis's default first-uploaded image fallback
* 1.3.3: Moving in metabox settings from plugin
* 1.3.2: Default site icon; default closed metaboxes
* 1.3.1: Apply sticky post image size overrides only if not in main query
* 1.3: Moved attachment.php functions to post_info and post_meta filters; post_meta and post_info theme options; theme options cleanup; sticky post image size overrides
* 1.2: Added Small image size (300x300) and attachment.php
* 1.1.9: Fixed duplicate portraits on People archives; color contrast fixes
* 1.1.8: Pull quote link colors; news sidebar & primary/secondary fallback for CPTs
* 1.1.7: Fixed icon font; moved admin-style.css to functions.php & enqueue on all admin screens
* 1.1.6: Added home.php blog template
* 1.1.5: Announcements text style corrections; slideshow hook correction
* 1.1.4: Gforms sidebar styles; corrected p padding when front page title is hidden
* 1.1.3: Corrected default header image path
* 1.1.2: bold widget links; page title size; img max-widths
* 1.1.1: after entry styles
* 1.1: home page title option; updated logo preview function