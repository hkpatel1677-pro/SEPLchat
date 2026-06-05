# SEPL Chat

SEPL Chat is a simple real-time web chat application built with PHP, MySQL, AJAX, and jQuery. It supports private and group messaging with live updates.

## Features

- Private chat between users
- Group chat creation and messaging
- Unread message counts
- Message deletion
- Live updates using AJAX polling

## Prerequisites

- XAMPP (Apache + MySQL) or equivalent LAMP/WAMP stack
- PHP 7.4+ (confirm your PHP version)
- MySQL / MariaDB
- A browser (Chrome/Firefox recommended)

## Installation

1. Place the project folder inside your web server document root (for XAMPP: `C:/xampp/htdocs/SEPLchat`).
2. Start Apache and MySQL from the XAMPP control panel.
3. Import the database schema:

	 - Using phpMyAdmin: open `http://localhost/phpmyadmin`, create a new database (e.g., `sepl_chat`) and import `database.sql`.
	 - Using MySQL CLI:

```bash
mysql -u root -p
CREATE DATABASE sepl_chat;
USE sepl_chat;
SOURCE database.sql;
```

4. Update database credentials in `connect.php` if necessary (DB name, username, password, host).

## Configuration

- Open `connect.php` and set the correct values for `$servername`, `$username`, `$password`, and `$dbname` to match your environment.

## Run the app

1. Open your browser and go to:

```
http://localhost/SEPLchat/login.php
```

2. Register a new user via `register.php` or login with existing credentials.

## Usage

- Create or join a group from the Group UI.
- Start a private chat by selecting a user from the sidebar.
- Messages are sent via AJAX and should appear in near real-time.

## Troubleshooting

- Blank page / PHP errors: enable error display in `php.ini` or check Apache error logs.
- Database connection errors: verify `connect.php` settings and confirm the database exists.
- If live updates don't appear: confirm AJAX requests in browser DevTools (Network tab) and check `load_message.php` / `send_message.php` responses.

## Security notes

- This project is intended as a learning/demo application. For production, you should:
	- Use prepared statements / parameterized queries to prevent SQL injection.
	- Sanitize and validate all user input and output.
	- Use HTTPS and secure cookies for authentication.
	- Avoid using the `root` database user; create a dedicated user with limited privileges.

## Author

Helly Patel