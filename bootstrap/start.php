<?php
	set_include_path('../');

	// Start the application
	$app        = new Reling\Application\Core\Application;
	
	// Examine the request
	$request    = new Request;
	
	// Route the request
	$route      = new Router($request);	
	
	// Which controller should be called according to the request
	$controller = $route->controller();
	
	// Which method should be called according to the request
	$method     = $route->method();
	
	// And what parameters were submitted by the request
	$parameters = $route->parameters();
	
	/*	
		Ok - We know what controller should be called but we don't know
		anything about the dependencies the controller might have. 
		
		So this little class does nothing else than examine the detected 
		controller and check for dependencies in the __construct() method.
		
		If there are some dependencies it will also resolve the dependencies
		of the found dependencies. Recursive action FTW! The magic is done
		by the reflection API of PHP.
	*/	 
	$di         = new DI($controller);
	
	// After the dependencies are resolved, get the controller instance
	$instance   = $di->resolve()->getInstance();
	
	// Check if the requested method exists in the requested controller. If not, try calling the "missingMethod" method :D
	$call       = array($instance, method_exists($instance,$method) ? $method : 'missingMethod');
	
	// Now call the requested method in the requested controller and pass the parameters
	$result     = call_user_func_array($call, $call[1] == 'missingMethod' ? array($parameters) : $parameters);
	
	// Create a new respone object
	$response   = new Response;
	
	// Set the content of the response and send it back to the browser
	$response->setContent($result)->send();	
?>