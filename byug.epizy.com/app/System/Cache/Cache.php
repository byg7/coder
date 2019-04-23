<?php
namespace System\Cache;

class Cache{
    static function createCache($component, $data){
        switch ($component){
            case 'routing' : {

                if(cache === false) return false;

                if(!file_exists(dirname(Routing::$directory))){
                    mkdir(dirname(Routing::$directory),02775);
                    file_put_contents(dirname(Routing::$directory).'/readme.txt', 'Здесь файлы создаются автоматически');
                }
                @chmod(dirname(Routing::$directory),02775);
                if(!file_exists(Routing::$directory)){
                    file_put_contents(Routing::$directory, Routing::transform($data));
                }

                break;
            }

            default : {
                $file=sha1($component);
                if(!file_exists(dirname(Routing::$directory))){
                    mkdir(dirname(Routing::$directory),02775);
                    file_put_contents(dirname(Routing::$directory).'/readme.txt', 'Здесь файлы создаются автоматически');
                }
                @chmod(dirname(Routing::$directory),02775);
                file_put_contents(dirname(Routing::$directory).'/'.$file.'.ser', serialize($data));

                return null;
            }
        }
    }
    static function loadFromCache($component){

        switch ($component){
            case 'routing' : {

                if(cache === false) return false;

                if(file_exists(Routing::$directory)){
                    require_once Routing::$directory;
                    chmod(dirname(Routing::$directory),02775);
                    return $cache;
                }

                return null;
            }

            default : {
                $file=sha1($component);
                if(!file_exists(dirname(Routing::$directory).'/'.$file.'.ser')){
                   $res=@unserialize(file_get_contents(dirname(Routing::$directory).'/'.$file.'.ser'));
                   return $res;
                }
                return null;
            }
        }
    }
}