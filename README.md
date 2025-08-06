## Simplified SMTP Mail

A lightweight and compatible WordPress plugin that configures WordPress's built-in PHPMailer to use SMTP for sending emails. Compatible with all plugins and themes using `wp_mail()`.

---

### ✨ Features
- 🧰 Uses WordPress’s internal PHPMailer – no external libraries required
- ✅ Fully compatible with all plugins/themes that rely on `wp_mail()`
- 🖥️ Simple admin interface with test email support
- 🔒 SMTP authentication and encryption (SSL/TLS)
- ⚡ Lightweight and no bloat

---

### 📧 Why use SMTP instead of your host’s mail function?
- 📬 Higher deliverability — avoid spam folders
- 🛡️ Full support for SPF, DKIM, and DMARC
- 👤 Authenticate with a real SMTP account (e.g., your domain email)
- 🚫 No dependency on your host's PHP `mail()` function
- 🤝 Works with WooCommerce, contact forms, password resets and more

---

### 🔧 Requirements
- 🧱 WordPress 5.0 or newer
- 📤 A valid SMTP account (e.g., Mailgun, Gmail, or your hosting provider)

---

### 🚀 Installation
1. 🧩 Install via **Plugins → Add New** in your WordPress admin and search for `Simplified SMTP Mail` (once listed on WordPress.org)
2. 📥 Or download it directly from [GitHub](https://github.com/BitVeilDK/simplified-smtp-mail) and upload it to your `/wp-content/plugins/` directory
3. ✅ Activate via WordPress admin plugins menu page
4. ⚙️ Go to **SMTP Mail** in your admin menu and configure your settings
5. 🔁 **Note**: It's important to click **Save Changes** before using the **Send Test Email** button. This ensures that the system is configured with the new correct parameters before testing whether everything works correctly. The test doesn't just verify the new settings but evaluates the entire system, including the updated configuration, to ensure maximum compatibility. :-)

---

### 💬 Support & Feedback
If you experience issues or want to suggest features, please reach out via [GitHub](https://github.com/BitVeilDK/simplified-smtp-mail).

**⚠️ Only in urgent or security-related situations**, you may contact me via the [contact form at the bottom of bitveil.dk](https://bitveil.dk#kontakt).

---

### ❓ Frequently Asked Questions
**Does this plugin override wp_mail?**  
🛠️ No. It configures PHPMailer used by wp_mail() — it doesn’t override the function itself.

**Does it work with WooCommerce?**  
🛒 Yes. It works with anything that uses wp_mail(), including WooCommerce, form plugins, notifications, password resets, etc.

**Is it secure?**  
🔐 Yes. Passwords are stored using the WordPress options API and can be encrypted depending on host-level security. Use SSL/TLS.

---

### 📝 Changelog
#### 1.4
- 🔧 Improved default port handling (cast to integer)
- 🤝 Improved mail config compatibility with WordPress options

#### 1.3
- ➕ Added support for Settings link on plugins list page
- 🧽 Updated admin menu icon and improved positioning

#### 1.2
- 📤 Added Test Email button to admin interface

#### 1.1
- 🎉 Initial public release

---

### 📦 GitHub Badges & Resources
[![GitHub release](https://img.shields.io/github/v/release/BitVeilDK/simplified-smtp-mail)](https://github.com/BitVeilDK/simplified-smtp-mail/releases)
[![License](https://img.shields.io/github/license/BitVeilDK/simplified-smtp-mail)](https://github.com/BitVeilDK/simplified-smtp-mail/blob/main/LICENSE)
[![Code size](https://img.shields.io/github/languages/code-size/BitVeilDK/simplified-smtp-mail)](https://github.com/BitVeilDK/simplified-smtp-mail)

---

### 📜 License
GPLv2 or later — see [LICENSE](https://www.gnu.org/licenses/gpl-2.0.html)

---

Copyright © <?php echo date('Y'); ?> BIT_on_Tech™ • Simplified Tech of Tomorrow • [bitveil.dk](https://bitveil.dk)
