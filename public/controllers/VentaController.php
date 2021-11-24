<?php

require_once './models/Venta.php';
require_once './models/Cripto.php';
require_once './models/Usuario.php';
require_once './models/fpdf.php';
require_once './interfaces/IApiUsable.php';

use Psr\Http\Message\UploadedFileInterface;

//  require('fpdf.php');

class VentaController extends Venta implements IApiUsable
{

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre_usuario = $parametros['nombre_usuario'];
        $archivos = $request->getUploadedFiles();
        $nombre_cripto = $parametros['nombre_cripto'];
        $cantidad = $parametros['cantidad'];
        $extension = explode(".", $archivos['foto']->getClientFilename());
        $fecha = date("Y-m-d");

        $ObjCripto = Cripto::obtenerCriptoPorNombre($nombre_cripto);
        $ObjUsuario = Usuario::obtenerUsuarioPorNombre($nombre_usuario);

        // var_dump($ObjUsuario);

        if ($archivos['foto']->getError() == UPLOAD_ERR_OK) {
            $destino = "./fotosCripto/";
            $extension = explode(".", $archivos['foto']->getClientFilename());
            $destino .= $nombre_cripto . '/';
            if (!file_exists($destino)) {
                mkdir($destino, 0777, true);
            }
            $cliente = explode('@', $nombre_usuario);
            $nombreFoto = $extension[0] . '-' . $cliente[0] . '-' . $fecha . '.' . $extension[1];
            // $archivos['foto']->getClientFilename()
            $archivos['foto']->moveTo($destino . $nombreFoto);
            //  $foto = $archivos['foto']->getClientFilename();
            // var_dump($nombreFoto);
        }

        $cripto = new Venta();
        $cripto->id_usuario = $ObjUsuario->id_usuario;
        $cripto->id_cripto = $ObjCripto->id_cripto;
        $cripto->nombre_cripto = $nombre_cripto;
        $cripto->nacionalidad_cripto = $ObjCripto->nacionalidad;
        $cripto->cantidad = $cantidad;
        $cripto->foto_venta = $nombreFoto;
        $cripto->crearVenta();

        $payload = json_encode(array("mensaje" => "Venta creado con exito"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $nacionalidad = $args['nacionalidad'];
        $nac = Cripto::obtenerCriptoNacionalidad($nacionalidad);
        $payload = json_encode($nac);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function NacionalidadPorFecha($request, $response, $args)
    {
        $nacionalidadID = $args['nacionalidad'];
        // $nac = Cripto::obtenerCriptoNacionalidad($nacionalidadID);

        $criptosNac = Venta::obtenerCriptoNacionalidad($nacionalidadID);

        $payload = json_encode($criptosNac);

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPorVentaNombre($request, $response, $args)
    {
        $nombre = $args['moneda'];
        // $nac = Cripto::obtenerCriptoNacionalidad($nacionalidadID);

        $nombreCriptos = Venta::obtenerPorNombre($nombre);

        // $jsonArr = json_encode($nombreCriptos);

        $payload = json_encode($nombreCriptos);

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

    public function descargaPDF($request, $response, $args)
    {

        $lista = Venta::obtenerTodos();

        // $cadena = "<ul>";
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 21);
        $pdf->Cell(150,10,'Venta de Criptomonedas [ ADMIN ] ', 1, 2 , 'C');
        $pdf->Ln();
        $pdf->SetFont('courier', 'B', 11);
        foreach ($lista as $venta) {
            $cadena =  'Nombre Cripto:' . $venta->nombre_cripto . ' Nacionalidad:' . $venta->nacionalidad_cripto . ' Cantidad:' . $venta->cantidad .
                ' ID Cripto:' . $venta->id_cripto;
            $pdf->Cell(12, 5, $cadena,0,1,'L');
            $pdf->Ln();
        }
        // $cadena .= "</ul>";

        $pdf->Output('F', './pdf/pdfVentas.pdf', false);


        $payload = json_encode(array("PDF generado"));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ModificarUno($request, $response, $args)
    {
        // $parametros = $request->getParsedBody();

        // $nombre = $parametros['nombre'];
        // Usuario::modificarUsuario($nombre);

        // $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

        // $response->getBody()->write($payload);
        // return $response
        //   ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        // $parametros = $request->getParsedBody();

        // $usuarioId = $parametros['usuarioId'];
        // Usuario::borrarUsuario($usuarioId);

        // $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        // $response->getBody()->write($payload);
        // return $response
        //   ->withHeader('Content-Type', 'application/json');
    }
}
