<?php

    namespace App\Utils;

    class View {

        // Variaveis padrões da View
        private static $vars = [];

        public static function init($vars = []){
            self::$vars = $vars;
        }

        // Método responsável por definir os dados iniciais da classe
        private static function getContentView($view){

            $file = __DIR__.'/../../resources/views/'.$view.'.html';

            return file_exists($file) ? file_get_contents($file) : '';
        }

        public static function render($view, $vars = []){
            // Conteúdo da View
            $contentview = self::getContentView($view);

            // JUNÇÃO DAS VARIÁVEIS DA VIEW
            $vars = array_merge(self::$vars, $vars);

            // Chaves do array de variáveis
            $keys = array_keys($vars);
            $keys = array_map(function($item){
                return '{{'.$item.'}}';
            }, $keys);

            // Retorna o contreúdo renderizado
            return str_replace($keys, array_values($vars), $contentview);
        }

    }

?>