<?php

get_header();
?>

<div class="member-archive">
    <h1>All Members Page</h1>

    <?php
    $args = array(
        'post_type'      => 'member',
        'posts_per_page' => -1,
        'meta_query'     => array(
            array(
                'key'   => '_member_status',
                'value' => 'active',
            )
        )
    );

    $query = new WP_Query($args);

    if ($query->have_posts()) :
    ?>
        <div class="member-wrapper">
            <?php
            while ($query->have_posts()) : $query->the_post();
                $post_id     = get_the_ID();
                $first_name  = get_post_meta($post_id, '_member_first_name', true);
                $last_name   = get_post_meta($post_id, '_member_last_name', true);
                $email       = get_post_meta($post_id, '_member_email', true);
                $profile_img = get_post_meta($post_id, '_member_profile_image', true);
                $team_ids    = get_post_meta($post_id, '_member_teams', true);

                $full_name = trim("$first_name $last_name");

                $team_names = [];
                if (!empty($team_ids) && is_array($team_ids)) {
                    foreach ($team_ids as $team_id) {
                        $team_names[] = get_the_title($team_id);
                    }
                }
                $team_list = !empty($team_names) ? implode(', ', $team_names) : '—';
            ?>
                <div class="member-card">
                    <a href="<?php the_permalink(); ?>">
                    <?php
                    if ($profile_img) {
                        $img_url = wp_get_attachment_url((int) $profile_img);
                        if ($img_url) {
                            echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($full_name) . '" class="profile-img">';
                        } else {
                            echo '<div class="profile-img"></div>';
                        }
                    } else {
                        echo '<div class="profile-img"></div>';
                    }
                    ?>
                    </a>
                    <div class="card-body">
                        <div class="member-name">
                            <h6><a href="<?php the_permalink(); ?>"><?php echo esc_html($full_name); ?></a></h6>
                        </div>
                        <div class="member-email">
                            <?php echo esc_html($email); ?>
                        </div>
                        <div class="member-teams">
                            Teams: <?php echo esc_html($team_list); ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else : ?>
        <p>No active members found.</p>
    <?php
    endif;
    wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>
