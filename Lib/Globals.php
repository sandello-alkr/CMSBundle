<?
namespace alkr\CMSBundle\Lib;
 
class Globals
{
    protected static $parameters;
    
    public static function setParams($params)
    {
        self::$parameters = $params;
    }

    public static function getParams()
    {
        return self::$parameters;
    }
 
    public static function getUrlByPath()
    {
        return self::$parameters['url_by_path'];
    }
}