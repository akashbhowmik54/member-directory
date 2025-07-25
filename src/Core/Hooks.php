<?php

namespace MemberDirectory\Core;

class Hooks {
    public function register(): void {
        add_action('admin_init', [$this, 'removeDefaultEditorAndTitle']);
        add_action('admin_notices', [$this, 'displayEmailErrorNotice']);
        add_filter('template_include', [$this, 'include_template']);

    }

    public function include_template($template) {
        if (is_singular('member')) {
            $new_template = AKB_MEMBER_DIRECTORY_PATH . 'templates/single-member.php';
            if (file_exists($new_template)) {
                return $new_template;
            }
        } elseif (is_post_type_archive('member')) {
            $new_template = AKB_MEMBER_DIRECTORY_PATH . 'templates/archive-member.php';
            if (file_exists($new_template)) {
                return $new_template;
            }
        }
        return $template;
    }

    public function removeDefaultEditorAndTitle(): void {
        remove_post_type_support('member', 'title');
        remove_post_type_support('member', 'editor');
        remove_post_type_support('team', 'title');
        remove_post_type_support('team', 'editor');
    }

    public function displayEmailErrorNotice(): void {
        if (isset($_GET['post'])) {
            $post_id = absint($_GET['post']);
            $message = get_transient("member_email_error_{$post_id}");
            if ($message) {
                echo '<div class="notice notice-error"><p>' . esc_html($message) . '</p></div>';
                delete_transient("member_email_error_{$post_id}");
            }
        }
    }
}
