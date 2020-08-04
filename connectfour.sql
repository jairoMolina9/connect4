CREATE DATABASE connect4;

USE connect4;

CREATE TABLE Users (
  userID int(50) AUTO_INCREMENT PRIMARY KEY,
  username varchar(50),
  password varchar(50),
  wins int DEFAULT 0,
  losses int DEFAULT 0
);

CREATE TABLE Games (
  gameID BIGINT PRIMARY KEY,
  userID varchar(50),
  winner varchar(50),
  FOREIGN KEY (userID) REFERENCES Users(userID)
);

CREATE TABLE Moves (
  moveID int(50) AUTO_INCREMENT PRIMARY KEY,
  gameID BIGINT,
  coordinate CHAR(3),
  playerTurn varchar(50)
  FOREIGN KEY (gameID) REFERENCES Games(gameID)
);
