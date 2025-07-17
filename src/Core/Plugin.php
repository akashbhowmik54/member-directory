<?php
namespace MemberDirectory\Core;

use MemberDirectory\Controllers\PostTypes\MemberPostType;
use MemberDirectory\Controllers\PostTypes\TeamPostType;

class Plugin {
    public static function init(): void {
        self::load_dependencies();

        // Register CPTs
        (new MemberPostType())->register();
        (new TeamPostType())->register();
    }

    private static function load_dependencies(): void {
        // Future includes: CPTs, hooks, shortcodes, etc.
    }
}
