<?php 
include_once('./controller/BaseController.php');
require_once('./controller/ShoppingCartController.php');
include_once('./config/DatabaseClass.php');

if(!isset($_SESSION)) {
	session_start();
}

class FrontController {
    
    const DefaultController = 'Welcome';
    const PublicControllers = [];
    
    //  @return bool
    private static function IsPublicRequest () {
        $isControllerSet = isset($_REQUEST['c']);
        $controller = $isControllerSet ? $_REQUEST['c'] : FrontController::DefaultController;
        in_array($controller, FrontController::PublicControllers);
        return $controller;
    }

    
    // @return void
    // @param string $default The default controller to be rendered
    private static function RenderController ($default = FrontController::DefaultController) {
        if (!isset($_REQUEST['c'])) {
        	FrontController::Render($default);
        } else FrontController::RenderFromQueryString();
    }

    // @return void
    // @param string $controller -> A default controller to rendered
    private static function Render ($controller) {
        require_once "controller/".$controller."Controller.php";
        $controller = $controller . 'Controller';
        $controller = new $controller;
        $controller->Index();  
    }

    // @return void
    private static function RenderFromQueryString () {
        // Check Qty string
        $controller = strtolower($_REQUEST['c']);
        $accion = isset($_REQUEST['a']) ? $_REQUEST['a'] : 'Index';
        require_once "controller/".$controller."Controller.php";
        $controller = $controller . 'Controller';
        $controller = new $controller;
        call_user_func(array($controller, $accion));
    }

    // @return void
    public static function ProcessRequest () {
        // If this is a public request, just render
        if (FrontController::IsPublicRequest()) { FrontController::RenderController(); return; }
        // Otherwise, force render authentication controller (Login screen)
        header('Location: ?c='.FrontController::DefaultController);
    }

}

FrontController::ProcessRequest();

?>
