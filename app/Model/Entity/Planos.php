<?php

    namespace App\Model\Entity;
    
    use WilliamCosta\DatabaseManager\Database;

    class Planos {

        public $id;

        public $idempresa;

        public $plano;

        /**
         * Método responsável por cadastrar a instância atual no banco de dados
         * @return boolean
         */
        public function cadastrar(){
            // INSERE O PLANO NO BANCO DE DADOS
            $this->id = (new Database('tb_plano'))->insert([
                'idempresa' => $this->idempresa,
                'plano' =>$this->plano
            ]);

            return true;
        }

        /**
         * Método responsável por atualizar os dados do banco com a instância atual
         * @return boolean
         */
        public function atualizar(){
            // ATUALIZA O PLANO NO BANCO DE DADOS
            return (new Database('tb_plano'))->update('id = '.$this->id, [
                'idempresa' => $this->idempresa,
                'plano' =>$this->plano
            ]);
        }

        /**
         * Método responsável por excluir um plano do banco de dados
         * @return boolean
         */
        public function excluir(){
            // EXCLUI O PLANO DO BANCO DE DADOS
            return (new Database('tb_plano'))->delete('id = '.$this->id);
        }

        /**
         * Método responsável por retornar um plano com base no seu ID
         * @param integer $id
         * @return Plano
         */
        public static function getPlanoById($id){
            return self::getPlanos('id = '.$id)->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar planos com base na empresa
         * @param integer $idempresa
         * @return Plano
         */
        public static function getPlanoByEmpresa($idempresa){
            return self::getPlanos('idempresa = '.$idempresa)->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar um plano baseado no nome do plano
         * @param string $plano
         * @return Plano
         */
        public static function getPlanoByName($plano){
            return self::getPlanos('plano = "'.$plano.'"')->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar o nome dos planos com base no idplano de preços
         * @param integer $idplano
         * @return PDOStatement
         */
        public static function getNomePlanoByPrecos($idplano){
            return (new Database('tb_plano'))->inner('tb_precos ON tb_plano.id = tb_precos.idplano', 'idplano = '.$idplano, 'plano')->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar planos
         * @param string $where
         * @param string $order
         * @param string $limit
         * @param string fields
         * @return PDOStatement 
         */
        public static function getPlanos($where = null, $order = null, $limit = null, $fields = '*'){

            return (new Database('tb_plano'))->select($where, $order, $limit, $fields);

        }

    }

?>