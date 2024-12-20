CREATE DATABASE academia;

USE academia;

-- Tabela de Planos
CREATE TABLE planos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL
);

-- Tabela de Alunos
CREATE TABLE alunos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    data_nascimento DATE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    endereco TEXT,
    sexo ENUM('masculino', 'feminino', 'outro') NOT NULL,
    plano_id INT,
    FOREIGN KEY (plano_id) REFERENCES planos(id)
);
