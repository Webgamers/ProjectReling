<?php
	namespace Reling\Application\Core;

	class Request {
	
		private $uri;
		private $uriParts = array();		
	
		public function __construct() {			
			$this->setUri()->setUriParts();
		}
		
		public function uri() {
			return $this->uri;
		}		
		
		public function segment($index) {
			return isset($this->uriParts[$index]) ? $this->uriParts[$index] : null;
		}
		
		public function segments() {
			return $this->uriParts;
		}	
		
		public function ajax() {
			return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
		}
		
		public function method() {
			return $_SERVER['REQUEST_METHOD'];
		}
		
		private function setUri() {
			if(!empty($_SERVER['PATH_INFO'])) {
				$this->uri = $_SERVER['PATH_INFO'];
				return $this;
			}
						
			if(!empty($_SERVER['ORIG_PATH_INFO']) && $_SERVER['ORIG_PATH_INFO'] !== '/index.php') {
				$this->uri = $_SERVER['ORIG_PATH_INFO'];
				return $this;
			}
				
			if(!empty($_SERVER['REQUEST_URI'])) {
				if(strpos($_SERVER['REQUEST_URI'], '?') > 0) {
					$this->uri = strstr($_SERVER['REQUEST_URI'], '?', true);
				} else {
					$this->uri = $_SERVER['REQUEST_URI'];
				}

				return $this;
			}
			
			return false;
		}
		
		private function setUriParts() {
			$this->uriParts = explode('/', $this->uri());
			return $this;
		}
	}
?>