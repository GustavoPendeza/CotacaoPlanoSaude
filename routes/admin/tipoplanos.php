<?php

    use \App\Http\Response;
    use \App\Controller\Admin;

    // ROTA DE LISTAGEM DE TIPOS DE PLANOS
    $obRouter->get('/admin/tipoplanos', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request){
            return new Response(200, Admin\TipoPlanos::getTipoPlanos($request));
        }
    ]);

    // ROTA DE CADASTRO DE NOVO TIPO DE PLANO
    $obRouter->get('/admin/tipoplanos/new', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request){
            return new Response(200, Admin\TipoPlanos::getNewTipoPlano($request));
        }
    ]);

    // ROTA DE CADASTRO DE NOVO TIPO DE PLANO (POST)
    $obRouter->post('/admin/tipoplanos/new', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request){
            return new Response(200, Admin\TipoPlanos::setNewTipoPlano($request));
        }
    ]);

    // ROTA DE EDIÇÃO DE UM TIPO DE PLANO
    $obRouter->get('/admin/tipoplanos/{id}/edit', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\TipoPlanos::getEditTipoPlano($request, $id));
        }
    ]);

    // ROTA DE EDIÇÃO DE UM TIPO DE PLANO (POST)
    $obRouter->post('/admin/tipoplanos/{id}/edit', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\TipoPlanos::setEditTipoPlano($request, $id));
        }
    ]);

    // ROTA DE EXCLUSÃO DE UM TIPO DE PLANO
    $obRouter->get('/admin/tipoplanos/{id}/delete', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\TipoPlanos::getDeleteTipoPlano($request, $id));
        }
    ]);

    // ROTA DE EXCLUSÃO DE UM TIPO DE PLANO (POST)
    $obRouter->post('/admin/tipoplanos/{id}/delete', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\TipoPlanos::setDeleteTipoPlano($request, $id));
        }
    ]);

?>