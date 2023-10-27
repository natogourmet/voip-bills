USE BillsPayingSystem;
CREATE TABLE bills (
	bill_id INT NOT NULL AUTO_INCREMENT,
	user_id VARCHAR(5),
	bill_value  INT(10),
	balance INT(10),
	PRIMARY KEY (bill_id)
);

-- iPhone
INSERT INTO bills VALUES (1, 565, 1000, 800 );

-- Motorcycle
INSERT INTO bills VALUES (2, 838, 10000, 3000 );

-- PS5
INSERT INTO bills VALUES (3, 240, 500, 450 );

-- House
INSERT INTO bills VALUES (4, 758, 300000, 100000 );

-- ROLAND FP-30X
INSERT INTO bills VALUES (5, 810, 800, 200 );
