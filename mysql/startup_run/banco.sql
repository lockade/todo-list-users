DROP DATABASE IF EXISTS project;
CREATE DATABASE `project` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

use project;

-- project.users definition

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(100) NOT NULL,
  `telefone` varchar(100) DEFAULT NULL,
  `genero` tinyint DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- project.task definition

CREATE TABLE `task` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `descricao` text,
  `data_expiracao` datetime DEFAULT NULL,
  `data_conclusao` datetime DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `task_FK` (`user_id`),
  CONSTRAINT `task_FK` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--  Auto-generated SQL script #202205161849
INSERT INTO project.users (nome,email,senha,telefone,genero,username,admin)
	VALUES ('admin','admin@admin.com','7c4a8d09ca3762af61e59520943dc26494f8941b','16993386693',1,'admin',1);
