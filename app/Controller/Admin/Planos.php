<?php

    namespace App\Controller\Admin;

    use \App\Utils\View;
    use \App\Model\Entity\Planos as EntityPlanos;
    use \App\Model\Entity\Empresas as EntityEmpresas;
    use WilliamCosta\DatabaseManager\Pagination;

    class Planos extends Page {

        /**
         * Método responsável por obter a renderização dos itens de planos para a página
         * @param Request $request
         * @param Pagination $obPagination
         * @return string
         */
        private static function getPlanoItems($request, &$obPagination){
            // PLANOS
            $itens = '';

            // QUANTIDADE TOTAL DE REGISTROS
            $quantidadeTotal = EntityPlanos::getPlanos(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            // PÁGINA ATUAL
            $queryParams = $request->getQueryParams();
            $paginaAtual = $queryParams['page'] ?? 1;

            // INSTÂNCIA DE PAGINAÇÃO
            $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

            // RESULTADOS DA PÁGINA
            $results = EntityPlanos::getPlanos(null, 'id DESC', $obPagination->getLimit());

            while ($obPlano = $results->fetchObject(EntityPlanos::class)) {
                // PEGANDO NOME DA EMPRESA COM BASE NO IDEMPRESA
                $obPlano->empresa = EntityEmpresas::getNomeEmpresaByPlano($obPlano->idempresa);
                $obPlano->empresa = $obPlano->empresa->empresa;

                $itens .=  View::render('admin/modules/planos/item', [
                    'id' => $obPlano->id,
                    'idempresa' => $obPlano->empresa,
                    'plano' => $obPlano->plano
                ]);
            }

            return $itens;
        }

        /**
         * Método responsável por renderizar a view de listagem de planos
         * @param Request $request
         * @return string
         */
        public static function getPlanos($request){
            // CONTEÚDO DA HOME
            $content = View::render('admin/modules/planos/index', [
                'itens' => self::getPlanoItems($request, $obPagination),
                'pagination' => parent::getPagination($request, $obPagination),
                'status' => self::getStatus($request)
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Planos - Admin', $content, 'planos');
        }

        /**
         * Método responsável por retornar o formulário de cadastro de um novo plano
         * @param Request $request
         * @return string
         */
        public static function getNewPlano($request){
            $empresas = '';
            $results = EntityEmpresas::getEmpresas(null, 'id ASC');
            
            while ($obEmpresa = $results->fetchObject(EntityEmpresas::class)) {
                $empresas .=  View::render('admin/modules/planos/option', [
                    'idempresa' => $obEmpresa->id,
                    'empresa' => $obEmpresa->empresa
                ]);
            }

            // CONTEÚDO DO FORMULÁRIO
            $content = View::render('admin/modules/planos/form', [
                'title' => 'Cadastrar Plano',
                'idselecionado' => '',
                'selecionado' => 'Selecione uma empresa',
                'empresas' => $empresas,
                'plano' => '',
                'status' => ''
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Cadastrar planos - Admin', $content, 'planos');
        }

        /**
         * Método responsável por cadastrar um plano no banco
         * @param Request $request
         * @return string
         */
        public static function setNewPlano($request){
            // POST VARS
            $postVars = $request->getPostVars();
            
            // NOVA INSTÂNCIA DE PLANO
            $obPlano = new EntityPlanos;
            $obPlano->idempresa = $postVars['idempresa'] ?? '';
            $obPlano->plano = $postVars['plano'] ?? '';
            $obPlano->cadastrar();

            // REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/planos?status=created');
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
                    return Alert::getSuccess('Plano criado com sucesso!');
                    break;
                case 'updated':
                    return Alert::getSuccess('Plano atualizado com sucesso!');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Plano excluído com sucesso!');
                    break;
            }
        }

        /**
         * Método responsável por retornar o formulário de edição de um plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function getEditPlano($request, $id){
            // OBTÉM O PLANO DO BANCO DE DADOS
            $obPlano = EntityPlanos::getPlanoById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obPlano instanceof EntityPlanos){
                $request->getRouter()->redirect('/admin/planos');
            }

            $empresas = '';
            $results = EntityEmpresas::getEmpresas(null, 'id ASC');
            
            while ($obEmpresa = $results->fetchObject(EntityEmpresas::class)) {
                $empresas .=  View::render('admin/modules/planos/option', [
                    'idempresa' => $obEmpresa->id,
                    'empresa' => $obEmpresa->empresa
                ]);
            }

            // PEGANDO NOME DA EMPRESA COM BASE NO IDEMPRESA
            $obPlano->empresa = EntityEmpresas::getNomeEmpresaByPlano($obPlano->idempresa);
            $obPlano->empresa = $obPlano->empresa->empresa;

            // CONTEÚDO DO FORMULÁRIO
            $content = View::render('admin/modules/planos/form', [
                'title' => 'Editar Plano',
                'idselecionado' => $obPlano->idempresa,
                'selecionado' => $obPlano->empresa,
                'empresas' => $empresas,
                'plano' => $obPlano->plano,
                'status' => self::getStatus($request)
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Editar plano - Admin', $content, 'planos');
        }

        /**
         * Método responsável por gravar a atualização de um plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function setEditPlano($request, $id){
            // OBTÉM O PLANO DO BANCO DE DADOS
            $obPlano = EntityPlanos::getPlanoById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obPlano instanceof EntityPlanos){
                $request->getRouter()->redirect('/admin/planos');
            }

            // POST VARS
            $postVars = $request->getPostVars();

            // ATUALIZA A INSTÂNCIA
            $obPlano->idempresa = $postVars['idempresa'] ?? $obPlano->idempresa;
            $obPlano->plano = $postVars['plano'] ?? $obPlano->plano;
            $obPlano->atualizar();

            // REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/planos/'.$obPlano->id.'/edit?status=updated');
        }

        /**
         * Método responsável por retornar o formulário de exclusão de um plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function getDeletePlano($request, $id){
            // OBTÉM O PLANO DO BANCO DE DADOS
            $obPlano = EntityPlanos::getPlanoById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obPlano instanceof EntityPlanos){
                $request->getRouter()->redirect('/admin/planos');
            }

            // PEGANDO NOME DA EMPRESA COM BASE NO IDEMPRESA
            $obPlano->empresa = EntityEmpresas::getNomeEmpresaByPlano($obPlano->idempresa);
            $obPlano->empresa = $obPlano->empresa->empresa;

            // CONTEÚDO DO FORMULÁRIO
            $content = View::render('admin/modules/planos/delete', [
                'empresa' => $obPlano->empresa,
                'plano' => $obPlano->plano
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Excluir plano - Admin', $content, 'planos');
        }

        /**
         * Método responsável por excluir um plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function setDeletePlano($request, $id){
            // OBTÉM O PLANO DO BANCO DE DADOS
            $obPlano = EntityPlanos::getPlanoById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obPlano instanceof EntityPlanos){
                $request->getRouter()->redirect('/admin/planos');
            }

            // EXCLUI O PLANO
            $obPlano->excluir();

            // REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/planos?status=deleted');
        }

    }

?>