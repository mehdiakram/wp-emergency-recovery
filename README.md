# Royal WordPress Emergency Recovery

A lightweight, temporary WordPress account recovery utility by Royal Technologies.

## Overview

**Royal WordPress Emergency Recovery** is a temporary PHP utility designed to help authorized WordPress administrators recover access to a WordPress website when normal login or password-reset methods are unavailable.

The script is intended for situations where you have legitimate filesystem access to the WordPress installation through FTP, SFTP, cPanel File Manager, CyberPanel File Manager, or another authorized server-management method.

### Recovery logic

The recovery process follows this order:

1. Check the configured email address.
2. If a WordPress user exists with that email, update that user's password and assign the Administrator role.
3. If no user is found by email, check the configured username.
4. If a user exists with that username, update that user's password and assign the Administrator role.
5. If neither email nor username matches an existing user, create a new Administrator account.
6. The recovery URL is protected by a secret recovery token.
7. After recovery, delete `royalrecover.php` from the server.

Email lookup always has priority over username lookup.

## Features

- Email-first account recovery
- Username fallback
- Automatic Administrator role assignment
- Creates a new Administrator if no matching account exists
- Secret-token protection
- Works without installing a WordPress plugin
- No phpMyAdmin or direct database editing required
- Lightweight single-file utility
- Can be uploaded through FTP/SFTP or hosting file managers
- Attempts to remove itself after successful execution
- Manual deletion is supported if automatic deletion is unavailable

## Security Warning

This script can change WordPress passwords, assign Administrator privileges, and create Administrator accounts.

**Use it only on WordPress websites that you own or are explicitly authorized to administer.**

Do not publish the recovery URL or recovery token.

After completing recovery, immediately remove `royalrecover.php` from the WordPress installation.

Do not leave this script permanently accessible on a production website.

## Requirements

- WordPress installation
- PHP supported by the installed WordPress version
- Filesystem access to the WordPress installation
- `wp-load.php` accessible from the script location
- A secure recovery token

No additional WordPress plugin is required.

## Project Structure

The project is intentionally simple:

`royalrecover.php`

`README.md`

The main recovery utility is contained in `royalrecover.php`.

## Download

### Download from GitHub

Open the repository:

https://github.com/mehdiakram/wp-emergency-recovery

Then:

1. Click **Code**.
2. Click **Download ZIP**.
3. Extract the ZIP file on your computer.
4. Open the project folder.
5. Use the `royalrecover.php` file.

### Download only `royalrecover.php`

You can also open the PHP file directly on GitHub, click **Raw**, and save the page as:

`royalrecover.php`

Make sure the file is saved with the `.php` extension and not `.txt`.

## Configuration

Open:

`royalrecover.php`

Find the configuration section.

Example:

    $admin_email = 'admin@example.com';
    $admin_username = 'recovery_admin';
    $admin_password = 'ChangeThisToAStrongPassword';
    define('RECOVERY_TOKEN', 'CHANGE_THIS_TO_A_LONG_RANDOM_SECRET');

Replace the example values with your own temporary recovery information.

### Recovery Token

The recovery token is a secret value used to protect access to the recovery script.

Example:

    define('RECOVERY_TOKEN', 'R7x!Kp92@Lm4#Qz81$Vn6');

Use your own long, random secret instead of copying the example.

Do not publish the token in GitHub, screenshots, documentation, or public messages.

### Administrator Email

Example:

    $admin_email = 'admin@example.com';

This email is checked first.

If a WordPress account already uses this email address, that account will be used for recovery.

### Administrator Username

Example:

    $admin_username = 'recovery_admin';

The username is checked only when no matching WordPress user is found using the configured email address.

### Temporary Password

Example:

    $admin_password = 'ChangeThisToAStrongPassword';

Use a strong temporary password.

After successfully logging in, change the password again from the WordPress dashboard.

## Recovery Process

The script follows this sequence:

    Start
      |
      v
    Load WordPress
      |
      v
    Validate recovery token
      |
      v
    Check email
      |
      +---- User found ----> Change password + Administrator role
      |
      v
    Check username
      |
      +---- User found ----> Change password + Administrator role
      |
      v
    No matching user
      |
      v
    Create new Administrator
      |
      v
    Complete recovery
      |
      v
    Delete recovery script

### 1. Email Lookup

The configured email address is checked first.

Example:

    $admin_email = 'admin@example.com';

If an existing WordPress user has this email address, the script updates that user's password and assigns the Administrator role.

Email has the highest priority in the recovery process.

### 2. Username Fallback

If no WordPress user is found using the configured email address, the script checks the configured username.

Example:

    $admin_username = 'recovery_admin';

The username is only checked after the email lookup fails.

### 3. Create a New Administrator

If neither the configured email nor username belongs to an existing WordPress user, the script can create a new Administrator account using the configured credentials.

This provides a recovery path when the original administrator account cannot be located.

## Uploading the Script

The `royalrecover.php` file must be placed in the WordPress root directory.

The WordPress root normally contains files such as:

    wp-admin/
    wp-content/
    wp-includes/
    wp-config.php
    wp-load.php
    index.php

Place:

    royalrecover.php

in the same directory as:

    wp-load.php

### Correct Location

Example:

    public_html/
    ├── wp-admin/
    ├── wp-content/
    ├── wp-includes/
    ├── wp-config.php
    ├── wp-load.php
    ├── index.php
    └── royalrecover.php

### Incorrect Location

Do not normally place the file inside:

    wp-admin/
    wp-content/
    wp-includes/

unless your particular setup requires a different WordPress bootstrap path.

## Upload with FTP

You can use an FTP client such as FileZilla.

1. Connect to your hosting account.
2. Open the WordPress root directory.
3. Upload `royalrecover.php`.
4. Confirm that `wp-load.php` exists in the same directory.
5. Open the recovery URL in your browser.

## Upload with SFTP

SFTP clients such as WinSCP, Cyberduck, or other SFTP applications can also be used.

Upload:

    royalrecover.php

to the WordPress installation root.

## Upload with cPanel File Manager

1. Open cPanel.
2. Open **File Manager**.
3. Go to the WordPress root directory, commonly `public_html`.
4. Upload `royalrecover.php`.
5. Confirm that `wp-load.php` is present.
6. Open the recovery URL.

## Upload with CyberPanel

1. Log in to CyberPanel.
2. Open the website's File Manager.
3. Navigate to the WordPress document root.
4. Upload `royalrecover.php`.
5. Confirm that `wp-load.php` exists.
6. Open the recovery URL.

## Run the Recovery

After uploading the script, open:

    https://YOUR-DOMAIN.com/royalrecover.php?token=YOUR_RECOVERY_TOKEN

Replace:

`YOUR-DOMAIN.com`

with your website domain.

Replace:

`YOUR_RECOVERY_TOKEN`

with the exact token configured in `royalrecover.php`.

Example:

    https://example.com/royalrecover.php?token=R7x!Kp92@Lm4#Qz81$Vn6

Use HTTPS whenever possible.

## What Happens During Recovery

When the URL is opened, the script:

1. Loads WordPress.
2. Validates the recovery token.
3. Checks the configured email address.
4. If the email matches an existing user, updates that account.
5. If the email does not match, checks the username.
6. If the username matches, updates that account.
7. If neither matches, creates a new Administrator account.
8. Reports the recovery result.
9. Attempts to remove itself when supported.

## After Successful Recovery

Once recovery is complete:

1. Open the normal WordPress login page.
2. Log in using the recovered account.
3. Go to the WordPress dashboard.
4. Change the temporary password.
5. Verify the Administrator account.
6. Delete `royalrecover.php`.
7. Confirm that the recovery URL no longer works.

Typical WordPress login URLs are:

    https://YOUR-DOMAIN.com/wp-admin/

or:

    https://YOUR-DOMAIN.com/wp-login.php

## Important: Delete the Script

The recovery script should not remain on the server.

Even if the script attempts self-deletion, always verify manually.

From your browser, the following URL should no longer be accessible after deletion:

    https://YOUR-DOMAIN.com/royalrecover.php

Ideally, it should return a 404 response.

## Security Checklist

Before running:

- Use a unique recovery token.
- Use a strong temporary password.
- Use HTTPS.
- Confirm that you have authorization to administer the website.
- Confirm that the file is uploaded to the correct WordPress installation.

After running:

- Log in successfully.
- Change the temporary password.
- Verify the Administrator account.
- Delete `royalrecover.php`.
- Verify that the recovery URL is no longer accessible.
- Remove any copies of the recovery token from shared notes or public locations.

## Troubleshooting

### Access Denied or Invalid Token

Check that the URL token exactly matches:

    define('RECOVERY_TOKEN', 'YOUR_SECRET');

Check for:

- Typing errors
- Missing characters
- Extra spaces
- Incorrect URL encoding
- A different token in the uploaded file

### `wp-load.php` Not Found

Make sure `royalrecover.php` is located in the WordPress root directory.

The following files should normally be in the same directory:

    wp-load.php
    wp-config.php
    index.php
    royalrecover.php

### WordPress Could Not Be Loaded

Check that:

- WordPress files are complete.
- `wp-load.php` exists.
- PHP is functioning correctly.
- The PHP version is compatible with the installed WordPress version.
- The script has permission to read the WordPress files.

### Existing User Was Not Found

Verify the configured email and username.

Remember that the script checks the email before the username.

If neither matches an existing account, the script may create the configured Administrator account.

### Script Did Not Delete Itself

Some hosting environments, security systems, file permissions, or server configurations may prevent self-deletion.

Delete the file manually through:

- FTP
- SFTP
- cPanel File Manager
- CyberPanel File Manager
- SSH

Do not leave the recovery file on the server.

## GitHub Security

Do not commit real production recovery credentials or real recovery tokens to GitHub.

Use placeholders in public repositories.

Example:

    $admin_email = 'admin@example.com';
    $admin_username = 'recovery_admin';
    $admin_password = 'CHANGE_ME';
    define('RECOVERY_TOKEN', 'CHANGE_ME_TO_A_RANDOM_SECRET');

If you maintain a local/private copy with real credentials, do not publish it.

## Recommended `.gitignore`

If you maintain local configuration files, consider using a `.gitignore` file to prevent accidental publication of sensitive files.

Example:

    .env
    *.local.php
    config.local.php
    secrets.php

Never commit actual production passwords or recovery tokens.

## Privacy

This utility is designed to run on the WordPress installation where it is uploaded.

It does not require an external recovery service.

Do not include personal information, passwords, tokens, or private server details in public GitHub issues or discussions.

## Database Access

This project is intended to avoid requiring direct database administration for normal recovery.

You do not need to manually edit WordPress user records through phpMyAdmin when using the script's supported recovery process.

WordPress handles user updates through its normal WordPress APIs.

## WordPress Dashboard Access

The utility is intended for situations where normal dashboard access is unavailable.

After successful recovery, use the normal WordPress dashboard for account management.

Do not keep using the recovery script as a permanent administration interface.

## HTTPS

Always prefer HTTPS when opening the recovery URL.

Avoid sending the recovery token over an unencrypted HTTP connection.

If your website uses Cloudflare or another reverse proxy, verify that HTTPS is correctly configured before using the recovery utility.

## Backup Recommendation

Before making major changes to a production WordPress installation, maintain a recent backup of:

- WordPress files
- Database
- `wp-config.php`
- Important uploads
- Custom themes and plugins

The recovery process changes WordPress user information, so a current backup is recommended.

## Production Safety

This is a temporary emergency recovery utility, not a replacement for normal WordPress authentication.

Do not:

- Leave the script permanently installed.
- Publish the recovery token.
- Use a weak password.
- Share the recovery URL publicly.
- Commit real credentials to GitHub.
- Use the utility on websites you are not authorized to administer.

## Recommended Recovery Workflow

Use the following workflow:

1. Download the latest project files.
2. Open `royalrecover.php`.
3. Configure a strong temporary password.
4. Configure the correct administrator email.
5. Configure the fallback username.
6. Set a unique recovery token.
7. Upload the file to the WordPress root.
8. Open the protected recovery URL.
9. Confirm the recovery result.
10. Log in to WordPress.
11. Change the temporary password.
12. Verify Administrator access.
13. Delete `royalrecover.php`.
14. Verify the recovery URL is no longer accessible.

## Quick Reference

### File

    royalrecover.php

### WordPress Root

The directory containing:

    wp-load.php

### Recovery URL

    https://YOUR-DOMAIN.com/royalrecover.php?token=YOUR_RECOVERY_TOKEN

### Login

    https://YOUR-DOMAIN.com/wp-admin/

### Recovery Priority

    Email
      ↓
    Username
      ↓
    Create Administrator

### After Recovery

    Login
      ↓
    Change Password
      ↓
    Delete royalrecover.php
      ↓
    Verify 404

## FAQ

### Does this require a WordPress plugin?

No. The utility is a standalone PHP file.

### Does it require phpMyAdmin?

No. The intended recovery process does not require phpMyAdmin.

### Does it require FTP access?

The recovery process requires a way to place the PHP file on the server. FTP, SFTP, cPanel File Manager, CyberPanel File Manager, or another authorized filesystem method can be used.

### What happens if the email already exists?

The existing WordPress user associated with that email is used for recovery.

### What happens if the email does not exist?

The configured username is checked.

### What happens if neither exists?

The script can create a new Administrator account using the configured credentials.

### Is the email checked before the username?

Yes. Email has priority.

### Should I keep the file after recovery?

No. Delete it immediately after successful recovery.

### Can I publish the recovery token on GitHub?

No. Never publish a real recovery token.

### Can this be used on another person's website?

Only if you have explicit authorization to administer that website.

## Development

Repository:

https://github.com/mehdiakram/wp-emergency-recovery

Main file:

    royalrecover.php

## Compatibility

The utility is intended for standard WordPress installations using a supported PHP version.

Actual compatibility can depend on:

- WordPress version
- PHP version
- Hosting configuration
- File permissions
- Security plugins
- Web Application Firewall rules
- Server restrictions
- WordPress multisite configuration

Always test carefully on the intended environment.

## WordPress Multisite

WordPress Multisite has additional user and role behavior.

This utility is primarily intended for standard WordPress installations.

For multisite environments, verify the intended site/network permissions carefully before using an emergency recovery utility.

## Hosting Compatibility

The recovery file can generally be uploaded through common hosting environments, including:

- cPanel
- CyberPanel
- Plesk
- FTP hosting
- SFTP servers
- VPS environments
- Dedicated servers
- Cloud servers

Actual behavior depends on the server's PHP and filesystem configuration.

## Changelog

### 1.0.0

- Initial release
- Email-first recovery
- Username fallback
- Administrator role assignment
- New Administrator creation
- Token-protected recovery
- Temporary single-file deployment
- Self-delete attempt

## Roadmap

Possible future improvements may include:

- More detailed recovery logging
- Optional IP restrictions
- Additional authentication safeguards
- Configurable expiration time
- One-time recovery tokens
- Better multisite support
- Improved hosting-environment compatibility
- Additional recovery diagnostics

## Contributing

Contributions, bug reports, and improvements are welcome.

Before submitting a contribution:

1. Do not include passwords or recovery tokens.
2. Do not include private customer information.
3. Clearly describe the problem or proposed improvement.
4. Test changes before submitting them.

## Security Issues

If you discover a security issue, avoid publicly posting sensitive exploit details before the issue can be reviewed.

Repository:

https://github.com/mehdiakram/wp-emergency-recovery

## License

MIT License

Copyright (c) Royal Technologies

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files, to deal in the software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the software, and to permit persons to whom the software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

## Disclaimer

Royal Technologies provides this project as an emergency administration utility.

The user is responsible for ensuring that they have appropriate authorization to access and modify the WordPress installation where this software is used.

Always follow applicable laws, hosting policies, organizational security policies, and responsible security practices.

## Royal Technologies

**Royal Technologies**

IT Company & Training Institute

Website:

https://www.royaltechbd.com/

GitHub:

https://github.com/mehdiakram/wp-emergency-recovery

Royal Technologies develops web solutions, WordPress products, software, hosting-related services, and IT training.

## Thank You

Thank you for using Royal WordPress Emergency Recovery.

If this project helps you recover access to an authorized WordPress installation, consider contributing improvements or reporting issues through the GitHub repository.
