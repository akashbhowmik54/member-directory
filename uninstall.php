<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

$member_posts = get_posts( [
    'post_type'   => 'member',
    'numberposts' => -1,
    'post_status' => 'any',
] );

foreach ( $member_posts as $post ) {
    wp_delete_post( $post->ID, true );
}

$team_posts = get_posts( [
    'post_type'   => 'team',
    'numberposts' => -1,
    'post_status' => 'any',
] );

foreach ( $team_posts as $post ) {
    wp_delete_post( $post->ID, true );
}