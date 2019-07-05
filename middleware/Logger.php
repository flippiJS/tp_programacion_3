<?php
require_once './controllers/LogController.php';
require_once 'AutentificadorJWT.php';

class Logger
{
    public static function LogOperacion($request, $response, $next)
    {
        $token = $request->getHeaderLine('token');
        $retorno = $next($request, $response);
        if ($token) { // Esta logueado
            // Datos del empleado logueado
            $empl = AutentificadorJWT::ObtenerData($token);
            // Registramos todas la operaciones
            // $empleadoId, $sectorId, $path, $method
            LogController::CargarUno($empl->id, $empl->sectorId, $request->getUri()->getPath(), $request->getMethod());
        } else { // No Logueado
            $resp = json_decode($retorno->getBody());
            if ($resp->token) { // Verificamos si es por login
                $empl = AutentificadorJWT::ObtenerData($resp->token);
                // Registramos todas la operaciones
                // $empleadoId, $sectorId, $path, $method                
                LogController::CargarUno($empl->id, $empl->sectorId, $request->getUri()->getPath(), $request->getMethod());
            } else {
                // Registramos todas la operaciones
                // $empleadoId, $sectorId, $path, $method
                LogController::CargarUno(null, null, $request->getUri()->getPath(), $request->getMethod());
            }
        }
        return $retorno;
    }
}
