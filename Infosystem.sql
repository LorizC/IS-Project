CREATE DATABASE Infosystem;

USE Infosystem;

CREATE TABLE Users (
  user_id INT(6) AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  birthdate DATE NOT NULL,
  security_question VARCHAR(255) NOT NULL,
  security_answer VARCHAR(255) NOT NULL,
  user_password VARCHAR(255) NOT NULL
);

CREATE TABLE Finance (
  finance_id(6) INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  budget DECIMAL(10, 2) NOT NULL,
  expenses DECIMAL(10, 2) NOT NULL,
  savings_goal DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES Users(user_id)
);
