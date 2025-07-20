<?php
namespace MemberDirectory\Core;

use MemberDirectory\Controllers\PostTypes\MemberPostType;
use MemberDirectory\Controllers\PostTypes\TeamPostType;
use MemberDirectory\MetaBoxes\MemberMetaBox;
use MemberDirectory\MetaBoxes\TeamMetaBox;
use MemberDirectory\Admin\AdminAssets;
use MemberDirectory\Core\Hooks;
use MemberDirectory\Admin\MemberAdminColumns;
use MemberDirectory\Handlers\ContactFormHandler;
use MemberDirectory\Frontend\FrontendAssets;

class Plugin {
    public static function init(): void {
        self::load_dependencies();

        // Register CPTs
        (new MemberPostType())->register();
        (new TeamPostType())->register();

        // Register Metaboxes
        (new MemberMetaBox())->register();
        (new TeamMetaBox())->register();

        // Register Assets
        (new AdminAssets())->register();
        (new FrontendAssets())->register();

        // Register Hooks
        (new Hooks())->register();

        (new MemberAdminColumns())->register();

        ContactFormHandler::init();

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

    private static function load_dependencies(): void {
        // Future includes: CPTs, hooks, shortcodes, etc.
    }
}
