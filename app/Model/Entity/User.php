<?php

    namespace App\Model\Entity;
    
    use WilliamCosta\DatabaseManager\Database;

    class User {

        /**
         * ID do usuário
         * @var integer $id
         */
        public $id;

        /**
         * Nome do usuário
         * @var string $nome
         */
        public $nome;

        /**
         * E-mail do usuário
         * @var string $email
         */
        public $email;

        /**
         * Senha do usuário
         * @var string $senha
         */
        public $senha;

        /**
         * Método responsável por cadastrar a instância atual no banco de dados
         * @return boolean
         */
        public function cadastrar(){
            // INSERE O USUÁRIO NO BANCO DE DADOS
            $this->id = (new Database('tb_usuarios'))->insert([
                'nome' => $this->nome,
                'email' => $this->email,
                'senha' => $this->senha
            ]);

            return true;
        }

        /**
         * Método responsável por atualizar os dados do banco com a instância atual
         * @return boolean
         */
        public function atualizar(){
            // ATUALIZA O USUÁRIO NO BANCO DE DADOS
            return (new Database('tb_usuarios'))->update('id = '.$this->id, [
                'nome' => $this->nome,
                'email' =>$this->email,
                'senha' =>$this->senha
            ]);
        }

        /**
         * Método responsável por excluir um usuário do banco de dados
         * @return boolean
         */
        public function excluir(){
            // EXCLUI O USUÁRIO DO BANCO DE DADOS
            return (new Database('tb_usuarios'))->delete('id = '.$this->id);
        }

        /**
         * Método responsável por retornar um usuário baseado no seu e-mail
         * @param string $email
         * @return User
         */
        public static function getUserByEmail($email){
            return self::getUsers('email = "'.$email.'"')->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar um Usuários com base no seu ID
         * @param integer $id
         * @return Testimony
         */
        public static function getUserById($id){
            return self::getUsers('id = '.$id)->fetchObject(self::class);
        }

        /**
         * Método responsável por retornar Usuários
         * @param string $where
         * @param string $order
         * @param string $limit
         * @param string fields
         * @return PDOStatement 
         */
        public static function getUsers($where = null, $order = null, $limit = null, $fields = '*'){

            return (new Database('tb_usuarios'))->select($where, $order, $limit, $fields);

        }

    }

?>