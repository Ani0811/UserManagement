CREATE TABLE `user_mast` (
  `USER_ID` int NOT NULL AUTO_INCREMENT,
  `USER_NAME` varchar(30) DEFAULT NULL,
  `USER_LOC` varchar(50) DEFAULT NULL,
  `USER_EMAIL` varchar(30) DEFAULT NULL,
  `USER_DOB` date DEFAULT NULL,
  `USER_TYPE` varchar(10) DEFAULT NULL,
  `ACTIVE` varchar(5) DEFAULT NULL,
  `USER_PASSWORD` longtext CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  PRIMARY KEY (`USER_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

