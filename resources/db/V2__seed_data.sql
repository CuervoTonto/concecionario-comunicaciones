-- ==========================
-- Clientes
-- ==========================
INSERT INTO Clientes (nombre, apellido, documento_identidad, telefono, email, direccion) VALUES
('Carlos',    'Ramírez',   '1020345678', '3101234567', 'carlos.ramirez@gmail.com',   'Cra. 15 #45-20, Bogotá'),
('Valentina', 'Torres',    '1032456789', '3152345678', 'valentina.torres@gmail.com', 'Cll. 80 #12-35, Medellín'),
('Andrés',    'Morales',   '1045678901', '3003456789', 'andres.morales@hotmail.com', 'Av. 6N #23-10, Cali'),
('Luisa',     'Fernández', '1058901234', '3174567890', 'luisa.fernandez@gmail.com',  'Cll. 5 #8-60, Barranquilla'),
('Miguel',    'Ospina',    '1067890123', '3205678901', 'miguel.ospina@outlook.com',  'Cra. 27 #15-44, Bucaramanga'),
('Daniela',   'Castillo',  '1076543210', '3116789012', 'daniela.castillo@gmail.com', 'Cll. 12 #3-90, Pereira'),
('Sebastián', 'Vargas',    '1089012345', '3187890123', 'sebastian.vargas@gmail.com', 'Cra. 9 #55-18, Manizales'),
('Camila',    'Herrera',   '1091234567', '3228901234', 'camila.herrera@hotmail.com', 'Av. El Dorado #68-50, Bogotá');

-- ==========================
-- Modelos
-- ==========================
INSERT INTO Modelos (marca, modelo) VALUES
('Toyota',    'Corolla'),
('Toyota',    'Hilux'),
('Chevrolet', 'Spark'),
('Chevrolet', 'Tracker'),
('Renault',   'Logan'),
('Renault',   'Duster'),
('Mazda',     'CX-5'),
('Kia',       'Sportage'),
('Nissan',    'Frontier'),
('Ford',      'Explorer');

-- ==========================
-- Vehículos
-- (modelo_id sigue el orden de inserción de Modelos)
-- ==========================
INSERT INTO Vehiculos (modelo_id, anio, color, numero_serie, precio) VALUES
(1,  2023, 'Blanco',   '1NXBR32E03Z123456', 85000000.00),   -- Toyota Corolla 2023
(1,  2024, 'Gris',     '1NXBR32E04Z234567', 92000000.00),   -- Toyota Corolla 2024
(2,  2023, 'Plata',    'MR0EX32G03P345678', 135000000.00),  -- Toyota Hilux 2023
(3,  2024, 'Rojo',     'KL1TD56E04B456789', 48000000.00),   -- Chevrolet Spark 2024
(4,  2023, 'Negro',    'KL1RB5EH03B567890', 98000000.00),   -- Chevrolet Tracker 2023
(5,  2022, 'Blanco',   'VF1KM1B0H56789012', 52000000.00),   -- Renault Logan 2022
(6,  2023, 'Azul',     'VF1HSRJF03Y890123', 115000000.00),  -- Renault Duster 2023
(7,  2024, 'Gris',     'JM3KE4DY04P901234', 175000000.00),  -- Mazda CX-5 2024
(8,  2023, 'Blanco',   'KNDPB3A20D012345',  145000000.00),  -- Kia Sportage 2023
(9,  2022, 'Negro',    '1N6AD0EV02C123456', 155000000.00),  -- Nissan Frontier 2022
(10, 2023, 'Azul',     '1FM5K8D83PGA34567', 210000000.00);  -- Ford Explorer 2023

-- ==========================
-- Ventas
-- (cada vehiculo_id se vende una sola vez para mantener coherencia)
-- ==========================
INSERT INTO Ventas (cliente_id, vehiculo_id, fecha_venta, precio_final, metodo_pago) VALUES
(1, 1,  '2025-01-15', 83000000.00,  'Contado'),
(2, 4,  '2025-02-03', 48000000.00,  'Crédito'),
(3, 7,  '2025-02-20', 112000000.00, 'Leasing'),
(4, 9,  '2025-03-08', 145000000.00, 'Crédito'),
(5, 3,  '2025-04-11', 133000000.00, 'Contado'),
(6, 6,  '2025-05-22', 51000000.00,  'Transferencia'),
(7, 10, '2025-06-14', 205000000.00, 'Leasing'),
(8, 5,  '2025-07-30', 96000000.00,  'Crédito'),
(1, 8,  '2025-09-05', 172000000.00, 'Contado'),
(3, 2,  '2025-10-18', 90000000.00,  'Transferencia');
