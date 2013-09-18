<?
    //include_once('../lib/ChromePhp.php');
    //ChromePhp::log('==================== New request =====================');
    //ChromePhp::log('<- request', $_SERVER['REQUEST_URI'], $_REQUEST);

    include_once('../router.inc.php');
    new Router();
?>