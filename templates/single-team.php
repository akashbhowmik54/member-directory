<?php get_header(); ?>

<?php
if (have_posts()) :
    while (have_posts()) : the_post();

        $team_id = get_the_ID();

        $team_name = get_post_meta($team_id, '_team_team_name', true);
        $short_description = get_post_meta($team_id, '_team_short_description', true);
        ?>

        <div class="team-single">
            <h1><?php echo esc_html($team_name ?: get_the_title()); ?></h1>
            <p><?php echo esc_html($short_description); ?></p>

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
            ?>

            <h2>Team Members:</h2>
            <ul class="single-member-list ">
                <?php if ($members->have_posts()) : ?>
                    <?php while ($members->have_posts()) : $members->the_post(); ?>
                        <?php
                        $first_name = get_post_meta(get_the_ID(), '_member_first_name', true);
                        $last_name  = get_post_meta(get_the_ID(), '_member_last_name', true);
                        $full_name = trim("$first_name $last_name");
                        ?>
                        <li class="single-member-name "><a href="<?php the_permalink(); ?>"><?php echo esc_html($full_name); ?></a></li>
                    <?php endwhile; ?>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <li class="single-member-name  no-members">No members assigned.</li>
                <?php endif; ?>
            </ul>
        </div>

    <?php
    endwhile;
endif;
?>

<?php get_footer(); ?>
