<?
    include_once('../lib/Analog.php');
    $log_file = 'log.txt';
    Analog::handler (Analog\Handler\File::init ($log_file));
    Analog::log('index');

    include_once('../router.inc.php');
    new Router();
?>