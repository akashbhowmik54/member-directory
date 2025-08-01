<?php
namespace MemberDirectory\MetaBoxes;

class MemberMetaBox implements MetaBoxInterface {
    public function register(): void {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post_member', [$this, 'save_meta_boxes']);
    }

    public function add_meta_boxes(): void {
        add_meta_box('member_meta_box', 'Member Information', [$this, 'render'], 'member');
    }

    public function render($post): void {
        $fields = [
            'first_name'    => 'First Name',
            'last_name'     => 'Last Name',
            'email'         => 'Email',
            'address'       => 'Address',
            'profile_image' => 'Profile Image',
            'cover_image'   => 'Cover Image',
            'favorite_color'=> 'Favorite Color',
            'status'        => 'Status',
        ];

        wp_nonce_field('member_meta_box_nonce', 'member_meta_box_nonce_field');

        foreach ($fields as $key => $label) {
            $value = get_post_meta($post->ID, "_member_$key", true);
            echo "<p><label><strong>$label:</strong></label><br>";

            if ($key === 'favorite_color') {
                echo "<input type='color' name='member_$key' value='" . esc_attr($value) . "' />";
            } elseif ($key === 'status') {
                $selected = esc_attr($value);
                echo "<select name='member_$key'>
                        <option value='active' " . selected($selected, 'active', false) . ">Active</option>
                        <option value='draft' " . selected($selected, 'draft', false) . ">Draft</option>
                      </select>";
            } elseif ($key === 'profile_image' || $key === 'cover_image') {
                $attachment_id = intval($value);
                $image_url = $attachment_id ? wp_get_attachment_url($attachment_id) : '';
                $preview = $image_url ? "<img src='" . esc_url($image_url) . "' style='max-width:100px;display:block;margin-top:5px;' />" : '';
                echo "
                    <input type='hidden' id='member_$key' name='member_$key' value='" . esc_attr($attachment_id) . "' />
                    <button type='button' class='button select-media' data-target='member_$key'>Select $label</button>
                    <div class='image-preview' id='preview_$key'>$preview</div>
                ";
            } elseif ($key === 'email') {
                echo "<input type='email' class='widefat' name='member_$key' value='" . esc_attr($value) . "' />";
            } else {
                echo "<input type='text' class='widefat' name='member_$key' value='" . esc_attr($value) . "' />";
            }

            echo "</p>";
        }

        $selected_teams = get_post_meta($post->ID, '_member_teams', true);
        $selected_teams = is_array($selected_teams) ? $selected_teams : [];

        $teams = get_posts([
            'post_type' => 'team',
            'numberposts' => -1,
            'post_status' => 'publish',
        ]);

        echo '<p><strong>Teams:</strong></p>';
        echo '<div class="team-checkbox">';
        foreach ($teams as $team) {
            $checked = in_array($team->ID, $selected_teams) ? 'checked' : '';
            echo "<label>
                    <input type='checkbox' name='member_teams[]' value='{$team->ID}' $checked />
                    {$team->post_title}
                </label>";
        }
        echo '</div>';

        $submissions = get_post_meta($post->ID, '_member_submissions', true);

        if (!empty($submissions) && is_array($submissions)) : ?>
            <h2 class="submission-title">Form Submissions</h2>
            <table class="submission-table" border="1">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($submissions as $index => $submission) : ?>
                        <tr>
                            <td><?php echo esc_html($submission['name'] ?? ''); ?></td>
                            <td><?php echo esc_html($submission['email'] ?? ''); ?></td>
                            <td><?php echo esc_html($submission['message'] ?? ''); ?></td>
                            <td><?php echo esc_html($submission['date'] ?? ''); ?></td>
                            <td>
                                <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                                    <?php wp_nonce_field('delete_member_submission', 'delete_member_submission_nonce'); ?>
                                    <input type="hidden" name="action" value="delete_member_submission">
                                    <input type="hidden" name="post_id" value="<?php echo esc_attr($post->ID); ?>">
                                    <input type="hidden" name="delete_submission_index" value="<?php echo esc_attr($index); ?>">
                                    <input type="submit" class="button button-secondary" value="Delete" onclick="return confirm('Are you sure you want to delete this submission?')">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else : ?>
            <p><strong>No submissions yet.</strong></p>
        <?php endif; 

    }

    public function save_meta_boxes($post_id): void {
        if (!isset($_POST['member_meta_box_nonce_field']) ||
            !wp_verify_nonce($_POST['member_meta_box_nonce_field'], 'member_meta_box_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        // Sanitize and validate email
        $email = isset($_POST['member_email']) ? sanitize_email($_POST['member_email']) : '';

        if (!empty($email)) {
            if (!is_email($email)) {
                set_transient("member_email_error_{$post_id}", 'Invalid email format. Please enter a valid email address.', 30);

                wp_redirect(add_query_arg([
                    'post'   => $post_id,
                    'action' => 'edit',
                ], admin_url('post.php')));
                exit;
            }

            $duplicate = new \WP_Query([
                'post_type'      => 'member',
                'posts_per_page' => 1,
                'post__not_in'   => [$post_id], 
                'meta_query'     => [
                    [
                        'key'     => '_member_email',
                        'value'   => $email,
                        'compare' => '='
                    ]
                ]
            ]);

            if ($duplicate->have_posts()) {
                set_transient("member_email_error_{$post_id}", 'Email already exists. Member not saved.', 30);

                if (get_post_status($post_id) === 'auto-draft') {
                    wp_delete_post($post_id, true);
                }

                wp_redirect(add_query_arg([
                    'post'   => $post_id,
                    'action' => 'edit',
                ], admin_url('post.php')));
                exit;
            }
        }

        $fields = ['first_name', 'last_name', 'email', 'address', 'profile_image', 'cover_image', 'favorite_color', 'status'];

        foreach ($fields as $field) {
            if (isset($_POST["member_$field"])) {

                if (in_array($field, ['profile_image', 'cover_image'])) {
                    update_post_meta($post_id, "_member_$field", intval($_POST["member_$field"]));
                } else {
                    update_post_meta($post_id, "_member_$field", sanitize_text_field($_POST["member_$field"]));
                }
            }
        }

        remove_action('save_post_member', [$this, 'save_meta_boxes']);

        $first_name = sanitize_text_field($_POST['member_first_name']);
        $last_name = sanitize_text_field($_POST['member_last_name']);

        if (!empty($first_name) || !empty($last_name)) {
            $title = trim("$first_name $last_name");
            $slug = sanitize_title("{$first_name}_{$last_name}"); 
            wp_update_post([
                'ID' => $post_id,
                'post_title' => $title,
                'post_name' => $slug  
            ]);
        }

        add_action('save_post_member', [$this, 'save_meta_boxes']);

        if (isset($_POST['member_teams'])) {
            $team_ids = array_map('intval', $_POST['member_teams']);
            update_post_meta($post_id, '_member_teams', $team_ids);
        } else {
            delete_post_meta($post_id, '_member_teams');
        }

        if (isset($_POST['delete_submission']) && isset($_POST['delete_submission_index'])) {
            $submissions = get_post_meta($post_id, '_member_submissions', true);
            $index = intval($_POST['delete_submission_index']);

            if (isset($submissions[$index])) {
                unset($submissions[$index]);
                $submissions = array_values($submissions); 
                update_post_meta($post_id, '_member_submissions', $submissions);
            }
        }
    }
}
