<?php

namespace MemberDirectory\Handlers;

class ContactFormHandler {
    public static function init(): void {
        add_action('init', [self::class, 'handle_form']);
    }

    public static function handle_form(): void {

        if (!isset($_POST['submit_contact'])) {
            return;
        }

        $member_id      = intval($_POST['contact_member_id'] ?? 0);
        $sender_name    = sanitize_text_field($_POST['sender_name'] ?? '');
        $sender_email   = sanitize_email($_POST['sender_email'] ?? '');
        $sender_message = sanitize_textarea_field($_POST['sender_message'] ?? '');

        if ($member_id && $sender_name && $sender_email && $sender_message) {
            
            $recipient = get_post_meta($member_id, '_member_email', true);

            $existing = get_post_meta($member_id, '_member_submissions', true);
            $existing = is_array($existing) ? $existing : [];

            $existing[] = [
                'name'    => $sender_name,
                'email'   => $sender_email,
                'message' => $sender_message,
                'date'    => current_time('mysql'),
            ];

            update_post_meta($member_id, '_member_submissions', $existing);

            $sent = wp_mail(
                $recipient,
                "Contact Form Submission from $sender_name",
                $sender_message,
                ['Reply-To: ' . $sender_email]
            );

            // add_action('wp_footer', function () {
            //     echo "<script>alert('Your message has been sent!');</script>";
            // });
            if ($sent) {
                wp_safe_redirect(add_query_arg('submitted', 'true', wp_get_referer()));
                exit;
            }
        }
    }
}
