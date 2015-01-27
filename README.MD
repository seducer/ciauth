1. Quick Installation Guide.

  1.1 Minimum system requirements:
    - Apache 2
    - PHP >= 5.3.7 (The reason for this is that PHP prior to 5.3.7 contains a security issue with its BCRYPT implementation.)
    - Mysql 5
  
  1.2 Upload the folders and files to your server. Index.php file will be at your root.
  1.2 Setting up the database connection. Open application/config/database.php.
  1.3 Setting up "Base Site URL". Open application/config/config.php (recommended)
  1.4 Import ciauth.sql file.
2. Usage

  2.1 Authorization path /welcome/login
  2.2 Log in to the web interface. login:admin, password:admin.

3. App guide
  
  App is based on MVC pattern.
  - Model classes(ciauth_model) are stored application/models/ folder
  - View filesare stored application/views/ folder
  - Controller classes are stored application/controllers/ folder
  - Other classes(libraries) are stored application/libraries/ folder
