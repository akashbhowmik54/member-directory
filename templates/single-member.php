<?php
/** @var WP_Post $post */
use MemberDirectory\Helpers\RelationshipHelper;

$status = get_post_meta($post->ID, '_member_status', true);
if (strtolower($status) !== 'active') {
    echo '<p class="inactive-message">This member is not active.</p>';
    return;
}

$first_name    = get_post_meta($post->ID, '_member_first_name', true);
$last_name     = get_post_meta($post->ID, '_member_last_name', true);
$full_name     = trim("$first_name $last_name");

$email         = get_post_meta($post->ID, '_member_email', true);
$address       = get_post_meta($post->ID, '_member_address', true);
$fav_color     = get_post_meta($post->ID, '_member_favorite_color', true);
$profile_image = get_post_meta($post->ID, '_member_profile_image', true);
$cover_image   = get_post_meta($post->ID, '_member_cover_image', true);

$teams = RelationshipHelper::get_teams_for_member($post->ID);

$cover_image_url   = $cover_image ? wp_get_attachment_url((int) $cover_image) : '';
$profile_image_url = $profile_image ? wp_get_attachment_url((int) $profile_image) : '';

get_header(); ?>

<div class="member-profile-container">
    <?php if ($cover_image_url): ?>
        <div class="member-cover">
            <img src="<?= esc_url($cover_image_url); ?>" alt="Cover Image">
        </div>
    <?php endif; ?>

    <div class="member-content">
        <div class="member-header">
            <?php if ($profile_image_url): ?>
                <div class="member-avatar">
                    <img src="<?= esc_url($profile_image_url); ?>" alt="Profile Image">
                </div>
            <?php endif; ?>
            <div class="member-info">
                <h2><?= esc_html($full_name); ?></h2>
                <span class="member-status <?= strtolower($status) === 'active' ? 'active' : 'inactive'; ?>">
                    <?= esc_html(ucfirst($status)); ?>
                </span>
            </div>
        </div>

        <div class="member-details">
            <ul>
                <li><strong>Email:</strong> <?= esc_html($email); ?></li>
                <li><strong>Address:</strong> <?= esc_html($address); ?></li>
                <li class="color-field"><strong>Favorite Color:</strong> <span class="fav-color" style="background: <?php echo esc_html($fav_color); ?>;"></span></li>
            </ul>
        </div>

        <div class="member-teams">
            <h3>Teams</h3>
            <ul>
                <?php if (!empty($teams)): ?>
                    <?php foreach ($teams as $team): ?>
                        <li><?= esc_html($team->post_title); ?></li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li>No teams assigned</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="contact-member">
            <h3>Contact This Member</h3>
            <div id="contact-success-message" class="contact-success-message">
                <span>Your message has been sent successfully!</span>
            </div>
            <form id="member-contact-form">
                <input type="hidden" name="contact_member_id" value="<?= esc_attr($post->ID); ?>" />
                <div class="form-group">
                    <label>Your Name:</label>
                    <input type="text" name="sender_name" required />
                </div>
                <div class="form-group">
                    <label>Your Email:</label>
                    <input type="email" name="sender_email" required />
                </div>
                <div class="form-group">
                    <label>Message:</label>
                    <textarea name="sender_message" required></textarea>
                </div>
                <div class="form-group">
                    <button type="submit" name="submit_contact">Send</button>
                </div>
            </form>
            <script>
                document.getElementById('member-contact-form').addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const form = e.target;

                    const data = {
                        contact_member_id: form.contact_member_id.value,
                        sender_name: form.sender_name.value,
                        sender_email: form.sender_email.value,
                        sender_message: form.sender_message.value
                    };

                    try {
                        const response = await fetch('<?php echo esc_url(rest_url("member-directory/v1/contact")); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(data)
                        });

                        const result = await response.json();
                        const messageBox = document.getElementById('form-message');
                        const successMessage = document.getElementById('contact-success-message');

                        if (result.success) {
                            form.reset();

                            successMessage.style.display = 'block';
                            successMessage.style.opacity = '1';

                            setTimeout(() => {
                                successMessage.style.opacity = '0';
                                setTimeout(() => {
                                    successMessage.style.display = 'none';
                                }, 500);
                            }, 5000);

                            messageBox.textContent = '';
                        } else {
                            messageBox.textContent = result.message || 'Something went wrong.';
                            messageBox.style.color = 'red';
                        }
                    } catch (error) {
                        document.getElementById('form-message').textContent = 'Error sending request.';
                        console.error('Error:', error);
                    }
                });

            </script>
        </div>
    </div>
</div>

<?php get_footer(); ?>
