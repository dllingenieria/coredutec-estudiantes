
# Esta tabla es para almacenar las jornadas de capacitacion
#Comentario de Juan
#Linea 1, etc

CREATE TABLE `CET.DIT`.`TJORNADA` (
  `Id` INT NOT NULL AUTO_INCREMENT,
  `Nombre` VARCHAR(45) NOT NULL,
  `Dias` INT NOT NULL,
  `HoraInicial` INT NOT NULL,
  `HoralFinal` INT NOT NULL,
  `Sesiones` INT NOT NULL,
  `HorasSesion` INT NOT NULL,
  `Estado` INT NOT NULL,
  PRIMARY KEY (`Id`),
  INDEX `FKJornada - Parametros_idx` (`Dias` ASC),
  INDEX `FKJornada - HoraInicial_idx` (`HoraInicial` ASC),
  INDEX `FKJornada - HoraFinal_idx` (`HoralFinal` ASC),
  CONSTRAINT `FKJornada - DiasCurso`
    FOREIGN KEY (`Dias`)
    REFERENCES `CET.DIT`.`TPARAMETRO` (`Id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FKJornada - HoraInicial`
    FOREIGN KEY (`HoraInicial`)
    REFERENCES `CET.DIT`.`TPARAMETRO` (`Id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `FKJornada - HoraFinal`
    FOREIGN KEY (`HoralFinal`)
    REFERENCES `CET.DIT`.`TPARAMETRO` (`Id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE);
