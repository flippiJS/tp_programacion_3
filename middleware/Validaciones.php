<?php
require_once './controllers/UsuarioController.php';
require_once './controllers/LogController.php';
require_once './models/Usuario.php';
require_once './middleware/AutentificadorJWT.php';

class Validaciones
{
    public static function verificarToken($request, $response, $next)
    {
        $datos = $request->getHeaderLine('token');
        try {
            AutentificadorJWT::VerificarToken($datos);
            return $next($request, $response);
        } catch (Exception $e) {
            return $response->withJson(array("mensaje" => "ERROR: Hubo un error con el TOKEN"));
        }
        return $response;
    }

    public static function verificarParametrosLogin($request, $response, $next)
    {
        $parametros = $request->getParsedBody();
        if (isset($parametros['usuario']) && isset($parametros['clave'])) {
            return $next($request, $response);
        }
        return $response->withJson(array("mensaje" => "ERROR: Request incorrecta"));
    }

    public static function verificarAdmin($request, $response, $next)
    {
        $token = $request->getHeaderLine('token');
        $empl = AutentificadorJWT::ObtenerData($token);

        if ($empl->sectorId == 1) { // Socios
            return $next($request, $response);
        }
        return $response->withJson(array("mensaje" => "ERROR: Debes ser Administrador para realizar esta accion"));
    }

    public static function verificarMozo($request, $response, $next)
    {
        $token = $request->getHeaderLine('token');
        $empl = AutentificadorJWT::ObtenerData($token);

        if ($empl->sectorId == 5 || $empl->sectorId == 1) { // Mozos u Socios
            return $next($request, $response);
        }
        return $response->withJson(array("mensaje" => "ERROR: Debes ser Mozo para realizar esta accion"));
    }

    public static function verificarEmpleado($request, $response, $next)
    {
        $token = $request->getHeaderLine('token');
        $empl = AutentificadorJWT::ObtenerData($token);

        if ($empl->sectorId == 2 || $empl->sectorId == 3 || $empl->sectorId == 4) { // Cocinero, bartender o cervecero
            return $next($request, $response);
        }
        return $response->withJson(array("mensaje" => "ERROR: Debes ser empleado para realizar esta accion"));
    }
}
