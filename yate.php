<?php
/**
* YATE. Yeah! Another template
* A simple template toolkit for php.
* I give this name inspired by the same name in book Head First Python.
* Usege and notice:
* - Supports {$variable_name}, {$array.key}, {$array.key.key2} placehoder to parse the same name variable.
* - Supports {~php_code} placehoder for you to run your php code.
* - Supports <?php php_code ?> and et al original php placehoder to run php code.
* ! But don't include/require/include_once/require_once files into your template, if these include/require files contain placehoder above.
* - These palacehoders in your include/require files will not be parse correctly. 
* - Hope you know this feature and maybe in the next version of YATE, I would write YATE to support include/require file parse.
* -
* Example:
*     $Yate = new Yate;
*     $a = array('head'=>'head HTML', 'body'=>'body HTML', 'array'=>array(1,2,3));
*     $Yate->set($a);
*     echo $Yate->replace_template('head.php');
*     echo $Yate->replace_template('html.php');
*     echo $Yate->replace_template('footer.php');
* -
* It simple for simple project! Happy to use it.
* Coded by ekeyme@gmail.com, since Ag 05, 2015
*/
class Yate{

    // 左与右占位符分别为 '{$' / '}' 不提供方便的自定制；要改请小心更改预定义类变量 Yate::preg_compile_rule
    #private $left_delimiter = '{$'; private $right_delimiter = '}';
    // 模板缓存目录
    private $template_c_dir = './templates_c';
    // 模板目录
    private $template_dir = './templates';
    // 如果模板更新时间大于缓存模板更新时间？秒，则重新编译模板
    private $cache_life = 1;

    //变量储存的键-值对数组，用以替换模板中的占位符
    private $key_value_pair = array();
    //编译模板文件名的前缀
    private $prefix_template_c = 'c_';
    // 解析编译模板的正则替换规则
    private $preg_compile_rule = array(
        '/\{(\$[a-z0-9_]+)\}/i' => '<?php echo $1; ?>', // {$name}
          
        '/\{(\$[a-z0-9_]+)\.([a-z0-9_]+)\}/i' => '<?php echo $1[\'$2\']; ?>', // {$arr.key} 
          
        '/\{(\$[a-z0-9_]+)\.([a-z0-9_]+)\.([a-z0-9_]+)\}/i' => '<?php echo $1[\'$2\'][\'$3\']; ?>', // {$arr.key.key2}

        '/\{\~(.+?)\}/' => '<?php $1 ?>' // PHP代码{~var_dump($a)} 
    );

    /***初始化*/
    public function __construct(){
        if (defined('YATE_TEMPLATE_DIR')) $this->template_dir = YATE_TEMPLATE_DIR;
        if (defined('YATE_TEMPLATE_C_DIR')) $this->template_c_dir = YATE_TEMPLATE_C_DIR;
        if (defined('YATE_CACHE_LIFE')) $this->cache_life = YATE_CACHE_LIFE;
    }
    
    /**  
     *键-值赋值  
     *@param $key mixed 键名  
     *@param $value mixed 值  
     *@return void
     */
    public function set($key , $value = ''){
        if(is_array($key)){  
            $this->key_value_pair=array_merge($key, $this->key_value_pair);  
        }  
        else{  
            $this->key_value_pair[$key]=$value;  
        }
    }  
    
    /**
     *显示模板
     *@return void
     */
    public function display($template_path){
        echo $this->replace_template($template_path);
    }
    
    /**
     *替换模板的内容
     *过程包括1.将标识替换回原始的PHP代码形式；2.生成并存储替换后的模板
     *@param $file string 模板文件名
     *@param $replace array 替换键-值对.如果不提供将使用类中的$this->key_value_pair 数组替换
     *@return string
     */
    public function replace_template($file, $replace = false){
        if (!$replace) $replace = $this->key_value_pair;
        
        $template_path = $this->template_dir. '/' . $file;
        $template_c_path = $this->template_c_dir . '/' . $this->prefix_template_c . $this->get_cache_file($template_path);
        
        // 缓存是否过期？过期重新编译并缓存
        if ($this->cache_life < filemtime($template_path)-filemtime($template_c_path))
            $this->compile_and_cache($template_path, $template_c_path);

        //打开输出控制缓冲，获取缓存区的内容
        ob_start();

        @extract($replace, EXTR_OVERWRITE);  
        include($template_c_path);  
        $content = ob_get_contents();  

        ob_end_clean();
        
        return $content;  
    }

    /**
     *编译并缓存模板文件
     *@param $template_path string 模板文件的路径
     *@param $template_c_path string 编译后模板文件的路径
     */
    private function compile_and_cache($template_path, $template_c_path){
        $content = $this->readfile($template_path);
        $content = preg_replace(array_keys($this->preg_compile_rule), $this->preg_compile_rule , $content);

        file_put_contents($template_c_path, $content);
        return $content;
    }

    /**
     *返回模板文件相对应的缓存模板文件名
     *通过模板文件的路径获得其缓存模板
     *@param $template_path string 模板文件的path路径
     *@return string
     */
    private function get_cache_file($template_path){
        return str_replace('/', '%', $template_path);
    }
    
    /**
     *读取文件的内容  
     *@param $file string 文件名  
     *@return $content string 文件内容  
     */  
    private function readfile($file){

        try{
            return file_get_contents($file);
        }
        catch(Exception $e){
            exit($e->errorMessage());
        }

    }


}

/**
 *检测并安装Yate所需的模板目录条件
 *@to-do
 */
function yate_check_and_install(){

}