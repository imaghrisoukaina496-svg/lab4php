<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
-- 001_create_db.sql
-- Création base + tables + contraintes + données de démonstration
DROP DATABASE IF EXISTS gestion_etudiants_pdo;
CREATE DATABASE gestion_etudiants_pdo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_etudiants_pdo;

-- Table filiere
CREATE TABLE filiere (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(16) NOT NULL,
  libelle VARCHAR(100) NOT NULL,
  CONSTRAINT uq_filiere_code UNIQUE (code)
) ENGINE=InnoDB;

-- Table etudiant
CREATE TABLE etudiant (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cne VARCHAR(20) NOT NULL,
  nom VARCHAR(80) NOT NULL,
  prenom VARCHAR(80) NOT NULL,
  email VARCHAR(120) NOT NULL,
  filiere_id INT NOT NULL,
  CONSTRAINT fk_etudiant_filiere FOREIGN KEY (filiere_id) REFERENCES filiere(id)
    ON UPDATE CASCADE ON DELETE RESTRICT,
  CONSTRAINT uq_etudiant_cne UNIQUE (cne),
  CONSTRAINT uq_etudiant_email UNIQUE (email)
) ENGINE=InnoDB;

-- Données de démonstration optionnelles
INSERT INTO filiere(code, libelle) VALUES
('INFO', 'Informatique'),
('MATH', 'Mathématiques');

INSERT INTO etudiant(cne, nom, prenom, email, filiere_id) VALUES
('CNE0001', 'Durand', 'Alice', 'alice@example.com', 1);

