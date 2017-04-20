<?php
/**
 * This will be my very first autoloader HMB
 *
 * God willing it will load classes via the prefix and class name.
 * @param string $class fully legit class name to load
 * @see deepdivedylan@github also follow his twitter @deepdivedylan
 * @see php-fig.org/psr/psr-4/examples
 **/
spl_autoload_register(function($class) {
	/**
	 * CONFIGURABLE PARAMETERS
	 * prefix: the prefix for all the classes (i.e., the namespace)
	 * baseDir: the base directory for all classes (default = current directory)
	 **/
	$prefix = "Edu\\Cnm\\DataDesign";
	$baseDir = __DIR__;

	// does the class use the namespace prefix?
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		// no, move to the next registered autoloader
		return;
	}

	// get the relative class name
	$className = substr($class, $len);

	// replace the namespace prefix with the base directory, replace namespace
	// separators with directory separators in the relative class name, append
	// with .php
	$file = $baseDir . str_replace("\\", "/", $className) . ".php";

	/home/foo/bar...
	c:\users\foo\bar


	// if the file exists, require it
	if(file_exists($file)) {
		require_once($file);
	}
});