<?php

    namespace App\Model\Entity;
    
    use WilliamCosta\DatabaseManager\Database;

    class TipoPlanos {

        public $id;

        public $idempresa;

        public $tipoplano;

        /**
         * Método responsável por cadastrar a instância atual no banco de dados
         * @return boolean
         */
        public function cadastrar(){
            // INSERE O TIPO DE PLANO NO BANCO DE DADOS
            $this->id = (new Database('tb_tipoplano'))->insert([
                'idempresa' => $this->idempresa,
                'tipoplano' =>$this->tipoplano
            ]);

            return true;
        }

        /**
         * Método responsável por atualizar os dados do banco com a instância atual
         * @return boolean
         */
        public function atualizar(){
            // ATUALIZA O TIPO DE PLANO NO BANCO DE DADOS
            return (new Database('tb_tipoplano'))->update('id = '.$this->id, [
                'idempresa' => $this->idempresa,
                'tipoplano' =>$this->tipoplano
            ]);
        }

        /**
         * Método responsável por excluir um tipo de plano do banco de dados
         * @return boolean
         */
        public function excluir(){
            // EXCLUI O TIPO DE PLANO DO BANCO DE DADOS
            return (new Database('tb_tipoplano'))->delete('id = '.$this->id);
        }

        /**
         * Método responsável por retornar um tipo de plano com base no seu ID
         * @param integer $id
         * @return TipoPlano
         */
        public static function getTipoPlanoById($id){
            return self::getTipoPlanos('id = '.$id)->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar tipos de planos com base na empresa
         * @param integer $idempresa
         * @return Plano
         */
        public static function getTipoPlanoByEmpresa($idempresa){
            return self::getTipoPlanos('idempresa = '.$idempresa)->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar um tipo de plano baseado no nome
         * @param string $plano
         * @return Plano
         */
        public static function getTipoPlanoByName($tipoplano){
            return self::getTipoPlanos('tipoplano = "'.$tipoplano.'"')->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar o nome dos planos com base no idplano de preços
         * @param integer $idtipoplano
         * @return PDOStatement
         */
        public static function getNomeTipoPlanoByPrecos($idtipoplano){
            return (new Database('tb_tipoplano'))->inner('tb_precos ON tb_tipoplano.id = tb_precos.idtipoplano', 'idtipoplano = '.$idtipoplano, 'tipoplano')->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar tipos de planos
         * @param string $where
         * @param string $order
         * @param string $limit
         * @param string fields
         * @return PDOStatement 
         */
        public static function getTipoPlanos($where = null, $order = null, $limit = null, $fields = '*'){

            return (new Database('tb_tipoplano'))->select($where, $order, $limit, $fields);

        }

    }

?>