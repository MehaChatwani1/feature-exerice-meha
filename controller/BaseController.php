<?php
abstract class BaseController {

  // Function to Load View page with dyanmic values
  // @return void
  protected function RenderPage ($pageName, $layoutPath, $viewPath, $model = null) {
    $PAGE = $pageName; 
    $MODEL = $model;
    ob_start();
    include $viewPath;
    $MASTER_CONTENT = ob_get_clean();
    require_once $layoutPath;
  }

  // Function to Redirect from one Contoller to other controller with action
  // @return void
  protected function RedirectToController ($controller, $action = null) {
    if ($action == NULL) {
      header('Location: ?c='.$controller);
    } else {
      header('Location: ?c='.$controller.'&a='.$action);
    }
    die();
  }
}
?>