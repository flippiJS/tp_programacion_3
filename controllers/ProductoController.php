<?php
require_once './models/Producto.php';
require_once 'IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $descripcion = $parametros['descripcion'];
        $sectorId = $parametros['sectorId'];
        $precio = $parametros['precio'];

        $producto = new Producto();
        $producto->descripcion = $descripcion;
        $producto->sectorId = $sectorId;
        $producto->precio = $precio;

        $producto->crearProducto();

        return $response->withJson(array("mensaje" => "Producto creado con exito"));
    }

    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $sector = Producto::obtenerProducto($id);
        $respuesta = $response->withJson($sector, 200);
        return $respuesta;
    }

    public function TraerTodos($request, $response, $args)
    {
        $productos = Producto::obtenerProductos();
        $respuesta = $response->withJson($productos, 200);
        return $respuesta;
    }

    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $productoId = $parametros['productoId'];
        $descripcion = $parametros['descripcion'];
        $sectorId = $parametros['sectorId'];
        $precio = $parametros['precio'];

        $producto = new Producto();
        $producto->id = $productoId;
        $producto->descripcion = $descripcion;
        $producto->sectorId = $sectorId;
        $producto->precio = $precio;

        $producto->modificarProducto();

        return $response->withJson(array("mensaje" => "Producto modificado con exito"));
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $productoId = $parametros['productoId'];
        Sector::borrarProducto($productoId);

        return $response->withJson(array("mensaje" => "Producto borrado con exito"));
    }
}
