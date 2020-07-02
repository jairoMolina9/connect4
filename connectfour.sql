USE spykegtp_connectfour;

CREATE TABLE Users (
  userID int(50) AUTO_INCREMENT PRIMARY KEY,
  username varchar(50),
  password varchar(50),
);

CREATE TABLE Games (
  gameID int(50) PRIMARY KEY,
  userID int(50) FOREIGN KEY REFERENCES Users(userID),
  winner varchar(50)
);

CREATE TABLE Moves (
  gameID int(50) PRIMARY KEY,
  coordinate CHAR(3),
  playerID varchar(50),
  FOREIGN KEY (gameID) REFERENCES Games(gameID)
);
