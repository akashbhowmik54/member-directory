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
                $preview = $value ? "<img src='" . esc_url($value) . "' style='max-width:100px;display:block;margin-top:5px;' />" : '';
                echo "
                    <input type='hidden' id='member_$key' name='member_$key' value='" . esc_attr($value) . "' />
                    <button type='button' class='button select-media' data-target='member_$key'>Select $label</button>
                    <div class='image-preview' id='preview_$key'>$preview</div>
                ";
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
        echo '<div style="margin-left:10px;">';
        foreach ($teams as $team) {
            $checked = in_array($team->ID, $selected_teams) ? 'checked' : '';
            echo "<label style='display:block; margin-bottom:4px;'>
                    <input type='checkbox' name='member_teams[]' value='{$team->ID}' $checked />
                    {$team->post_title}
                </label>";
        }
        echo '</div>';
    }

    public function save_meta_boxes($post_id): void {
        if (!isset($_POST['member_meta_box_nonce_field']) ||
            !wp_verify_nonce($_POST['member_meta_box_nonce_field'], 'member_meta_box_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        $fields = ['first_name', 'last_name', 'email', 'address', 'profile_image', 'cover_image', 'favorite_color', 'status'];

        foreach ($fields as $field) {
            if (isset($_POST["member_$field"])) {
                update_post_meta($post_id, "_member_$field", sanitize_text_field($_POST["member_$field"]));
            }
        }

        remove_action('save_post_member', [$this, 'save_meta_boxes']);

        if (!empty($_POST['member_first_name'])) {
            wp_update_post([
                'ID' => $post_id,
                'post_title' => sanitize_text_field($_POST['member_first_name']),
            ]);
        }

        remove_action('save_post_member', [$this, 'save_meta_boxes']);

        if (isset($_POST['member_teams'])) {
            $team_ids = array_map('intval', $_POST['member_teams']);
            update_post_meta($post_id, '_member_teams', $team_ids);
        } else {
            delete_post_meta($post_id, '_member_teams');
        }

    }
}
