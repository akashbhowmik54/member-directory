<?php
namespace MemberDirectory\Core;

class Plugin {
    public static function init(): void {
        self::load_dependencies();
    }

    private static function load_dependencies(): void {
        // Future includes: CPTs, hooks, shortcodes, etc.
    }
}
error_log('hello');