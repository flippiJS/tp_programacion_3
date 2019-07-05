<?php

class Encuesta
{
    public $id;
    public $pedido;
    public $puntajeMozo;
    public $puntajeCocinero;
    public $puntajeMesa;
    public $puntajeRestaurante;
    public $comentario;
    public $fechaAlta;

    public function crearEncuesta()
    {
        $retorno = "No existe el pedido o no esta pagado";
        $pedido = Pedido::obtenerPedido($this->pedido);

        if ($pedido && $pedido->estado == 5) { // Existe pedido y esta en cobrado
            // Creamos la encuesta
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("INSERT INTO encuestas (pedido, puntajeMozo, puntajeCocinero, puntajeMesa, puntajeRestaurante, comentario, fechaAlta) VALUES (:pedido, :puntajeMozo, :puntajeCocinero, :puntajeMesa, :puntajeRestaurante, :comentario, :fechaAlta)");
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':pedido', $this->pedido, PDO::PARAM_INT);
            $consulta->bindValue(':puntajeMozo', $this->puntajeMozo, PDO::PARAM_INT);
            $consulta->bindValue(':puntajeCocinero', $this->puntajeCocinero, PDO::PARAM_INT);
            $consulta->bindValue(':puntajeMesa', $this->puntajeMesa, PDO::PARAM_INT);
            $consulta->bindValue(':puntajeRestaurante', $this->puntajeRestaurante, PDO::PARAM_INT);
            $consulta->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
            $consulta->bindValue(':fechaAlta', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->execute();

            $retorno = "Encuesta registrada. Muchas gracias por confiar en nosotros!";
        }
        return $retorno;
    }
}
