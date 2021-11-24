<?php

require_once __DIR__.'/AutentificadorJWT.php';
require_once __DIR__.'/MWParaAutenticar.php';

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface;
// use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;

class MWAutorizar
{
    public $rolesUsuarios = ['admin'];
    public $rolesCripto = ['admin'];
    public $rolesListar = ['admin','cliente'];
    public $rolesVenta = ['admin'];
    public $rolesListarVenta = ['admin'];
    public $rolesBorrar = ['admin'];
    public $rolesModificar = ['admin'];
    public $rolesDescargar = ['admin'];
    // public $rolesPedidos = ['Socio', 'Mozo', 'Bartender', 'Cervecero', 'Cocinero'];
    // public $rolesInformes = ['Socio'];
    
    public function Autorizacion(Request $request, RequestHandlerInterface $handler): ResponseInterface
    {
        //Obtengo la seccion desde la ruta del uri 
        $path = $request->getUri()->getPath();
        $seccion = explode('/', $path);
        $flag = 0;

        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $data = AutentificadorJWT::ObtenerData($token);

        if(in_array($data->codigo, $this->{'roles' . ucfirst($seccion[1])})){
            $response = $handler->handle($request);
            $flag = 1;
        }

      
        elseif (in_array('listar',$seccion)) {
        if (in_array($data->codigo, $this->{'roles' . ucfirst($seccion[2])})) {
            $response = $handler->handle($request);
        }  
       } 
        // ($flag == 0  && $seccion[2] != NULL){
        //     $seccionDos = explode('/', $path)[2];
        //     if (in_array($data->codigo, $this->{'roles' . ucfirst($seccionDos)})) {
        //         $response = $handler->handle($request);
        //     }  
        // }

        else{
            $responseFactory = new ResponseFactory();
            $response = $responseFactory->createResponse(400, 'Access Denied');
            $response->getBody()->write(json_encode(["mensaje"=>"No tiene los permisos necesarios"]));
            return $response;
        }
        return $response;
    }
}