<?php
	spl_autoload_register(function($className) {
		//if(file_exists(PATH_MDLS.$className.'.php')) require_once(PATH_MDLS.$className.'.php');
		if(file_exists('../app/controllers/'.$className.'.php')) require_once('../app/controllers/'.$className.'.php');
		//if(file_exists(PATH_LIB_CORE.$className.'.php')) require_once(PATH_LIB_CORE.$className.'.php');
		//if(file_exists(PATH_LIB_USER.$className.'.php')) require_once(PATH_LIB_USER.$className.'.php');
	});
?>