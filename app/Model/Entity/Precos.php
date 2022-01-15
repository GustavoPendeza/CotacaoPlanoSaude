<?php

    namespace App\Model\Entity;
    
    use WilliamCosta\DatabaseManager\Database;

    class Precos {

        public $id;

        public $idempresa;

        public $idplano;

        public $idtipoplano;

        /**
         * Método responsável por cadastrar a instância atual no banco de dados
         * @return boolean
         */
        public function cadastrar(){
            // INSERE OS PREÇOS DE PLANO NO BANCO DE DADOS
            $this->id = (new Database('tb_precos'))->insert([
                'idempresa' => $this->idempresa,
                'idplano' =>$this->idplano,
                'idtipoplano' =>$this->idtipoplano,
                // ENFERMARIA SEM COPARTICIPAÇÃO
                'pes0018' =>$this->pes0018,
                'pes1923' =>$this->pes1923,
                'pes2428' =>$this->pes2428,
                'pes2933' =>$this->pes2933,
                'pes3438' =>$this->pes3438,
                'pes3943' =>$this->pes3943,
                'pes4448' =>$this->pes4448,
                'pes4953' =>$this->pes4953,
                'pes5458' =>$this->pes5458,
                'pes59m' =>$this->pes59m,
                // ENFERMARIA COM COPARTICIPAÇÃO
                'pec0018' =>$this->pec0018,
                'pec1923' =>$this->pec1923,
                'pec2428' =>$this->pec2428,
                'pec2933' =>$this->pec2933,
                'pec3438' =>$this->pec3438,
                'pec3943' =>$this->pec3943,
                'pec4448' =>$this->pec4448,
                'pec4953' =>$this->pec4953,
                'pec5458' =>$this->pec5458,
                'pec59m' =>$this->pec59m,
                // APARTAMENTO SEM COPARTICIPAÇÃO
                'pas0018' =>$this->pas0018,
                'pas1923' =>$this->pas1923,
                'pas2428' =>$this->pas2428,
                'pas2933' =>$this->pas2933,
                'pas3438' =>$this->pas3438,
                'pas3943' =>$this->pas3943,
                'pas4448' =>$this->pas4448,
                'pas4953' =>$this->pas4953,
                'pas5458' =>$this->pas5458,
                'pas59m' =>$this->pas59m,
                // APARTAMENTO COM COPARTICIPAÇÃO
                'pac0018' =>$this->pac0018,
                'pac1923' =>$this->pac1923,
                'pac2428' =>$this->pac2428,
                'pac2933' =>$this->pac2933,
                'pac3438' =>$this->pac3438,
                'pac3943' =>$this->pac3943,
                'pac4448' =>$this->pac4448,
                'pac4953' =>$this->pac4953,
                'pac5458' =>$this->pac5458,
                'pac59m' =>$this->pac59m
            ]);

            return true;
        }

        /**
         * Método responsável por atualizar os dados do banco com a instância atual
         * @return boolean
         */
        public function atualizar(){
            // ATUALIZA OS PREÇOS DE PLANO NO BANCO DE DADOS
            return (new Database('tb_precos'))->update('id = '.$this->id, [
                'idempresa' => $this->idempresa,
                'idtipoplano' =>$this->idtipoplano,
                'idplano' =>$this->idplano,
                // ENFERMARIA SEM COPARTICIPAÇÃO
                'pes0018' =>$this->pes0018,
                'pes1923' =>$this->pes1923,
                'pes2428' =>$this->pes2428,
                'pes2933' =>$this->pes2933,
                'pes3438' =>$this->pes3438,
                'pes3943' =>$this->pes3943,
                'pes4448' =>$this->pes4448,
                'pes4953' =>$this->pes4953,
                'pes5458' =>$this->pes5458,
                'pes59m' =>$this->pes59m,
                // ENFERMARIA COM COPARTICIPAÇÃO
                'pec0018' =>$this->pec0018,
                'pec1923' =>$this->pec1923,
                'pec2428' =>$this->pec2428,
                'pec2933' =>$this->pec2933,
                'pec3438' =>$this->pec3438,
                'pec3943' =>$this->pec3943,
                'pec4448' =>$this->pec4448,
                'pec4953' =>$this->pec4953,
                'pec5458' =>$this->pec5458,
                'pec59m' =>$this->pec59m,
                // APARTAMENTO SEM COPARTICIPAÇÃO
                'pas0018' =>$this->pas0018,
                'pas1923' =>$this->pas1923,
                'pas2428' =>$this->pas2428,
                'pas2933' =>$this->pas2933,
                'pas3438' =>$this->pas3438,
                'pas3943' =>$this->pas3943,
                'pas4448' =>$this->pas4448,
                'pas4953' =>$this->pas4953,
                'pas5458' =>$this->pas5458,
                'pas59m' =>$this->pas59m,
                // APARTAMENTO COM COPARTICIPAÇÃO
                'pac0018' =>$this->pac0018,
                'pac1923' =>$this->pac1923,
                'pac2428' =>$this->pac2428,
                'pac2933' =>$this->pac2933,
                'pac3438' =>$this->pac3438,
                'pac3943' =>$this->pac3943,
                'pac4448' =>$this->pac4448,
                'pac4953' =>$this->pac4953,
                'pac5458' =>$this->pac5458,
                'pac59m' =>$this->pac59m
            ]);
        }

        /**
         * Método responsável por excluir preços de plano do banco de dados
         * @return boolean
         */
        public function excluir(){
            // EXCLUI OS PREÇOS DE PLANO DO BANCO DE DADOS
            return (new Database('tb_precos'))->delete('id = '.$this->id);
        }

        /**
         * Método responsável por retornar preços de plano com base no seu ID
         * @param integer $id
         * @return Precos
         */
        public static function getPrecosById($id){
            return self::getPrecos('id = '.$id)->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar preços de planos com base na empresa
         * @param integer $idempresa
         * @return Plano
         */
        public static function getPrecosByEmpresa($idempresa){
            return self::getPrecos('idempresa = '.$idempresa)->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar preços de planos
         * @param string $where
         * @param string $order
         * @param string $limit
         * @param string fields
         * @return PDOStatement 
         */
        public static function getPrecos($where = null, $order = null, $limit = null, $fields = '*'){

            return (new Database('tb_precos'))->select($where, $order, $limit, $fields);

        }

    }

?>