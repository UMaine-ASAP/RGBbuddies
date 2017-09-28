<?php
  function call($controller, $action) {
    //require the file that mactches the controller name
    require_once("controllers/".$controller."_controller.php");

    //create a new instance of the needed controller
    switch($controller) {
      case 'pages':
        $controller = new PagesController();
        break;
      case 'posts':
        //we need the model to query the database later in the controller
        require_once('models/post.php');
        $controller = new PostsController();
        break;
      case 'user':
        $controller = new UserController();
        break;
    }

    //call the action
    $controller->{ $action }();
  }

  //just a list of the controllers we have and their actions
  //we consider these "allowed" values
  $controllers = array('pages' => ['home', 'error', 'profile'],
                       'posts' => ['index', 'show'],
                       'user' => ['index', 'register']);

  //check that the requested controller and action are both allowed
  //if someone tries to access something else he will be redirected to the error action of the pages
  if (array_key_exists($controller, $controllers)) {
    if (in_array($action, $controllers[$controller])) {
      call($controller, $action);
    } else {
      call('pages', 'error');
    }
  } else {
    call('pages', 'error');
  }
?>
