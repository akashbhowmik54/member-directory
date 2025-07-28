<?php
namespace MemberDirectory\Controllers\PostTypes;

class MemberPostType {
    public function register() {
        add_action('init', [$this, 'register_post_type']);
    }

    public function register_post_type() {
        $labels = [
            'name' => 'Members',
            'singular_name' => 'Member',
            'add_new' => 'Add New Member',
            'edit_item' => 'Edit Member',
            'new_item' => 'New Member',
            'view_item' => 'View Member',
            'search_items' => 'Search Members',
            'not_found' => 'No Members Found',
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_position' => 20,
            'menu_icon' => 'dashicons-id',
            'rewrite' => ['slug' => 'members'],
            'supports' => ['thumbnail', 'title'],
            'show_in_rest' => true,
        ];

        register_post_type('member', $args);
    }
}
