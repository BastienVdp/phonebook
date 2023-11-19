<?php 

namespace App\Core;

use stdClass;
use App\Core\Router;
use App\Models\User;
use App\Core\Request;
use App\Core\Session;
use Firebase\JWT\JWT;
use App\Core\Database;
use App\Core\Response;

/* 
 * The above class represents a PHP application with routing, 
 * database connection, session management,
 * and user authentication functionality. 
 */
class Application
{
	private string $key = 'gB8Mb1GrBYnK6ZuHahPtWgVFGj3wLytr';

    public static $root_dir;
    public static $app;

    public Request $request;
    public Response $response;
    public Router $router;
    public Controller $controller;
    public Database $database;
    public Session $session;
    public ?User $user = null;
    public ?string $token = null;
    
    public function __construct(string $path, array $config, bool $isApi = false)
    {
        self::$root_dir = $path;
        self::$app = $this;

        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->database = new Database($config['database']);
        $this->session = new Session($config['session']);

        if (Application::$app->session->get('user')) {
            $this->user = Application::$app->session->get('user');
        }
    }

    /**
     * The "run" function echoes the result of resolving the router.
     */
    public function run(): void
    {
        echo $this->router->resolve();
    }

    /**
     * The function "get" sets a GET route in the PHP application's router.
     * 
     * @param string path The path parameter is a string that represents the URL path for which the
     * callback function should be executed. It can be a specific path like "/users" or a dynamic path
     * like "/users/{id}".
     * @param mixed callback The callback parameter is a mixed type, which means it can accept any type
     * of value. In this context, it is likely used to pass a function or method as an argument to be
     * executed when the specified path is accessed.
     */
    public static function get(string $path, mixed $callback): void
    {
        self::$app->router->get($path, $callback);
    }
    
    /**
     * The function "post" is used to register a POST route with a specified path and callback function
     * in a PHP application.
     * 
     * @param string path The path parameter is a string that represents the URL path for the route. It
     * specifies the endpoint where the HTTP POST request will be sent to.
     * @param mixed callback The callback parameter is a mixed type, which means it can accept any type
     * of value. In this context, it is likely used to specify a function or method that will be
     * executed when a POST request is made to the specified path.
     */
    public static function post(string $path, mixed $callback): void
    {
        self::$app->router->post($path, $callback);
    }

    /**
     * The function deletes a route in the PHP application's router.
     * 
     * @param string path The path parameter is a string that represents the URL path for the route
     * that you want to delete. It specifies the endpoint that the route will be associated with.
     * @param mixed callback The callback parameter is a function or method that will be executed when
     * the specified path is accessed with the DELETE HTTP method. It can be a closure, a function
     * name, or an array containing an object and a method name.
     */
    public static function delete(string $path, mixed $callback): void
    {
        self::$app->router->delete($path, $callback);
    }

    /**
     * The function checks if a user is currently connected in a PHP application.
     * 
     * @return a boolean value indicating whether the user is connected or not.
     */
    public static function isConnected()
    {
        return self::$app->user !== null;
    }

    /**
     * The function checks if the current user is an admin.
     * 
     * @return the value of the "admin" property of the "user" object in the "app" property of the
     * class.
     */
    public static function isAdmin()
    {
        return self::$app->user->admin;
    }

    /**
     * The function sets the user property and stores the user object in the session.
     * 
     * @param User user The "user" parameter is an instance of the User class.
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
        self::$app->session->set('user', $user);
    }

    /**
     * The function `unsetUser` sets the user property to null and removes the 'user' session variable.
     */
    public function unsetUser(): void
    {
        $this->user = null;
        self::$app->session->remove('user');
    }

    public function setToken($token)
    {
        $this->token = $token;
        self::$app->session->set('token', $token);
    }

    public function generateJwtToken($user)
    {
        $token = [
            'iat' => time(), // Heure d'émission
            'exp' => time() + (60 * 60 * 24), // Expiration après 1 heure
            'user' => $user, // Ajouter d'autres claims
        ];
    
        $jwt = JWT::encode($token, $this->key, 'HS256');
    
        return $jwt;
    }
}
