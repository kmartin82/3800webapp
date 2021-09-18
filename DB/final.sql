use martin_course_db;

DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS userinfo;
DROP TABLE IF EXISTS cart;
DROP TABLE IF EXISTS inventory;
DROP TABLE IF EXISTS users;


CREATE TABLE inventory (
	upc             VARCHAR(20)     NOT NULL,
	title           VARCHAR(50)		NOT NULL,
	price		 	FLOAT(10)     	NOT NULL,
	description     VARCHAR(300)	NOT NULL,
	quantity 		INT(4)   	 	NOT NULL,
	PRIMARY KEY (upc)
);

CREATE TABLE users (
	username		VARCHAR(20) NOT NULL,
	user_password	VARCHAR(255) NOT NULL,
	PRIMARY KEY (username)
);

CREATE TABLE cart (
	upc             VARCHAR(20)     NOT NULL,
	quantity 		INT(4)   	 	NOT NULL,
	username		VARCHAR(20)		NOT	NULL,
	PRIMARY KEY (upc, username),
	CONSTRAINT FOREIGN KEY (username) references users(username),
	CONSTRAINT FOREIGN KEY (upc) references inventory(upc)
);

CREATE TABLE userinfo (
	username		VARCHAR(20) NOT NULL,
	firstname		VARCHAR(20),
	lastname		VARCHAR(20),
	address			VARCHAR(30),
	city			VARCHAR(20),
	stateuser		VARCHAR(4),
	zip				varchar(10),
	telephone		varchar(20),
	primary KEY (username),
	CONSTRAINT FOREIGN KEY (username) references users(username)
);

CREATE TABLE orders (
	orderid			INT(5) NOT NULL,
	upc				VARCHAR(20) NOT NULL,
	username		VARCHAR(20) NOT NULL,
	quantity		INT(4) NOT NULL,
	price			FLOAT(10) NOT NULL,
	orderdate		DATETIME NOT NULL,
	PRIMARY KEY (orderid, upc, username),
	CONSTRAINT FOREIGN KEY (upc) references inventory(upc),
	CONSTRAINT FOREIGN KEY (username) references users(username)
);

INSERT INTO inventory (upc, title, price, description, quantity)
VALUES ('cyber77', 'Cyber Punk 2077', 59.99, 'Cyberpunk 2077 is an open-world, action-adventure story set in Night City, a megalopolis obsessed with power, glamour and body modification. You play as V, a mercenary outlaw going after a one-of-a-kind implant that is the key to immortality', 25);

INSERT INTO inventory (upc, title, price, description, quantity)
VALUES ('wit1358', 'The Witcher 3: Wild Hunt', 39.99, 'As war rages on throughout the Northern Realms, you take on the greatest contract of your life — tracking down the Child of Prophecy, a living weapon that can alter the shape of the world', 10);

INSERT INTO inventory (upc, title, price, description, quantity)
VALUES ('fall412', 'Fallout 4', 25.99, 'Bethesda Game Studios, the award-winning creators of Fallout 3 and The Elder Scrolls V: Skyrim, welcome you to the world of Fallout 4 – their most ambitious game ever, and the next generation of open-world gaming', 16);

INSERT INTO inventory (upc, title, price, description, quantity)
VALUES ('mad5554', 'Mad Max', 19.99, 'Play as Mad Max, a reluctant hero and survivor who wants nothing more than to leave the madness behind and find solace', 35);

INSERT INTO inventory (upc, title, price, description, quantity)
VALUES ('halo343', 'Halo: The Master Chief Collection', 39.99, 'The Master Chief’s iconic journey includes six games, built for PC and collected in a single integrated experience. Whether you’re a long-time fan or meeting Spartan 117 for the first time, The Master Chief Collection is the definitive Halo gaming experience.', 28);

INSERT INTO inventory (upc, title, price, description, quantity)
VALUES ('metro34', 'Metro: Last Light Redux', 19.99, 'It is the year 2034. Beneath the ruins of post-apocalyptic Moscow, in the tunnels of the Metro, the remnants of mankind are besieged by deadly threats from outside – and within. Mutants stalk the catacombs beneath the desolate surface, and hunt amidst the poisoned skies above', 40);

INSERT INTO inventory (upc, title, price, description, quantity)
VALUES ('mordor1', 'Middle-earth: Shadow of Mordor', 9.99, 'Fight through Mordor and uncover the truth of the spirit that compels you, discover the origins of the Rings of Power, build your legend and ultimately confront the evil of Sauron in this new chronicle of Middle-earth', 8);

INSERT INTO inventory (upc, title, price, description, quantity)
VALUES ('bio1935', 'BioShock Infinite', 29.99, 'Indebted to the wrong people, with his life on the line, veteran of the U.S. Cavalry and now hired gun, Booker DeWitt has only one opportunity to wipe his slate clean. He must rescue Elizabeth, a mysterious girl imprisoned since childhood and locked up in the flying city of Columbia', 14);

INSERT INTO inventory (upc, title, price, description, quantity)
VALUES ('bat8195', 'Batman: Arkham Knight', 19.99, 'Batman: Arkham Knight brings the award-winning Arkham trilogy from Rocksteady Studios to its epic conclusion. Developed exclusively for New-Gen platforms, Batman: Arkham Knight introduces Rocksteadys uniquely designed version of the Batmobile', 38);

INSERT INTO inventory (upc, title, price, description, quantity)
VALUES ('dirt801', 'DiRT Rally', 19.99, 'DiRT Rally is the most authentic and thrilling rally game ever made, road-tested over 80 million miles by the DiRT community. It perfectly captures that white knuckle feeling of racing on the edge as you hurtle along dangerous roads, knowing that one crash could irreparably harm your stage time', 45);