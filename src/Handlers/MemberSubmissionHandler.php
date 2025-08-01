<?php

namespace MemberDirectory\Handlers;

class MemberSubmissionHandler {
    public function register(): void {
        add_action('admin_post_delete_member_submission', [$this, 'handle_delete']);
    }

    public function handle_delete(): void {
        if (
            !isset($_POST['delete_member_submission_nonce']) ||
            !wp_verify_nonce($_POST['delete_member_submission_nonce'], 'delete_member_submission')
        ) {
            wp_die(__('Security check failed', 'textdomain'));
        }

        $post_id = absint($_POST['post_id'] ?? 0);
        $index   = absint($_POST['delete_submission_index'] ?? -1);

        if (!current_user_can('edit_post', $post_id) || $index < 0) {
            wp_die(__('Unauthorized or invalid submission', 'textdomain'));
        }

        $submissions = get_post_meta($post_id, '_member_submissions', true);
        if (is_array($submissions) && isset($submissions[$index])) {
            unset($submissions[$index]);
            $submissions = array_values($submissions);
            update_post_meta($post_id, '_member_submissions', $submissions);
        }

        wp_safe_redirect(get_edit_post_link($post_id, ''));
        exit;
    }
}
