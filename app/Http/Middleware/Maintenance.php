<?php

    namespace App\Http\Middleware;

use \Exception;

class Maintenance {

        /**
         * Método reponsável por executar o middleware
         * @param Request $request
         * @param Closure $next
         * @return Response
         */
        public function handle($request, $next){
            // VARIFICA O ESTADO DE MANUTENÇÃO DA PÁGINA
            if (getenv('MAINTENANCE') == 'true') {
                throw new Exception("Página em manutenção. Tente novamente mais tarde", 200);
            };
            // EXECUTA O PRÓXIMO NÍVEL DE MIDDLEWARE
            return $next($request);
        }

    }

?>