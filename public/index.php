<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/CriptoController.php';
require_once './controllers/VentaController.php';
require_once './middlewares/AutentificadorJWT.php';
require_once './middlewares/MWParaAutenticar.php';
require_once './middlewares/MWParaAutorizar.php';
require_once './middlewares/JSONMiddleware.php';
require_once './db/AccesoDatos.php';

// require('fpdf.php');


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
// $app->setBasePath('/public');
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("hola alumnos de los lunes!");
    return $response;
});

// peticiones
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{usuario}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
  })->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');

  $app->group('/logueo', function (RouteCollectorProxy $group) {
    $group->post('/', \UsuarioController::class . ':Loguear');
  });

  $app->group('/cripto', function (RouteCollectorProxy $group) {
    $group->post('[/]', \CriptoController::class . ':CargarUno');
  })->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');

  $app->group('/listar', function (RouteCollectorProxy $group) {
    $group->get('/{nacionalidad}', \CriptoController::class . ':TraerUno');
    $group->get('/', \CriptoController::class . ':TraerTodos');
  })->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');

  $app->group('/venta', function (RouteCollectorProxy $group) {
    // $group->get('/{nacionalidad}', \CriptoController::class . ':TraerUno');
    // $group->get('/', \CriptoController::class . ':TraerTodos');
    $group->post('[/]', \VentaController::class . ':CargarUno')->add(\MWParaAutenticar::class . ':Autenticacion');
    $group->get('/{nacionalidad}', \VentaController::class . ':NacionalidadPorFecha')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');
    $group->get('/moneda/{moneda}', \VentaController::class . ':TraerPorVentaNombre')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');
  });

  $app->group('/borrar', function (RouteCollectorProxy $group) {
    $group->delete('/{cripto}', \CriptoController::class . ':BorrarUno')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');

  });

  $app->group('/modificar', function (RouteCollectorProxy $group) {
    $group->put('/{cripto}', \CriptoController::class . ':ModificarUno')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');
  });

  $app->group('/descargar', function (RouteCollectorProxy $group) {
    $group->get('[/]', \VentaController::class . ':descargaPDF')->add(\MWParaAutenticar::class . ':Autenticacion')->add(\MWAutorizar::class . ':Autorizacion');
  });


// Run app
$app->run();

