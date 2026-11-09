--------------------------------------------------------------------
-- Cambios para Pedidos
--------------------------------------------------------------------

USE `restaurante`;

-- PASO 0. Limpiar pedidos de prueba (todavía no hay pedidos reales)
DELETE FROM `pago`;
DELETE FROM `detallePedido`;
DELETE FROM `pedido`;

ALTER TABLE `pago`          AUTO_INCREMENT = 1;
ALTER TABLE `detallePedido` AUTO_INCREMENT = 1;
ALTER TABLE `pedido`        AUTO_INCREMENT = 1;

-- PASO 1. Tabla pedido
ALTER TABLE `pedido` MODIFY `idMesa` INT(11) NOT NULL;

ALTER TABLE `pedido` DROP COLUMN IF EXISTS `fecha`;
ALTER TABLE `pedido` DROP COLUMN IF EXISTS `hora`;

ALTER TABLE `pedido` MODIFY `estado` TINYINT(4) NOT NULL DEFAULT 1;

ALTER TABLE `pedido` ADD COLUMN IF NOT EXISTS `nombre` VARCHAR(60) NOT NULL AFTER `idMesa`;
ALTER TABLE `pedido` ADD COLUMN IF NOT EXISTS `idEmpleado` INT(11) DEFAULT NULL AFTER `idUsuario`;
ALTER TABLE `pedido` ADD COLUMN IF NOT EXISTS `fechaHoraInicio` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

ALTER TABLE `pedido` CHANGE COLUMN IF EXISTS `fechaHoraTomado` `fechaHoraConfirmado` DATETIME DEFAULT NULL;
ALTER TABLE `pedido` ADD COLUMN IF NOT EXISTS `fechaHoraConfirmado` DATETIME DEFAULT NULL;

ALTER TABLE `pedido` DROP FOREIGN KEY IF EXISTS `pedido_ibfk_3`;
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_3` FOREIGN KEY (`idEmpleado`) REFERENCES `usuario` (`idUsuario`);

-- PASO 2. Tabla detallePedido
ALTER TABLE `detallePedido` MODIFY `idPedido`   INT(11) NOT NULL;
ALTER TABLE `detallePedido` MODIFY `idProducto` INT(11) NOT NULL;
ALTER TABLE `detallePedido` ADD COLUMN IF NOT EXISTS `estado` TINYINT(4) NOT NULL DEFAULT 1;

-- PASO 3. Usuario empleado de prueba (solo si no existe): empleado@pjf.com / Emp123
INSERT INTO `usuario` (`nombre`, `correo`, `password`, `rol`, `estado`)
SELECT 'Empleado de prueba', 'empleado@pjf.com',
       '$2y$10$Y.Ew89OtywHzUbkqrXHRMeCKOIgELCeIEYIemLwQbTMtwDb8juknK', 'empleado', 'activo'
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `usuario` WHERE `correo` = 'empleado@pjf.com');