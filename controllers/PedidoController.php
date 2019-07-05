<?php
require_once './models/Pedido.php';
require_once 'IApiUsable.php';

class PedidoController extends Pedido implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $mesaId = $parametros['mesaId'];
        $mozoId = $parametros['mozoId'];
        $listadoProductos = json_decode($parametros['productos'], true);

        $pedido = new Pedido();
        $pedido->productos = $listadoProductos;
        $pedido->mesaId = $mesaId;
        $pedido->mozoId = $mozoId;

        $codigoPedido = $pedido->crearPedido();

        return $response->withJson(array("mensaje" => 'Pedido creado con exito', "codigoPedido" => $codigoPedido));;
    }

    public function TraerUno($request, $response, $args)
    {
        $codigo = $args['codigo'];
        $sector = Sector::obtenerPedido($codigo);
        $respuesta = $response->withJson($sector, 200);
        return $respuesta;
    }

    public function TraerTodos($request, $response, $args)
    {
        $pedidos = Pedido::obtenerPedidos();
        return $response->withJson(array("pedidos" => $pedidos));
    }

    public function TraerTodosXHora($request, $response, $args)
    {
        $pedidos = Pedido::obtenerPedidosXHora();
        return $response->withJson(array("pedidos" => $pedidos));
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

    public function TraerPendientes($request, $response, $args)
    {
        $token = $request->getHeaderLine('token');

        $emplLog = AutentificadorJWT::ObtenerData($token);
        $pendientes = Pedido::obtenerPendientes($emplLog->sectorId);

        return $response->withJson(array("pendientes" => $pendientes));
    }

    public function AsignarPendiente($request, $response, $args)
    {
        $token = $request->getHeaderLine('token');
        $parametros = $request->getParsedBody();

        $emplLog = AutentificadorJWT::ObtenerData($token);
        $pendiente = $parametros['pendiente'];
        $pedido = $parametros['pedido'];
        $tiempoEstimado = $parametros['tiempoEstimado'];

        Pedido::tomarPendiente($pendiente, $pedido, $tiempoEstimado, $emplLog->id);
        return $response->withJson(array("mensaje" => "Pendiente ha sido asignado"));
    }

    public function TiempoFaltantePedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $pedidoCodigo = $parametros['pedidoCodigo'];
        $mesaCodigo = $parametros['mesaCodigo'];

        // Buscamos mesa y pedido
        $mesa = Mesa::obtenerMesa($mesaCodigo);
        $pedido = Pedido::obtenerPedido($pedidoCodigo);

        // Obtenemos el tiempo estimado de cada uno de los productos
        $productos = Pedido::obtenerProductosXPedido($pedido->id);

        // Calculamos los minutos totales
        $totalMinutos = 0;
        foreach ($productos as $clave => $prod) {
            $totalMinutos += $prod['tiempoEstimado'];
        }

        // Inicializamos fechas horas
        $fechaHoraActual = new DateTime(date("Y-m-d H:i:s"));
        $fechaHoraPedido = new DateTime(date($pedido->fechaAlta));

        // Agregamos los minutos totales de los productos
        $fechaHoraPedido->add(new DateInterval('PT' . $totalMinutos . 'M'));

        // Calculamos la diferencia
        $diferencia = $fechaHoraActual->diff($fechaHoraPedido);

        if ($fechaHoraActual < $fechaHoraPedido) {
            $diferencia = $fechaHoraActual->diff($fechaHoraPedido);
        }
        $diferencia = $diferencia->format("%H:%I:%S");
        return $response->withJson(array("mensaje" => "Faltan $diferencia para la entrega de tu pedido. Muchas Gracias!!"));
    }

    public function ProductoListo($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $productoEnPreparacion = $parametros['productoEnPreparacion'];
        $pedidoCodigo = $parametros['pedidoCodigo'];

        $retorno = Pedido::listoProducto($productoEnPreparacion, $pedidoCodigo);

        return $response->withJson(array("mensaje" => $retorno));
    }

    public function LlevarPedidoALaMesa($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $pedidoCodigo = $parametros['pedidoCodigo'];

        $retorno = Pedido::entregarPedido($pedidoCodigo);

        return $response->withJson(array("mensaje" => $retorno));
    }

    public function AbonarPedido($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $pedidoCodigo = $parametros['pedidoCodigo'];

        $retorno = Pedido::pagarPedido($pedidoCodigo);

        return $response->withJson(array("mensaje" => $retorno));
    }
}
