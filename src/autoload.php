<?php

require_once __DIR__ . '/Autoloading/ClassLoader.php';

$loader = new ClassLoader(dirname(__DIR__));

$loader->register();

return $loader;