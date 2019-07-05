<?php
require_once './models/Encuesta.php';
require_once 'IApiUsable.php';

class EncuestaController extends Encuesta
{
    public static function CargarUno($request, $response)
    {
        $parametros = $request->getParsedBody();

        $pedidoCodigo = $parametros['pedidoCodigo'];
        $puntajeMesa = $parametros['puntajeMesa'];
        $puntajeRestaurante = $parametros['puntajeRestaurante'];
        $puntajeMozo = $parametros['puntajeMozo'];
        $puntajeCocinero = $parametros['puntajeCocinero'];
        $comentario = $parametros['comentario'];

        $encuesta = new Encuesta();
        $encuesta->pedido = $pedidoCodigo;
        $encuesta->puntajeMesa = $puntajeMesa;
        $encuesta->puntajeRestaurante = $puntajeRestaurante;
        $encuesta->puntajeMozo = $puntajeMozo;
        $encuesta->puntajeCocinero = $puntajeCocinero;
        $encuesta->comentario = $comentario;

        $retorno = $encuesta->crearEncuesta();

        return $response->withJson(array("mensaje" => $retorno));
    }
}
