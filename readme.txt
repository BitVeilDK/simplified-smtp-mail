=== Simplified SMTP Mail ===
Contributors: BitVeilDK
Tags: smtp, email, wp_mail, phpmailer, deliverability, mail, wordpress
Requires at least: 5.0
Tested up to: 6.8.2
Requires PHP: 7.2
Stable tag: 1.5.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Configures WordPress’s built-in PHPMailer to send emails via SMTP. Should therefore be compatible with all plugins and themes.


== Description ==

A lightweight and reliable way to send WordPress emails via your own SMTP server. Works seamlessly with WooCommerce, contact forms, and all plugins relying on `wp_mail()`.

== Features ==

* Uses WordPress's internal PHPMailer
* Supports SSL and TLS
* Compatible with all plugins and themes
* Test email functionality
* Simple and clean admin interface

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory.
2. Activate through the 'Plugins' menu.
3. Navigate to **SMTP Mail** in the admin menu.
4. Enter your SMTP credentials.
5. IMPORTANT: Click 'Save Changes' before using the 'Send Test Email' button. This ensures the system uses your updated SMTP settings during the test.

== Support ==

Open issues or suggest features via GitHub: https://github.com/BitVeilDK/simplified-smtp-mail  
Urgent/security cases only: https://bitveil.dk#kontakt
