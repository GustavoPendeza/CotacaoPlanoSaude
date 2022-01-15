<?php

    namespace App\Controller\Admin;

    use App\Http\Request;
    use App\Model\Entity\User;
    use App\Utils\View;
    use App\Session\Admin\Login as SessionAdminLogin;

    class Login extends Page {

        /**
         * Método responsável por retornar a renderização da página de login
         * @param Request $request
         * @param string $errorMessage
         * @return string
         */
        public static function getLogin($request, $errorMessage = null){
            // STATUS
            $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';

            // CONTEÚDO DA PÁGINA DE LOGIN
            $content = View::render('admin/login', [
                'status' => $status
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPage('Login', $content);
        }

        /**
         * Método responsável por definir o login do usuário
         * @param Request $request
         */
        public static function setLogin($request){
            // POST VARS
            $postVars = $request->getPostVars();
            $email = $postVars['email'] ?? '';
            $senha = $postVars['senha'] ?? '';

            // BUSCA USUÁRIO PELO BANCO || VERIFICA A SENHA DO USUÁRIO
            $obUser = User::getUserByEmail($email);
            if (!$obUser instanceof User || !password_verify($senha, $obUser->senha)) {
                return self::getLogin($request, 'E-mail ou Senha inválidos');
            }

            // CRIA SESSÃO DE LOGIN
            SessionAdminLogin::login($obUser);

            // REDIRECIONA O USUÁRIO PARA A HOME DO ADMIN
            $request->getRouter()->redirect('/admin');
        }

        /**
         * Método responsável por deslogar o usuário
         * @param Request $request
         */
        public static function setLogout($request){
            // DESTROI SESSÃO DE LOGIN
            SessionAdminLogin::logout();

            // REDIRECIONA O USUÁRIO PARA A TELA DE LOGIN
            $request->getRouter()->redirect('/admin/login');
        }

    }

?>