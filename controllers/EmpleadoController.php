<?php
require_once './models/Empleado.php';
require_once 'IApiUsable.php';

class EmpleadoController extends Empleado implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $sectorId = $parametros['sectorId'];
        $usuarioId = $parametros['usuarioId'];
        $estado = 1; // Activo default

        $empleado = new Empleado();
        $empleado->nombre = $nombre;
        $empleado->sectorId = $sectorId;
        $empleado->usuarioId = $usuarioId;
        $empleado->estado = $estado;
        $empleado->crearEmpleado();

        return $response->withJson(array("mensaje" => "Empleado creado"));
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos empleado por empleado id
        $empl = $args['empleado'];
        $empleado = Empleado::obtenerEmpleado($empl);
        return $response->withJson(array("empleado" => $empleado));
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Empleado::obtenerEmpleados();
        return $response->withJson(array("listaEmpleados" => $lista));
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $empleadoId = $parametros['empleadoId'];
        $estado = $parametros['estado'];

        Empleado::actualizarEmpleado($empleadoId, $estado);
        return $response->withJson(array("mensaje" => "Empleado actualizado"));
    }
}
