CREATE TABLE IF NOT EXISTS tbl_project (
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(128),
	description TEXT,
	create_time DATETIME,
	create_user_id INTEGER,
	update_time DATETIME,
	update_user_id INTEGER
);

CREATE TABLE IF NOT EXISTS tbl_issue (
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name varchar(256) NOT NULL,
	description varchar(2000),
	project_id INTEGER,
	type_id INTEGER,
	status_id INTEGER,
	owner_id INTEGER,
	requester_id INTEGER,
	create_time DATETIME,
	create_user_id INTEGER,
	update_time DATETIME,
	update_user_id INTEGER
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS tbl_user (
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	email Varchar(256) NOT NULL,
	username Varchar(256),
	password Varchar(256),
	last_login_time Datetime,
	create_time DATETIME,
	create_user_id INTEGER,
	update_time DATETIME,
	update_user_id INTEGER
) ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS tbl_project_user_assignment (
	project_id Int(11) NOT NULL,
	user_id Int(11) NOT NULL,
	create_time DATETIME,
	create_user_id INTEGER,
	update_time DATETIME,
	update_user_id INTEGER,
	PRIMARY KEY (project_id, user_id)
) ENGINE = InnoDB;

-- The Relationships
ALTER TABLE tbl_issue ADD CONSTRAINT FK_issue_project FOREIGN KEY (project_id) REFERENCES tbl_project (id) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE tbl_issue ADD CONSTRAINT FK_issue_owner FOREIGN KEY (owner_id) REFERENCES tbl_user (id) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE tbl_issue ADD CONSTRAINT FK_issue_requester FOREIGN KEY (requester_id) REFERENCES tbl_user (id) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE tbl_project_user_assignment ADD CONSTRAINT FK_project_user FOREIGN KEY (project_id) REFERENCES tbl_project (id) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE tbl_project_user_assignment ADD CONSTRAINT FK_user_project FOREIGN KEY (user_id) REFERENCES tbl_user (id) ON DELETE CASCADE ON UPDATE RESTRICT;
-- Insert some seed data so we can just begin using the database
INSERT INTO tbl_user (email, username, password) VALUES
('test1@notanaddress.com','Test_User_One', MD5('test1')),
('test2@notanaddress.com','Test_User_Two', MD5('test2')),
('test3@notanaddress.com','Test_User_Three', MD5('test3'));
INSERT INTO tbl_project_user_assignment (project_id, user_id, create_time, create_user_id, update_time, update_user_id) VALUES
-- (2, 1, '2010-01-01 00:00:00', 1, '2010-01-01 00:00:00', 1),
(2, 2, '2010-01-01 00:00:00', 1, '2010-01-01 00:00:00', 1);

CREATE TABLE IF NOT EXISTS AuthItem (
	name varchar(64) not null,
	type integer not null,
	description text,
	bizrule text,
	data text,
	primary key (name)
);

CREATE TABLE IF NOT EXISTS AuthItemChild (
	parent varchar(64) not null,
	child varchar(64) not null,
	primary key (parent,child),
	foreign key (parent) references AuthItem (name) on delete cascade on update cascade,
	foreign key (child) references AuthItem (name) on delete cascade on update cascade
);

CREATE TABLE IF NOT EXISTS AuthAssignment (
	itemname varchar(64) not null,
	userid varchar(64) not null,
	bizrule text,
	data text,
	primary key (itemname,userid),
	foreign key (itemname) references AuthItem (name) on delete cascade on update cascade
);

CREATE TABLE IF NOT EXISTS tbl_project_user_role (
	project_id INTEGER NOT NULL,
	user_id INTEGER NOT NULL,
	role VARCHAR(64) NOT NULL,
	primary key (project_id, user_id, role),
	foreign key (project_id) references tbl_project (id),
	foreign key (user_id) references tbl_user (id),
	foreign key (role) references AuthItem (name)
);

CREATE TABLE IF NOT EXISTS tbl_comment (
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	content TEXT NOT NULL,
	issue_id INTEGER,
	create_time DATETIME,
	create_user_id INTEGER,
	update_time DATETIME,
	update_user_id INTEGER
);

ALTER TABLE tbl_comment ADD CONSTRAINT FK_comment_issue FOREIGN KEY (issue_id) REFERENCES tbl_issue (id);
ALTER TABLE tbl_comment ADD CONSTRAINT FK_comment_author FOREIGN KEY (create_user_id) REFERENCES tbl_user (id);

CREATE TABLE IF NOT EXISTS tbl_sys_message (
	id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
	message TEXT NOT NULL,
	create_time DATETIME,
	create_user_id INTEGER,
	update_time DATETIME,
	update_user_id INTEGER
);
