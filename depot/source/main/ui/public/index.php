<?php   
 
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    // last request was more than 30 minates ago
    session_destroy();   // destroy session data in storage
    session_unset();     // unset $_SESSION variable for the runtime
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp




require_once (ROOT . DS . 'lib' . DS . 'bootstrap.php');
require_once (ROOT . DS . 'lib' . DS . 'shared.php');


?>
