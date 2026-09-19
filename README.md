# Royal WordPress Emergency Recovery

A temporary PHP-based emergency recovery tool for WordPress websites.

It can be useful when you have FTP or file access to a WordPress installation but cannot access the WordPress dashboard.

## Features

- Email-first user lookup
- Username fallback
- Password reset for an existing user
- Administrator role assignment
- Creates a new Administrator if no matching user exists
- Token-protected recovery URL
- No WordPress plugin installation required
- Attempts to delete itself after execution
- Designed for temporary emergency recovery

## How It Works

The recovery process follows this order:

1. Search for a WordPress user by email
2. If the email is not found, search by username
3. If the username is not found, create a new Administrator account

### Example

Suppose the WordPress installation contains:

| Username | Email |
|---|---|
| mehdi | mehdi@example.com |
| admin2 | admin@example.com |

And the recovery configuration contains:

```php
$admin_email = 'admin@example.com';
$admin_username = 'mehdi';
