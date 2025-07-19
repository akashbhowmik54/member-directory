<?php
/**
 * Member Profile Template
 * 
 * @global WP_Post $member
 */

get_header(); ?>

<div class="member-profile">
    <?php if ($member) : ?>
        <h1><?php echo esc_html(
            get_post_meta($member->ID, '_member_first_name', true) . ' ' . 
            get_post_meta($member->ID, '_member_last_name', true)
        ); ?></h1>
        <p>Email: <?php echo esc_html(get_post_meta($member->ID, '_member_email', true)); ?></p>
        <p>Favorite Color: <?php echo esc_html(get_post_meta($member->ID, '_member_favorite_color', true)); ?></p>
        <?php
        $profile_img = get_post_meta($member->ID, '_member_profile_image', true);
        if ($profile_img) {
            echo '<img src="' . esc_url($profile_img) . '" alt="Profile Image" style="max-width:150px;" />';
        }
        ?>
    <?php else : ?>
        <p>Member not found.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>