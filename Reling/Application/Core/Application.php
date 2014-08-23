<?php
	namespace Reling\Application\Core;
	
	use Exception;

    class Application {
	
		private $config;
	
		public function __construct() {
			$this->loadConfig();
			$this->registerClassAliases();
		}
		
		private function loadConfig() {
			$this->config = require_once('../app/config/app.php');
			return $this;
		}
		
		private function registerClassAliases() {
			foreach($this->config['aliases'] as $alias => $original) {
				if(!class_alias($original, $alias)) {
					throw new Exception(sprintf('Creation of alias "%s" for "%s" failed!', $alias, $original));
				}
			}
		}
		
		public function debug() {
			return $this->config['debug'];
		}
		
		public function host() {
			return $this->config['host'];			
		}
		
		public function url() {
			return $this->config['url'];
		}
	}
?>