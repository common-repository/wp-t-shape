<?php

namespace TShape;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


abstract class AbstractView
{

    protected $post_id;
    protected $post;
    protected $skills = array();

    protected $levelNames;

    function __construct($post_id)
    {
        $this->post_id = $post_id;

        $this->skills = $this->fetchData($post_id);

        $this->levelNames = $this->getLevels();

    }


    /**
     * @param $post_id
     * @return array
     */
    protected function fetchData($post_id)
    {
        global $wpdb;

        $stmt = "SELECT " . WPTS_BUR_SKILLS_TABLE . ".name as skillname,  " . WPTS_BUR_LEVEL_TYPES_TABLE . ".`id` as level  
        FROM " . WPTS_BUR_SKILLS_TABLE . ", " . WPTS_BUR_LEVEL_TYPES_TABLE . " 
        where tshape_id = " . $post_id . "
        AND " . WPTS_BUR_SKILLS_TABLE . ".bur_ts_level_types_id = " . WPTS_BUR_LEVEL_TYPES_TABLE . ".id";

        $skillsResult = $wpdb->get_results($stmt);

        $skills = array();

        if ($wpdb->num_rows > 0) {

            foreach ($skillsResult as $key => $value) {
                $skills[$value->skillname] = $value->level;

            }
        }
        return $skills;
    }


    /**
     * @param
     * @return array
     */
    public static function getLevels()
    {
        global $wpdb;

        $stmt = "SELECT * 
        FROM " . WPTS_BUR_LEVEL_TYPES_TABLE;

        $levelsResult = $wpdb->get_results($stmt);

        if ($wpdb->num_rows > 0) {

            foreach ($levelsResult as $key => $value) {

                if ($value->label != null) {

                    $name = $value->label;

                } else {
                    $name = __($value->name, "wp-tshape");
                }

                $levels[$value->id] = $name;
            }

        }
        return $levels;
    }


    /**
     * gives back level name (translated) or custom label if filled.
     * @param
     * @return array
     */

    public function getLevelName($levelid)
    {
        return $this->levelNames[$levelid];
    }


    /**
     * @param
     * @return array
     */
    public static function getSkillsWithDetails($post_id)
    {
        global $wpdb;

        $stmt = "SELECT " . WPTS_BUR_SKILLS_TABLE . ".name as skillname, " . WPTS_BUR_SKILLS_TABLE . ".id as skillid, " . WPTS_BUR_LEVEL_TYPES_TABLE . ".name as levelname, 
        " . WPTS_BUR_LEVEL_TYPES_TABLE . ".label as label," . WPTS_BUR_LEVEL_TYPES_TABLE . ".`id` as levelid  
        FROM " . WPTS_BUR_SKILLS_TABLE . ", " . WPTS_BUR_LEVEL_TYPES_TABLE . " 
        where tshape_id = " . $post_id . "
        AND " . WPTS_BUR_SKILLS_TABLE . ".bur_ts_level_types_id = " . WPTS_BUR_LEVEL_TYPES_TABLE . ".id";

        $levelsResult = $wpdb->get_results($stmt);

        $skills = null;
        $skill_arr = array();
        if ($wpdb->num_rows > 0) {

            foreach ($levelsResult as $key => $value) {

                $skill_arr["skillname"] = $value->skillname;
                $skill_arr["skillid"] = $value->skillid;
                $skill_arr["levelid"] = $value->levelid;

                if ($value->label != null) {

                    $skill_arr["levelname"] = $value->label;

                } else {
                    $skill_arr["levelname"] = __($value->levelname, "wp-tshape");
                }
                $skills[] = $skill_arr;
            }
        }
        return $skills;
    }


    /*
     *
     * get settings of WP T-Shape Plugin
     *
     * */

    public function getSettings()
    {

        $wpts_settings = array();
        $settings = get_option('wpts_settings_options');

        if(is_array($settings) && array_key_exists("bg_skilllevel", $settings)){
            $wpts_settings["bg_skill_level"] = $settings["bg_skilllevel"];
        }

        if(is_array($settings) && array_key_exists("bg_skillname", $settings)){
            $wpts_settings["bg_skill_name"] = $settings["bg_skillname"];
        }

        if(is_array($settings) && array_key_exists("font_col_skillname", $settings)){
            $wpts_settings["font_col_skillname"] = $settings["font_col_skillname"];
        }

       /* $wpts_settings["bg_skill_level"] = $settings["bg_skilllevel"];
        $wpts_settings["bg_skill_name"] = $settings["bg_skillname"];
        $wpts_settings["font_col_skillname"] = $settings["font_col_skillname"];*/

        return $wpts_settings;
    }

}