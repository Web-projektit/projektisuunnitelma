CREATE TABLE Roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nimi VARCHAR(50),
    arvo INT
);

INSERT INTO Roles (nimi,arvo) VALUES ('user',1),('mainuser',2),('viewer',4),('editor',8),('admin',16);

CREATE TABLE Users (
    id INT(9) PRIMARY KEY AUTO_INCREMENT,
    firstname VARCHAR(50),
    lastname VARCHAR(50),
    username VARCHAR(50),
    email VARCHAR(100),
    password VARCHAR(255),
    image VARCHAR(100 ),
    is_active BOOLEAN DEFAULT TRUE,
    role_id INT,
    created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES Roles(id)
);


-- Projekti-taulu
CREATE TABLE Projektit (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    nimi VARCHAR(255),
    kuvaus TEXT,
    asemointi TEXT,
    rajaus TEXT,
    aloitus DATE,
    lopetus DATE,
    updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id)
);

CREATE TABLE Comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    project_id INT,
    user_id INT,
    username VARCHAR(50),
    field VARCHAR(50),
    comment TEXT,
    updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES Projektit(id),
    FOREIGN KEY (user_id) REFERENCES Users(id)
);

-- Työkalut-taulu
CREATE TABLE Tyokalut (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tyokalu VARCHAR(255),
    kategoria ENUM('Selain', 'Palvelin', 'Tietokanta', 'Pilvipalvelin', 'Kehityspalvelin', 'Sähköpostipalvelin', 'Ohjelmointi', 'Versiohallinta', 'Tekoäly')
);

-- ProjektitTyokalut-taulu (suhde taulu)
CREATE TABLE Projektit_tyokalut (
    projekti_id INT,
    tyokalu_id INT,
    FOREIGN KEY (projekti_id) REFERENCES Projektit(id),
    FOREIGN KEY (tyokalu_id) REFERENCES Tyokalut(id),
    PRIMARY KEY(projekti_id, tyokalu_id)
);
