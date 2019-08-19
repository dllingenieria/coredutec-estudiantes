DELIMITER $$
CREATE DEFINER=`dit`@`%` PROCEDURE `SPCONSULTARCALENDARIOPREPROGRAMACION`(IN pIdPreprogramacion INT)
    COMMENT 'Definicion: Retonar la fecha inicial, final, y los días de una preprogramación - Creado por: Juan Herrera - Fecha de creacion: 23 de Abril de 2016'
BEGIN
	SELECT pre.FechaInicial, pre.FechaFinal, par.Nombre
    FROM `CET.DIT`.`TPREPROGRAMACION` pre INNER JOIN `CET.DIT`.`TPARAMETRO` par ON pre.DiasCurso=par.Id
    WHERE pre.Id=pIdPreprogramacion;
END$$
DELIMITER ;


