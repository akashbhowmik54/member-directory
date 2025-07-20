<?php get_header(); ?>

<div class="team-archive-wrapper">
    <h1>All Teams</h1>

    <?php
    $teams = new WP_Query([
        'post_type'      => 'team',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ]);

    if ($teams->have_posts()) :
        while ($teams->have_posts()) : $teams->the_post();
            $team_id   = get_the_ID();
            $team_name = get_post_meta($team_id, '_team_team_name', true);
            $desc      = get_post_meta($team_id, '_team_short_description', true);
            ?>

            <div class="team-box">
                <h2><?php echo esc_html($team_name ?: get_the_title()); ?></h2>
                <p><?php echo esc_html($desc); ?></p>

                <h4>Members:</h4>
                <ul>
                    <?php
                    $members = new WP_Query([
                        'post_type'      => 'member',
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'meta_query'     => [
                            [
                                'key'     => '_member_teams',
                                'value'   => $team_id,
                                'compare' => 'LIKE',
                            ],
                        ],
                    ]);

                    if ($members->have_posts()) :
                        while ($members->have_posts()) : $members->the_post();
                            $first_name = get_post_meta(get_the_ID(), '_member_first_name', true);
                            $last_name  = get_post_meta(get_the_ID(), '_member_last_name', true);
                            $full_name = trim("$first_name $last_name");
                            ?>
                            <li><?php echo esc_html($full_name); ?></li>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                        echo '<li>No members assigned.</li>';
                    endif;
                    ?>
                </ul>
            </div>

        <?php
        endwhile;
        wp_reset_postdata();
    else :
        echo '<p>No teams found.</p>';
    endif;
    ?>
</div>

<?php get_footer(); ?>
