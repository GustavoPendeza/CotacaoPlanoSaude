<?php

    use \App\Http\Response;
    use \App\Controller\Pages;

    // ROTA DA HOME
    $obRouter->get('/', [
        function(){
            return new Response(200, Pages\Home::getHome());
        }
    ]);

    // ROTA DO COTAÇÃO AMIL
    $obRouter->get('/amil', [
        function($request){
            return new Response(200, Pages\Amil::getNewAmil($request));
        }
    ]);

    // ROTA DE DEPOIMENTOS
    /*$obRouter->get('/depoimentos', [
        function($request){
            return new Response(200, Pages\Testimony::getTestimonies($request));
        }
    ]);

    // ROTA DE DEPOIMENTOS (INSERT)
    $obRouter->post('/depoimentos', [
        function($request){
            return new Response(200, Pages\Testimony::insertTestimony($request));
        }
    ]);*/

?>