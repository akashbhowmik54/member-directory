<?php
/** @var WP_Post $member */

use MemberDirectory\Helpers\RelationshipHelper;

$status = get_post_meta($member->ID, '_member_status', true);
if (strtolower($status) !== 'active') {
    echo '<p>This member is not active.</p>';
    return;
}

// Get member data
$full_name     = get_the_title($member);
$email         = get_post_meta($member->ID, '_member_email', true);
$address       = get_post_meta($member->ID, '_member_address', true);
$fav_color     = get_post_meta($member->ID, '_member_favorite_color', true);
$profile_image = get_post_meta($member->ID, '_member_profile_image', true);
$cover_image   = get_post_meta($member->ID, '_member_cover_image', true);

// Get teams
$teams = RelationshipHelper::get_teams_for_member($member->ID);

get_header();
?>

<div class="member-profile">
    <?php
    // Convert attachment ID to image URL
    $cover_image_url   = $cover_image ? wp_get_attachment_url((int) $cover_image) : '';
    $profile_image_url = $profile_image ? wp_get_attachment_url((int) $profile_image) : '';
    ?>
    <?php if ($cover_image_url): ?>
        <div class="cover-image">
            <img src="<?= esc_url($cover_image_url); ?>" alt="Cover Image">
        </div>
    <?php endif; ?>

    <?php if ($profile_image_url): ?>
        <div class="profile-image">
            <img src="<?= esc_url($profile_image_url); ?>" alt="Profile Image">
        </div>
    <?php endif; ?>

    <h2><?= esc_html($full_name); ?></h2>

    <ul class="member-details">
        <li><strong>Email:</strong> <?= esc_html($email); ?></li>
        <li><strong>Address:</strong> <?= esc_html($address); ?></li>
        <li><strong>Favorite Color:</strong> <?= esc_html($fav_color); ?></li>
        <li><strong>Status:</strong> <?= esc_html($status); ?></li>
    </ul>

    <h3>Teams</h3>
    <ul class="member-teams">
        <?php if (!empty($teams)): ?>
            <?php foreach ($teams as $team): ?>
                <li><?= esc_html($team->post_title); ?></li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>No teams assigned</li>
        <?php endif; ?>
    </ul>

    <h3>Contact This Member</h3>
    
    <?php if (isset($_GET['submitted']) && $_GET['submitted'] === 'true') : ?>
        <div id="contact-success-message" class="contact-success-message" style="padding: 12px; background: #e1f7e7; color: #2d6a4f; margin-bottom: 20px;">
            <span>Your message has been sent successfully!</span>
        </div>
        <script>
            setTimeout(function () {
                var msg = document.getElementById('contact-success-message');
                if (msg) {
                    msg.style.display = 'none';
                }

                const url = new URL(window.location);
                url.searchParams.delete('submitted');
                window.history.replaceState({}, document.title, url);
            }, 5000);
        </script>
    <?php endif; ?>

    <form method="post" action="">
        <input type="hidden" name="contact_member_id" value="<?= esc_attr($member->ID); ?>" />
        <p>
            <label>Your Name:</label><br>
            <input type="text" name="sender_name" required />
        </p>
        <p>
            <label>Your Email:</label><br>
            <input type="email" name="sender_email" required />
        </p>
        <p>
            <label>Message:</label><br>
            <textarea name="sender_message" required></textarea>
        </p>
        <p>
            <button type="submit" name="submit_contact">Send</button>
        </p>
    </form>
</div>

<?php 
get_footer();