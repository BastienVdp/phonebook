<?php 

namespace App\Core;
abstract class Controller
{
    public string $layout = 'default';
    public array $middlewares = [];

    /**
     * The function "registerMiddleware" adds an array of middlewares to the existing list of
     * middlewares.
     * 
     * @param array middlewares An array of middleware classes or middleware groups.
     */
    protected function registerMiddleware(array $middlewares): void
    {
        $this->middlewares[] = $middlewares;
    }
}
