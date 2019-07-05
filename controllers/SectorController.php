<?php
require_once './models/Sector.php';
require_once 'IApiUsable.php';

class SectorController extends Sector implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];

        $sector = new Sector();
        $sector->descripcion = $descripcion;

        $sector->crearSector();

        return $response->withJson(array("mensaje" => "Sector creado con exito"));
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $sector = Sector::obtenerSector($id);
        $respuesta = $response->withJson($sector, 200);
        return $respuesta;
    }

    public function TraerTodos($request, $response, $args)
    {
        $sectores = Sector::obtenerTodos();
        $respuesta = $response->withJson($sectores, 200);
        return $respuesta;
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        Sector::modificarSector($nombre);

        return $response->withJson(array("mensaje" => "Sector modificado con exito"));
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $sectorId = $parametros['sectorId'];
        Sector::borrarSector($sectorId);

        return $response->withJson(array("mensaje" => "Sector borrado con exito"));
    }
}
