<?php
namespace MemberDirectory\Core;

class Activator {
    public static function activate() {
        (new Router())->add_rewrite_rules();
        flush_rewrite_rules();
    }
}
