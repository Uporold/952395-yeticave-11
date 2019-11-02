CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
  USE yeticave;

CREATE TABLE categories (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  name     VARCHAR(64) NOT NULL,
  code     VARCHAR(64) NOT NULL
);

CREATE TABLE lots (
  id       INT AUTO_INCREMENT PRIMARY KEY,
  dt_add   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  name     VARCHAR(64) UNIQUE NOT NULL,
  text     VARCHAR(255),
  path     VARCHAR(128) UNIQUE,
  st_price INT NOT NULL,
  dt_end   TIMESTAMP NOT NULL,
  bet_step INT NOT NULL,

  autor_id INT NOT NULL,
  winner_id INT NOT NULL,
  cat_id INT NOT NULL
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
  email    VARCHAR(128) NOT NULL UNIQUE,
  name     VARCHAR(64) NOT NULL,
  password VARCHAR(64) NOT NULL,
  contacts VARCHAR(128) NOT NULL,

  lot_id   INT,
  bet_id   INT

);

CREATE UNIQUE INDEX name ON lots(name);
CREATE UNIQUE INDEX path ON lots(path);
CREATE UNIQUE INDEX email ON users(email);
CREATE INDEX user_name ON users(name);
CREATE INDEX lot_descr ON lots(text);
CREATE INDEX start_price ON lots(st_price);

