<?
    include_once('../lib/ChromePhp.php');
    ChromePhp::log('==================== New request =====================');
    ChromePhp::log('server requested', $_REQUEST);

    include_once('../router.inc.php');
    new Router();
?>