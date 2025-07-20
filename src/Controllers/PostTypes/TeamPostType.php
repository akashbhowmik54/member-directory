<?php
namespace MemberDirectory\Controllers\PostTypes;

class TeamPostType {
    public function register() {
        add_action('init', [$this, 'register_post_type']);
    }

    public function register_post_type() {
        $labels = [
            'name' => 'Teams',
            'singular_name' => 'Team',
            'add_new' => 'Add New Team',
            'edit_item' => 'Edit Team',
            'new_item' => 'New Team',
            'view_item' => 'View Team',
            'search_items' => 'Search Teams',
            'not_found' => 'No Teams Found',
        ];

        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'menu_position' => 21,
            'menu_icon' => 'dashicons-groups',
            'rewrite' => ['slug' => 'teams'],
            'supports' => ['title'],
            'show_in_rest' => true,
        ];

        register_post_type('team', $args);
    }
}
