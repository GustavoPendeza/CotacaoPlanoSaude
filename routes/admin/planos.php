<?php

    use \App\Http\Response;
    use \App\Controller\Admin;

    // ROTA DE LISTAGEM DE PLANOS
    $obRouter->get('/admin/planos', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request){
            return new Response(200, Admin\Planos::getPlanos($request));
        }
    ]);

    // ROTA DE CADASTRO DE NOVO PLANO
    $obRouter->get('/admin/planos/new', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request){
            return new Response(200, Admin\Planos::getNewPlano($request));
        }
    ]);

    // ROTA DE CADASTRO DE NOVO PLANO (POST)
    $obRouter->post('/admin/planos/new', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request){
            return new Response(200, Admin\Planos::setNewPlano($request));
        }
    ]);

    // ROTA DE EDIÇÃO DE UM PLANO
    $obRouter->get('/admin/planos/{id}/edit', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\Planos::getEditPlano($request, $id));
        }
    ]);

    // ROTA DE EDIÇÃO DE UM PLANO (POST)
    $obRouter->post('/admin/planos/{id}/edit', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\Planos::setEditPlano($request, $id));
        }
    ]);

    // ROTA DE EXCLUSÃO DE UM PLANO
    $obRouter->get('/admin/planos/{id}/delete', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\Planos::getDeletePlano($request, $id));
        }
    ]);

    // ROTA DE EXCLUSÃO DE UM PLANO (POST)
    $obRouter->post('/admin/planos/{id}/delete', [
        'middlewares' => [
            'required-admin-login'
        ],
        function($request, $id){
            return new Response(200, Admin\Planos::setDeletePlano($request, $id));
        }
    ]);

?>