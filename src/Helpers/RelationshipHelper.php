<?php
namespace MemberDirectory\Helpers;

class RelationshipHelper {
    public static function get_teams_for_member(int $member_id): array {
        $team_ids = get_post_meta($member_id, '_member_teams', true);
        if (!is_array($team_ids)) return [];

        return array_map('get_post', $team_ids);
    }

    public static function get_members_for_team(int $team_id): array {
        $members = get_posts([
            'post_type' => 'member',
            'numberposts' => -1,
            'post_status' => 'publish',
            'meta_query' => [
                [
                    'key'     => '_member_teams',
                    'value'   => $team_id,
                    'compare' => 'LIKE',
                ],
            ],
        ]);
        return $members;
    }
    
}


