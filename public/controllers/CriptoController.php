<?php

require_once './models/Cripto.php';
require_once './interfaces/IApiUsable.php';
use Psr\Http\Message\UploadedFileInterface;

class CriptoController extends Cripto implements IApiUsable
{

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $precio = $parametros['precio'];
        $archivos = $request->getUploadedFiles();
        $nombre = $parametros['nombre'];
        $nacionalidad = $parametros['nacionalidad'];
        $extension= explode(".", $archivos['foto']->getClientFilename());
          
        // var_dump($archivos);
        if ($archivos['foto']->getError() == UPLOAD_ERR_OK) {
            $destino="./fotos/";
            $extension= explode(".", $archivos['foto']->getClientFilename());
            $destino .= $nombre . '/';
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }
            // $archivos['foto']->getClientFilename()
             $archivos['foto']->moveTo($destino . $archivos['foto']->getClientFilename());
             $foto = $archivos['foto']->getClientFilename();
            
        }
        $cripto = new Cripto();
        $cripto->precio = $precio;
        $cripto->foto = $foto;
        $cripto->nombre = $nombre;
        $cripto->nacionalidad = $nacionalidad;
        $cripto->crearCripto();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $nacionalidad = $args['nacionalidad'];
        // var_dump($nacionalidad);
        $nac = Cripto::obtenerCriptoNacionalidad($nacionalidad);
        $payload = json_encode($nac);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Cripto::obtenerTodos();
        $payload = json_encode(array("listaUsuario" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        parse_str(file_get_contents('php://input'), $parametros);

        $nombre = $args['cripto'];
        $precio = $parametros['precio'];
        // $foto = $request->getUploadedFiles();
        $nacionalidad = $parametros['nacionalidad'];
        $foto = $parametros['foto'];

        $cripto = new Cripto();
        $cripto->nombre = $nombre;
        $cripto->precio = $precio;
        $cripto->foto = $foto;
        $cripto->nacionalidad = $nacionalidad;

        $arrCriptos =  Cripto::obtenerTodos();

        foreach ($arrCriptos as  $moneda) {
            if ($cripto->foto == $moneda->foto ) {
                $destino="./Backup/";
                $extension= explode(".", $moneda->foto);
                $destino .= $nombre . '/';
                if (!file_exists($destino)) {
                    mkdir($destino, 0777, true);
                }
                $origen = './fotos/' . strtolower($nombre) . '/' . $moneda->foto ;
                // $archivos['foto']->getClientFilename()
                // $moneda->foto->moveTo($destino . $moneda->foto);
                copy($origen,$destino. $cripto->foto);
                break;        
            }
        }

        $cripto->modificarCripto();
        

        $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $cripto = $args['cripto'];

        Cripto::borrarCripto($cripto);

        $payload = json_encode(array("mensaje" => "Criptomneda borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }






}
