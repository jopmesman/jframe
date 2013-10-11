<?php
/**
 * @file Main router class.
 * All the magic starts here!
 */


/**
 * Load the files automaticaly
 * @param string $classNaam
 * Some lazy loading isn't it ;-)
 */
function __autoload($className) {
	//parse out filename where class should be located
	list($filename , $suffix) = explode('_' , $className);

	//select the folder where class should be located based on suffix
	switch (strtolower($suffix)) {
		case 'controller':
			$folder = '/controllers/';
		break;
		case 'model':
			$folder = '/models/';
		break;
		case 'library':
			$folder = '/libraries/';
		break;
		case 'driver':
			$folder = '/libraries/drivers/';
		break;
	}

	//compose file name
	$file = SERVER_ROOT . $folder . strtolower($filename) . '.php';

	//fetch file
	if (file_exists($file)) {
		//get file
		include_once($file);
	}
  else {
    throw new Exception('Wrong class selected');
  }
}


//Add some variables to use in the main template
$data = array();

try {
  $routing = new Routing_controller;
  $userController = new User_controller;
  //Init the routing
  $route = $routing->initRouting($_GET['page']);

  //Does the user have to be loggedin?
  if ($route['loggedin'] and userLoggedIn() == FALSE) {
    //Yes and the user is not!
    setErrorMessage("You are not allowed here.");
    gotoPage('home');
  }
  else{
    //Make an instance of the defined controller
    $controller = new $route['controller'];
    $resultOfController = call_user_method_array($route['function'], $controller, $route['variables']);

    //what's the type of return?
    switch ($route['returntype']) {
      case 'html':
        //add content and let the flow go
        $data['content'] = $resultOfController;
        break;
      case 'json':

        break;
    }
  }

  //Set the title
  if(isset($route['title'])) {
    $data['title'] = $route['title'];
  }


} catch (Exception $exc) {
  //Add a title
  $data['title'] = 'Error';
  //Add error content
  $errorController = new Error_Controller();
  $data['content'] = $errorController->getErrorPage();
  setErrorMessage('There is a technical error occurred');
}

$view = new View_Model(MAIN_TEMPLATE);

$blocksController = new Blocks_controller();
$data['blocks'] = $blocksController->CreateBlocks();

$messageserror = getErrorMessages();
if (count($messageserror) > 0 ){
  $data['messageserror'] = $messageserror;
}

$messagessuccess = getSuccessMessages();
if (count($messagessuccess) > 0 ){
  $data['messagessuccess'] = $messagessuccess;
}

//assign the variables
foreach ($data as $akey => $avalue) {
  $view->assign($akey, $avalue);
}

//Render it. FAST!
$view->render();