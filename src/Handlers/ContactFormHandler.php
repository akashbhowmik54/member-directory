<?php

namespace MemberDirectory\Handlers;

class ContactFormHandler {
    public static function init(): void {
        add_action('rest_api_init', [self::class, 'register_rest_route']);
    }

    public static function register_rest_route(): void {
        register_rest_route('member-directory/v1', '/contact', [
            'methods'  => 'POST',
            'callback' => [self::class, 'handle_rest_form'],
            'permission_callback' => '__return_true', 
        ]);
    }

    public static function handle_rest_form(\WP_REST_Request $request): \WP_REST_Response {
        $member_id      = intval($request->get_param('contact_member_id'));
        $sender_name    = sanitize_text_field($request->get_param('sender_name'));
        $sender_email   = sanitize_email($request->get_param('sender_email'));
        $sender_message = sanitize_textarea_field($request->get_param('sender_message'));

        if (!$member_id || !$sender_name || !$sender_email || !$sender_message) {
            return new \WP_REST_Response([
                'success' => false,
                'message' => 'All fields are required.'
            ], 400);
        }

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

        if ($sent) {
            return new \WP_REST_Response([
                'success' => true,
                'message' => 'Message sent successfully.'
            ], 200);
        }

        return new \WP_REST_Response([
            'success' => false,
            'message' => 'Failed to send email.'
        ], 500);
    }
}
