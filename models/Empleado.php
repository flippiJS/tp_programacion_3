<?php

class Empleado
{
    public $id;
    public $nombre;
    public $sectorId;
    public $usuarioId;
    public $estado;
    public $fechaAlta;

    public function crearEmpleado()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("INSERT INTO empleados (nombre, sectorId, usuarioId, estado, fechaAlta) VALUES (:nombre, :sectorId, :usuarioId, :estado, :fechaAlta)");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':sectorId', $this->sectorId, PDO::PARAM_STR);
        $consulta->bindValue(':usuarioId', $this->usuarioId, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();

        return $objAccesoDato->obtenerUltimoId();
    }

    public static function obtenerEmpleados()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT e.id, e.nombre, s.descripcion AS sector, ee.descripcion AS estado, u.fechaBaja, login.ultimoLogin AS ultimoLogin, COUNT(lo.id) AS cantOperaciones FROM empleados e INNER JOIN usuarios u ON u.id = e.usuarioId  INNER JOIN sectores s ON s.id = e.sectorId INNER JOIN empleados_estados ee ON e.estado = ee.id LEFT JOIN logs lo ON lo.empleadoId = e.id LEFT JOIN (SELECT empleadoId AS empleadoId, MAX(fechaAlta) AS ultimoLogin FROM logs WHERE path LIKE '/auth/login' GROUP BY empleadoId ORDER BY fechaAlta ASC) login ON e.id = login.empleadoId GROUP BY e.id;");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_FUNC, "listarEmpleados");
    }

    public static function obtenerEmpleado($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT id, nombre, sectorId, usuarioId, estado, fechaAlta FROM empleados WHERE id = :empeladoId");
        $consulta->bindValue(':empeladoId', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject("Empleado");
    }

    public static function obtenerEmpleadoPorUsuarioId($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT e.id AS id, e.nombre AS nombre, e.usuarioId AS usuarioId, e.estado AS estado, s.id AS sectorId FROM empleados e INNER JOIN sectores s on e.sectorId = s.id WHERE e.usuarioId = :usuarioId");
        $consulta->bindValue(':usuarioId', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject("Empleado");
    }

    public static function actualizarEmpleado($id, $estado)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE empleados SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}

function listarEmpleados($id, $nombre, $sector, $estado, $fechaBaja, $ultimoLogin, $cantOperaciones)
{
    return array('id' => $id, 'nombre' => $nombre, 'sector' => $sector, 'estado' => $estado, 'fechaBaja' => $fechaBaja, 'ultimoLogin' => $ultimoLogin, 'cantOperaciones' => $cantOperaciones);
}
