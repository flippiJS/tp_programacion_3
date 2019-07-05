<?php

class Mesa
{
    public $id;
    public $codigo;
    public $estadoId;
    public $fechaBaja;

    public function crearMesa()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $this->codigo =  substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 5);
        $consulta = $objAccesoDato->prepararConsulta("INSERT INTO mesas (estadoId, codigo) VALUES (:estadoId, :codigo)");
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':estadoId', $this->estadoId, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDato->obtenerUltimoId();
    }

    public static function obtenerMesas()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT id, codigo, m.descripcion as estadoId FROM mesas INNER JOIN mesas_estados m ON mesas.estadoId = m.id");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, "Mesa");
    }

    public static function obtenerMesa($codigo)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT id, codigo, estadoId FROM mesas WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public function modificarMesa()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estadoId = :estadoId WHERE codigo = :codigo");
        $consulta->bindValue(':estadoId', $this->estadoId);
        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function borrarMesa($mesa)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET fechaBaja = :fechaBaja WHERE id = :id");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':codigo', $mesa, PDO::PARAM_INT);
        $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
        $consulta->execute();
    }
}
