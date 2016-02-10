# Installation

## Install php necessities

### Debian

```
sudo apt-get install php5 php5-intl
```

### Arch

```
sudo pacman -S php
```

Then enable the intl extension by modifying your php.ini file.

## Make the project skeleton

1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app caketickets`.

If Composer is installed globally, run
```bash
composer create-project --prefer-dist cakephp/app caketickets
```

## Pull the git repo

```
git init
git remote add origin git@github.com:Custodia/caketickets.git
git fetch
git checkout -t origin/master
```

## Mysql tables

You need to create these SQL tables for the program to work.

```
CREATE TABLE users(
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(20) NOT NULL,
	email VARCHAR(50) NOT NULL,
	password VARCHAR(255) NOT NULL,
	role VARCHAR(10),
	created DATETIME DEFAULT NULL,
	modified DATETIME DEFAULT NULL,
	UNIQUE KEY (username),
	UNIQUE KEY (email)
);

CREATE TABLE projects (
	id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(100),
	body TEXT,
	user_id INT NOT NULL,
	tag_id INT NOT NULL,
	created DATETIME DEFAULT NULL,
	modified DATETIME DEFAULT NULL
);

CREATE TABLE tags (
	id INT AUTO_INCREMENT PRIMARY KEY,
	parent_id INT,
	lft INT,
	rght INT,
	name VARCHAR(50),
	description VARCHAR(100),
	created DATETIME DEFAULT NULL,
	modified DATETIME DEFAULT NULL,
	UNIQUE KEY (name)
);

CREATE TABLE tickets (
	id INT AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(100),
	status VARCHAR(10),
	body TEXT,
	created DATETIME DEFAULT NULL,
	modified DATETIME DEFAULT NULL
);

CREATE TABLE comments (
	id INT AUTO_INCREMENT PRIMARY KEY,
	body TEXT,
	user_id INT,
	created DATETIME DEFAULT NULL,
	modified DATETIME DEFAULT NULL
);

CREATE TABLE projects_users (
	project_id INT NOT NULL,
	user_id INT NOT NULL,
	role VARCHAR(10) DEFAULT 'User',
	PRIMARY KEY (project_id, user_id),
	FOREIGN KEY project_user_key(project_id) REFERENCES projects(id),
	FOREIGN KEY user_project_key(user_id) REFERENCES users(id)
);

CREATE TABLE projects_tickets (
	project_id INT NOT NULL,
	ticket_id INT NOT NULL,
	PRIMARY KEY (project_id, ticket_id),
	FOREIGN KEY project_ticket_key(project_id) REFERENCES projects(id),
	FOREIGN KEY ticket_project_key(ticket_id) REFERENCES tickets(id),
	UNIQUE KEY (ticket_id)
);

CREATE TABLE tickets_users (
	ticket_id INT NOT NULL,
	user_id INT NOT NULL,
	PRIMARY KEY (ticket_id, user_id),
	FOREIGN KEY ticket_user_key(ticket_id) REFERENCES tickets(id),
	FOREIGN KEY user_ticket_key(user_id) REFERENCES users(id)
);

CREATE TABLE tickets_comments (
	ticket_id INT NOT NULL,
	comment_id INT NOT NULL,
	PRIMARY KEY (ticket_id, comment_id),
	UNIQUE KEY (comment_id),
	FOREIGN KEY ticket_comment_key(ticket_id) REFERENCES tickets(id),
	FOREIGN KEY comment_ticket_key(comment_id) REFERENCES comments(id)
);
```

You might also want to manually insert a admin user 

```INSERT INTO users (username, email, password, role)
VALUES (admin, admin@email.com, admin, Admin)```

## Setup config/app.php

### Database settings.

Depends on your personal database settings but example below:

```
    'Datasources' => [
        'default' => [
            'className' => 'Cake\Database\Connection',
            'driver' => 'Cake\Database\Driver\Mysql',
            'persistent' => false,
            'host' => 'localhost',
            'username' => 'caketicketsadmin',
            'password' => 'cakestastegood',
            'database' => 'cake_tickets',
            'encoding' => 'utf8',
            'timezone' => 'UTC',
            'cacheMetadata' => true,
        ],
    ]
```
