<?php

class Usuario
{
    public $id_usuario;
    public $mail;
    public $tipo;
    public $clave;

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (mail,tipo, clave) VALUES (:mail,:tipo, :clave)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':mail', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_usuario,mail, tipo, clave FROM usuarios");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_usuario,mail, tipo, clave FROM usuarios WHERE mail = :mail");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public  function modificarUsuario()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET mail = :mail, clave = :clave WHERE id_usuario = :id_usuario");
        $consulta->bindValue(':usuario', $this->mail, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':id_usuario', $this->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    // public static function borrarUsuario($usuario)
    // {
    //     $objAccesoDato = AccesoDatos::obtenerInstancia();
    //     $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE id = :id");
    //     $fecha = new DateTime(date("d-m-Y"));
    //     $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
    //     $consulta->bindValue(':fechaBaja', date_format($fecha, 'Y-m-d H:i:s'));
    //     $consulta->execute();
    // }

    public static function obtenerUsuarioPorNombre($mail)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_usuario FROM usuarios WHERE mail = :mail");
        $consulta->bindValue(':mail', $mail, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetch(PDO::FETCH_OBJ);
    }

    public static function Login($usr)
    {

        $obj_usuarios = Usuario::obtenerTodos();

        foreach ($obj_usuarios as $usuarioObj) {
            if ($usr->mail == $usuarioObj->mail) {
                if (password_verify($usr->clave, $usuarioObj->clave)) {
                    return true;
                }  
            }
        }
        return false;
    }

    public static function obtenerUsuarioMail($mail){
        $arrUsuario = Usuario::obtenerTodos();
        foreach ($arrUsuario as $usuario) {
          if ($usuario->mail == $mail) {
            return $usuario->id_usuario;
          }
        }
      }

      public static function obtenerUsuarioCodigo($mail){
        $arrUsuario = Usuario::obtenerTodos();
        foreach ($arrUsuario as $usuario) {
          if ($usuario->mail == $mail) {
            return $usuario->tipo;
          }
        }
      }




}