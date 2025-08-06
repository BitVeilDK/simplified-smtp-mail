<?php
/*
Plugin Name: Simplified SMTP Mail
Plugin URI: https://bitveil.dk/plugins/simplified-smtp-mail
Description: Configures WordPress's built-in PHPMailer to send emails via SMTP. Should therefore be compatible with all plugins and themes.
Version: 1.1
Author: BIT_on_Tech™
Author URI: https://bitveil.dk
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: simplified-smtp-mail
Domain Path: /languages
*/

// === Admin Menu ===
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

// Add Settings link in plugin list
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $settings_link = '<a href="' . admin_url('admin.php?page=smtp-mail-settings') . '">' . __('Settings', 'simplified-smtp-mail') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
});

// === Settings Registration ===
add_action('admin_init', function () {
    register_setting('simplified_smtp_mail_group', 'smtp_mail_from_name');
    register_setting('simplified_smtp_mail_group', 'smtp_mail_from_email');
    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_host');
    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_user');
    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_pass');
    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_port');
    register_setting('simplified_smtp_mail_group', 'smtp_mail_smtp_secure');
});

// === Settings Page ===
function simplified_smtp_mail_settings_page() {
    ?>
    <div class="wrap">
		<?php
		$banner_url = plugins_url('assets/banner-772x250.png', __FILE__);
		?>
		<div style="display:flex;align-items:center;background:#101020;border-radius:14px;box-shadow:0 2px 12px #0003;margin-bottom:28px;min-height:84px;padding:0 20px;">
		  <img src="<?php echo esc_attr($banner_url); ?>" alt="Simplified SMTP Mail" style="height:64px;width:auto;max-width:240px;border-radius:10px;margin-right:30px;">

		  <div>
			<p style="color:#eee;margin:0;font-size:1rem;line-height:1;">
			  Configure secure and reliable SMTP email for WordPress.<br>
			  No bloat, no nonsense – just compatibility.<br>
			  <a href="https://bitveil.dk/freecode/wp/plugins/simplified-smtp-mail/"
				 style="color:#00dcff;text-decoration:underline;font-size:1rem;" target="_blank">
				Plugin homepage &amp; documentation
			  </a>
			</p>
		  </div>
		</div>

		<?php
		if (isset($_POST['test_mail_submit'])) {
			$to = get_option('smtp_mail_from_email');
			$subject = __('Test Email from Simplified SMTP Mail', 'simplified-smtp-mail');
			$message = __('This is a test email sent by the Simplified SMTP Mail plugin.', 'simplified-smtp-mail');
			$headers = ['Content-Type: text/html; charset=UTF-8'];
			$result = wp_mail($to, $subject, $message, $headers);

			$notice_color = $result ? '#0fbb4f' : '#d72c34'; // grøn/rød
			$icon = $result
				? '<span style="font-size:1.3em;margin-right:10px;">✅</span>'
				: '<span style="font-size:1.3em;margin-right:10px;">❌</span>';
			$text = $result
				? __('Test email sent successfully!', 'simplified-smtp-mail')
				: __('Test email failed. Please check your settings.', 'simplified-smtp-mail');
			echo '<div id="ssm-notice" style="margin:16px 0 24px 0;padding:14px 20px;background:#181f16;border-radius:8px;display:flex;align-items:center;box-shadow:0 2px 12px #0002;transition:opacity 0.6s;">
					<div style="width:6px;height:40px;background:' . $notice_color . ';border-radius:3px;margin-right:18px;"></div>
					<div style="color:' . $notice_color . ';font-weight:600;font-size:1.15em;display:flex;align-items:center;">'
						. $icon . $text .
					'</div>
				</div>
				<script>
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
                <tr><th scope="row"><?php _e('From Name', 'simplified-smtp-mail'); ?></th>
                    <td><input type="text" name="smtp_mail_from_name" value="<?php echo esc_attr(get_option('smtp_mail_from_name')); ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><?php _e('From Email', 'simplified-smtp-mail'); ?></th>
                    <td><input type="email" name="smtp_mail_from_email" value="<?php echo esc_attr(get_option('smtp_mail_from_email')); ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><?php _e('SMTP Host', 'simplified-smtp-mail'); ?></th>
                    <td><input type="text" name="smtp_mail_smtp_host" value="<?php echo esc_attr(get_option('smtp_mail_smtp_host')); ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><?php _e('SMTP User', 'simplified-smtp-mail'); ?></th>
                    <td><input type="text" name="smtp_mail_smtp_user" value="<?php echo esc_attr(get_option('smtp_mail_smtp_user')); ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><?php _e('SMTP Password', 'simplified-smtp-mail'); ?></th>
                    <td><input type="password" name="smtp_mail_smtp_pass" value="<?php echo esc_attr(get_option('smtp_mail_smtp_pass')); ?>" class="regular-text"></td></tr>
                <tr><th scope="row"><?php _e('SMTP Port', 'simplified-smtp-mail'); ?></th>
                    <td><input type="number" name="smtp_mail_smtp_port" value="<?php echo esc_attr(get_option('smtp_mail_smtp_port', 465)); ?>" class="small-text"></td></tr>
                <tr><th scope="row"><?php _e('Encryption', 'simplified-smtp-mail'); ?></th>
                    <td>
                        <select name="smtp_mail_smtp_secure">
                            <option value="ssl" <?php selected(get_option('smtp_mail_smtp_secure'), 'ssl'); ?>>SSL</option>
                            <option value="tls" <?php selected(get_option('smtp_mail_smtp_secure'), 'tls'); ?>>TLS</option>
                        </select>
                    </td></tr>
            </table>
            <?php submit_button(__('Save Changes', 'simplified-smtp-mail')); ?>
        </form>
        <form method="post">
            <p><input type="submit" name="test_mail_submit" class="button" value="<?php _e('Send Test Email', 'simplified-smtp-mail'); ?>"></p>
        </form>
    </div>
    <?php
}

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
