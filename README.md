# Royal WordPress Emergency Recovery

### A temporary emergency account recovery utility for WordPress

Developed and branded by **Royal Technologies**.

Website:  
https://www.royaltechbd.com/

---

## About Royal Technologies

Royal Technologies is an IT company providing services including:

- Web Design
- Web Development
- WordPress Development
- Web Hosting
- Domain Registration
- SEO
- IT Consulting
- Software Development
- Remote and Technical Support

Learn more:

https://www.royaltechbd.com/

---

# What is Royal WordPress Emergency Recovery?

Royal WordPress Emergency Recovery is a small, temporary PHP utility designed for situations where you have legitimate filesystem or FTP access to a WordPress website but cannot access the WordPress administration dashboard.

It loads the existing WordPress installation and provides a controlled method for recovering an administrator account.

The tool does not require installing a WordPress plugin.

---

# Features

- Email-first account lookup
- Username fallback
- Existing user password recovery
- Administrator role assignment
- New Administrator account creation
- Token-protected recovery URL
- No database editing required
- No WordPress plugin installation required
- Lightweight PHP script
- Branded Royal Technologies interface
- Attempts automatic self-deletion after execution
- No credentials required in the URL except the recovery token

---

# Recovery Logic

The script follows this exact order:

```text
Email
  |
  |-- User found
  |      |
  |      --> Change password
  |      --> Assign Administrator role
  |
  |-- User not found
         |
         v
       Username
         |
         |-- User found
         |      |
         |      --> Change password
         |      --> Assign Administrator role
         |
         |-- User not found
                |
                v
          Create new Administrator
