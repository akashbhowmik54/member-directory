<?php

namespace MemberDirectory\Core;

class Router {
    public function register() {
        add_filter('template_include', [__CLASS__, 'include_template']);
    }

    public static function include_template($template) {
        $plugin_path = plugin_dir_path(dirname(__FILE__, 2));

        if (is_post_type_archive('team')) {
            $new_template = $plugin_path . 'templates/archive-team.php';
            if ('' !== $new_template) {
                return $new_template;
            }
        }

        if (is_singular('team')) {
            $new_template = $plugin_path . 'templates/single-team.php';
            if ('' !== $new_template) {
                return $new_template;
            }
        }
        
        if (is_post_type_archive('member')) {
            $new_template = $plugin_path . 'templates/archive-member.php';
            if ('' !== $new_template) {
                return $new_template;
            }
        }

        if (is_singular('member')) {
            $new_template = $plugin_path . 'templates/single-member.php';
            if ('' !== $new_template) {
                return $new_template;
            }
        }

        return $template;
    }
}
