<?php

/**
 * Показывает все используемые переменные в шаблоне
 *
 * @author rgroup.ru <[client@rgroup.ru]>
 * @version 1.0
 */
class Twig_Extension_TplData extends Twig_Extension
{
    public function getFunctions()
    {
        // dump is safe if var_dump is overridden by xdebug
        $isDumpOutputHtmlSafe = extension_loaded('xdebug')
            // false means that it was not set (and the default is on) or it explicitly enabled
            && (false === ini_get('xdebug.overload_var_dump') || ini_get('xdebug.overload_var_dump'))
            // false means that it was not set (and the default is on) or it explicitly enabled
            // xdebug.overload_var_dump produces HTML only when html_errors is also enabled
            && (false === ini_get('html_errors') || ini_get('html_errors'))
            || 'cli' === php_sapi_name()
        ;

        return array(
            new Twig_SimpleFunction('tpl_data', 'twig_tplData', array('is_safe' => $isDumpOutputHtmlSafe ? array('html') : array(), 'needs_context' => true, 'needs_environment' => true)),
        );

    }

    public function getName()
    {
        return 'tpl_data';
    }
}


/**
 * Функция показывает все переменные переданные в шаблон
 *
 * @param  Twig_Environment $env
 * @param  array           $context
 * @return dump
 */
function twig_tplData(Twig_Environment $env, $context)
{
    if (!$env->isDebug()) {
        return;
    }
    // dump('adasd');
    return dump($context);
}
