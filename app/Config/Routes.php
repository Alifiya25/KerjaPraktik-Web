<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/menu', 'MenuController::index');
$routes->get('/input-data', 'MenuController::inputData');
$routes->get('/grafik-data', 'MenuController::grafikData');

$routes->get('/data/tabel_thomson', 'ExcelViewerController::tabelThomson');
$routes->get('lihat/tabel_ambang', 'ExcelViewerController::tabelAmbangBatas');





