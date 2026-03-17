-- ==========================
-- Tabla de Clientes
-- ==========================
CREATE TABLE Clientes (
    cliente_id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100) NOT NULL,
    documento_identidad VARCHAR(20) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    email VARCHAR(100) UNIQUE,
    direccion VARCHAR(150)
);

-- ==========================
-- Tabla de Modelos (Catálogo)
-- ==========================
CREATE TABLE Modelos (
    modelo_id INT PRIMARY KEY AUTO_INCREMENT,
    marca VARCHAR(50) NOT NULL,
    modelo VARCHAR(50) NOT NULL,
    UNIQUE (marca, modelo)
);

-- ==========================
-- Tabla de Vehículos
-- ==========================
CREATE TABLE Vehiculos (
    vehiculo_id INT PRIMARY KEY AUTO_INCREMENT,
    modelo_id INT NOT NULL,
    anio INT NOT NULL,
    color VARCHAR(30),
    numero_serie VARCHAR(50) UNIQUE NOT NULL,
    precio DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (modelo_id) REFERENCES Modelos(modelo_id)
);

-- ==========================
-- Tabla de Ventas
-- ==========================
CREATE TABLE Ventas (
    venta_id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    vehiculo_id INT NOT NULL,
    fecha_venta DATE NOT NULL,
    precio_final DECIMAL(12,2) NOT NULL,
    metodo_pago VARCHAR(50),
    FOREIGN KEY (cliente_id) REFERENCES Clientes(cliente_id),
    FOREIGN KEY (vehiculo_id) REFERENCES Vehiculos(vehiculo_id)
);