<?php
namespace TShape;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

interface View{

    /**
     * View constructor.
     * @param int $post_id
     */
    function __construct($post_id);


    /**
     * @param array $attr
     * @return mixed
     */
    public function render($attr = null);

}