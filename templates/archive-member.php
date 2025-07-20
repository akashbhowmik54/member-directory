<?php
// akb-member-directory/templates/archive-member.php

get_header();
?>

<div class="member-archive">
    <h1>All Members</h1>

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
        <table class="member-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ccc; padding: 8px;">Profile Image</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Full Name</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Email</th>
                    <th style="border: 1px solid #ccc; padding: 8px;">Teams</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($query->have_posts()) : $query->the_post();
                    $post_id     = get_the_ID();
                    $first_name  = get_post_meta($post_id, '_member_first_name', true);
                    $last_name   = get_post_meta($post_id, '_member_last_name', true);
                    $email       = get_post_meta($post_id, '_member_email', true);
                    $profile_img = get_post_meta($post_id, '_member_profile_image', true);
                    $team_ids    = get_post_meta($post_id, '_member_teams', true); 

                    $full_name = trim("$first_name $last_name");

                    // Get team names
                    $team_names = [];
                    if (!empty($team_ids) && is_array($team_ids)) {
                        foreach ($team_ids as $team_id) {
                            $team_names[] = get_the_title($team_id);
                        }
                    }
                    $team_list = !empty($team_names) ? implode(', ', $team_names) : '—';
                ?>
                    <tr>
                        <td style="border: 1px solid #ccc; padding: 8px;">
                            <?php
                            if ($profile_img) {
                                $img_url = wp_get_attachment_url((int) $profile_img);
                                if ($img_url) {
                            ?>
                                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($full_name); ?>" style="width:50px;height:50px;border-radius:50%;">
                                <?php
                                } else {
                                    echo '—';
                                }
                            } else {
                                echo '—';
                            }
                            ?>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 8px;">
                            <a href="<?php the_permalink(); ?>"><?php echo esc_html($full_name); ?></a>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 8px;">
                            <?php echo esc_html($email); ?>
                        </td>
                        <td style="border: 1px solid #ccc; padding: 8px;">
                            <?php echo esc_html($team_list); ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No active members found.</p>
    <?php
    endif;
    wp_reset_postdata();
    ?>
</div>

<?php get_footer(); ?>
