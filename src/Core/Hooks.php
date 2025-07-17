<?php

namespace MemberDirectory\Core;

class Hooks {
    public function register(): void {
        add_action('admin_init', [$this, 'removeDefaultEditorAndTitle']);
    }

    public function removeDefaultEditorAndTitle(): void {
        remove_post_type_support('member', 'title');
        remove_post_type_support('member', 'editor');
        remove_post_type_support('team', 'title');
        remove_post_type_support('team', 'editor');
    }
}
