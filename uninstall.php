<?php
/**
 * Uninstall script for Simplified SMTP Mail
 * Removes all plugin options from the WordPress database.
 *
 * @package   Simplified SMTP Mail
 * @author    BIT_on_Tech™
 * @link      https://bitveil.dk/plugins/simplified-smtp-mail
 * @copyright Copyright (c) 2025 BIT_on_Tech™
 * @license   GPLv2 or later
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Remove plugin options
delete_option('smtp_mail_from_name');
delete_option('smtp_mail_from_email');
delete_option('smtp_mail_smtp_host');
delete_option('smtp_mail_smtp_user');
delete_option('smtp_mail_smtp_pass');
delete_option('smtp_mail_smtp_port');
delete_option('smtp_mail_smtp_secure');
