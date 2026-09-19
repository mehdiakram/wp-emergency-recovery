# Royal WordPress Emergency Recovery

**A lightweight, temporary WordPress account recovery utility by Royal Technologies.**

[![WordPress](https://img.shields.io/badge/WordPress-Compatible-6%2B-21759B?logo=wordpress&logoColor=white)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**Developed by Royal Technologies**

Website: https://www.royaltechbd.com/

---

## Overview

**Royal WordPress Emergency Recovery** is a small, temporary PHP utility designed to help authorized website administrators recover access to a WordPress installation when normal WordPress login access is unavailable but FTP, SFTP, hosting file manager, or server filesystem access is available.

The utility works by loading the existing WordPress installation through `wp-load.php` and performing a controlled user recovery operation.

It does not require:

- WordPress Dashboard access
- Installing a WordPress plugin
- phpMyAdmin
- Direct database editing

The script is designed for **temporary emergency use** and should be deleted immediately after the recovery process is complete.

---

## Important Security Notice

> **This is an emergency recovery utility. Do not leave it on a production website.**

The script can change a WordPress user's password and assign the Administrator role, or create a new Administrator account.

Anyone who obtains the valid recovery token and can access the script URL may be able to perform the recovery operation.

Therefore:

1. Use it only on websites you own or are authorized to administer.
2. Use a unique recovery token for every recovery.
3. Never publish a real recovery token.
4. Never publish a real password.
5. Never commit production credentials to GitHub.
6. Delete `royalrecover.php` immediately after use.
7. Verify manually that the file has actually been removed.

---

# Features

## Account Recovery

The script can recover an existing WordPress account by changing its password.

## Email-First Lookup

The email address is checked first.

If a matching WordPress user exists, that account is recovered.

## Username Fallback

If no user is found using the email address, the script checks the configured username.

## Administrator Role

The recovered user is assigned the WordPress `administrator` role.

## New Administrator Creation

If neither the email nor username exists, the script can create a new WordPress Administrator account.

## Token Protection

The recovery URL requires a secret token.

Example:

```text
https://example.com/royalrecover.php?token=YOUR_SECRET_TOKEN
