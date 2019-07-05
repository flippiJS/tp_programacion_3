<?php
error_reporting(-1);
ini_set('display_errors', 1);

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';

require_once './db/AccesoDatos.php';
require_once './middleware/AutentificadorJWT.php';
require_once './middleware/Validaciones.php';
require_once './middleware/Logger.php';

require_once './controllers/UsuarioController.php';
require_once './controllers/EmpleadoController.php';
require_once './controllers/SectorController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/EncuestaController.php';


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

// Login
$app->group('/auth/login', function () {
  $this->post('[/]', \UsuarioController::class . ':Login');
})->add(\Logger::class . ':LogOperacion')
  ->add(\Validaciones::class . ':verificarParametrosLogin');

// Usuarios
$app->group('/usuarios', function () {
  $this->get('/{usuario}', \UsuarioController::class . ':TraerUno');
  $this->post('[/]', \UsuarioController::class . ':CargarUno');
})->add(\Logger::class . ':LogOperacion')
  ->add(\Validaciones::class . ':verificarAdmin')
  ->add(\Validaciones::class . ':verificarToken');

// Empleados
$app->group('/empleados', function () {
  $this->get('[/]', \EmpleadoController::class . ':TraerTodos');
  $this->post('[/]', \EmpleadoController::class . ':CargarUno');
  $this->post('/estado', \EmpleadoController::class . ':ModificarUno');
})->add(\Logger::class . ':LogOperacion')
  ->add(\Validaciones::class . ':verificarAdmin')
  ->add(\Validaciones::class . ':verificarToken');

// Sectores
$app->group('/sectores', function () {
  $this->get('', \SectorController::class . ':TraerTodos');
  $this->get('/[{id}]', \SectorController::class . ':TraerUno');
  $this->post('[/]', \SectorController::class . ':CargarUno');
})->add(\Logger::class . ':LogOperacion')
  ->add(\Validaciones::class . ':verificarAdmin')
  ->add(\Validaciones::class . ':verificarToken');

// Mesas
$app->group('/mesas', function () {
  $this->get('', \MesaController::class . ':TraerTodos');
  $this->post('[/]', \MesaController::class . ':CargarUno');
  $this->post('/cerrar', \MesaController::class . ':CerrarUna');
})->add(\Logger::class . ':LogOperacion')
  ->add(\Validaciones::class . ':verificarAdmin')
  ->add(\Validaciones::class . ':verificarToken');

// Productos
$app->group('/productos', function () {
  $this->get('', \ProductoController::class . ':TraerTodos');
  $this->get('/[{id}]', \ProductoController::class . ':TraerUno');
  $this->post('[/]', \ProductoController::class . ':CargarUno');
})->add(\Logger::class . ':LogOperacion')
  ->add(\Validaciones::class . ':verificarAdmin')
  ->add(\Validaciones::class . ':verificarToken');

// Pedidos
$app->group('/pedidos', function () {
  $this->get('[/]', \PedidoController::class . ':TraerTodos')
    ->add(\Validaciones::class . ':verificarAdmin');
  $this->post('[/]', \PedidoController::class . ':CargarUno')
    ->add(\Validaciones::class . ':verificarMozo');
  $this->get('/codigo/{codigo}', \PedidoController::class . ':TraerUno')
    ->add(\Validaciones::class . ':verificarAdmin');
  $this->get('/pendientes', \PedidoController::class . ':TraerPendientes')
    ->add(\Validaciones::class . ':verificarEmpleado');
  $this->post('/asignarPendiente', \PedidoController::class . ':AsignarPendiente')
    ->add(\Validaciones::class . ':verificarEmpleado');
  $this->post('/productoListo', \PedidoController::class . ':ProductoListo')
    ->add(\Validaciones::class . ':verificarEmpleado');
  $this->post('/entregar', \PedidoController::class . ':LlevarPedidoALaMesa')
    ->add(\Validaciones::class . ':verificarMozo');
  $this->post('/pagar', \PedidoController::class . ':AbonarPedido')
    ->add(\Validaciones::class . ':verificarMozo');
})
  ->add(\Logger::class . ':LogOperacion')
  ->add(\Validaciones::class . ':verificarToken');

// Clientes
$app->group('/clientes', function () {
  $this->post('/pedido/encuesta', \EncuestaController::class . ':CargarUno');
  $this->post('/pedido/tiempo', \PedidoController::class . ':TiempoFaltantePedido');
})->add(\Logger::class . ':LogOperacion');

// Listados
$app->group('/listados', function () {
  $this->get('/empleados', \EmpleadoController::class . ':TraerTodos');
  $this->get('/pedidos', \PedidoController::class . ':TraerTodos');
  $this->get('/pedidosHora', \PedidoController::class . ':TraerTodosXHora');
  $this->get('/logs', \LogController::class . ':TraerTodos');
})->add(\Logger::class . ':LogOperacion')
  ->add(\Validaciones::class . ':verificarAdmin')
  ->add(\Validaciones::class . ':verificarToken');

$app->run();
