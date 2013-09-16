<?
    include_once('../lib/ChromePhp.php');
    ChromePhp::log('==================== New request =====================');

    include_once('../router.inc.php');
    new Router();
?>