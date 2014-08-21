<?php
	namespace Reling\Application\Core;
	
	use ReflectionClass;
	use ReflectionParameter;

	class DependencyInjector {
	
		private $dependencies = array();
		private $instances    = array();
		
		public function __construct($className) {
			$this->getDependencies($className);					
		}		
		
		public function resolve() {
			$dependencies = array_reverse($this->dependencies);
			
			foreach($dependencies as $className => $deps) {			
				
				$classInstances = array();
				foreach($deps as $name => $dep) {
					if(!isset($this->instances[$dep])) {
						$reflection            = new ReflectionClass($dep);
						$this->instances[$dep] = $reflection->newInstanceArgs();						
						$classInstances[]      = $this->instances[$dep];						
					} else {
						$classInstances[]      = $this->instances[$dep];
					}				
				}				
				
				if(!isset($this->instances[$className])) {
					$reflection                  = new ReflectionClass($className);					
					$this->instances[$className] = $reflection->newInstanceArgs($classInstances);
				}
			}
			
			return $this;
		}
		
		public function getInstance() {
			$instances = array_reverse($this->instances);			
			return reset($instances);
		}
		
		private function getDependencies($className) {
			$reflection  = new ReflectionClass($className);
			$constructor = $reflection->getConstructor();
			
			$parameters  = $constructor->getParameters();
			if(!empty($parameters)) {
				foreach($parameters as $parameter) {					
					$this->dependencies[ucfirst($className)][ucfirst($parameter->name)] = ucfirst($parameter->name);
					
					// Be aware of recursion !
					$this->getDependencies($parameter->name);
				}				
			}

			$this->instances[$className] = $reflection->newInstanceArgs();
		}
	}
?>