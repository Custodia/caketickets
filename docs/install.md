## Mysql tables

```
CREATE TABLE users(
	id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(20) NOT NULL,
	email VARCHAR(50) NOT NULL,
	password VARCHAR(50) NOT NULL,
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
	created DATETIME DEFAULT NULL,
	modified DATETIME DEFAULT NULL
);

CREATE TABLE projects_users (
	project_id INT NOT NULL,
	user_id INT NOT NULL,
	role VARCHAR(10),
	PRIMARY KEY (project_id, user_id),
	FOREIGN KEY project_user_key(project_id) REFERENCES projects(id),
	FOREIGN KEY user_project_key(user_id) REFERENCES users(id)
);

CREATE TABLE projects_tickets (
	project_id INT NOT NULL,
	ticket_id INT NOT NULL,
	PRIMARY KEY (project_id, ticket_id),
	FOREIGN KEY project_ticket_key(project_id) REFERENCES projects(id),
	FOREIGN KEY ticket_project_key(ticket_id) REFERENCES tickets(id)
);

CREATE TABLE tickets_users (
	ticket_id INT NOT NULL,
	user_id INT NOT NULL,
	PRIMARY KEY (ticket_id, user_id),
	FOREIGN KEY ticket_user_key(ticket_id) REFERENCES tickets(id),
	FOREIGN KEY user_ticket_key(user_id) REFERENCES users(id)
);

CREATE TABLE tickets_users_comments (
	ticket_id INT NOT NULL,
	user_id INT NOT NULL,
	comment_id INT NOT NULL,
	PRIMARY KEY (ticket_id, user_id, comment_id),
	UNIQUE KEY (comment_id),
	FOREIGN KEY ticket_comment_key(ticket_id) REFERENCES tickets(id),
	FOREIGN KEY user_comment_key(user_id) REFERENCES users(id),
	FOREIGN KEY comment_ticket_key(comment_id) REFERENCES comments(id)
)
```