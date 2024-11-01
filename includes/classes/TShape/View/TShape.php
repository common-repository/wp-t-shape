<?php

namespace TShape\View;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use TShape\AbstractView;
use TShape\View;

class TShape extends AbstractView implements View
{

    public function getSortedData()
    {
        $skills_arr = $this->skills;

        //sort skills array: low to and high and back to low
        arsort($skills_arr);
        $tshape_arr = [];
        $i = 0;
        foreach ($skills_arr as $key => $value) {
            if ($i == 0) {
                $tshape_arr [$key] = $value;

            } else {
                if (($i % 2) == 1) {
                    $tshape_arr = array($key => $value) + $tshape_arr;
                } else {
                    $tshape_arr [$key] = $value;
                }
            }

            $i++;
        }
        return $tshape_arr;
    }


    /**
     * @param null $attr
     * @return string
     */
    public function render($attr = null)
    {
        // get settings of WP T-Shape Plugin
        $settings = AbstractView::getSettings();


        // get attributes
        if(array_key_exists("bg_skill_level", $settings)){
            $bg_skill_level = 'background: '.$settings["bg_skill_level"]. '; ';
        }
        else{
            $bg_skill_level = "";
        }

        if(array_key_exists("bg_skill_name", $settings)){
            $bg_skill_name = 'background: '.$settings["bg_skill_name"]. '; ';
        }
        else{
            $bg_skill_name = "";
        }

        if(array_key_exists("font_col_skillname", $settings)){
            $font_col_skillname = 'color: '.$settings["font_col_skillname"]. '; ';
        }
        else{
            $font_col_skillname = "";
        }

        if(array_key_exists("height", $attr)){
            $height = 'height: '.$attr["height"]. 'px; ';
            $width = 'width: '.($attr["height"]-30). 'px; ';
        }
        else{
            $height = "";
            $width = "";
        }

        $sortedData = $this->getSortedData();


        $renderedTshape = '';
        //TO DO get name of level from DB, translate ToolTip Text

        $renderedTshape .= '<div class= "tshape-tshape">';

        // @todo: better naming of key and value, what is it?
        foreach ($sortedData as $key => $value) {

            $skills_level_asword = AbstractView::getLevelname($value);

            switch ($value) {
                case 1:
                    $skills_level_css_class = "tshape-skills__level--basic";

                    break;
                case 2:
                    $skills_level_css_class = "tshape-skills__level--good";

                    break;
                case 3:
                    $skills_level_css_class = "tshape-skills__level--verygood";

                    break;
                case 4:
                    $skills_level_css_class = "tshape-skills__level--expert";

                    break;
            }

            $renderedTshape .= '<div class = "tshape-skills">';
            $renderedTshape .= '<div class = "tshape-skills__name" style="'.esc_html($height). esc_attr($bg_skill_name) . esc_attr($font_col_skillname) . '"> <div class = "tshape-skills__nametext"> <div class="tshape-skills__textoverflow" style="' . (esc_attr($width)) . '"> ' . $key . ' </div></div></div>';
            $renderedTshape .= '<div data-balloon="' . esc_html($skills_level_asword) . ' in ' . esc_html($key) . '" data-balloon-pos="down" data-balloon-length="large" class = "tshape-skills__level  ' . esc_attr($skills_level_css_class) . ' " style="' . esc_attr($bg_skill_level) . '"></div>';
            $renderedTshape .= '</div>';
        }
        $renderedTshape .= '</div>';

        return $renderedTshape;
    }
}