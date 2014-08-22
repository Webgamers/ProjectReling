<?php
	spl_autoload_register(function($c) {
		preg_match('/^(.+)?([^\\\\]+)$/U',ltrim($c,'\\'),$m);
		@include_once(str_replace('\\','/',$m[1]).str_replace(['\\','_'],'/',$m[2]).'.php');
	});
?>