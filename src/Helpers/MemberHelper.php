<?php

namespace MemberDirectory\Helpers;

class MemberHelper {
    public static function get_member_by_slug(string $slug): ?\WP_Post {
        $parts = explode('_', $slug);
        if (count($parts) !== 2) return null;

        [$first, $last] = $parts;

        $members = get_posts([
            'post_type' => 'member',
            'post_status' => 'publish',
            'meta_query' => [
                'relation' => 'AND',
                [
                    'key' => '_member_first_name',
                    'value' => $first,
                    'compare' => '='
                ],
                [
                    'key' => '_member_last_name',
                    'value' => $last,
                    'compare' => '='
                ],
                [
                    'key' => '_member_status',
                    'value' => 'active',
                    'compare' => '='
                ]
            ],
            'numberposts' => 1
        ]);

        return !empty($members) ? $members[0] : null;
    }
}
