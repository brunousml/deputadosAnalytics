#SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
#SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
#SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

CREATE TABLE IF NOT EXISTS deputado (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    matricula VARCHAR(255) NOT NULL ,
    nome VARCHAR(255) NOT NULL,
    identificacao VARCHAR(255) NOT NULL ,
    numero VARCHAR(255) NOT NULL ,
    partido VARCHAR(6) NOT NULL ,
    estado CHAR(2) NOT NULL   
);

CREATE TABLE IF NOT EXISTS presencasessao (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    deputadoId INT NOT NULL ,
    data DATE NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    comportamento VARCHAR(255) NOT NULL ,
    justificativa VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS `presencacomissao` (
  id int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  deputadoId int(11) NOT NULL,
  data date NOT NULL,
  titulo varchar(255) NOT NULL,
  tipo varchar(255) NOT NULL,
  comportamento varchar(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS legislatura (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
    numero INT(5) NOT NULL ,
    atual INT(1) NOT NULL,
    data DATE NOT NULL    
);

INSERT INTO legislatura VALUES ('', 54, 1, '2012-02-02');


#SET SQL_MODE=@OLD_SQL_MODE;
#SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
#SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
