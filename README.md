# Projeto Açaí e Polpas Amazônia
## Project  based on a website connected to a *MySQL* Database using *PHP* as an intermediate layer between the *web page* and the *data*

[🖌️ Figma Project](https://www.figma.com/design/KG2g0vrnxkWhpYED4uM7DG/Projeto-A%C3%A7a%C3%AD?node-id=0-1&p=f&t=wKqWymchvS68Lj0V-0)

> All images used in this project are stored using [Cloudinary](https://cloudinary.com/) services

## API
Developed using **PHP** to allow database queries requests from the web page
Its manages **users** and **orders** integrated with *online spreadsheets*
Users are classified as:
- **Costumer:** Has an activated order once authenticated
- **Administrator:** Has a *profile avatar* and can *modify* database data
Users sessions last for *1 hour*, requiring re-authentication after expiration

> *Secure mechanisms* prevent unauthorized users from accessing restricted pages

## Sign-in and Sign-up System
Each user identified through a **sign-in / sign-up system**, including: 
- Name
- E-mail*
- Contact phone number
- Address
	- Street
	- House number
	- District
	- City
	- State
	- Reference point
- Profile avatar
- Password**

> *Email normalized (invalid characters removed)

> ** Passwords are encrypted with `password_hash()` in PHP and securely stored in the database. The system allows users to **reset their password** via email, using the **PHPmailer** Library to send a verification token

## Available Admin Actions
- Perform *CRUD* operations at **Admin data**
- Remove or modify **Costumer data**
- Perform *CRUD* operations at **Products** and **Products versions**
- View **Orders***
- Modify personal data

>*If the order does not have anything add in, its gonna be remove after a day.

## Product Page
Products can be filtered by:
- **Name**: Search, (A-Z) and (Z-A)
- **Price:** Ascending or descending

## Important Notes
For security reasons, *image upload(product and admin)* and *spreadsheet editing* features are disabled by default.
Those features requires **private API tokes**  from *Cloudinary* and *Google Sheets*
To enable them, you must **setup your own connections**, as described in the *"How to Run"* section.
To enable them, visit **"How to run"** section to **setup your own connections**

## Directory Scheme
```
|
|- public                   #Web page files
|- database.sql             #Database dump
|- databaseConnection.php   #Database connection file
|- docker-compose.yml       #Docker images settings
|- Dockerfile               #Apache image settings
```

## How to Run

### API Keys (any OS)
- Create a [Google Cloud Project](https://www.youtube.com/watch?v=k_PB4ORz2r0) and enable the **Google Sheets API**.
- Create a service account and download the `credentials.json` file. Place it in project root:
- At `cart.php` file, update the config variable path to the path to your `credentias.json`:
```php
$config->setAuthConfig('../../credentials.json')
```
- At `cart.php` file yet, update `$spreadsheetId` value to your **spreadsheet id**:
```php
$spreadsheetId = "idHere"
```
- Share the spreadsheet with your service account email
- Create an account on [Cloudinary](https://cloudinary.com)
- Copy your **API Key** ([tutorial here](https://youtu.be/ZSIt6nCkqNc?si=zzNuC-CHRqCzuVdX&t=34)) and paste it in the `.env` file inside the `composer` directory

### 🪟 Windows
> *OBS:* Turn on **System Virtualization** at BIOS before running the project

- Install [Docker Desktop](https://docs.docker.com/desktop/)
- Install WSL Ubuntu (or another desired Linux distro):
```bash
wsl --install ubuntu
```
- Open **Docker Desktop**:
	- Navigate through `Options - Resources - WSL Integration`
	- Enable **"Integration with my default WSL distro"**, click on your desired distro down bellow and **apply**
- Open **Ubuntu App** (terminal) and copy the repository:
```bash
cd ~
mkdir projects
cd projects
git clone https://github.com/LuizGustavo1001/Projeto-Acai-2.0.git
cd Projeto-Acai-2.0
```
- Install **composer requirements**:
```bash
cd public/composer
composer install
composer require cloudinary/cloudinary_php
composer require vlucas/phpdotenv
composer require phpmailer/phpmailer
composer require google/apiclient:^2.0
```
- Start **container application**:
```bash
docker compose up
```
### 🐧Linux (Terminal)
- Install **Docker** at Terminal: [Ubuntu/Debian](https://docs.docker.com/engine/install/ubuntu/) | [Fedora](https://docs.docker.com/engine/install/fedora/) 
- **Clone** the repository and start **container application**:
```bash
cd ~
cd Documents
git clone https://github.com/LuizGustavo1001/Projeto-Acai-2.0.git
cd Projeto-Acai-2.0
docker compose up
```
- Install **composer requirements**:
```bash
cd public/composer
composer install
composer require cloudinary/cloudinary_php
composer require vlucas/phpdotenv
composer require symfony/mailer
composer require google/apiclient:^2.0
```

### Database
```
phpmyadmin user: user, 1111
admin: admin@dominio.com 1901
client: client@dominio.com 1901
```

- Access **phpmyadmin** web page:
```
http://localhost:8081
```
- **Import database** from `database.sql` file at project root
	- Top center navigation bar
	- "File to import"
	- "Browse..."
- Access the **Web page**:
```
http://localhost:8080
```
