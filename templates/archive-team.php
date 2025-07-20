<?php
// akb-member-directory/templates/archive-team.php

get_header();

echo '<div class="team-archive">';
echo '<h1>All Teams</h1>';

if (have_posts()) :
    echo '<ul class="team-list">';
    while (have_posts()) : the_post();
        echo '<li>';
        echo '<a href="' . get_permalink() . '">' . get_the_title() . '</a>';
        echo '</li>';
    endwhile;
    echo '</ul>';

    // Pagination
    the_posts_pagination();
else :
    echo '<p>No teams found.</p>';
endif;

echo '</div>';

get_footer();
