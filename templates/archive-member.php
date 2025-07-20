<?php
// akb-member-directory/templates/archive-member.php

get_header();

echo '<div class="member-archive">';
echo '<h1>All Members</h1>';

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
    echo '<table class="member-table" style="width: 100%; border-collapse: collapse;">';
    echo '<thead>
            <tr>
                <th style="border: 1px solid #ccc; padding: 8px;">Profile Image</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Full Name</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Email</th>
                <th style="border: 1px solid #ccc; padding: 8px;">Teams</th>
            </tr>
          </thead>';
    echo '<tbody>';
    while ($query->have_posts()) : $query->the_post();
        $post_id     = get_the_ID();
        $first_name  = get_post_meta($post_id, '_member_first_name', true);
        $last_name   = get_post_meta($post_id, '_member_last_name', true);
        $email       = get_post_meta($post_id, '_member_email', true);
        $profile_img = get_post_meta($post_id, '_member_profile_image', true);
        $team_ids    = get_post_meta($post_id, '_member_teams', true); // assuming this returns array of team IDs

        $full_name = trim("$first_name $last_name");

        // Get team names
        $team_names = [];
        if (!empty($team_ids) && is_array($team_ids)) {
            foreach ($team_ids as $team_id) {
                $team_names[] = get_the_title($team_id);
            }
        }
        $team_list = !empty($team_names) ? implode(', ', $team_names) : '—';

        echo '<tr>';
        echo '<td style="border: 1px solid #ccc; padding: 8px;">';
        if ($profile_img) {
            $img_url = wp_get_attachment_url((int) $profile_img);
            if ($img_url) {
                echo '<img src="' . esc_url($img_url) . '" alt="' . esc_attr($full_name) . '" style="width:50px;height:50px;border-radius:50%;">';
            } else {
                echo '—';
            }
        } else {
            echo '—';
        }
        echo '</td>';
        echo '<td style="border: 1px solid #ccc; padding: 8px;">';
        echo '<a href="' . get_permalink() . '">' . esc_html($full_name) . '</a>';
        echo '</td>';
        echo '<td style="border: 1px solid #ccc; padding: 8px;">' . esc_html($email) . '</td>';
        echo '<td style="border: 1px solid #ccc; padding: 8px;">' . esc_html($team_list) . '</td>';
        echo '</tr>';
    endwhile;
    echo '</tbody></table>';
else :
    echo '<p>No active members found.</p>';
endif;

wp_reset_postdata();

echo '</div>';

get_footer();
