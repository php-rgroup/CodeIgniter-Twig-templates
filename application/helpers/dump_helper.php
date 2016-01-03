<?php

/**
 * Dump helper. Functions to dump variables to the screen, in a nicley formatted manner.
 * @author Joost van Veen
 * @version 1.0
 */
if (!function_exists('dump')) {
    function dump($string = '') {
        ob_start();
        var_dump($string);
        $string = ob_get_contents();
        $string = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $string);
        ob_end_clean();
        echo '<pre style="border: 1px dotted red; padding: 10px;">'.$string.'</pre>';
    }
}