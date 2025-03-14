--SELECT VERSION();

--SHOW GRANTS FOR 'root'@'localhost';

--FLUSH PRIVILEGES;

--SHOW VARIABLES LIKE '%file%';

--SHOW VARIABLES LIKE 'secure-file-priv';
CREATE DATABASE StudentDB;

USE StudentDB;

CREATE TABLE `NameTable` (
    `StudentId` BIGINT PRIMARY KEY,
    `StudentName` VARCHAR(255) NOT NULL
);

CREATE TABLE `CourseTable` (
    `StudentID` BIGINT,
    `CourseCode` VARCHAR(10),
    `Grade1` INT,
    `Grade2` INT,
    `Grade3` INT,
    `Grade4` INT,
    PRIMARY KEY (`StudentID`, `CourseCode`),
    FOREIGN KEY (`StudentID`) REFERENCES `NameTable`(`StudentID`)
);

FLUSH PRIVILEGES;
-- Load data into NameTable
LOAD DATA INFILE '/Users/nataliesong/mysql_data/NameTable.txt'
INTO TABLE NameTable
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(StudentID, StudentName);

-- Load data into CourseTable
LOAD DATA INFILE '/Users/nataliesong/mysql_data/Coursetable.txt'
INTO TABLE CourseTable
FIELDS TERMINATED BY ',' 
LINES TERMINATED BY '\n'
(StudentID, CourseCode, Grade1, Grade2, Grade3, Grade4);

SELECT * FROM NameTable;
SELECT * FROM CourseTable;

SHOW GRANTS FOR 'root'@'localhost';

DESCRIBE NameTable;
DESCRIBE CourseTable;