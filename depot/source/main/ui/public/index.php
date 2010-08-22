<?php   
 
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));
define('DEVELOPMENT_ENVIRONMENT',true);


include ("index1.php");

echo ROOT;
echo DS;

require_once (ROOT . DS . 'lib' . DS . 'bootstrap.php');


?>
