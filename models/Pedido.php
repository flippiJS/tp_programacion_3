<?php

require_once 'Producto.php';

class Pedido
{
    public $id;
    public $codigo;
    public $estado;
    public $mesaId;
    public $mozoId;
    public $productos;
    public $precioTotal;
    public $fechaAlta;
    public $fechaEntrega;

    public function crearPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("INSERT INTO pedidos (codigo, estado, mesaId, mozoId, precioTotal, fechaAlta) VALUES (:codigo, :estado, :mesaId, :mozoId, :precioTotal, :fechaAlta)");
        // Creamos codigo unico de 5 digitos para identificar el pedido
        $this->codigo =  substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 5);
        // Obtenemos el precio total de todos los productos del pedido
        $this->precioTotal = $this->obtenerPrecioTotal();
        // Time stamp para saber cuando se creo
        $fecha = new DateTime();

        $consulta->bindValue(':codigo', $this->codigo, PDO::PARAM_STR);
        $consulta->bindValue(':estado', 1, PDO::PARAM_INT); // Estado inicial default "Iniciado"
        $consulta->bindValue(':mesaId', $this->mesaId);
        $consulta->bindValue(':mozoId', $this->mozoId);
        $consulta->bindValue(':precioTotal', $this->precioTotal);
        $consulta->bindValue(':fechaAlta', date_format($fecha, 'Y-m-d H:i:s'));

        $consulta->execute();
        $this->id = $objAccesoDato->obtenerUltimoId();
        // Agregamos los productos al pedido creado
        $this->agregarProductos();

        return $this->codigo;
    }

    public function agregarProductos()
    {
        foreach ($this->productos as $producto) {
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("INSERT INTO pedidos_productos (pedidoId, productoId, cantidad) VALUES (:pedidoId, :productoId, :cantidad)");
            $consulta->bindValue(':pedidoId', $this->id, PDO::PARAM_STR);
            $consulta->bindValue(':productoId', $producto['productoId'], PDO::PARAM_INT);
            $consulta->bindValue(':cantidad', $producto['cantidad'], PDO::PARAM_INT);
            $consulta->execute();
        }
    }

    public function obtenerPedido($codigo)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * from pedidos WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    private function obtenerPrecioTotal()
    {
        $precioT = 0;
        foreach ($this->productos as $producto) {
            $prodObj = Producto::obtenerProducto($producto['productoId']);
            $precioT += ($producto['cantidad'] * $prodObj->precio);
        }
        return $precioT;
    }

    public function obtenerPedidos()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT pe.id, pe.codigo, pe.precioTotal, es.descripcion, me.codigo AS codigoMesa, em.nombre AS mozo FROM pedidos pe INNER JOIN pedidos_estados es ON pe.estado = es.id INNER JOIN mesas me ON me.id = pe.mesaId INNER JOIN empleados em ON em.id = pe.mozoId");
        $consulta->execute();
        $resultados = $consulta->fetchAll(PDO::FETCH_FUNC, "listarPedidos");
        return $resultados;
    }

    public function obtenerPedidosXHora()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT pe.id, pe.fechaAlta, pe.fechaEntrega, pe.precioTotal FROM pedidos pe INNER JOIN pedidos_estados es ON pe.estado = es.id INNER JOIN mesas me ON me.id = pe.mesaId INNER JOIN empleados em ON em.id = pe.mozoId");
        $consulta->execute();
        $resultados = $consulta->fetchAll(PDO::FETCH_FUNC, "listarPedidosXHora");
        return $resultados;
    }

    public function obtenerProductosXPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT pp.* FROM pedidos pe INNER JOIN pedidos_productos pp ON pp.pedidoId = pe.id WHERE pp.fechaEntrega IS NULL");
        $consulta->execute();
        $resultados = $consulta->fetchAll(PDO::FETCH_FUNC, "productosXPedido");
        return $resultados;
    }

    public function obtenerPendientes($sectorId)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        if ($sectorId == 1) { // Es socio
            $consulta = $objAccesoDato->prepararConsulta("SELECT p.id, pe.codigo, p.cantidad, pr.descripcion FROM pedidos_productos p INNER JOIN productos pr ON p.productoId = pr.id INNER JOIN sectores s ON pr.sectorId = s.id INNER JOIN pedidos pe ON pe.id = p.pedidoId WHERE p.empleadoId IS NULL");
        } else { // Otro sector
            $consulta = $objAccesoDato->prepararConsulta("SELECT p.id, pe.codigo, p.cantidad, pr.descripcion FROM pedidos_productos p INNER JOIN productos pr ON p.productoId = pr.id INNER JOIN sectores s ON pr.sectorId = s.id INNER JOIN pedidos pe ON pe.id = p.pedidoId WHERE p.empleadoId IS NULL AND pr.sectorId = :sectorId");
            $consulta->bindValue(':sectorId', $sectorId, PDO::PARAM_STR);
        }
        $consulta->execute();
        $resultados = $consulta->fetchAll(PDO::FETCH_FUNC, "listarPendientes");
        return $resultados;
    }

    public function tomarPendiente($pendiente, $pedido, $tiempoEstimado, $empleado)
    {
        // Tomamos el poducto
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos_productos SET empleadoId = :empleadoId, tiempoEstimado = :tiempoEstimado WHERE id = :id");
        $consulta->bindValue(':empleadoId', $empleado, PDO::PARAM_INT);
        $consulta->bindValue(':tiempoEstimado', $tiempoEstimado, PDO::PARAM_INT); // Tiempo en minutos
        $consulta->bindValue(':id', $pendiente, PDO::PARAM_INT);
        $consulta->execute();

        // Actualizamos estado del pedido
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':estado', 2, PDO::PARAM_INT); // En preparacion
        $consulta->bindValue(':id', $pedido, PDO::PARAM_INT);
        $consulta->execute();
    }

    public function listoProducto($productoEnPreparacion, $pedidoCodigo)
    {
        $retorno = "Producto listo para ser entregado";
        // Actualizamos el producto
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos_productos SET fechaEntrega = :fechaEntrega WHERE id = :id");
        $fecha = new DateTime(date("Y-m-d H:i:s"));
        $consulta->bindValue(':fechaEntrega', $fecha->format("Y-m-d H:i:s"));
        $consulta->bindValue(':id', $productoEnPreparacion, PDO::PARAM_INT);
        $consulta->execute();

        // Si no hay mas productos pendientes, cambiamos el pedido a Listo para servir
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT COUNT(*) FROM pedidos pe INNER JOIN pedidos_productos pp ON pp.pedidoId = pe.id WHERE pp.fechaEntrega IS NULL AND pe.codigo = :codigo");
        $consulta->bindValue(':codigo', $pedidoCodigo);
        $consulta->execute();
        $productosPendientes = $consulta->fetch();

        if ($productosPendientes[0] == 0) {
            // Actualizamos el pedido
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE codigo = :codigo");
            $consulta->bindValue(':estado', 3); // Estado 3 Listo para servir
            $consulta->bindValue(':codigo', $pedidoCodigo);
            $consulta->execute();

            $retorno = "Producto listo y Pedido Listo para ser entregado";
        }

        return $retorno;
    }

    public function entregarPedido($pedidoCodigo)
    {
        $retorno = "No existe el pedido o no esta listo";
        $pedido = Pedido::obtenerPedido($pedidoCodigo);

        if ($pedido && $pedido->estado == 3) { // Existe pedido y esta en listo para servir
            // Actualizamos el pedido
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado, fechaEntrega = :fechaEntrega WHERE codigo = :codigo");
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $consulta->bindValue(':estado', 4); // Estado 4 Entregado
            $consulta->bindValue(':fechaEntrega', date_format($fecha, 'Y-m-d H:i:s'));
            $consulta->bindValue(':codigo', $pedido->codigo);
            $consulta->execute();

            // Actualizamos la Mesa
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estadoId = :estadoId WHERE id = :id");
            $consulta->bindValue(':estadoId', 3); // Estado 3 Comiendo
            $consulta->bindValue(':id', $pedido->mesaId);
            $consulta->execute();

            $retorno = "Pedido entregado";
        }

        return $retorno;
    }

    public function pagarPedido($pedidoCodigo)
    {
        $retorno = "No existe el pedido o no esta entregado";
        $pedido = Pedido::obtenerPedido($pedidoCodigo);

        if ($pedido && $pedido->estado == 4) { // Existe pedido y esta en entregado
            // Actualizamos el pedido
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado WHERE codigo = :codigo");
            $consulta->bindValue(':estado', 5); // Estado 5 Cobrado
            $consulta->bindValue(':codigo', $pedido->codigo);
            $consulta->execute();

            // Actualizamos la Mesa
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estadoId = :estadoId WHERE id = :id");
            $consulta->bindValue(':estadoId', 4); // Estado 4 Clientes pagando
            $consulta->bindValue(':id', $pedido->mesaId);
            $consulta->execute();

            $retorno = "Pedido pagado";
        }
        return $retorno;
    }
}


function listarPedidos($id, $codigo, $precioTotal, $descripcion, $codigoMesa, $mozo)
{
    return array('id' => $id, 'codigo' => $codigo, 'precioTotal' => $precioTotal, 'estado' => $descripcion, 'mesa' => $codigoMesa, 'mozo' => $mozo);
}

function listarPedidosXHora($id, $fechaAlta, $fechaEntrega, $precioTotal)
{
    return array('id' => $id, 'horaInicio' => $fechaAlta, 'horaFin' => $fechaEntrega, 'precioTotal' => $precioTotal);
}

function listarPendientes($id, $codigo, $cantidad, $descripcion)
{
    return array('id' => $id, 'codigoPedido' => $codigo, 'cantidad' => $cantidad, 'descripcion' => $descripcion);
}

function productosXPedido($id, $pedidoId, $productoId, $cantidad, $empleadoId, $tiempoEstimado)
{
    return array('id' => $id, 'pedidoId' => $pedidoId, 'productoId' => $productoId, 'cantidad' => $cantidad, 'empleadoId' => $empleadoId, 'tiempoEstimado' => $tiempoEstimado);
}