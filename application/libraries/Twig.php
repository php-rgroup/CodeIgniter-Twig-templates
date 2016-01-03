<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Twig
{
    protected $ci;

    // Extension file
    protected $ext = '.twig';
    protected $twig;

    private $template = VIEWPATH;
    private $debug = TRUE;

    public function __construct()
    {
        $this->ci =& get_instance();

        // Load library
        require_once APPPATH . 'libraries/Twig/Autoloader.php';

        //
        Twig_Autoloader::register();
        log_message('debug', "Twig Autoloader Loaded");

        //
        $loader = new Twig_Loader_Filesystem( $this->template );
        $this->twig = new Twig_Environment($loader, array(
            'cache' => APPPATH . 'cache',
            'debug' => $this->debug
        ));

        // Load dump Twig
        if ($this->debug) {
            $this->twig->addExtension(new Twig_Extension_tplData());
            $this->twig->addExtension(new Twig_Extension_tplVars());
            $this->twig->addExtension(new Twig_Extension_tplArrays());
        }


        $this->twig->addFunction( 'tpl_dump', new Twig_Function_Function('dump') );


    }

    // -------------------------------------------------------------------------


    /**
     * Показать шаблон
     *
     * @param  string $template
     * @param  array  $data
     * @return void
     */
    public function view($template, $data = array()) {
        if ( $this->_template_exists($template) === TRUE ) {

            // Elapsed_time
            $data['elapsed_time'] = $this->ci->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');

            // Memory
            $memory = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2) . 'MB';
            $data['memory_usage'] = $memory;

            // Добавить системные константы
            $this->_added_constants($data);

            echo $this->twig->render( $template . $this->ext, $data );
        }
    }

    // -------------------------------------------------------------------------


    /**
     * Рендер шаблона и вернуть его
     *
     * @param  string $template
     * @param  array  $data
     * @return void
     */
    public function render($template, $data = array()) {
        if ( $this->_template_exists($template) === TRUE ) {
            return $this->twig->render( $template . $this->ext, $data );
        }
    }

    // -------------------------------------------------------------------------


    /**
     * Рендер переданной строки, а не файла.
     *
     * @param  string $string
     * @param  array  $data
     * @return string
     */
    public function string( $string = '', $data = array() ) {
        // Не загружаем класс для работы со строками,
        // если у нас не переданы в шаблон данные, которыми
        // нужно заменять текст. Просто возвращаем переданную строку.
        if ( !count($data) ) {
            return $string;
        }

        $loader = new Twig_Loader_String();
        $twig   = new Twig_Environment($loader);

        return $twig->render($string, $data);
    }

    // -------------------------------------------------------------------------


    /**
     * Поиск шаблона в каталоге
     *
     * @param  string $filename
     * @return boolean
     */
    private function _template_exists( $filename = '' ) {
        if ( !file_exists( $this->template .'/'. $filename . $this->ext ) ) {
            show_error('Не найден шаблон: ' . $filename . $this->ext);
        }

        return TRUE;
    }



    /**
     * Добавить константы в массив переданных данных
     *
     * @param  array  &$data
     * @return void
     */
    private function _added_constants( &$data = array() ) {
        if ( function_exists('get_defined_constants') ) {
            $constant_all = get_defined_constants();
            $system_constant = array(
                'ENVIRONMENT', 'SELF', 'BASEPATH', 'FCPATH',
                'SYSDIR', 'APPPATH', 'VIEWPATH', 'CI_VERSION');
            foreach ( $system_constant as $key ) {
                $data['CONSTANT'][$key] = $constant_all[$key];
            }
        }

    }

}

/* End of file Twig.php */
/* Location: ./application/libraries/Twig.php */
