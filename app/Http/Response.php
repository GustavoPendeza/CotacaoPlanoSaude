<?php

    namespace App\Http;

    class Response {

        // Código do Status HTTP
        private $httpCode = 200;

        // Cabeçalho do Response
        private $headers = [];

        // Tipo do conteúdo que está sendo retornado
        private $contentType = 'text/html';

        // Conteúdo do Response
        private $content;

        public function __construct($httpCode, $content, $contentType = 'text/html'){
            $this->httpCode = $httpCode;
            $this->content = $content;
            $this->setContentType($contentType);
        }

        public function setContentType($contentType){
            $this->contentType = $contentType;
            $this->addHeader('Content-Type', $contentType);
        }

        public function addHeader($key, $value){
            $this->headers[$key] = $value;
        }

        private function sendHeaders(){
            // STATUS
            http_response_code($this->httpCode);

            // ENVIAR HEADERS
            foreach ($this->headers as $key => $value) {
                header($key.": ".$value);
            }
        }

        public function sendResponse(){
            // ENVIA OS HEADERS
            $this->sendHeaders();
            // ENVIA O CONTEÚDO DA PÁGINA
            switch ($this->contentType) {
                case 'text/html':
                    echo $this->content;
                    exit;
            }
        }
    }

?>