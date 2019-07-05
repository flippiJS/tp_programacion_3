<?php

class Log
{
    public $id;
    public $empleadoId;
    public $sectorId;
    public $path;
    public $method;
    public $fechaAlta;

    public static function crearLog($empleadoId, $sectorId, $path, $method)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("INSERT INTO logs (empleadoId, sectorId, path, method, fechaAlta) VALUES (:empleadoId, :sectorId, :path, :method, :fechaAlta)");
        $fecha = new DateTime(date("d-m-Y H:i:s"));
        $consulta->bindValue(':empleadoId', $empleadoId, PDO::PARAM_INT);
        $consulta->bindValue(':sectorId', $sectorId, PDO::PARAM_INT);
        $consulta->bindValue(':path', $path);
        $consulta->bindValue(':method', $method);
        $consulta->bindValue(':fechaAlta', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();

        return $objAccesoDato->obtenerUltimoId();
    }

    public function obtenerLogs()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM logs");
        $consulta->execute();
        return $consulta->fetchObject("Log");
    }
}
