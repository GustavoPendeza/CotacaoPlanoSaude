<?php

    namespace App\Model\Entity;
    
    use WilliamCosta\DatabaseManager\Database;

    class Empresas {

        public $id;

        public $empresa;

        /**
         * Método responsável por retornar empresas
         * @param string $where
         * @param string $order
         * @param string $limit
         * @param string fields
         * @return PDOStatement 
         */
        public static function getEmpresas($where = null, $order = null, $limit = null, $fields = '*'){

            return (new Database('tb_empresa'))->select($where, $order, $limit, $fields);

        }

        /**
         * Método responsável por retornar o nome das empresas com base no idempresa de planos
         * @param integer $idempresa
         * @return PDOStatement
         */
        public static function getNomeEmpresaByPlano($idempresa){
            return (new Database('tb_empresa'))->inner('tb_plano ON tb_empresa.id = tb_plano.idempresa', 'idempresa = '.$idempresa, 'empresa')->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar o nome das empresas com base no idempresa de tipos de planos
         * @param integer $idempresa
         * @return PDOStatement
         */
        public static function getNomeEmpresaByTipoPlano($idempresa){
            return (new Database('tb_empresa'))->inner('tb_tipoplano ON tb_empresa.id = tb_tipoplano.idempresa', 'idempresa = '.$idempresa, 'empresa')->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar o nome das empresas com base no idempresa de preços de planos
         * @param integer $idempresa
         * @return PDOStatement
         */
        public static function getNomeEmpresaByPrecos($idempresa){
            return (new Database('tb_empresa'))->inner('tb_precos ON tb_empresa.id = tb_precos.idempresa', 'idempresa = '.$idempresa, 'empresa')->fetchObject(self::class);
        }
        

    }

?>