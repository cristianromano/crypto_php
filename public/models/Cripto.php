<?php


class Cripto 
{
    public $precio;
    public $foto;
    public $nombre;
    public $nacionalidad;
    

    public function crearCripto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO criptomonedas(precio,foto,nombre,nacionalidad) 
        VALUES (:precio,:foto,:nombre,:nacionalidad)");
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_cripto, precio, foto , nombre , nacionalidad FROM criptomonedas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cripto');
    }

    public static function obtenerCriptoNacionalidad($nacionalidad)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_cripto, precio, foto , nombre , nacionalidad FROM criptomonedas WHERE nacionalidad = :nacionalidad");
        $consulta->bindValue(':nacionalidad', $nacionalidad, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cripto');
    }

    public static function obtenerCriptoId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_cripto, precio, foto , nombre , nacionalidad FROM criptomonedas WHERE id_cripto = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cripto');
    }

    public static function obtenerCriptoPorNombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_cripto ,nacionalidad FROM criptomonedas WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_OBJ);
    }

        public static function borrarCripto($cripto)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM criptomonedas WHERE nombre = :cripto");
        $consulta->bindValue(':cripto', $cripto, PDO::PARAM_STR);
        $consulta->execute();
    }

    public  function modificarCripto()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE criptomonedas SET nacionalidad = :nacionalidad, foto = :foto WHERE nombre = :nombre");
        $consulta->bindValue(':nacionalidad', $this->nacionalidad, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        // $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->execute();
    }

}
