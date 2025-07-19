<?php
namespace MemberDirectory\Admin;

class MemberAdminColumns {
    public function register(): void {
        add_filter('manage_member_posts_columns', [$this, 'add_columns']);
        add_action('manage_member_posts_custom_column', [$this, 'render_column'], 10, 2);
    }

    public function add_columns($columns): array {
        $columns['total_submissions'] = __('Total Submissions', 'akb-member-directory');
        return $columns;
    }

    public function render_column($column, $post_id): void {
        if ($column === 'total_submissions') {
            $submissions = get_post_meta($post_id, '_member_submissions', true);
            echo is_array($submissions) ? count($submissions) : 0;
        }
    }
}
