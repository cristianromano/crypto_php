<?php


class Venta 
{
    // public $precio;
    // public $foto;
    // public $nombre;
    // public $nacionalidad;
    

    public function crearVenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ventas(id_usuario,id_cripto,nombre_cripto,nacionalidad_cripto,cantidad,foto_venta) 
        VALUES (:id_usuario,:id_cripto,:nombre_cripto,:nacionalidad_cripto,:cantidad,:foto_venta)");
        $consulta->bindValue(':id_usuario', $this->id_usuario, PDO::PARAM_INT);
        $consulta->bindValue(':id_cripto', $this->id_cripto, PDO::PARAM_INT);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':foto_venta', $this->foto_venta, PDO::PARAM_STR);
        $consulta->bindValue(':nombre_cripto', $this->nombre_cripto, PDO::PARAM_STR);
        $consulta->bindValue(':nacionalidad_cripto', $this->nacionalidad_cripto, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_usuario, id_cripto,nombre_cripto,nacionalidad_cripto,cantidad , foto_venta FROM ventas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerCriptoNacionalidad($nacionalidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_usuario,id_cripto,cantidad,foto_venta FROM ventas WHERE nacionalidad_cripto = :nacionalidad
        AND fecha BETWEEN :fechaUno AND :FechaDos ");
        $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
        $consulta->bindValue(':fechaUno', '2020-6-21', PDO::PARAM_STR);
        $consulta->bindValue(':FechaDos', '2021-12-21', PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerPorFechas($nacionalidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_cripto, precio, foto , nombre , nacionalidad FROM criptomonedas WHERE nacionalidad = :nacionalidad");
        $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cripto');
    }

    public static function obtenerCriptoIdVenta($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_usuario,id_cripto,cantidad,foto_venta FROM ventas WHERE id_cripto = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Venta');
    }

    public static function obtenerPorNombre($nombre)
    {
        echo 'hola';
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            'SELECT mail , id_cripto , cantidad , foto_venta , nombre_cripto
            FROM ventas V INNER JOIN usuarios U' .' ON  U.id_usuario = V.id_usuario' .
            ' WHERE nombre_cripto = :nombre');
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_OBJ);
    }


    // public static function toString($venta){
    //     $cadena .= '<ul>'. '<li>'


    //     return $cadena;
    // }



}
