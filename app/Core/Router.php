<?php 

namespace App\Core;
class Router
{
    public array $routes = [];
    public string $action = '';
    
    public Request $request;
    public Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    
   /**
    * La fonction ajoute une route GET au tableau de routes avec le chemin et le callback spécifiés.
    *
    * @param string path Le paramètre de chemin est une chaîne de caractères qui représente le chemin URL de la route. Il
    * spécifie le point de terminaison que l'utilisateur utilisera pour déclencher la fonction de rappel.
    * @param callback Le paramètre de callback est une fonction ou une méthode qui sera exécutée lorsque le
    * chemin spécifié est demandé en utilisant la méthode HTTP GET. Il peut s'agir d'une fermeture, d'un nom de fonction ou
    * d'un tableau contenant un objet et un nom de méthode.
    */
    public function get(string $path, $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    
    /**
    * La fonction "post" ajoute une fonction de rappel au tableau de routes pour le chemin et la méthode HTTP
    * "POST" spécifiés.
    *
    * @param string path Le paramètre de chemin est une chaîne de caractères qui représente le chemin URL de la route. Il
    * spécifie le point de terminaison où la fonction de rappel sera exécutée lorsqu'une requête HTTP POST est
    * effectuée vers ce chemin.
    * @param callback Le paramètre de rappel est une fonction de rappel ou une méthode qui sera exécutée
    * lorsque le chemin spécifié est accédé en utilisant la méthode HTTP POST. Il peut s'agir d'une fermeture, d'une
    * fonction anonyme ou du nom d'une fonction ou méthode définie ailleurs dans le code.
    */
    public function post(string $path, $callback): void
    {
        $this->routes['post'][$path] = $callback;
    }

   /**
    * The delete function adds a callback function to the routes array for the specified path.
    * 
    * @param string path The path parameter is a string that represents the URL path for the route that
    * you want to delete. It is used to match the requested URL path with the registered routes.
    * @param callback The callback parameter is a function or method that will be executed when the
    * specified path is accessed with the DELETE HTTP method. It can be a closure, an anonymous
    * function, or the name of a function or method defined elsewhere in your code.
    */
    public function delete(string $path, $callback): void
    {
        $this->routes['delete'][$path] = $callback;
    }

    /**
    * La fonction resolve vérifie si une fonction de rappel existe pour un chemin et une méthode donnés, et si ce n'est pas le cas,
    * elle définit un code d'état 404 et renvoie "Non trouvé". Si une fonction de rappel existe, elle instancie le
    * contrôleur et exécute tous les intergiciels (middlewares) avant d'appeler la fonction de rappel avec les objets de requête et de réponse.
    *
    * @return mixte une valeur mixte. Il peut s'agir de n'importe quel type de valeur en fonction du chemin d'exécution
    * du code.
    */
    public function resolve(): mixed
    {
    
        $path = $this->request->getPath(); // on récupère l'url
        $method = $this->request->getMethod(); // on récupère la méthode (get, post, etc)
        $callback = $this->routes[$method][$path] ?? false; // on récupère le callback associée à l'url et à la méthode

        if (!$callback) { 
            $callback = $this->getCallback($path, $method); 
            if ($callback === false) { 
                $this->response->setStatusCode(404);
                return 'Not Found';
            }
        }

        // si le callback est un tableau, on instancie le controller et on appelle la méthode
        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
            $this->action = $callback[1]; // on définit l'action du controller dans l'application (pour l'utiliser dans les middlewares)
            
            Application::$app->controller = $callback[0];
            Middleware::runMiddlewares($callback[0]->middlewares, $this->request); // on exécute les middlewares du controlleur
        }
        
        return call_user_func($callback, $this->request, $this->response); // On exécute la fonction du controller
    }

  /**
   * The function `getCallback` takes a path and method as input, matches the path against defined
   * routes, extracts route parameters, sets the request parameters, and returns the corresponding
   * callback function if a match is found.
   * 
   * @param string path The path parameter is a string that represents the URL path that the user is
   * requesting. It should be in the format of "/example/path".
   * @param string method The method parameter is a string that represents the HTTP method of the
   * request, such as "GET", "POST", "PUT", etc.
   * 
   * @return mixed the callback associated with the matching route if a match is found. If no match is
   * found, it returns false.
   */
    public function getCallback(string $path, string $method): mixed
    {
        $path = trim($path, '/'); // on enlève les / au début et à la fin de l'url
        $routes = $this->routes[$method]; // on récupère les routes associées à la méthode (get, post, etc)

        foreach ($routes as $route => $callback) {
            $route = trim($route, '/'); 
            
            if (preg_match( // on vérifie si l'url correspond à l'expression régulière
                $this->buildRegexFromRoute($route), // On crée une expression régulière à partir de la route
                $path, // on compare l'expression régulière avec l'url
                $matches 
            )
            ) {
                $routeName = $this->extractRouteNames($route); // on récupère les noms des paramètres définis dans les routes ex : {id}
                $values = array_slice($matches, 1); // 
                
                $params = array_combine($routeName, $values); // on combine les noms des paramètres avec les valeurs des paramètres
                
                $this->request->setParams($params); // on définit les paramètres de la requête
                
                return $callback;
            }
        }

        return false;
    }

    /**
     * The function builds a regular expression pattern from a given route string by replacing
     * placeholders with a regex pattern.
     * 
     * @param string route The route parameter is a string that represents a route pattern. It may
     * contain placeholders enclosed in curly braces, such as "{id}" or "{slug}". These placeholders
     * represent dynamic parts of the route that can match any value.
     * 
     * @return string a regular expression string.
     */
    private function buildRegexFromRoute(string $route): string
    {
        return "@^" . preg_replace("/\{(\w+)\}/", "(\w+)", $route) . "$@"; // on remplace les paramètres par des regex pour matcher l'url
    }

   /**
    * The function extracts route names from a given route string in PHP.
    * 
    * @param string route The parameter `` is a string that represents a route.
    * 
    * @return array an array of route names extracted from the given route string.
    */
    private function extractRouteNames(string $route): array
    {
        preg_match_all("/\{(\w+)\}/", $route, $matches); // on récupère les noms des paramètres définis dans les routes ex : {id}
        return $matches[1];
    }
}
