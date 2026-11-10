USE `restaurante`;

-- Para llevar: idMesa acepta NULL (NULL = sin mesa = para llevar)
ALTER TABLE `pedido` MODIFY `idMesa` INT(11) NULL;

-- Hora de recogida (NULL = inmediato)
ALTER TABLE `pedido`
  ADD COLUMN IF NOT EXISTS `fechaHoraProgramada` DATETIME DEFAULT NULL AFTER `fechaHoraConfirmado`;

-- Reserva desde la que el cliente hace su pedido
ALTER TABLE `pedido`
  ADD COLUMN IF NOT EXISTS `idReserva` INT(11) DEFAULT NULL;

ALTER TABLE `pedido` DROP FOREIGN KEY IF EXISTS `fk_pedido_reserva`;
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_reserva` FOREIGN KEY (`idReserva`) REFERENCES `reserva` (`idReserva`);

SHOW CREATE TABLE `pedido`;