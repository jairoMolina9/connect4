USE spykegtp_connectfour;

CREATE TABLE users (
  userID int(50) AUTO_INCREMENT PRIMARY KEY,
  username varchar(50),
  password varchar(50),
  wins int(50),
  losses int(50)
);
