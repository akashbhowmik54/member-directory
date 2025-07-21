<?php get_header(); ?>

<div class="team-archive-wrapper">
    <div class="container">
        <h1 class="page-title">All Teams</h1>

        <?php
        $teams = new WP_Query([
            'post_type'      => 'team',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
        ]);

        if ($teams->have_posts()) :
            echo '<div class="teams-grid">';
            while ($teams->have_posts()) : $teams->the_post();
                $team_id   = get_the_ID();
                $team_name = get_post_meta($team_id, '_team_team_name', true);
                $desc      = get_post_meta($team_id, '_team_short_description', true);
                ?>
                <div class="team-card">
                    <div class="team-card-header">
                        <h2 class="team-name"><?php echo esc_html($team_name ?: get_the_title()); ?></h2>
                        <p class="team-desc"><?php echo esc_html($desc); ?></p>
                    </div>

                    <div class="team-members">
                        <h4 class="member-heading">Members</h4>
                        <ul class="member-list">
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
                                    <li class="member-name"><a href="<?php the_permalink(); ?>"><?php echo esc_html($full_name); ?></a></li>
                                <?php
                                endwhile;
                                wp_reset_postdata();
                            else :
                                ?>
                                <li class="member-name no-members">No members assigned.</li>
                            <?php
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
            <?php
            endwhile;
            echo '</div>';
            wp_reset_postdata();
        else :
            ?>
            <p class="no-team-msg">No teams found.</p>
        <?php
        endif;
        ?>
    </div>
</div>

<?php get_footer(); ?>
