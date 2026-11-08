USE restaurante;

-- MODIFICAR ESTRUCTURA DE USUARIO

ALTER TABLE usuario
    MODIFY nombre VARCHAR(100) NOT NULL,
    MODIFY correo VARCHAR(150) NOT NULL,
    MODIFY password VARCHAR(255) NOT NULL,
    MODIFY rol ENUM('admin', 'empleado', 'cliente') NOT NULL,
    ADD COLUMN telefono VARCHAR(20) NULL,  
    ADD COLUMN fechaRegistro DATETIME
        NOT NULL DEFAULT CURRENT_TIMESTAMP;

-- CREAR TABLA DE CONSENTIMIENTO

CREATE TABLE consentimientoDatos (
    idConsentimiento INT AUTO_INCREMENT PRIMARY KEY,
    idUsuario INT NOT NULL,
    versionPolitica VARCHAR(20) NOT NULL,
    fechaConsentimiento DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    aceptado TINYINT(1) NOT NULL,
    fechaRevocacion DATETIME DEFAULT NULL,

    CONSTRAINT fk_consentimiento_usuario
    FOREIGN KEY (idUsuario)
    REFERENCES usuario(idUsuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;