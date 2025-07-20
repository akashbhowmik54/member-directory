<?php
namespace MemberDirectory\MetaBoxes;

class TeamMetaBox implements MetaBoxInterface {
    public function register(): void {
        add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
        add_action('save_post_team', [$this, 'save_meta_boxes']);
    }

    public function add_meta_boxes(): void {
        add_meta_box('team_meta_box', 'Team Information', [$this, 'render'], 'team');
    }

    public function render($post): void {
        $fields = [
            'team_name'        => 'Team Name',
            'short_description'=> 'Short Description',
        ];

        wp_nonce_field('team_meta_box_nonce', 'team_meta_box_nonce_field');

        foreach ($fields as $key => $label) {
            $value = get_post_meta($post->ID, "_team_$key", true);
            echo "<p><label><strong>$label:</strong></label><br>";
            if ($key === 'short_description') {
                echo "<textarea name='team_$key' class='widefat'>" . esc_textarea($value) . "</textarea>";
            } else {
                echo "<input type='text' class='widefat' name='team_$key' value='" . esc_attr($value) . "' />";
            }
            echo "</p>";
        }
    }

    public function save_meta_boxes($post_id): void {
        if (!isset($_POST['team_meta_box_nonce_field']) ||
            !wp_verify_nonce($_POST['team_meta_box_nonce_field'], 'team_meta_box_nonce')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        $fields = ['team_name', 'short_description'];

        foreach ($fields as $field) {
            if (isset($_POST["team_$field"])) {
                update_post_meta($post_id, "_team_$field", sanitize_text_field($_POST["team_$field"]));
            }
        }

        remove_action('save_post_team', [$this, 'save_meta_boxes']);

        if (!empty($_POST['team_team_name'])) {
            $team_title = sanitize_text_field($_POST['team_team_name']);
            wp_update_post([
                'ID' => $post_id,
                'post_title' => $team_title,
                'post_name' => sanitize_title($team_title),
            ]);
        }

        add_action('save_post_team', [$this, 'save_meta_boxes']);
    }
}
