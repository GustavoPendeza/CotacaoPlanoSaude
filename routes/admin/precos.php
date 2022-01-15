<?php

    use \App\Http\Response;
    use \App\Controller\Admin;

    // ROTA DE LISTAGEM DE PREÇOS DE UM PLANO
    $obRouter->get('/admin/precos', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request){
            return new Response(200, Admin\Precos::getPrecos($request));
        }
    ]);

    // ROTA DE CADASTRO DE NOVOS PREÇOS DE UM PLANO
    $obRouter->get('/admin/precos/new', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request){
            return new Response(200, Admin\Precos::getNewPrecos($request));
        }
    ]);

    // ROTA DE CADASTRO DE NOVOS PREÇOS DE UM PLANO (POST)
    $obRouter->post('/admin/precos/new', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request){
            return new Response(200, Admin\Precos::setNewPrecos($request));
        }
    ]);

    // ROTA DE INFORMAÇÃO DE PREÇOS DE UM PLANO
    $obRouter->get('/admin/precos/{id}/info', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\Precos::getInfoPrecos($request, $id));
        }
    ]);

    // ROTA DE EDIÇÃO DE UM PREÇOS DE PLANO
    $obRouter->get('/admin/precos/{id}/edit', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\Precos::getEditPrecos($request, $id));
        }
    ]);

    // ROTA DE EDIÇÃO DE PREÇOS DE UM PLANO (POST)
    $obRouter->post('/admin/precos/{id}/edit', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\Precos::setEditPrecos($request, $id));
        }
    ]);

    // ROTA DE EXCLUSÃO DE PREÇOS DE UM PLANO
    $obRouter->get('/admin/precos/{id}/delete', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\Precos::getDeletePrecos($request, $id));
        }
    ]);

    // ROTA DE EXCLUSÃO DE PREÇOS DE UM PLANO (POST)
    $obRouter->post('/admin/precos/{id}/delete', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\Precos::setDeletePrecos($request, $id));
        }
    ]);

?>