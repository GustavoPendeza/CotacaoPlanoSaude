<?php

    namespace App\Controller\Admin;

    use \App\Utils\View;
    use \App\Model\Entity\Precos as EntityPrecos;
    use \App\Model\Entity\Planos as EntityPlanos;
    use \App\Model\Entity\TipoPlanos as EntityTipoPlanos;
    use \App\Model\Entity\Empresas as EntityEmpresas;
    use WilliamCosta\DatabaseManager\Pagination;

    class Precos extends Page {

        /**
         * Método responsável por obter a renderização dos itens de preços para a página
         * @param Request $request
         * @param Pagination $obPagination
         * @return string
         */
        private static function getPrecosItems($request, &$obPagination){
            // PLANOS
            $itens = '';

            // QUANTIDADE TOTAL DE REGISTROS
            $quantidadeTotal = EntityPrecos::getPrecos(null, null, null, 'COUNT(*) as qtd')->fetchObject()->qtd;

            // PÁGINA ATUAL
            $queryParams = $request->getQueryParams();
            $paginaAtual = $queryParams['page'] ?? 1;

            // INSTÂNCIA DE PAGINAÇÃO
            $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 5);

            // RESULTADOS DA PÁGINA
            $results = EntityPrecos::getPrecos(null, 'id DESC', $obPagination->getLimit());

            while ($obPrecos = $results->fetchObject(EntityPrecos::class)) {
                // PEGANDO NOME DA EMPRESA COM BASE NO IDEMPRESA
                $obPrecos->empresa = EntityEmpresas::getNomeEmpresaByPrecos($obPrecos->idempresa);
                $obPrecos->empresa = $obPrecos->empresa->empresa;

                // PEGANDO NOME DO PLANO COM BASE NO IDPLANO
                $obPrecos->plano = EntityPlanos::getNomePlanoByPrecos($obPrecos->idplano);
                $obPrecos->plano = $obPrecos->plano->plano;

                // PEGANDO TIPO DE PLANO COM BASE NO IDTIPOPLANO
                $obPrecos->tipoplano = EntityTipoPlanos::getNomeTipoPlanoByPrecos($obPrecos->idtipoplano);
                $obPrecos->tipoplano = $obPrecos->tipoplano->tipoplano;

                $itens .=  View::render('admin/modules/precos/item', [
                    'id' => $obPrecos->id,
                    'idempresa' => $obPrecos->empresa,
                    'idplano' => $obPrecos->plano,
                    'idtipoplano' => $obPrecos->tipoplano,
                    // ENFERMARIA COM COPARTICIPAÇÃO
                    'pec0018' => $obPrecos->pec0018,
                    'pec1923' => $obPrecos->pec1923,
                    'pec2428' => $obPrecos->pec2428,
                    'pec2933' => $obPrecos->pec2933,
                    'pec3438' => $obPrecos->pec3438,
                    'pec3943' => $obPrecos->pec3943,
                    'pec4448' => $obPrecos->pec4448,
                    'pec4953' => $obPrecos->pec4953,
                    'pec5458' => $obPrecos->pec5458,
                    'pec59m' => $obPrecos->pec59m,
                    // ENFERMARIA SEM COPARTICIPAÇÃO
                    'pes0018' => $obPrecos->pes0018,
                    'pes1923' => $obPrecos->pes1923,
                    'pes2428' => $obPrecos->pes2428,
                    'pes2933' => $obPrecos->pes2933,
                    'pes3438' => $obPrecos->pes3438,
                    'pes3943' => $obPrecos->pes3943,
                    'pes4448' => $obPrecos->pes4448,
                    'pes4953' => $obPrecos->pes4953,
                    'pes5458' => $obPrecos->pes5458,
                    'pes59m' => $obPrecos->pes59m,
                    // APARTAMENTO COM COPARTICIPAÇÃO
                    'pac0018' => $obPrecos->pac0018,
                    'pac1923' => $obPrecos->pac1923,
                    'pac2428' => $obPrecos->pac2428,
                    'pac2933' => $obPrecos->pac2933,
                    'pac3438' => $obPrecos->pac3438,
                    'pac3943' => $obPrecos->pac3943,
                    'pac4448' => $obPrecos->pac4448,
                    'pac4953' => $obPrecos->pac4953,
                    'pac5458' => $obPrecos->pac5458,
                    'pac59m' => $obPrecos->pac59m,
                    // APARTAMENTO SEM COPARTICIPAÇÃO
                    'pas0018' => $obPrecos->pas0018,
                    'pas1923' => $obPrecos->pas1923,
                    'pas2428' => $obPrecos->pas2428,
                    'pas2933' => $obPrecos->pas2933,
                    'pas3438' => $obPrecos->pas3438,
                    'pas3943' => $obPrecos->pas3943,
                    'pas4448' => $obPrecos->pas4448,
                    'pas4953' => $obPrecos->pas4953,
                    'pas5458' => $obPrecos->pas5458,
                    'pas59m' => $obPrecos->pas59m
                ]);
            }

            return $itens;
        }

        /**
         * Método responsável por renderizar a view de listagem de preços de planos
         * @param Request $request
         * @return string
         */
        public static function getPrecos($request){
            // CONTEÚDO DA HOME
            $content = View::render('admin/modules/precos/index', [
                'itens' => self::getPrecosItems($request, $obPagination),
                'pagination' => parent::getPagination($request, $obPagination),
                'status' => self::getStatus($request)
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Preços - Admin', $content, 'precos');
        }
        
        /**
         * Método responsável por trocar o formato dos preços de ponto para vírgula
         * @param float $preco
         * @return string
         */
        public static function mudarFormato($preco){
            $preco = number_format($preco, 2, ',', '.');

            return $preco;
        }

        /**
         * Método responsável por retornar a tabela de preços de um plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function getInfoPrecos($request, $id){
            // OBTÉM OS PREÇOS DO BANCO DE DADOS
            $obPrecos = EntityPrecos::getPrecosById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obPrecos instanceof EntityPrecos){
                $request->getRouter()->redirect('/admin/precos');
            }

            // PEGANDO NOME DA EMPRESA COM BASE NO IDEMPRESA
            $obPrecos->empresa = EntityEmpresas::getNomeEmpresaByPrecos($obPrecos->idempresa);
            $obPrecos->empresa = $obPrecos->empresa->empresa;

            // PEGANDO NOME DO PLANO COM BASE NO IDPLANO
            $obPrecos->plano = EntityPlanos::getNomePlanoByPrecos($obPrecos->idplano);
            $obPrecos->plano = $obPrecos->plano->plano; 
            
            // PEGANDO TIPO DE PLANO COM BASE NO IDTIPOPLANO
            $obPrecos->tipoplano = EntityTipoPlanos::getNomeTipoPlanoByPrecos($obPrecos->idtipoplano);
            $obPrecos->tipoplano = $obPrecos->tipoplano->tipoplano;

            // CONTEÚDO DO FORMULÁRIO
            $content = View::render('admin/modules/precos/info', [
                'empresa' => $obPrecos->empresa,
                'plano' => $obPrecos->plano,
                'tipoplano' => $obPrecos->tipoplano,
                // ENFERMARIA COM COPARTICIPAÇÃO
                'pec0018' => self::mudarFormato($obPrecos->pec0018),
                'pec1923' => self::mudarFormato($obPrecos->pec1923),
                'pec2428' => self::mudarFormato($obPrecos->pec2428),
                'pec2933' => self::mudarFormato($obPrecos->pec2933),
                'pec3438' => self::mudarFormato($obPrecos->pec3438),
                'pec3943' => self::mudarFormato($obPrecos->pec3943),
                'pec4448' => self::mudarFormato($obPrecos->pec4448),
                'pec4953' => self::mudarFormato($obPrecos->pec4953),
                'pec5458' => self::mudarFormato($obPrecos->pec5458),
                'pec59m' => self::mudarFormato($obPrecos->pec59m),
                // ENFERMARIA SEM COPARTICIPAÇÃO
                'pes0018' => self::mudarFormato($obPrecos->pes0018),
                'pes1923' => self::mudarFormato($obPrecos->pes1923),
                'pes2428' => self::mudarFormato($obPrecos->pes2428),
                'pes2933' => self::mudarFormato($obPrecos->pes2933),
                'pes3438' => self::mudarFormato($obPrecos->pes3438),
                'pes3943' => self::mudarFormato($obPrecos->pes3943),
                'pes4448' => self::mudarFormato($obPrecos->pes4448),
                'pes4953' => self::mudarFormato($obPrecos->pes4953),
                'pes5458' => self::mudarFormato($obPrecos->pes5458),
                'pes59m' => self::mudarFormato($obPrecos->pes59m),
                // APARTAMENTO COM COPARTICIPAÇÃO
                'pac0018' => self::mudarFormato($obPrecos->pac0018),
                'pac1923' => self::mudarFormato($obPrecos->pac1923),
                'pac2428' => self::mudarFormato($obPrecos->pac2428),
                'pac2933' => self::mudarFormato($obPrecos->pac2933),
                'pac3438' => self::mudarFormato($obPrecos->pac3438),
                'pac3943' => self::mudarFormato($obPrecos->pac3943),
                'pac4448' => self::mudarFormato($obPrecos->pac4448),
                'pac4953' => self::mudarFormato($obPrecos->pac4953),
                'pac5458' => self::mudarFormato($obPrecos->pac5458),
                'pac59m' => self::mudarFormato($obPrecos->pac59m),
                // APARTAMENTO SEM COPARTICIPAÇÃO
                'pas0018' => self::mudarFormato($obPrecos->pas0018),
                'pas1923' => self::mudarFormato($obPrecos->pas1923),
                'pas2428' => self::mudarFormato($obPrecos->pas2428),
                'pas2933' => self::mudarFormato($obPrecos->pas2933),
                'pas3438' => self::mudarFormato($obPrecos->pas3438),
                'pas3943' => self::mudarFormato($obPrecos->pas3943),
                'pas4448' => self::mudarFormato($obPrecos->pas4448),
                'pas4953' => self::mudarFormato($obPrecos->pas4953),
                'pas5458' => self::mudarFormato($obPrecos->pas5458),
                'pas59m' => self::mudarFormato($obPrecos->pas59m)
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Tabela de preços - Admin', $content, 'precos');
        }

        /**
         * Método responsável por retornar o formulário de cadastro de novos preços de um plano
         * @param Request $request
         * @return string
         */
        public static function getNewPrecos($request){
            $empresas = '';
            $planos = '';
            $tipoplanos = '';

            $results1 = EntityEmpresas::getEmpresas(null, 'id ASC');
            $results2 = EntityPlanos::getPlanos(null, 'id ASC');
            $results3 = EntityTipoPlanos::getTipoPlanos(null, 'id ASC');

            // PEGA O NOME DAS EMPRESAS CADASTRADAS NO BANCO
            while ($obEmpresa = $results1->fetchObject(EntityEmpresas::class)) {
                $empresas .=  View::render('admin/modules/precos/optionempresas', [
                    'idempresa' => $obEmpresa->id,
                    'empresa' => $obEmpresa->empresa
                ]);
            }

            // PEGA O NOME DAS EMPRESAS CADASTRADAS NO BANCO
            while ($obPlanos = $results2->fetchObject(EntityPlanos::class)) {
                $planos .=  View::render('admin/modules/precos/optionplanos', [
                    'idplano' => $obPlanos->id,
                    'plano' => $obPlanos->plano
                ]);
            }

            // PEGA O NOME DAS EMPRESAS CADASTRADAS NO BANCO
            while ($obTipoPlanos = $results3->fetchObject(EntityTipoPlanos::class)) {
                $tipoplanos .=  View::render('admin/modules/precos/optiontipoplanos', [
                    'idtipoplano' => $obTipoPlanos->id,
                    'tipoplano' => $obTipoPlanos->tipoplano
                ]);
            }

            // CONTEÚDO DO FORMULÁRIO
            $content = View::render('admin/modules/precos/form', [
                'title' => 'Cadastrar Preços de um Plano',
                'idselecempresas' => '',
                'selecempresas' => 'Selecione uma empresa',
                'empresas' => $empresas,
                'idselecplano' => '',
                'selecplano' => 'Selecione um plano',
                'planos' => $planos,
                'idselectipoplano' => '',
                'selectipoplano' => 'Selecione um tipo de plano',
                'tipoplanos' => $tipoplanos,
                // ENFERMARIA COM COPARTICIPAÇÃO
                'pec0018' => '',
                'pec1923' => '',
                'pec2428' => '',
                'pec2933' => '',
                'pec3438' => '',
                'pec3943' => '',
                'pec4448' => '',
                'pec4953' => '',
                'pec5458' => '',
                'pec59m' => '',
                // ENFERMARIA SEM COPARTICIPAÇÃO
                'pes0018' => '',
                'pes1923' => '',
                'pes2428' => '',
                'pes2933' => '',
                'pes3438' => '',
                'pes3943' => '',
                'pes4448' => '',
                'pes4953' => '',
                'pes5458' => '',
                'pes59m' => '',
                // APARTAMENTO COM COPARTICIPAÇÃO
                'pac0018' => '',
                'pac1923' => '',
                'pac2428' => '',
                'pac2933' => '',
                'pac3438' => '',
                'pac3943' => '',
                'pac4448' => '',
                'pac4953' => '',
                'pac5458' => '',
                'pac59m' => '',
                // APARTAMENTO SEM COPARTICIPAÇÃO
                'pas0018' => '',
                'pas1923' => '',
                'pas2428' => '',
                'pas2933' => '',
                'pas3438' => '',
                'pas3943' => '',
                'pas4448' => '',
                'pas4953' => '',
                'pas5458' => '',
                'pas59m' => '',
                'status' => ''
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Cadastrar preços de um plano - Admin', $content, 'precos');
        }

        /**
         * Método responsável por cadastrar preços de um plano no banco
         * @param Request $request
         * @return string
         */
        public static function setNewPrecos($request){
            // POST VARS
            $postVars = $request->getPostVars();
            
            // NOVA INSTÂNCIA DE PLANO
            $obPrecos = new EntityPrecos;
            $obPrecos->idempresa = $postVars['idempresa'] ?? '';
            $obPrecos->idplano = $postVars['idplano'] ?? '';
            $obPrecos->idtipoplano = $postVars['idtipoplano'] ?? '';
            // ENFERMARIA COM COPARTICIPAÇÃO
            $obPrecos->pec0018 = $postVars['pec0018'] ?? '';
            $obPrecos->pec1923 = $postVars['pec1923'] ?? '';
            $obPrecos->pec2428 = $postVars['pec2428'] ?? '';
            $obPrecos->pec2933 = $postVars['pec2933'] ?? '';
            $obPrecos->pec3438 = $postVars['pec3438'] ?? '';
            $obPrecos->pec3943 = $postVars['pec3943'] ?? '';
            $obPrecos->pec4448 = $postVars['pec4448'] ?? '';
            $obPrecos->pec4953 = $postVars['pec4953'] ?? '';
            $obPrecos->pec5458 = $postVars['pec5458'] ?? '';
            $obPrecos->pec59m = $postVars['pec59m'] ?? '';
            // ENFERMARIA SEM COPARTICIPAÇÃO
            $obPrecos->pes0018 = $postVars['pes0018'] ?? '';
            $obPrecos->pes1923 = $postVars['pes1923'] ?? '';
            $obPrecos->pes2428 = $postVars['pes2428'] ?? '';
            $obPrecos->pes2933 = $postVars['pes2933'] ?? '';
            $obPrecos->pes3438 = $postVars['pes3438'] ?? '';
            $obPrecos->pes3943 = $postVars['pes3943'] ?? '';
            $obPrecos->pes4448 = $postVars['pes4448'] ?? '';
            $obPrecos->pes4953 = $postVars['pes4953'] ?? '';
            $obPrecos->pes5458 = $postVars['pes5458'] ?? '';
            $obPrecos->pes59m = $postVars['pes59m'] ?? '';
            // APARTAMENTO COM COPARTICIPAÇÃO
            $obPrecos->pac0018 = $postVars['pac0018'] ?? '';
            $obPrecos->pac1923 = $postVars['pac1923'] ?? '';
            $obPrecos->pac2428 = $postVars['pac2428'] ?? '';
            $obPrecos->pac2933 = $postVars['pac2933'] ?? '';
            $obPrecos->pac3438 = $postVars['pac3438'] ?? '';
            $obPrecos->pac3943 = $postVars['pac3943'] ?? '';
            $obPrecos->pac4448 = $postVars['pac4448'] ?? '';
            $obPrecos->pac4953 = $postVars['pac4953'] ?? '';
            $obPrecos->pac5458 = $postVars['pac5458'] ?? '';
            $obPrecos->pac59m = $postVars['pac59m'] ?? '';
            // APARTAMENTO SEM COPARTICIPAÇÃO
            $obPrecos->pas0018 = $postVars['pas0018'] ?? '';
            $obPrecos->pas1923 = $postVars['pas1923'] ?? '';
            $obPrecos->pas2428 = $postVars['pas2428'] ?? '';
            $obPrecos->pas2933 = $postVars['pas2933'] ?? '';
            $obPrecos->pas3438 = $postVars['pas3438'] ?? '';
            $obPrecos->pas3943 = $postVars['pas3943'] ?? '';
            $obPrecos->pas4448 = $postVars['pas4448'] ?? '';
            $obPrecos->pas4953 = $postVars['pas4953'] ?? '';
            $obPrecos->pas5458 = $postVars['pas5458'] ?? '';
            $obPrecos->pas59m = $postVars['pas59m'] ?? '';
            $obPrecos->cadastrar();

            // REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/precos?status=created');
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
                    return Alert::getSuccess('Tabela de preço criada criado com sucesso!');
                    break;
                case 'updated':
                    return Alert::getSuccess('Tabela de preço atualizado com sucesso!');
                    break;
                case 'deleted':
                    return Alert::getSuccess('Tabela de preço excluído com sucesso!');
                    break;
            }
        }

        /**
         * Método responsável por retornar o formulário de edição dos preços de um plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function getEditPrecos($request, $id){
            // OBTÉM OS PREÇOS DO BANCO DE DADOS
            $obPrecos = EntityPrecos::getPrecosById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obPrecos instanceof EntityPrecos){
                $request->getRouter()->redirect('/admin/precos');
            }

            $empresas = '';
            $planos = '';
            $tipoplanos = '';

            $results1 = EntityEmpresas::getEmpresas(null, 'id ASC');
            $results2 = EntityPlanos::getPlanos(null, 'id ASC');
            $results3 = EntityTipoPlanos::getTipoPlanos(null, 'id ASC');
            
            while ($obEmpresa = $results1->fetchObject(EntityEmpresas::class)) {
                $empresas .=  View::render('admin/modules/precos/optionempresas', [
                    'idempresa' => $obEmpresa->id,
                    'empresa' => $obEmpresa->empresa
                ]);
            }

            // PEGA O NOME DAS EMPRESAS CADASTRADAS NO BANCO
            while ($obPlanos = $results2->fetchObject(EntityPlanos::class)) {
                $planos .=  View::render('admin/modules/precos/optionplanos', [
                    'idplano' => $obPlanos->id,
                    'plano' => $obPlanos->plano
                ]);
            }

            // PEGA O NOME DAS EMPRESAS CADASTRADAS NO BANCO
            while ($obTipoPlanos = $results3->fetchObject(EntityTipoPlanos::class)) {
                $tipoplanos .=  View::render('admin/modules/precos/optiontipoplanos', [
                    'idtipoplano' => $obTipoPlanos->id,
                    'tipoplano' => $obTipoPlanos->tipoplano
                ]);
            }

            // PEGANDO NOME DA EMPRESA COM BASE NO IDEMPRESA
            $obPrecos->empresa = EntityEmpresas::getNomeEmpresaByPrecos($obPrecos->idempresa);
            $obPrecos->empresa = $obPrecos->empresa->empresa;

            // PEGANDO NOME DO PLANO COM BASE NO IDPLANO
            $obPrecos->plano = EntityPlanos::getNomePlanoByPrecos($obPrecos->idplano);
            $obPrecos->plano = $obPrecos->plano->plano; 
            
            // PEGANDO TIPO DE PLANO COM BASE NO IDTIPOPLANO
            $obPrecos->tipoplano = EntityTipoPlanos::getNomeTipoPlanoByPrecos($obPrecos->idtipoplano);
            $obPrecos->tipoplano = $obPrecos->tipoplano->tipoplano;

            // CONTEÚDO DO FORMULÁRIO
            $content = View::render('admin/modules/precos/form', [
                'title' => 'Editar preços de um plano',
                'idselecempresas' => $obPrecos->idempresa,
                'selecempresas' => $obPrecos->empresa,
                'empresas' => $empresas,
                'idselecplano' => $obPrecos->idplano,
                'selecplano' => $obPrecos->plano,
                'planos' => $planos,
                'idselectipoplano' => $obPrecos->idtipoplano,
                'selectipoplano' => $obPrecos->tipoplano,
                'tipoplanos' => $tipoplanos,
                'plano' => $obPrecos->plano,
                // ENFERMARIA COM COPARTICIPAÇÃO
                'pec0018' => $obPrecos->pec0018,
                'pec1923' => $obPrecos->pec1923,
                'pec2428' => $obPrecos->pec2428,
                'pec2933' => $obPrecos->pec2933,
                'pec3438' => $obPrecos->pec3438,
                'pec3943' => $obPrecos->pec3943,
                'pec4448' => $obPrecos->pec4448,
                'pec4953' => $obPrecos->pec4953,
                'pec5458' => $obPrecos->pec5458,
                'pec59m' => $obPrecos->pec59m,
                // ENFERMARIA SEM COPARTICIPAÇÃO
                'pes0018' => $obPrecos->pes0018,
                'pes1923' => $obPrecos->pes1923,
                'pes2428' => $obPrecos->pes2428,
                'pes2933' => $obPrecos->pes2933,
                'pes3438' => $obPrecos->pes3438,
                'pes3943' => $obPrecos->pes3943,
                'pes4448' => $obPrecos->pes4448,
                'pes4953' => $obPrecos->pes4953,
                'pes5458' => $obPrecos->pes5458,
                'pes59m' => $obPrecos->pes59m,
                // APARTAMENTO COM COPARTICIPAÇÃO
                'pac0018' => $obPrecos->pac0018,
                'pac1923' => $obPrecos->pac1923,
                'pac2428' => $obPrecos->pac2428,
                'pac2933' => $obPrecos->pac2933,
                'pac3438' => $obPrecos->pac3438,
                'pac3943' => $obPrecos->pac3943,
                'pac4448' => $obPrecos->pac4448,
                'pac4953' => $obPrecos->pac4953,
                'pac5458' => $obPrecos->pac5458,
                'pac59m' => $obPrecos->pac59m,
                // APARTAMENTO SEM COPARTICIPAÇÃO
                'pas0018' => $obPrecos->pas0018,
                'pas1923' => $obPrecos->pas1923,
                'pas2428' => $obPrecos->pas2428,
                'pas2933' => $obPrecos->pas2933,
                'pas3438' => $obPrecos->pas3438,
                'pas3943' => $obPrecos->pas3943,
                'pas4448' => $obPrecos->pas4448,
                'pas4953' => $obPrecos->pas4953,
                'pas5458' => $obPrecos->pas5458,
                'pas59m' => $obPrecos->pas59m,
                'status' => self::getStatus($request)
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Editar preços de um plano - Admin', $content, 'precos');
        }

        /**
         * Método responsável por gravar a atualização de um plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function setEditPrecos($request, $id){
            // OBTÉM OS PREÇOS DO BANCO DE DADOS
            $obPrecos = EntityPrecos::getPrecosById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obPrecos instanceof EntityPrecos){
                $request->getRouter()->redirect('/admin/precos');
            }

            // POST VARS
            $postVars = $request->getPostVars();

            // ATUALIZA A INSTÂNCIA
            $obPrecos->idempresa = $postVars['idempresa'] ?? $obPrecos->idempresa;
            $obPrecos->idplano = $postVars['idplano'] ?? $obPrecos->idplano;
            $obPrecos->idtipoplano = $postVars['idtipoplano'] ?? $obPrecos->idtipoplano;
            // ENFERMARIA COM COPARTICIPAÇÃO
            $obPrecos->pec0018 = $postVars['pec0018'] ?? $obPrecos->pec0018;
            $obPrecos->pec1923 = $postVars['pec1923'] ?? $obPrecos->pec1923;
            $obPrecos->pec2428 = $postVars['pec2428'] ?? $obPrecos->pec2428;
            $obPrecos->pec2933 = $postVars['pec2933'] ?? $obPrecos->pec2933;
            $obPrecos->pec3438 = $postVars['pec3438'] ?? $obPrecos->pec3438;
            $obPrecos->pec3943 = $postVars['pec3943'] ?? $obPrecos->pec3943;
            $obPrecos->pec4448 = $postVars['pec4448'] ?? $obPrecos->pec4448;
            $obPrecos->pec4953 = $postVars['pec4953'] ?? $obPrecos->pec4953;
            $obPrecos->pec5458 = $postVars['pec5458'] ?? $obPrecos->pec5458;
            $obPrecos->pec59m = $postVars['pec59m'] ?? $obPrecos->pec59m;
            // ENFERMARIA SEM COPARTICIPAÇÃO
            $obPrecos->pes0018 = $postVars['pes0018'] ?? $obPrecos->pes0018;
            $obPrecos->pes1923 = $postVars['pes1923'] ?? $obPrecos->pes1923;
            $obPrecos->pes2428 = $postVars['pes2428'] ?? $obPrecos->pes2428;
            $obPrecos->pes2933 = $postVars['pes2933'] ?? $obPrecos->pes2933;
            $obPrecos->pes3438 = $postVars['pes3438'] ?? $obPrecos->pes3438;
            $obPrecos->pes3943 = $postVars['pes3943'] ?? $obPrecos->pes3943;
            $obPrecos->pes4448 = $postVars['pes4448'] ?? $obPrecos->pes4448;
            $obPrecos->pes4953 = $postVars['pes4953'] ?? $obPrecos->pes4953;
            $obPrecos->pes5458 = $postVars['pes5458'] ?? $obPrecos->pes5458;
            $obPrecos->pes59m = $postVars['pes59m'] ?? $obPrecos->pes59m;
            // APARTAMENTO COM COPARTICIPAÇÃO
            $obPrecos->pac0018 = $postVars['pac0018'] ?? $obPrecos->pac0018;
            $obPrecos->pac1923 = $postVars['pac1923'] ?? $obPrecos->pac1923;
            $obPrecos->pac2428 = $postVars['pac2428'] ?? $obPrecos->pac2428;
            $obPrecos->pac2933 = $postVars['pac2933'] ?? $obPrecos->pac2933;
            $obPrecos->pac3438 = $postVars['pac3438'] ?? $obPrecos->pac3438;
            $obPrecos->pac3943 = $postVars['pac3943'] ?? $obPrecos->pac3943;
            $obPrecos->pac4448 = $postVars['pac4448'] ?? $obPrecos->pac4448;
            $obPrecos->pac4953 = $postVars['pac4953'] ?? $obPrecos->pac4953;
            $obPrecos->pac5458 = $postVars['pac5458'] ?? $obPrecos->pac5458;
            $obPrecos->pac59m = $postVars['pac59m'] ?? $obPrecos->pac59m;
            // APARTAMENTO SEM COPARTICIPAÇÃO
            $obPrecos->pas0018 = $postVars['pas0018'] ?? $obPrecos->pas0018;
            $obPrecos->pas1923 = $postVars['pas1923'] ?? $obPrecos->pas1923;
            $obPrecos->pas2428 = $postVars['pas2428'] ?? $obPrecos->pas2428;
            $obPrecos->pas2933 = $postVars['pas2933'] ?? $obPrecos->pas2933;
            $obPrecos->pas3438 = $postVars['pas3438'] ?? $obPrecos->pas3438;
            $obPrecos->pas3943 = $postVars['pas3943'] ?? $obPrecos->pas3943;
            $obPrecos->pas4448 = $postVars['pas4448'] ?? $obPrecos->pas4448;
            $obPrecos->pas4953 = $postVars['pas4953'] ?? $obPrecos->pas4953;
            $obPrecos->pas5458 = $postVars['pas5458'] ?? $obPrecos->pas5458;
            $obPrecos->pas59m = $postVars['pas59m'] ?? $obPrecos->pas59m;
            $obPrecos->atualizar();

            // REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/precos/'.$obPrecos->id.'/edit?status=updated');
        }

        /**
         * Método responsável por retornar o formulário de exclusão dos preços de um plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function getDeletePrecos($request, $id){
            // OBTÉM OS PREÇOS DO BANCO DE DADOS
            $obPrecos = EntityPrecos::getPrecosById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obPrecos instanceof EntityPrecos){
                $request->getRouter()->redirect('/admin/precos');
            }

            // PEGANDO NOME DA EMPRESA COM BASE NO IDEMPRESA
            $obPrecos->empresa = EntityEmpresas::getNomeEmpresaByPrecos($obPrecos->idempresa);
            $obPrecos->empresa = $obPrecos->empresa->empresa;

            // PEGANDO NOME DO PLANO COM BASE NO IDPLANO
            $obPrecos->plano = EntityPlanos::getNomePlanoByPrecos($obPrecos->idplano);
            $obPrecos->plano = $obPrecos->plano->plano; 
            
            // PEGANDO TIPO DE PLANO COM BASE NO IDTIPOPLANO
            $obPrecos->tipoplano = EntityTipoPlanos::getNomeTipoPlanoByPrecos($obPrecos->idtipoplano);
            $obPrecos->tipoplano = $obPrecos->tipoplano->tipoplano;

            // CONTEÚDO DO FORMULÁRIO
            $content = View::render('admin/modules/precos/delete', [
                'empresa' => $obPrecos->empresa,
                'plano' => $obPrecos->plano,
                'tipoplano' => $obPrecos->tipoplano
            ]);

            // RETORNA A PÁGINA COMPLETA
            return parent::getPanel('Excluir preços - Admin', $content, 'precos');
        }

        /**
         * Método responsável por excluir um plano
         * @param Request $request
         * @param integer $id
         * @return string
         */
        public static function setDeletePrecos($request, $id){
            // OBTÉM OS PREÇOS DO BANCO DE DADOS
            $obPrecos = EntityPrecos::getPrecosById($id);
            
            // VALIDA A INSTÂNCIA 
            if(!$obPrecos instanceof EntityPrecos){
                $request->getRouter()->redirect('/admin/precos');
            }

            // EXCLUI O PLANO
            $obPrecos->excluir();

            // REDIRECIONA O USUÁRIO
            $request->getRouter()->redirect('/admin/precos?status=deleted');
        }

    }

?>