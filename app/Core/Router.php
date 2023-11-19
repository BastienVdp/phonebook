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
    * The function adds a GET route to the routes array with the specified path and callback.
    * 
    * @param string path The path parameter is a string that represents the URL path for the route. It
    * specifies the endpoint that the user will access in order to trigger the callback function.
    * @param callback The callback parameter is a function or method that will be executed when the
    * specified path is requested using the HTTP GET method. It can be a closure, a function name, or
    * an array containing an object and a method name.
    */
    public function get(string $path, $callback): void
    {
        $this->routes['get'][$path] = $callback;
    }

    /**
     * The function "post" adds a callback function to the routes array for the specified path and HTTP
     * method "POST".
     * 
     * @param string path The path parameter is a string that represents the URL path for the route. It
     * specifies the endpoint where the callback function will be executed when an HTTP POST request is
     * made to that path.
     * @param callback The  parameter is a callback function or method that will be executed
     * when the specified path is accessed using the HTTP POST method. It can be a closure, an
     * anonymous function, or the name of a function or method defined elsewhere in the code.
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
     * The resolve function checks if a callback exists for a given path and method, and if not, it
     * sets a 404 status code and returns "Not Found". If a callback exists, it instantiates the
     * controller and runs any middlewares before calling the callback with the request and response
     * objects.
     * 
     * @return mixed a mixed value. It could be any type of value depending on the execution path of
     * the code.
     */
    public function resolve(): mixed
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            $callback = $this->getCallback($path, $method);
            if ($callback === false) {
                $this->response->setStatusCode(404);
                return 'Not Found';
            }
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0]();
            $this->action = $callback[1];
            
            Application::$app->controller = $callback[0];
            Middleware::runMiddlewares($callback[0]->middlewares, $this->request);
        }
        
        return call_user_func($callback, $this->request, $this->response);
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
        $path = trim($path, '/');
        $routes = $this->routes[$method];

        foreach ($routes as $route => $callback) {
            $route = trim($route, '/');
            
            if (preg_match(
                $this->buildRegexFromRoute($route), 
                $path, 
                $matches
            )
            ) {
                $routeName = $this->extractRouteNames($route);
                $values = array_slice($matches, 1);
                
                $params = array_combine($routeName, $values);
                
                $this->request->setParams($params);
                
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
        return "@^" . preg_replace("/\{(\w+)\}/", "(\w+)", $route) . "$@";
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
        preg_match_all("/\{(\w+)\}/", $route, $matches);
        return $matches[1];
    }
}
