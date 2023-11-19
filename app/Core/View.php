<?php 

namespace App\Core;
class View 
{
	public static function make(string $view, array $params = [], string $layout = null): string
    {
        $layoutContent = self::layoutContent($layout ? $layout : Application::$app->controller->layout);
        $viewContent = self::viewContent($view, $params);
     
        return str_replace('{{ content }}', $viewContent, $layoutContent);
    }

    private static function layoutContent(string $layout): string
    {
        ob_start();
        include_once Application::$root_dir . "/resources/views/layouts/$layout.php";
        return ob_get_clean();
    }

    private static function viewContent(string $view, array $params = []): string
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include_once Application::$root_dir . "/resources/views/$view.php";
        return ob_get_clean();
    }

    public static function getCurrentPath()
    {
        return Application::$app->request->getPath();
    }

    public static function isCurrentPath($path)
    {
        return Application::$app->request->getPath() === $path;
    }

    public static function isCurrentPathStartWith($path)
    {
        return strpos(Application::$app->request->getPath(), $path) === 0;
    }

}