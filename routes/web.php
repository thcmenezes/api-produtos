<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/ncms/capturar/portal_unico', [
    'as' => 'capturar_portal_unico', 'uses' => 'NcmsController@capturarPortalUnico'
]);

$router->post('/api/login', 'TokenController@gerarToken');

$router->group(['prefix' => 'api', 'middleware' => 'autenticador'], function() use($router) {
    
    $router->get('produtos[/{produtoId}]', 'ProdutosController@index');
    $router->post('produtos', 'ProdutosController@store');
    $router->put('produtos/{produtoId}', 'ProdutosController@update');
    $router->delete('produtos/{produtoId}', 'ProdutosController@destroy');

    $router->get('ncms[/{ncmId}]', 'NcmsController@index');
    $router->post('ncms', 'NcmsController@store');
    $router->put('ncms/{ncmId}', 'NcmsController@update');
    $router->delete('ncms/{ncmId}', 'NcmsController@destroy');
    
}); 
