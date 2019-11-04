CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
  USE yeticave;

CREATE TABLE categories (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  cat_name VARCHAR(64) NOT NULL,
  code     VARCHAR(64) NOT NULL
);

CREATE TABLE lots (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  lot_name VARCHAR(64) NOT NULL,
  cat_id   VARCHAR(64) NOT NULL,
  st_price INT NOT NULL,
  path     VARCHAR(128) UNIQUE,
  dt_add   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dt_end   TIMESTAMP NOT NULL,
  text     VARCHAR(255),
  bet_step INT,
  autor_id INT NOT NULL,
  winner_id INT
);

CREATE TABLE bets (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  dt_add   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  value    INT NOT NULL,
  user_id  INT NOT NULL,
  lot_id   INT NOT NULL
);

CREATE TABLE users (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  dt_reg   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email    VARCHAR(128) NOT NULL,
  name     VARCHAR(64) NOT NULL,
  password VARCHAR(64) NOT NULL,
  contacts VARCHAR(128) NOT NULL,
  lot_id   INT,
  bet_id   INT
);

CREATE UNIQUE INDEX lot_name ON lots(lot_name);
CREATE UNIQUE INDEX email ON users(email);
CREATE INDEX name ON users(name);
CREATE INDEX cat_name ON categories(cat_name);
CREATE INDEX value ON bets(value);
CREATE INDEX start_price ON lots(st_price);
