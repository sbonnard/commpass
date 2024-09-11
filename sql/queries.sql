CREATE TABLE partner_sector(
   id_partner_sector INT AUTO_INCREMENT,
   sector VARCHAR(100) NOT NULL,
   PRIMARY KEY(id_partner_sector)
);

CREATE TABLE target(
   id_target INT AUTO_INCREMENT,
   target_com VARCHAR(50) NOT NULL,
   PRIMARY KEY(id_target)
);

CREATE TABLE type_operation(
   id_type_operation INT AUTO_INCREMENT,
   operation VARCHAR(50),
   PRIMARY KEY(id_type_operation)
);

CREATE TABLE brand(
   id_brand INT AUTO_INCREMENT,
   brand_name VARCHAR(100) NOT NULL,
   PRIMARY KEY(id_brand)
);

CREATE TABLE company(
   id_company INT AUTO_INCREMENT,
   company_name VARCHAR(100) NOT NULL,
   PRIMARY KEY(id_company)
);

CREATE TABLE partner(
   id_partners INT AUTO_INCREMENT,
   partner_name VARCHAR(255) NOT NULL,
   id_partner_sector INT NOT NULL,
   PRIMARY KEY(id_partners),
   FOREIGN KEY(id_partner_sector) REFERENCES partner_sector(id_partner_sector)
);

CREATE TABLE users(
   id_user INT AUTO_INCREMENT,
   username VARCHAR(100) NOT NULL,
   firstname VARCHAR(50) NOT NULL,
   lastname VARCHAR(50) NOT NULL,
   password VARCHAR(255) NOT NULL,
   email VARCHAR(255) NOT NULL,
   phone VARCHAR(10) NOT NULL,
   client BOOLEAN NOT NULL DEFAULT 1,
   id_company INT NOT NULL,
   PRIMARY KEY(id_user),
   FOREIGN KEY(id_company) REFERENCES company(id_company)
);

CREATE TABLE campaign(
   id_campaign INT AUTO_INCREMENT,
   campaign_name VARCHAR(100) NOT NULL,
   budget DECIMAL(15,2) NOT NULL,
   id_user INT NOT NULL,
   id_target INT NOT NULL,
   PRIMARY KEY(id_campaign),
   FOREIGN KEY(id_user) REFERENCES users(id_user),
   FOREIGN KEY(id_target) REFERENCES target(id_target)
);

CREATE TABLE operation(
   id_operation INT AUTO_INCREMENT,
   description VARCHAR(255) NOT NULL,
   price DECIMAL(15,2) NOT NULL,
   date_ DATE NOT NULL,
   id_campaign INT NOT NULL,
   id_type_operation INT NOT NULL,
   PRIMARY KEY(id_operation),
   FOREIGN KEY(id_campaign) REFERENCES campaign(id_campaign),
   FOREIGN KEY(id_type_operation) REFERENCES type_operation(id_type_operation)
);

CREATE TABLE operation_partner(
   id_operation INT,
   id_partners INT,
   PRIMARY KEY(id_operation, id_partners),
   FOREIGN KEY(id_operation) REFERENCES operation(id_operation),
   FOREIGN KEY(id_partners) REFERENCES partner(id_partners)
);

CREATE TABLE operation_brand(
   id_operation INT,
   id_brand INT,
   PRIMARY KEY(id_operation, id_brand),
   FOREIGN KEY(id_operation) REFERENCES operation(id_operation),
   FOREIGN KEY(id_brand) REFERENCES brand(id_brand)
);

-- INSERTS 

INSERT INTO company (company_name) 
VALUES ("Toile de Com"), ("FakeBusiness"), ("Luminase"), ("Groupe Pignon"), ("Nerexam Solutions")