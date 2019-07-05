<?php
require_once './models/Usuario.php';

class UsuarioController extends Usuario
{
    public function CargarUno($request, $response)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->crearUsuario();

        return $response->withJson(array("mensaje" => "Usuario creado con exito"));;
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $respuesta = $response->withJson($usuario, 200);
        return $respuesta;
    }

    public function Login($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];

        $usr = Usuario::obtenerUsuario($usuario);

        if ($usr) { // Existe usuario en BD
            if (password_verify($clave, $usr->clave)) { // Validamos la clave ingresada

                $empl = Empleado::obtenerEmpleadoPorUsuarioId($usr->id);

                if ($empl) { // Existe empleado

                    $token = AutentificadorJWT::CrearToken($empl);

                    return $response->withJson(array("token" => $token));
                }

                return $response->withJson(array("mensaje" => 'ERROR: Empleado no encontrado'));
            }
            return $response->withJson(array("mensaje" => 'ERROR: Clave incorrecta'));
        }
        return $response->withJson(array("mensaje" => 'ERROR: Usuario no encontrado'));
    }
}
