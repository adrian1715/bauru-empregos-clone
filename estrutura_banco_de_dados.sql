CREATE DATABASE IF NOT EXISTS bauru_empregos_clone;

CREATE TABLE IF NOT EXISTS vagas (
    id int not null primary key AUTO_INCREMENT,
    cargo varchar(255),
    empresa varchar(255),
    descricao text,
    cidade varchar(255),
    estado char(2),
    contato varchar(255),
    data date DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS vagas_pendentes (
    id int not null primary key AUTO_INCREMENT,
    cargo varchar(255),
    empresa varchar(255),
    descricao text,
    cidade varchar(255),
    estado char(2),
    contato varchar(255),
    data date DEFAULT CURRENT_TIMESTAMP
);