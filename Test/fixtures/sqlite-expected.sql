-- vim:ts=3:sts=3:sw=3:et

-- This file contains the expected results from
-- MindFrame2_Adapter_Database_ToSql_MysqlTest. This file is interpreted in a similar
-- fasion as ini files except in this case, white-space is important. A new
-- "section" is denoted with: "-- //* ", followed by the name of the test,
-- followed by "*//" and a blank line. All lines following that point are read
-- verbatim until a blank line is encountered followed by a new section
-- indicator.

-- //* testBuildCreateDatabaseSql *//

ATTACH DATABASE ":memory:" AS `Test_Database`;

CREATE TABLE `Test_Database`.`User` (
  `User_Id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `Username` TEXT NOT NULL,
  `Display_Name` TEXT DEFAULT NULL,
  `Email_Address` TEXT NOT NULL,
  `Last_Login` TEXT DEFAULT NULL,
  `Login_Count` INTEGER NOT NULL DEFAULT '0',
  `Status` INTEGER NOT NULL DEFAULT '1',
  `Fk_User_Id_Supervisor` INTEGER DEFAULT NULL
);
CREATE UNIQUE INDEX `Test_Database`.`Ix_Unique_Username` ON `User` (`Username`);
CREATE INDEX `Test_Database`.`Ix_Supervisor` ON `User` (`Fk_User_Id_Supervisor`);

CREATE TABLE `Test_Database`.`Password` (
  `Username` TEXT NOT NULL,
  `Ciphertext` TEXT NOT NULL,
  `Iv` TEXT NOT NULL,
  PRIMARY KEY (`Username`)
);

-- //* testBuildDeleteTableSql *//

DELETE FROM `Test_Database`.`User`
WHERE
  `User_Id` = '1';

-- //* testBuildDropDatabaseSql *//

DROP DATABASE `Test_Database`;

-- //* testBuildDropTemporaryTableSql *//

DROP TEMPORARY TABLE `Test_Database`.`#Test`;

-- //* testBuildGrantAllSql *//

GRANT ALL ON `Test_Database`.* TO 'User'@'localhost' IDENTIFIED BY 'Pass';

-- //* testBuildInsertTableSql *//

INSERT INTO `Test_Database`.`User`
(`Username`, `Display_Name`, `Email_Address`, `Last_Login`, `Login_Count`, `Status`, `Fk_User_Id_Supervisor`)
VALUES('Test', NULL, NULL, '2010-01-01 22:55:33', 8, NULL, NULL);

-- //* testBuildSelectIntoTemporaryTableSql *//

CREATE TEMPORARY TABLE `Test_Database`.`#Test` ENGINE=MEMORY
SELECT Col1, Col2 FROM Test;

-- //* testBuildSelectTableSql *//

SELECT
  `User`.`User_Id`,
  `User`.`Username`,
  `User`.`Display_Name`,
  `User`.`Email_Address`,
  `User`.`Last_Login`,
  `User`.`Login_Count`,
  BIN(`User`.`Status`) AS Status,
  `User`.`Fk_User_Id_Supervisor`,
  `Fk_Supervisor`.`Username` AS `Fk_Supervisor:Username`
FROM
  `Test_Database`.`User`
LEFT OUTER JOIN
  `Test_Database`.`User` AS `Fk_Supervisor` ON `User`.`Fk_User_Id_Supervisor` = `Fk_Supervisor`.`User_Id`
WHERE
  `User`.`User_Id` <= '100'
  AND `User`.`Username` = 'Test''s'
  AND `User`.`Email_Address` = '54.66'
  AND `User`.`Last_Login` BETWEEN '2010-01-01' AND '2010-01-15'
  AND `User`.`Login_Count` > '5'
  AND `User`.`Status` IN ('Active', '5', 'Inactive')
  AND `User`.`Fk_User_Id_Supervisor` IS NULL
  AND `Fk_Supervisor`.`Username` LIKE 'Test%'
ORDER BY `User`.`Username` ASC
LIMIT 1;

