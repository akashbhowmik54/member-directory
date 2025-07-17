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
    }
}
