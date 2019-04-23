<?php
namespace System\Cache;

class Routing implements CacheInterface{
    static $directory = APP_ROOT.'/cache/routing.php';

    static function transform($data){
        return "<?php\n\$cache=".var_export(($data), true).";\n";
    }
}