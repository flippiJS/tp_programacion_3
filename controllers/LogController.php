<?php
require_once './models/Log.php';

class LogController
{
    public static function CargarUno($empleadoId, $sectorId, $path, $method)
    {
        Log::crearLog($empleadoId, $sectorId, $path, $method);
    }

    public function TraerTodos($request, $response, $args)
    {
        $logs = Log::obtenerLogs();
        return $response->withJson(array("logs" => $logs));
    }
}
