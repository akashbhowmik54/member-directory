<?php
namespace MemberDirectory\Admin;

class AdminAssets {
    public function register(): void {
        add_action('admin_enqueue_scripts', [$this, 'enqueue']);
    }

    public function enqueue($hook): void {
        global $post_type;

        if (($hook === 'post-new.php' || $hook === 'post.php') && $post_type === 'member') {
            wp_enqueue_media();
            wp_enqueue_script(
                'member-media-upload',
                AKB_MEMBER_DIRECTORY_URL . 'assets/js/member-meta-box.js',
                ['jquery'],
                '1.0.0',
                true
            );

            wp_enqueue_style(
                'admin-styles',
                AKB_MEMBER_DIRECTORY_URL . 'assets/css/admin-style.css', 
                [],
                '1.0.0', 
                'all' 
            );
        }
    }
}
