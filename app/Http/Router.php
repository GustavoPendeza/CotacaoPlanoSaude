<?php

    namespace App\Http;

    use App\Http\Middleware\Queue as MiddlewareQueue;
    use \Closure;
    use \Exception;
    use \ReflectionFunction;

    class Router {

        // URL completa 
        private $url = '';

        // Prefixo de todas as rotas
        private $prefix = '';

        // Índice de rotas
        private $routes = [];

        // Instância de Request
        private $request;

        public function __construct($url){
            $this->request = new Request($this);
            $this->url = $url;
            $this->setPrefix();
        }

        // Método responsável por definir o prefixo das rotas
        private function setPrefix(){
            // INFORMAÇÕES DA URL ATUAL
            $parseURL = parse_url($this->url);
            // DEFINE O PREFIXO DA URL
            $this->prefix = $parseURL['path'] ?? '';
        }

        // Método responsável por adicionar uma rota na classe
        private function addRoute($method, $route, $params = []){
            // VALIDAÇÃO DOS PARÂMETROS
            foreach ($params as $key => $value) {
                if ($value instanceof Closure) {
                    $params['controller'] = $value;
                    unset($params[$key]);
                    continue;
                }
            }

            // MIDDLEWARES DA ROTA
            $params['middlewares'] = $params['middlewares'] ?? [];

            // VARIÁVEIS DA ROTA
            $params['variables'] = [];

            // PADRÃO DE VALIDAÇÃO DAS VARIÁVEIS DAS ROTAS
            $patternVariable = '/{(.*?)}/';
            if (preg_match_all($patternVariable, $route, $matches)) {
                $route = preg_replace($patternVariable, '(.*?)', $route);
                $params['variables'] = $matches[1];
            }

            // PADRÃO DE VALIDAÇÃO DA URL
            $patternRoute = '/^'.str_replace('/', '\/', $route).'$/';

            // ADICIONA A ROTA DENTRO DA CLASSE
            $this->routes[$patternRoute][$method] = $params;
        }

        // Método responsável por definir uma rota de GET
        public function get($route, $params = []){
            $this->addRoute('GET', $route, $params);
        }

        // Método responsável por definir uma rota de POST
        public function post($route, $params = []){
            $this->addRoute('POST', $route, $params);
        }

        // Método responsável por definir uma rota de PUT
        public function put($route, $params = []){
            $this->addRoute('PUT', $route, $params);
        }

        // Método responsável por definir uma rota de DELETE
        public function delete($route, $params = []){
            $this->addRoute('DELETE', $route, $params);
        }

        // Método responsável por retornar a URI desconsiderando o prefixo
        private function getUri(){
            $uri = $this->request->getUri();

            // SEPARA A URI COM PREFIXO
            // $uri vira um array com dois valores, o ultimo sendo somente '/'
            $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

            // RETORNA A URI SEM PREFIXO
            return end($xUri);
        }

        // Método responsável por retornar os dados da rota atual
        private function getRoute(){
            $uri = $this->getUri();

            // METHOD
            $httpMethod = $this->request->getHttpMethod();
            
            // VALIDA AS ROTAS
            foreach ($this->routes as $patternRoute => $methods) {
                // VERIFICA SE A URI BATE COM O PADRÃO
                if (preg_match($patternRoute, $uri, $matches)) {
                    // REMOVE A PRIMEIRA POSIÇÃO (URI INTEIRA)
                    unset($matches[0]);

                    // VARIÁVEIS PROCESSADAS
                    // CHAVES
                    $keys = $methods[$httpMethod]['variables'];
                    // COMBINA AS CHAVES E OS VALORES EM UM ARRAY
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    // RECEBE A REQUISIÇÃO
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    // VERIFICA O MÉTODO
                    if (isset($methods[$httpMethod])) {
                        // RETORNA OS PARÂMETROS DA ROTA
                        return $methods[$httpMethod];
                    }

                    throw new Exception("Método não permitido", 405);
                }
            }

            throw new Exception("URL não encontrada", 404);
        }

        // Método responsável por retornar a rota atual
        public function run(){
            try {
                // RECEBE A ROTA ATUAL
                $route = $this->getRoute();

                // VERIFICA O CONTROLADOR
                if (!isset($route['controller'])) {
                    throw new Exception("A URL não pôde ser processada", 500);
                }

                // ARGUMENTOS DA FUNÇÃO
                $args = [];

                // REFLECTION
                $reflection = new ReflectionFunction($route['controller']);
                foreach ($reflection->getParameters() as $parameter) {
                    $name = $parameter->getName();
                    $args[$name] = $route['variables'][$name] ?? '';
                }

                // RETORNA A EXECUÇÃO DA FILA DE MIDDLEWARES
                return (new MiddlewareQueue($route['middlewares'], $route['controller'], $args))->next($this->request);
            } catch (Exception $e) {
                return new Response($e->getCode(), $e->getMessage());
            }
        }

        // Método responsável por retornar a URL atual
        public function getCurrentUrl(){
            return $this->url.$this->getUri();
        }

        /**
         * Método responável por redirecionar a URL
         * @param string $route
         */
        public function redirect($route){
            // URL
            $url = $this->url.$route;

            // EXECUTA O REDIRECT 
            header('location: '.$url);
            exit;
        }
    }

?>