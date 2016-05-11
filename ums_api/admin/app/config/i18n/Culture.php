<?php
/**
 * Created by PhpStorm.
 * User: ViviPro
 * Date: 3/23/2016
 * Time: 3:36 PM
 */
class Culture{
    protected $realpath;
    public $langkey;
    public $module;
    public $langarr;
    /**
     * Culture constructor.
     */
    public function __construct($langkey=null,$module=null)
    {
        if($langkey!=null){
            $this->langkey = $langkey;
            $this->module = $module;
            $this->realpath = dirname(__FILE__)."/";
            $this->init();
        }
        return $this;
    }

    

    public function init(){
        $arr = $this->registermodule("_general");
        $arr = array_merge($arr,$this->registermodule("_sidebar"));
        if(strlen($this->module)!=null) {
            $mdlarr = $this->registermodule($this->module);
            $this->langarr = array_merge($arr, $mdlarr);
        }
        else $this->langarr = $arr;
    }
    public function registermodule($module){
        $module_lang = $this->realpath."lang/{$this->langkey}/$module.lang";
        if(!file_exists($module_lang)) return array();
        $lines = file($module_lang);
        foreach($lines as $line){
            if(strlen($line)>0){
                list($key,$value) = explode("=",$line);
                $key = rtrim(ltrim($key));
                $value = rtrim(ltrim($value));
                $arr[$key] = $value;
            }
        }
        return $arr;
    }
}