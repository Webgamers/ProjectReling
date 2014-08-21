<?php
	namespace Reling\Application\Core;

	class Router {
	
		private $tokens = array(
			':string' => '([a-zA-Z]+)',
			':num'    => '([0-9]+)',
			':alpha'  => '([a-zA-Z0-9-_]+)'
		);
	
		private $route;
		private $handler;
		private $controller;
		private $method;
		private $parameters;
		private $routes;
		private $request;
	
		public function __construct(Request $request) {
			$this->routes  = require_once('../app/routes.php');
			$this->request = $request;

			$this->setRoute()
				 ->setController()
				 ->setMethod()
				 ->setParameters();
		}
				
		public function controller() {
			return !empty($this->controller) ? $this->controller : 'BaseController';
		}
		
		public function method() {
			return !empty($this->method) ? $this->method : 'getNotFound';
		}
		
		public function parameters() {
			return !empty($this->parameters) ? $this->parameters : array($this->request->uri());
		}
		
		public function current() {
			return $this->route;
		}
		
		public function handler() {
			return $this->handler;
		}
		
		private function setRoute() {		
			foreach($this->routes as $route => $handler) {				
				if(preg_match('#^/?' . strtr($route, $this->tokens) . '/?$#', strtoupper($this->request->method()).' '.$this->request->uri(), $parameters)) {	
					$this->route      = $route;
					$this->handler    = $handler;					
					$this->parameters = isset($parameters[1]) ? $parameters[1] : array();					
					
					break;
				}
			}
			
			return $this;
		}
		
		private function setController() {
			$this->controller = !empty($this->handler) ? explode('@', $this->handler)[0] : '';
			return $this;
		}
		
		private function setMethod() {
			$this->method = !empty($this->handler) ? explode('@', $this->handler)[1] : '';
			return $this;
		}
		
		private function setParameters() {
			foreach($this->tokens as $wildcard => $regex) {
				if(!strstr($this->route, $wildcard)) {
					$this->parameters = array_slice($this->request->segments(), 2);
				}
			}
		}
	}
?>