# myblog
Simple website and blog.
Follow instructions below to set up myblog.
___
1. System Requirements: 
  - PHP 7 (add php to PATH); 
  - MySQL; 
  - Apache.

I recommend XAMPP for simplicity.

2. Setup database:
  - create database; 
  - navigate to config/globals.php and add: 
    - host (localhost); 
    - user (root);
    - password;
    - database name;
  - navigate to database/;
  - open command prompt here and type: `php migration.php -up`.

This will create all tables.

3. Optional

You can setup path to profile images and fonts in config/globals.php.

4. Mail

In order to make mail work download sendmail and configure sendmail.ini:

    smtp_server=smtp.mailtrap.io;
    smtp_port=25;
    smtp_ssl=auto;
    auth_username=;
    auth_password=;

Username and password you will get after creating mailtrap account ([Mailtrap.io](https://mailtrap.io/)). After that you can check forgot password functionality.

---

Thats all configurations. Visit index.php, register new user, login, upload profile image, add some posts.

