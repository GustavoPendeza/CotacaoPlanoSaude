<?php

    namespace App\Controller\Admin;

    use \App\Utils\View;
    use \App\Model\Entity\TipoPlanos as EntityTipoPlanos;
    use \App\Model\Entity\Empresas as EntityEmpresas;
    use WilliamCosta\DatabaseManager\Pagination;

    class TipoPlanos extends Page {

        /**
         * Método responsável por obter a renderização dos itens dos tipos de planos para a página
         * @param Request $request
         * @param Pagination $obPagination
         * @return string
         */
        private static function getTipoPlanoItems($request, &$obPagination){
            // PLANOS
            $itens = '';

            // QUANTIDADE TOTAL DE REGISTROS
            $quantidadeTotal = EntityTipoPlanos::getTipoPlanos(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            // PÁGINA ATUAL
            $queryParams = $request->getQueryParams();
            $paginaAtual = $queryParams['page'] ?? 1;

            // INSTÂNCIA DE PAGINAÇÃO
            $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

            // RESULTADOS DA PÁGINA
            $results = EntityTipoPlanos::getTipoPlanos(null, 'id DESC', $obPagination->getLimit());

            while ($obTipoPlano = $results->fetchObject(EntityTipoPlanos::class)) {
                // PEGANDO NOME DA EMPRESA COM BASE NO IDEMPRESA
                $obTipoPlano->empresa = EntityEmpresas::getNomeEmpresaByTipoPlano($obTipoPlano->idempresa);
                $obTipoPlano->empresa = $obTipoPlano->empresa->empresa;

                $itens .=  View::render('admin/modules/tipoplanos/item', [
                    'id' => $obTipoPlano->id,
                    'idempresa' => $obTipoPlano->empresa,
                    'tipoplano' => $obTipoPlano->tipoplano
                ]);
            }

            return $itens;
        }

        /**
         * Método responsável por renderizar a view de listagem de tipos de planos
         * @param Request $request
         * @return string
         */
        public static function getTipoPlanos($request){
            // CONTEÚDO DA HOME
            $content = View::render('admin/modules/tipoplanos/index', [
                'itens' => self::getTipoPlanoItems($request, $obPagination),
                'pagination' => parent::getPagination($request, $obPagination),
                'status' => self::getStatus($request)
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Tipos de planos - Admin', $content, 'tipoplanos');
        }

        /**
         * Método responsável por retornar o formulário de cadastro de um novo tipo de plano
         * @param Request $request
         * @return string
         */
        public static function getNewTipoPlano($request){
            $empresas = '';
            $results = EntityEmpresas::getEmpresas(null, 'id ASC');
            
            while ($obEmpresa = $results->fetchObject(EntityEmpresas::class)) {
                $empresas .=  View::render('admin/modules/tipoplanos/option', [
                    'idempresa' => $obEmpresa->id,
                    'empresa' => $obEmpresa->empresa
                ]);
            }

            // CONTEÚDO DO FORMULÁRIO
            $content = View::render('admin/modules/tipoplanos/form', [
                'title' => 'Cadastrar Tipo de Plano',
                'idselecionado' => '',
                'selecionado' => 'Selecione uma empresa',
                'empresas' => $empresas,
                'tipoplano' => '',
                'status' => ''
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Cadastrar tipos de planos - Admin', $content, 'tipoplanos');
        }

        /**
         * Método responsável por cadastrar um tipo de plano no banco
         * @param Request $request
         * @return string
         */
        public static function setNewTipoPlano($request){
            // POST VARS
            $postVars = $request->getPostVars();
            
            // NOVA INSTÂNCIA DE TIPO DE PLANO
            $obTipoPlano = new EntityTipoPlanos;
            $obTipoPlano->idempresa = $postVars['idempresa'] ?? '';
            $obTipoPlano->tipoplano = $postVars['tipoplano'] ?? '';
            $obTipoPlano->cadastrar();

            // REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/tipoplanos?status=created');
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
                    return Alert::getSuccess('Tipo de plano criado com sucesso!');
                    break;
                case 'updated':
                    return Alert::getSuccess('Tipo de plano atualizado com sucesso!');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Tipo de plano excluído com sucesso!');
                    break;
            }
        }

        /**
         * Método responsável por retornar o formulário de edição de um tipo de plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function getEditTipoPlano($request, $id){
            // OBTÉM O TIPO DE PLANO DO BANCO DE DADOS
            $obTipoPlano = EntityTipoPlanos::getTipoPlanoById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obTipoPlano instanceof EntityTipoPlanos){
                $request->getRouter()->redirect('/admin/tipoplanos');
            }

            $empresas = '';
            $results = EntityEmpresas::getEmpresas(null, 'id ASC');
            
            while ($obEmpresa = $results->fetchObject(EntityEmpresas::class)) {
                $empresas .=  View::render('admin/modules/tipoplanos/option', [
                    'idempresa' => $obEmpresa->id,
                    'empresa' => $obEmpresa->empresa
                ]);
            }

            // PEGANDO NOME DA EMPRESA COM BASE NO IDEMPRESA
            $obTipoPlano->empresa = EntityEmpresas::getNomeEmpresaByTipoPlano($obTipoPlano->idempresa);
            $obTipoPlano->empresa = $obTipoPlano->empresa->empresa;

            // CONTEÚDO DO FORMULÁRIO
            $content = View::render('admin/modules/tipoplanos/form', [
                'title' => 'Editar Tipo de Plano',
                'idselecionado' => $obTipoPlano->idempresa,
                'selecionado' => $obTipoPlano->empresa,
                'empresas' => $empresas,
                'tipoplano' => $obTipoPlano->tipoplano,
                'status' => self::getStatus($request)
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Editar tipo de plano - Admin', $content, 'tipoplanos');
        }

        /**
         * Método responsável por gravar a atualização de um tipo de plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function setEditTipoPlano($request, $id){
            // OBTÉM O TIPO DE PLANO DO BANCO DE DADOS
            $obTipoPlano = EntityTipoPlanos::getTipoPlanoById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obTipoPlano instanceof EntityTipoPlanos){
                $request->getRouter()->redirect('/admin/tipoplanos');
            }

            // POST VARS
            $postVars = $request->getPostVars();

            // ATUALIZA A INSTÂNCIA
            $obTipoPlano->idempresa = $postVars['idempresa'] ?? $obTipoPlano->idempresa;
            $obTipoPlano->tipoplano = $postVars['tipoplano'] ?? $obTipoPlano->tipoplano;
            $obTipoPlano->atualizar();

            // REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/tipoplanos/'.$obTipoPlano->id.'/edit?status=updated');
        }

        /**
         * Método responsável por retornar o formulário de exclusão de um tipo de plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function getDeleteTipoPlano($request, $id){
            // OBTÉM O TIPO DE PLANO DO BANCO DE DADOS
            $obTipoPlano = EntityTipoPlanos::getTipoPlanoById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obTipoPlano instanceof EntityTipoPlanos){
                $request->getRouter()->redirect('/admin/tipoplanos');
            }

            // PEGANDO NOME DA EMPRESA COM BASE NO IDEMPRESA
            $obTipoPlano->empresa = EntityEmpresas::getNomeEmpresaByTipoPlano($obTipoPlano->idempresa);
            $obTipoPlano->empresa = $obTipoPlano->empresa->empresa;

            // CONTEÚDO DO FORMULÁRIO
            $content = View::render('admin/modules/tipoplanos/delete', [
                'empresa' => $obTipoPlano->empresa,
                'tipoplano' => $obTipoPlano->tipoplano
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Excluir tipo de plano - Admin', $content, 'tipoplanos');
        }

        /**
         * Método responsável por excluir um tipo de plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function setDeleteTipoPlano($request, $id){
            // OBTÉM O TIPO DE PLANO DO BANCO DE DADOS
            $obTipoPlano = EntityTipoPlanos::getTipoPlanoById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obTipoPlano instanceof EntityTipoPlanos){
                $request->getRouter()->redirect('/admin/tipoplanos');
            }

            // EXCLUI O PLANO
            $obTipoPlano->excluir();

            // REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/tipoplanos?status=deleted');
        }

    }

?>