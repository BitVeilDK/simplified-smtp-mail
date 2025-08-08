<?php
/*
Plugin Name: Simplified SMTP Mail
Plugin URI: https://bitveil.dk/freecode/wp/plugins/simplified-smtp-mail/
Description: Configures WordPress's built-in PHPMailer to send emails via SMTP. Should therefore be compatible with all plugins and themes.
Version: 1.49
Author: BIT_on_Tech™
Author URI: https://bitveil.dk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: simplified-smtp-mail
Domain Path: /languages
*/

/*
  File: simplified-smtp-mail.php - Simplified SMTP Mail (WordPress plugin core)
  Beskrivelse: Konfigurerer WordPress’ indbyggede PHPMailer til SMTP, med UI til opsætning, testmail, kryptering (SSL/TLS/None), valgfri autentificering og små UX-forbedringer.
  Dato: 2025-08-08
  Copyright © 2025 BIT_on_Tech™ • Simplified Tech of Tomorrow • All rights reserved • bitveil.dk
*/

// ============================
// Admin Menu & Settings-link
// ============================
add_action('admin_menu', function () {
    add_menu_page(
        __('SMTP Mail', 'simplified-smtp-mail'),
        __('SMTP Mail', 'simplified-smtp-mail'),
        'manage_options',
        'smtp-mail-settings',
        'simplified_smtp_mail_settings_page',
        'dashicons-email'
    );
});

// Add Settings link on Plugins list
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $settings_link = '<a href="' . esc_url(admin_url('admin.php?page=smtp-mail-settings')) . '">' . esc_html__('Settings', 'simplified-smtp-mail') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
});

// ============================
// Settings Registration
// ============================
add_action('admin_init', function () {
    // Best practice: Register each option (stored in wp_options)
    register_setting('simplified_smtp_mail_group', 'smtp_mail_from_name');
    register_setting('simplified_smtp_mail_group', 'smtp_mail_from_email');
    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_host');

    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_auth');   // bool: require auth
    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_user');
    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_pass');

    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_secure'); // ssl|tls|none
    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_port');   // int
});

// ============================
// Settings Page (UI + Test)
// ============================
function simplified_smtp_mail_settings_page() {
    // Capability check (best practice)
    if ( ! current_user_can('manage_options') ) {
        wp_die(__('You do not have permission to access this page.', 'simplified-smtp-mail'));
    }

    // Test To (only from POST while on page; not stored)
    $test_to = isset($_POST['test_mail_to']) ? sanitize_email(wp_unslash($_POST['test_mail_to'])) : '';
    ?>
    <div class="wrap">
        <?php
        // Banner
        $banner_url = plugins_url('assets/banner-772x250.png', __FILE__);
        ?>
        <div style="display:flex;align-items:center;background:#101020;border-radius:14px;box-shadow:0 2px 12px #0003;margin-bottom:28px;min-height:84px;padding:0 20px;">
            <img src="<?php echo esc_attr($banner_url); ?>" alt="Simplified SMTP Mail" style="height:64px;width:auto;max-width:240px;border-radius:10px;margin-right:30px;">
            <div>
                <p style="color:#eee;margin:0;font-size:1rem;line-height:1;">
                    Configure secure and reliable SMTP email for WordPress.<br>
                    No bloat, no nonsense – just compatibility.<br>
                    <a href="https://bitveil.dk/freecode/wp/plugins/simplified-smtp-mail/"
                       style="color:#00dcff;text-decoration:underline;font-size:1rem;" target="_blank" rel="noopener">
                        Plugin homepage &amp; documentation
                    </a>
                </p>
            </div>
        </div>

        <?php
        // Handle Test Email submit with nonce & error capture for better debugging
        if ( isset($_POST['test_mail_submit']) ) {
            check_admin_referer('ssm_test_mail'); // CSRF protection

            $fail_msg = null;
            add_action('wp_mail_failed', function($wp_error) use (&$fail_msg){
                if ( is_wp_error($wp_error) ) {
                    $fail_msg = $wp_error->get_error_message();
                }
            }, 10, 1);

            $to = $test_to ?: get_option('smtp_mail_from_email');
            if ( empty($to) || !is_email($to) ) {
                // Fallback to site admin email if From Email missing/invalid
                $to = get_bloginfo('admin_email');
            }

            $subject = __('Test Email from Simplified SMTP Mail', 'simplified-smtp-mail');
            $message = __('This is a test email sent by the Simplified SMTP Mail plugin.', 'simplified-smtp-mail');
            $headers = ['Content-Type: text/html; charset=UTF-8'];
            $result  = wp_mail($to, $subject, $message, $headers);

            $notice_color = $result ? '#0fbb4f' : '#d72c34';
            $icon = $result
                ? '<span style="font-size:1.3em;margin-right:10px;">✅</span>'
                : '<span style="font-size:1.3em;margin-right:10px;">❌</span>';
            $text = $result
                ? __('Test email sent successfully!', 'simplified-smtp-mail')
                : __('Test email failed. Please check your settings.', 'simplified-smtp-mail');

            echo '<div id="ssm-notice" style="margin:16px 0 10px 0;padding:14px 20px;background:#181f16;border-radius:8px;display:flex;align-items:center;box-shadow:0 2px 12px #0002;transition:opacity 0.6s;">
                    <div style="width:6px;height:40px;background:' . esc_attr($notice_color) . ';border-radius:3px;margin-right:18px;"></div>
                    <div style="color:' . esc_attr($notice_color) . ';font-weight:600;font-size:1.15em;display:flex;align-items:center;">'
                        . $icon . esc_html($text) .
                    '</div>
                </div>';

            if ( ! $result && $fail_msg ) {
                echo '<pre style="background:#2a2a2a;color:#ffb4b4;padding:10px;border-radius:6px;white-space:pre-wrap;overflow:auto;margin-top:8px;">'
                   . esc_html($fail_msg)
                   . '</pre>';
            }

            echo '<script>
                  setTimeout(function(){
                    var notice = document.getElementById("ssm-notice");
                    if(notice){ notice.style.opacity = "0"; setTimeout(function(){ notice.remove(); }, 600); }
                  }, 5000);
                </script>';
        }
        ?>

        <h1><?php _e('Simplified SMTP Mail', 'simplified-smtp-mail'); ?></h1>

        <form method="post" action="options.php">
            <?php settings_fields('simplified_smtp_mail_group'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('From Name', 'simplified-smtp-mail'); ?></th>
                    <td><input type="text" name="smtp_mail_from_name" value="<?php echo esc_attr(get_option('smtp_mail_from_name')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('From Email', 'simplified-smtp-mail'); ?></th>
                    <td><input type="email" name="smtp_mail_from_email" value="<?php echo esc_attr(get_option('smtp_mail_from_email')); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><?php _e('SMTP Host', 'simplified-smtp-mail'); ?></th>
                    <td><input type="text" name="smtp_mail_smtp_host" value="<?php echo esc_attr(get_option('smtp_mail_smtp_host')); ?>" class="regular-text"></td>
                </tr>

                <!-- Require Authentication BEFORE user/pass -->
                <tr>
                    <th scope="row"><?php _e('Require Authentication', 'simplified-smtp-mail'); ?></th>
                    <td>
                        <label>
                            <input id="ssm_auth_checkbox" type="checkbox" name="smtp_mail_smtp_auth" value="1" <?php checked(get_option('smtp_mail_smtp_auth', '1'), '1'); ?>>
                            <?php _e('Use SMTP username/password authentication', 'simplified-smtp-mail'); ?>
                        </label>
                    </td>
                </tr>

                <!-- These two rows are toggled dynamically -->
                <tr id="ssm_row_user">
                    <th scope="row"><?php _e('SMTP User', 'simplified-smtp-mail'); ?></th>
                    <td><input id="ssm_user_input" type="text" name="smtp_mail_smtp_user" value="<?php echo esc_attr(get_option('smtp_mail_smtp_user')); ?>" class="regular-text"></td>
                </tr>
                <tr id="ssm_row_pass">
                    <th scope="row"><?php _e('SMTP Password', 'simplified-smtp-mail'); ?></th>
                    <td><input id="ssm_pass_input" type="password" name="smtp_mail_smtp_pass" value="<?php echo esc_attr(get_option('smtp_mail_smtp_pass')); ?>" class="regular-text" autocomplete="new-password"></td>
                </tr>

                <!-- Encryption FIRST (then port) -->
                <tr>
                    <th scope="row"><?php _e('Encryption', 'simplified-smtp-mail'); ?></th>
                    <td>
                        <select name="smtp_mail_smtp_secure" id="ssm_encryption">
                            <option value="ssl"  <?php selected(get_option('smtp_mail_smtp_secure'), 'ssl');  ?>><?php _e('SSL', 'simplified-smtp-mail'); ?></option>
                            <option value="tls"  <?php selected(get_option('smtp_mail_smtp_secure'), 'tls');  ?>><?php _e('TLS', 'simplified-smtp-mail'); ?></option>
                            <option value="none" <?php selected(get_option('smtp_mail_smtp_secure'), 'none'); ?>><?php _e('None', 'simplified-smtp-mail'); ?></option>
                        </select>
                        <p class="description"><?php _e('Use “None” only on trusted networks. Opportunistic TLS will still be used when available.', 'simplified-smtp-mail'); ?></p>
                    </td>
                </tr>

                <!-- Port AFTER encryption -->
                <tr>
                    <th scope="row"><?php _e('SMTP Port', 'simplified-smtp-mail'); ?></th>
                    <td><input id="ssm_port_input" type="number" name="smtp_mail_smtp_port" value="<?php echo esc_attr(get_option('smtp_mail_smtp_port', 465)); ?>" class="small-text"></td>
                </tr>
            </table>

            <?php submit_button(__('Save Changes', 'simplified-smtp-mail')); ?>
        </form>

        <form method="post" style="margin-top:18px;">
            <?php wp_nonce_field('ssm_test_mail'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row"><?php _e('Test To Email (optional)', 'simplified-smtp-mail'); ?></th>
                    <td>
                        <input type="email" name="test_mail_to" value="<?php echo esc_attr($test_to); ?>" class="regular-text" placeholder="<?php echo esc_attr(get_option('smtp_mail_from_email')); ?>">
                        <br><span style="color:#aaa;font-size:0.96em;"><?php _e('If empty, the test will be sent to the "From Email" address above.', 'simplified-smtp-mail'); ?></span>
                    </td>
                </tr>
            </table>
            <p><input type="submit" name="test_mail_submit" class="button" value="<?php _e('Send Test Email', 'simplified-smtp-mail'); ?>"></p>
        </form>

        <script>
        (function(){
            // Toggle SMTP User/Pass based on "Require Authentication"
            var authCb = document.getElementById('ssm_auth_checkbox');
            var rowUser = document.getElementById('ssm_row_user');
            var rowPass = document.getElementById('ssm_row_pass');
            var userInp = document.getElementById('ssm_user_input');
            var passInp = document.getElementById('ssm_pass_input');

            function toggleAuthRows(){
                var on = !!authCb.checked;
                rowUser.style.display = on ? '' : 'none';
                rowPass.style.display = on ? '' : 'none';
                // disable inputs when hidden so they don't post inadvertently
                userInp.disabled = !on;
                passInp.disabled = !on;
            }
            if (authCb && rowUser && rowPass && userInp && passInp){
                toggleAuthRows();
                authCb.addEventListener('change', toggleAuthRows, false);
            }

            // Suggest port based on encryption (user can override)
            var enc = document.getElementById('ssm_encryption');
            var port = document.getElementById('ssm_port_input');
            function suggestPort(){
                if (!enc || !port) return;
                var v = enc.value;
                if (v === 'ssl'  && (!port.value || port.value === '587' || port.value === '25')) port.value = 465;
                if (v === 'tls'  && (!port.value || port.value === '465' || port.value === '25')) port.value = 587;
                if (v === 'none' && (!port.value || port.value === '465' || port.value === '587')) port.value = 25;
            }
            if (enc && port){
                enc.addEventListener('change', suggestPort, false);
            }
        })();
        </script>
    </div>
    <?php
}

// ============================
// Configure PHPMailer (hook)
// ============================
add_action('phpmailer_init', function ($phpmailer) {
    // Fetch options (sanitize/cast where appropriate)
    $host   = get_option('smtp_mail_smtp_host');
    $auth   = get_option('smtp_mail_smtp_auth', '1') ? true : false;
    $user   = get_option('smtp_mail_smtp_user');
    $pass   = get_option('smtp_mail_smtp_pass');
    $enc    = get_option('smtp_mail_smtp_secure');
    $port   = intval(get_option('smtp_mail_smtp_port'));

    $from_email = get_option('smtp_mail_from_email');
    if ( empty($from_email) || !is_email($from_email) ) {
        $from_email = get_bloginfo('admin_email'); // fallback for safety
    }
    $from_name  = get_option('smtp_mail_from_name');

    // Apply settings
    $phpmailer->isSMTP();
    $phpmailer->Host       = $host;
    $phpmailer->SMTPAuth   = $auth;
    $phpmailer->Username   = $user;
    $phpmailer->Password   = $pass;

    if ($enc === 'none') {
        // No forced encryption; keep opportunistic TLS on
        $phpmailer->SMTPSecure  = '';
        $phpmailer->SMTPAutoTLS = true; // explicit (default is true)
    } else {
        $phpmailer->SMTPSecure  = $enc;  // 'ssl' or 'tls'
        $phpmailer->SMTPAutoTLS = true;
    }

    if ($port > 0) {
        $phpmailer->Port = $port;
    }

    // Set From (PHPMailer/WordPress will validate, but we already fallback above)
    $phpmailer->setFrom($from_email, $from_name);
});

// === Use WordPress' PHPMailer and configure it ===
add_action('phpmailer_init', function ($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host       = get_option('smtp_mail_smtp_host');
    $phpmailer->SMTPAuth   = true;
    $phpmailer->Username   = get_option('smtp_mail_smtp_user');
    $phpmailer->Password   = get_option('smtp_mail_smtp_pass');
    $phpmailer->SMTPSecure = get_option('smtp_mail_smtp_secure');
    $phpmailer->Port       = get_option('smtp_mail_smtp_port');
    $phpmailer->setFrom(get_option('smtp_mail_from_email'), get_option('smtp_mail_from_name'));
});
