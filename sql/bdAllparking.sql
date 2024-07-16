CREATE DATABASE allparking;

USE allparking;

CREATE TABLE tbPreco (
    id INT PRIMARY KEY AUTO_INCREMENT,
    primeiraHora DECIMAL (10,2) NOT NULL,
    demaisHoras DECIMAL (10,2) NOT NULL
);

CREATE TABLE tbRegistro (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomeCliente VARCHAR(255) NOT NULL,
    placaCarro VARCHAR(10) NOT NULL,
    dataHoraEntrada DATETIME NOT NULL,
    dataHoraSaida DATETIME,
    valorTotal DECIMAL (10,2),
    precoId INT,
    FOREIGN KEY (precoId) REFERENCES tbPreco(id)
);

USE allparking;

SELECT * FROM tbPreco;

SELECT * FROM tbRegistro;