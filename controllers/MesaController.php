<?php
require_once './models/Mesa.php';
require_once 'IApiUsable.php';

class MesaController extends Mesa implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $mesa = new Mesa();
        $mesa->estadoId = 1; // Iniciado default
        $mesa->crearMesa();

        return $response->withJson(array("mensaje" => "Mesa creada con exito"));
    }

    public function TraerTodos($request, $response, $args)
    {
        $mesas = Mesa::obtenerMesas();
        return $response->withJson(array("mesas" => $mesas));
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $mesa = Mesa::obtenerMesa($id);
        return $response->withJson(array("mesa" => $mesa));
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesaCodigo = $parametros['mesaCodigo'];
        $estadoId = $parametros['estadoId'];

        $mesa = new Mesa();
        $mesa->codigo = $mesaCodigo;
        $mesa->estadoId = $estadoId;

        $mesa->modificarMesa();

        return $response->withJson(array("mensaje" => "Mesa modificada con exito"));
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesaId = $parametros['mesaId'];
        Mesa::borrarMesa($mesaId);

        return $response->withJson(array("mensaje" => "Mesa borrada con exito"));
    }

    public function CerrarUna($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesaCodigo = $parametros['mesaCodigo'];

        $mesa = new Mesa();
        $mesa->codigo = $mesaCodigo;
        $mesa->estadoId = 1; // Cerrada

        $mesa->modificarMesa();

        return $response->withJson(array("mensaje" => "Mesa cerrada con exito"));
    }
}
