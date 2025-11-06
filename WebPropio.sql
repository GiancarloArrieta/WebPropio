create database tickets;
use tickets;

select * from usuarios;
select * from tickets;

SELECT
	u_usuario.name as Usuario,
    u_encargado.name as Encargado,
    tickets.título as Título,
    tickets.descripción as Descripción,
    tickets.fecha_hora_reporte as "Fecha y hora del reporte",
    estatus.nombre as Estatus
    
    FROM tickets
    
    INNER JOIN usuarios AS u_usuario ON tickets.id_usuario = u_usuario.id
    LEFT JOIN usuarios AS u_encargado ON tickets.id_encargado = u_encargado.id
    INNER JOIN estatus ON tickets.id_estatus = estatus.id
    
    WHERE tickets.id_usuario = 3;
    
UPDATE tickets SET id_encargado = 2, id_estatus = 2 where id = 1;
    