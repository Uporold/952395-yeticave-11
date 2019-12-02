CREATE DATABASE yeticave
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
  USE yeticave;

CREATE TABLE categories (
  id           INT AUTO_INCREMENT PRIMARY KEY,
  categoryName VARCHAR(64) NOT NULL,
  code         VARCHAR(64) NOT NULL
);

CREATE TABLE users (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  dt_reg      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  email       VARCHAR(128) NOT NULL UNIQUE,
  name        VARCHAR(64) NOT NULL,
  password    VARCHAR(64) NOT NULL,
  contacts    VARCHAR(128) NOT NULL
);

CREATE TABLE lots (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  lot_name    VARCHAR(64) NOT NULL UNIQUE,
  categoryId  INT NOT NULL,
  st_price    INT NOT NULL,
  path        VARCHAR(128) UNIQUE,
  dt_add      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  dt_end      TIMESTAMP NOT NULL,
  text        TEXT NOT NULL,
  bet_step    INT NOT NULL,
  autor_id    INT NOT NULL,
  winner_id   INT DEFAULT NULL,
  FOREIGN KEY (categoryId) REFERENCES categories(id),
  FOREIGN KEY (autor_id) REFERENCES users(id),
  FOREIGN KEY (winner_id) REFERENCES users(id)
);

CREATE TABLE bets (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  dt_add      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  value       INT NOT NULL,
  user_id     INT NOT NULL,
  lot_id      INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (lot_id) REFERENCES lots(id)
);

CREATE FULLTEXT INDEX lot_ft_search
ON lots(lot_name, text);
