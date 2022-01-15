<?php

    namespace App\Controller\Pages;

    use App\Utils\View;
    use App\Controller\Admin\Alert;
    //use App\Model\Entity\Amil;
    use \App\Model\Entity\Precos as EntityPrecos;
    use \App\Model\Entity\Planos as EntityPlanos;
    use \App\Model\Entity\TipoPlanos as EntityTipoPlanos;

    class Amil extends Page {

        public static function getNewAmil($request){

            $precos = '';

            $results = EntityPrecos::getPrecos('idempresa = 1', 'id ASC', null, 'id, idempresa, idplano, idtipoplano');

            // PEGA O INFORMAÇÕES DE PREÇOS CADASTRADAS NO BANCO
            while ($obPrecos = $results->fetchObject(EntityPrecos::class)) {

                // PEGANDO NOME DO PLANO COM BASE NO IDPLANO
                $obPrecos->plano = EntityPlanos::getNomePlanoByPrecos($obPrecos->idplano);
                $obPrecos->plano = $obPrecos->plano->plano;

                // PEGANDO TIPO DE PLANO COM BASE NO IDTIPOPLANO
                $obPrecos->tipoplano = EntityTipoPlanos::getNomeTipoPlanoByPrecos($obPrecos->idtipoplano);
                $obPrecos->tipoplano = $obPrecos->tipoplano->tipoplano;

                $precos .=  View::render('pages/amil/option', [
                    'idprecos' => $obPrecos->id,
                    'plano' => $obPrecos->plano,
                    'tipoplano' => $obPrecos->tipoplano
                ]);
            }

            // View da Home
            $content =  View::render('pages/amil/index', [
                'title' => 'Cotação Amil',
                'precos' => $precos,
                'status' => self::getStatus($request)
            ]);
            // Retorna a view da Home
            return parent::getPage('Amil', $content);
        }

        /**
         * Método responsável por retornar a mensagem de status
         * @param Request $request
         * @return string
         */
        private static function getStatus($request){
            // QUERY PARAMS
            $queryParams = $request->getQueryParams();

            // VERIFICA SE STATUS EXISTE
            if(!isset($queryParams['status'])) return '';

            // MENSAGENS DE STATUS
            switch ($queryParams['status']) {
                case 'created':
                    return Alert::getSuccess('Cotação Amil criada criado com sucesso!');
                    break;
                case 'updated':
                    return Alert::getSuccess('Cotação Amil atualizado com sucesso!');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Cotação Amil excluído com sucesso!');
                    break;
            }
        }

    }

?>