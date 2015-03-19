<?php
/**
 * Created by kevin.
 * User: kevin
 * Date: 14-10-23
 * Time: 下午4:16
 */

function dump($var, $bool=true) {
    static $i = 0;
    if($i == 0) {
        header("Content-type:text/html;charset:utf-8");
    }
    CVarDumper::dump($var,10,true);
    $i++;
   if($bool) {
       exit;
   } else {
       echo '</br>';
   }
}
