# YATE. Yeah! Another template.

**A simple template engine for php.** It is simple for simple project! Happy to use it. Or if you are a fresh coder you can read the source code to learn to write a template engine.

**YATE**, this name is comming from the book, *Head First Python*, containing the same name template for python.

## Feature

* Supports *{$variable_name}*, *{$array.key}*, *{$array.key.key2}* placehoder to parse the same name variable/array.
* Supports *{~php_code}* placehoder for you to run your php code. **The most usefull is to iterate your array.**
* Supports *<?php php_code ?>* and other original php placehoder to run php code.

### Notice

But **don't** *include/require/include_once/require_once files* into your template, if these *include/require files* contain placehoder above.  These palacehoders in your *include/require files* will not be parse correctly.  

Hope you know this feature and maybe in the next version of YATE, I would write YATE to support include/require file parse.

## Quickstart

* In your php file:

```php
require('path/to/yate.php'); 
// You can use 3 constant below to customize YATE.
# define('YATE_TEMPLATE_DIR', 'path/to/temp_dir'); // default: ./templates
# define('YATE_TEMPLATE_C_DIR', 'path/to/temp_cache_dir'); // default: ./templates_c
# define('YATE_CACHE_LIFE', 'second to cache life'); // default: 1

$Yate = new Yate;
$a = array(
    'table_name'=>'Yeah table',
    'array'=>array(
            array('name'=>'Yate','age'=>'1'),
            array('name'=>'China','age'=>'66')
        )
    );
$Yate->set($a);  // pass $a to YATE
echo $Yate->replace_template('head.php'); // Or you can just use $Yate->display('head.php') to output HTML
echo $Yate->replace_template('template.php');
echo $Yate->replace_template('footer.php');
```
* In your template file *template.php*(*head.php* and *footer.php* NOT showed):

```html
<p>{$table_name}</p>
<table>
	<tr><td>name</td><td>age</td></tr>
    {~foreach($array as $v): }  // if variable is array use *{~ php_code}* to iterate it. 
	<tr><td>{~$v.name }</td><td>{$v.age}</td></tr> 
    {~endforeach; }  // end the foreach syntax
</table>
```

## Copyright and license

[MIT license](https://github.com/ekeyme/yate/blob/master/LICENSE)