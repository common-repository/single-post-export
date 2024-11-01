<?php 
namespace HtmExport\Plugin;
if ( ! defined( 'ABSPATH' ) ) exit;
/* Class Loader */
class ClassLoader
{         
    /*
        Function to look up appropriate includes
    */
    public static function  LoadLibs($className){   

        if(class_exists($className)){
            return;
        }
    
        //trim preceding items
        $cls = ltrim($className, '\\');

        //If the class name doesnt have our namespace then bail
        if(strpos($cls, __NAMESPACE__) !== 0){
            return;
        }

     
        //Strip out namespace.
        $cls = str_replace(__NAMESPACE__, '', $cls);
        
        //Get the path
        $path = dirname( __FILE__ );

        //file name 
        $file = str_replace('\\', '/', $cls) . '.php';

        //lower case file name 
        $file = strtolower($file);

        //Trim the preceding        
        $file = ltrim($file, '\\');
        $file = ltrim($file, '/');

   
      
        if(strpos($file,'woo')  !== false){
             $file = 'woo'.'/'.'class-'.$file;     
        }else{
            //If there is a helper in the class name then include that in our search
            $file = 'class-'.$file;
        }
       
        //Prepend path to file name 
        $file = $path.'/'.$file;

        //Check file exists 
        if (!file_exists ($file)){             
            return;
        }    
        //Include file 
        include $file;
    }

     /*
        Function to look up appropriate includes
    */
    public static function  LoadTemplate($name){  
        //Get the path
        $path = dirname( __FILE__ );

        //Prepend path to file name 
        $file = $path.'/'.'templates'.$name;
   
        //Check file exists 
        if (!file_exists ($file)){             
            return;
        }    
        //Include file 
        include $file;
    }

            /*
        Function to look up appropriate includes
    */
    public static function LoadCustomLib($name){  
        //Get the path
        $path = dirname( __FILE__ );

        //Prepend path to file name 
        $file = $path.'/'.$name.'.php';
   
        //Check file exists 
        if (!file_exists ($file)){             
            return;
        }    
        //Include file 
        include $file;
    }


    
    /*
        Init the classes 
    */
    public static function InitClasses($classes){
        $namespace = __NAMESPACE__;
        for($i = 0; $i < count($classes); $i++){
            //Is this an array ? 
            if(is_array($classes[$i])){
                $function = $classes[$i]['function'];
                $condition = $classes[$i]['condition'];
                $classname = $namespace.'\\'.$classes[$i]['class'];
                if($function() == $condition){
                    new $classname;
                }
            }else{
                  $classname = $namespace.'\\'.$classes[$i];
                  new $classname;
            }
        }
    }
}

//Register the autoloader here 
spl_autoload_register('\HtmExport\Plugin\ClassLoader::LoadLibs');
