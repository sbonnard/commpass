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
VALUES ("Toile de Com"), ("FakeBusiness"), ("Luminase"), ("Groupe Pignon"), ("Nerexam Solutions");

INSERT INTO brand (brand_name) 
VALUES ("Lumosphère"), ("Vélocitix"), ("Stellar Threads"), ("Aurélys"), ("Nexmus"), ("Cafés Geronimo"), ("Fripig"), ("Maxstock");

INSERT INTO users (username, firstname, lastname, password, email, phone, client, id_company)
VALUES ("sbonnard94", "Sébastien", "Bonnard", "$2y$10$ZMkpWcRvhkY0PHUZPlb8COU3sCBTRqIKdvvK4sZd2U84wH2HHNPwK", "sebastien.bonnard94@gmail.com", "0608118078", 0, 1);

INSERT INTO users (username, firstname, lastname, password, email, phone, client, id_company)
VALUES 
("mhamelin4", "Marius", "Hamelin", "$2y$10$ZMkpWcRvhkY0PHUZPlb8COU3sCBTRqIKdvvK4sZd2U84wH2HHNPwK", "marius.hamelin@luminase.com", "0600102030", 1, 3),
("ppignon5", "Pascale", "Pignon", "$2y$10$ZMkpWcRvhkY0PHUZPlb8COU3sCBTRqIKdvvK4sZd2U84wH2HHNPwK", "pascale.pignon@pignon-group.com", "0600102030", 1, 4)
;

INSERT INTO users (username, firstname, lastname, password, email, phone, client, id_company)
VALUES 
("alemaitre2", "Alain", "Lemaître", "$2y$10$ebV/iVLbDG46ifm89nk4Me49kWhpbjiZ7Kx2qKt2Q8Fd4HN66B/3W", "alain.lemaitre@comtogether.com", "0600102030", 0, 1),
("jcarriere3", "Julie", "Carrière", "$2y$10$cCubd56otzIKiNdKRj3i.u4Crxaxz586Ygn5QmVszFF91z2SgMqFS", "julie.carriere@fakebusiness.com", "0600102030", 1, 1);

INSERT INTO target (target_com)
VALUES ("Faire connaître"), ("Faire aimer"), ("Faire agir");

INSERT INTO partner_sector (sector)
VALUES ("presse"), ("radio");

INSERT INTO partner (partner_name, id_partner_sector)
VALUES ("La Manche Libre", 1), ("Ouest France", 1), ("Tendance Ouest", 2);

INSERT INTO campaign (campaign_name, budget, date, id_user, id_target, id_company)
VALUES ("Soldes d'été", 25000, "2024-06-27", 3, 3, 2),
("Promos d'hiver", 18000, "2023-11-14", 3, 3, 2),
("Tous plus verts", 25000, "2024-02-01", 3, 2, 2);

INSERT INTO campaign (campaign_name, budget, date, id_user, id_target, id_company)
VALUES ("Salon du luminaire", 178000, "2022-05-05", 4, 1, 3),
("Lancement Groupe Pignon", 21000, "2023-05-14", 5, 1, 4),
("Tous plus verts", 25000, "2022-02-01", 3, 2, 2);

INSERT INTO operation (description, price, date_, id_campaign)
VALUES 
("Impression et livraison des PLV", 4725.95, "2024-06-29", 1),
("Impression et livraison des PLV", 4725.95, "2023-11-29", 2),
("Flocage de totebags", 690, "2024-07-12", 1),
("Flocage de totebags", 690, "2023-11-12", 2),
("Flyers soldes d'été, 1000 exemplaires", 300.25, "2024-06-12", 1),
("Flyers soldes d'hiver, 1000 exemplaires", 300.25, "2023-11-12", 2),
("Vitrine web mise à jour", 227.92, "2024-06-29", 1),
("Vitrine web mise à jour", 227.92, "2023-11-29", 2),
("Avatars du personnel", 205, "2024-01-15", 3),
("Encart presse dans la Manche Libre", 75, "2024-02-05", 3),
("Réseaux sociaux", 144.85, "2024-03-02", 3);

INSERT INTO operation_brand (id_operation, id_brand)
VALUES 
(1, 1),
(1, 2),
(1, 3),
(2, 1),
(2, 2),
(2, 4),
(3, 4),
(3, 3),
(4, 4),
(4, 1),
(5, 1),
(6, 1),
(7, 2),
(7, 3),
(8, 1),
(8, 4),
(9, 1),
(10, 1),
(11, 1);
