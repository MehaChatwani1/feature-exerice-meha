<?php
class WelcomeController extends BaseController {
    
    public function __construct(){
    }

    // @return void
    public function Index () {
        $module = [];
        parent::RenderPage(
            'Welcome', 
            'view/layout/layout.php', 
            'view/welcome_view.php',
            $module
        );
    }
}

?>