CREATE DATABASE yeticave;

USE yeticave;

CREATE TABLE categories (
	id		INT AUTO_INCREMENT PRIMARY KEY,
	name	CHAR(32)
);

CREATE TABLE lots (
	id				INT AUTO_INCREMENT PRIMARY KEY,
  	category_id		INT,
  	author_id		INT,
	winner_id		INT,
	title			CHAR(128),
	description		TEXT,
  	creation_date	DATETIME,
  	complete_date	DATETIME,
	img_path        CHAR(128),
	start_price		INT,
	bet_step		INT,
	favorite_count	INT
);

CREATE TABLE bets (
	id				INT AUTO_INCREMENT PRIMARY KEY,
  	user_id			INT,
	lot_id			INT,
	placement_date	DATETIME,
	price			INT
);

CREATE TABLE users (
	id					INT AUTO_INCREMENT PRIMARY KEY,
	name				CHAR(128),
	email				CHAR(128),
	password			CHAR(60),
	registration_date	DATETIME,
	avatar_path			CHAR(128),
	contacts			CHAR(128)
);

CREATE UNIQUE INDEX category_id ON categories(id);

CREATE UNIQUE INDEX lot_id ON lots(id);

CREATE UNIQUE INDEX bet_id ON bets(id);

CREATE UNIQUE INDEX user_id ON users(id);
CREATE UNIQUE INDEX user_email ON users(email);

CREATE INDEX category_name ON categories(name);

CREATE INDEX lot_category_id_index ON lots(category_id);
CREATE INDEX lot_author_id_index ON lots(author_id);
CREATE INDEX lot_winner_id_index ON lots(winner_id);
CREATE INDEX lot_title_index ON lots(title);

CREATE INDEX bet_user_id_index ON bets(user_id);
CREATE INDEX bet_lot_id_index ON bets(lot_id);

CREATE INDEX user_name_index ON users(name);
CREATE INDEX user_email_index ON users(email);
