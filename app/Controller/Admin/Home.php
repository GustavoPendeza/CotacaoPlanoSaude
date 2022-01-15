<?php

    namespace App\Controller\Admin;

    use \App\Utils\View;
    use App\Session\Admin\Login as SessionAdminLogin;

    class Home extends Page {

        /**
         * Método responsável por renderizar a view de home do painel
         * @param Request $request
         * @return string
         */
        public static function getHome($request){
            // PEGANDO INFORMAÇÕES DO LOGIN
            $obUser = SessionAdminLogin::infoLogin();

            // CONTEÚDO DA HOME
            $content = View::render('admin/modules/home/index', [
                'nome' => $obUser['nome']
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Home - Admin', $content, 'home');
        }

    }

?>