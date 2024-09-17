USE hospital;

CREATE TABLE habitacion
(      
    id_habitacion INTEGER PRIMARY KEY AUTO_INCREMENT,
    nombre_habitacion VARCHAR(45),
    numero_habitacion INTEGER
);

CREATE TABLE paciente
(      
    id_paciente INTEGER PRIMARY KEY AUTO_INCREMENT,
    nombre_paciente VARCHAR(50) DEFAULT NULL,
    apellido_paciente VARCHAR(50) DEFAULT NULL,
	direccion_paciente VARCHAR(100) DEFAULT NULL
);

INSERT INTO habitacion (id_habitacion, nombre_habitacion, numero_habitacion) VALUES (1,'Amanecer',150);
INSERT INTO habitacion (id_habitacion, nombre_habitacion, numero_habitacion) VALUES (2,'Atardecer',80);
INSERT INTO habitacion (id_habitacion, nombre_habitacion, numero_habitacion) VALUES (3,'Anochecer',138);

INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (1,'Juan','Pérez','Calle Falsa 123');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (2,'María','Gómez','Avenida Siempreviva 742');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (3,'Carlos','Rodríguez','Calle de la Luna 456');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (4,'Ana','López','Calle del Sol 789');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (5,'Luis','Martínez','Calle de las Flores 101');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (6,'Laura','García','Calle de los Pinos 202');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (7,'José','Hernández','Calle de los Olivos 303');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (8,'Marta','Fernández','Calle de los Robles 404');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (9,'Pedro','Sánchez','Calle de los Cedros 505');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (10,'Lucía','Ramírez','Calle de los Álamos 606');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (11,'Miguel','Torres','Calle de los Sauces 707');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (12,'Elena','Díaz','Calle de los Nogales 808');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (13,'Javier','Vázquez','Calle de los Castaños 909');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (14,'Sofía','Morales','Calle de los Abetos 1010');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (15,'Fernando','Ruiz','Calle de los Laureles 1111');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (16,'Isabel','Jiménez','Calle de los Cipreses 1212');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (17,'Raúl','Molina','Calle de los Eucaliptos 1313');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (18,'Patricia','Ortiz','Calle de los Fresnos 1414');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (19,'Alberto','Castro','Calle de los Álamos 1515');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (20,'Cristina','Núñez','Calle de los Olmos 1616');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (21,'Ricardo','Romero','Calle de los Pinos 1717');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (22,'Teresa','Santos','Calle de los Cedros 1818');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (23,'Andrés','Iglesias','Calle de los Robles 1919');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (24,'Beatriz','Ramos','Calle de los Sauces 2020');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (25,'Francisco','Gil','Calle de los Nogales 2121');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (26,'Natalia','Cruz','Calle de los Castaños 2222');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (27,'Manuel','Flores','Calle de los Abetos 2323');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (28,'Rosa','Herrera','Calle de los Laureles 2424');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (29,'Sergio','Marín','Calle de los Cipreses 2525');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (30,'Clara','Pascual','Calle de los Eucaliptos 2626');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (31,'Adrián','Serrano','Calle de los Fresnos 2727');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (32,'Eva','Blanco','Calle de los Álamos 2828');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (33,'Pablo','Navarro','Calle de los Olmos 2929');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (34,'Alicia','Méndez','Calle de los Pinos 3030');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (35,'Hugo','Rojas','Calle de los Cedros 3131');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (36,'Sandra','Cabrera','Calle de los Robles 3232');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (37,'David','Campos','Calle de los Sauces 3333');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (38,'Silvia','Peña','Calle de los Nogales 3434');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (39,'Daniel','Vega','Calle de los Castaños 3535');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (40,'Paula','Soto','Calle de los Abetos 3636');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (41,'Álvaro','Suárez','Calle de los Laureles 3737');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (42,'Lorena','Delgado','Calle de los Cipreses 3838');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (43,'Jorge','Fuentes','Calle de los Eucaliptos 3939');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (44,'Carmen','Carrillo','Calle de los Fresnos 4040');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (45,'Iván','Aguilar','Calle de los Álamos 4141');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (46,'Verónica','Prieto','Calle de los Olmos 4242');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (47,'Óscar','Lara','Calle de los Pinos 4343');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (48,'Mónica','Mora','Calle de los Cedros 4444');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (49,'Rubén','Santiago','Calle de los Robles 4545');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (50,'Nuria','Vargas','Calle de los Sauces 4646');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (51,'Tomás','Reyes','Calle de los Nogales 4747');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (52,'Inés','Campos','Calle de los Castaños 4848');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (53,'Gonzalo','Medina','Calle de los Abetos 4949');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (54,'Elisa','Sanz','Calle de los Laureles 5050');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (55,'Víctor','León','Calle de los Cipreses 5151');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (56,'Rocío','Cortés','Calle de los Eucaliptos 5252');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (57,'Félix','Santos','Calle de los Fresnos 5353');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (58,'Marina','Pérez','Calle de los Álamos 5454');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (59,'Ángel','García','Calle de los Olmos 5555');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (60,'Gloria','Martín','Calle de los Pinos 5656');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (61,'Emilio','López','Calle de los Cedros 5757');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (62,'Raquel','Díaz','Calle de los Robles 5858');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (63,'Eduardo','Gómez','Calle de los Sauces 5959');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (64,'Irene','Rodríguez','Calle de los Nogales 6060');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (65,'Mario','Fernández','Calle de los Castaños 6161');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (66,'Julia','Sánchez','Calle de los Abetos 6262');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (67,'Antonio','Ruiz','Calle de los Laureles 6363');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (68,'Sara','Jiménez','Calle de los Cipreses 6464');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (69,'Guillermo','Molina','Calle de los Eucaliptos 6565');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (70,'Lidia','Ortiz','Calle de los Fresnos 6666');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (71,'Vicente','Castro','Calle de los Álamos 6767');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (72,'Celia','Núñez','Calle de los Olmos 6868');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (73,'Esteban','Romero','Calle de los Pinos 6969');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (74,'Miriam','Santos','Calle de los Cedros 7070');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (75,'Ramón','Iglesias','Calle de los Robles 7171');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (76,'Ainhoa','Ramos','Calle de los Sauces 7272');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (77,'Gabriel','Gil','Calle de los Nogales 7373');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (78,'Noelia','Cruz','Calle de los Castaños 7474');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (79,'Alfonso','Flores','Calle de los Abetos 7575');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (80,'Nerea','Herrera','Calle de los Laureles 7676');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (81,'Salvador','Marín','Calle de los Cipreses 7777');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (82,'Olga','Pascual','Calle de los Eucaliptos 7878');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (83,'Martín','Serrano','Calle de los Fresnos 7979');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (84,'Ariadna','Blanco','Calle de los Álamos 8080');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (85,'Héctor','Navarro','Calle de los Olmos 8181');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (86,'Adela','Méndez','Calle de los Pinos 8282');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (87,'Iván','Rojas','Calle de los Cedros 8383');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (88,'Nuria','Cabrera','Calle de los Robles 8484');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (89,'Joaquín','Campos','Calle de los Sauces 8585');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (90,'Alicia','Peña','Calle de los Nogales 8686');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (91,'Marcos','Vega','Calle de los Castaños 8787');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (92,'Elena','Soto','Calle de los Abetos 8888');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (93,'Roberto','Suárez','Calle de los Laureles 8989');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (94,'Claudia','Delgado','Calle de los Cipreses 9090');
INSERT INTO paciente (id_paciente, nombre_paciente, apellido_paciente, direccion_paciente) VALUES (95,'Luis','Fuentes','Calle de los Eucaliptos 9191');
