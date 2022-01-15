<?php

    namespace App\Session\Admin;

    class Login {

        /**
         * Método reponsável por iniciar a sessão
         */
        private static function init(){
            // VERIFICA SE A SESSÃO NÃO ESTÁ ATIVA
            if (session_status() != PHP_SESSION_ACTIVE) {
                session_start();
            }
        }

        /**
         * Método responsável por criar o login do usuário
         * @param User $obUser
         * @return boolean
         */
        public static function login($obUser){
            // INICIA A SESSÃO
            self::init();

            // DEFINE A SESSÃO DO USUÁRIO
            $_SESSION['admin']['usuario'] = [
                'id' => $obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email
            ];

            return true;
        }

        /**
         * Método responsável por verificar se o usuário está logado
         * @return boolean
         */
        public static function isLogged(){
            // INICIA A SESSÃO
            self::init();

            // RETORNA A VERIFICAÇÃO
            return isset($_SESSION['admin']['usuario']['id']);
        }

        /**
         * Método responsável por retornar informações sobre o login
         * @return boolean
         */
        public static function infoLogin(){
            // INICIA A SESSÃO
            self::init();

            // ARMAZENA INFORMAÇÕES NUMA VARIÁVEL
            $user = $_SESSION['admin']['usuario'];

            // RETORNA A INFORMAÇÕES DA SESSÃO
            return $user;
        }

        /**
         * Método responsável por executar o logout do usuário
         * @return boolean
         */
        public static function logout(){
            // INICIA A SESSÃO
            self::init();

            // DESLOGA O USUÁRIO
            unset($_SESSION['admin']['usuario']);

            // SUCESSO
            return true;
        }

    }

?>