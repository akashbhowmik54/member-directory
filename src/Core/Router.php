<?php

namespace MemberDirectory\Core;

class Router {
    public function register() {
        add_action('init', [$this, 'add_rewrite_rules']);
        add_filter('query_vars', [$this, 'add_query_vars']);
        add_action('template_redirect', [$this, 'template_loader']);
    }

    public function add_rewrite_rules() {

        add_rewrite_rule(
            '^member/?$',
            'index.php?members_list=1',
            'top'
        );

        add_rewrite_rule(
            '^team/?$',
            'index.php?teams_list=1',
            'top'
        );

        add_rewrite_rule(
            '^member/([^/]+)?',
            'index.php?member_slug=$matches[1]',
            'top'
        );
    }

    public function add_query_vars($vars) {
        $vars[] = 'member_slug';
        $vars[] = 'members_list';
        $vars[] = 'teams_list';
        return $vars;
    }

    public function template_loader() {

        if (get_query_var('members_list')) {
            $template_path = AKB_MEMBER_DIRECTORY_PATH . 'templates/archive-member.php';
            if (file_exists($template_path)) {
                include $template_path;
                exit;
            } else {
                wp_die('Members archive template not found');
            }
        }

        if (get_query_var('teams_list')) {
            $template_path = AKB_MEMBER_DIRECTORY_PATH . 'templates/archive-team.php';
            if (file_exists($template_path)) {
                include $template_path;
                exit;
            } else {
                wp_die('Teams archive template not found');
            }
        }

        $slug = get_query_var('member_slug');
        if (!$slug) return;

        $member = \MemberDirectory\Helpers\MemberHelper::get_member_by_slug($slug);

        if ($member) {
            // Use the correct path to your template
            $template_path = AKB_MEMBER_DIRECTORY_PATH . 'templates/member-profile.php';
            if (file_exists($template_path)) {
                include $template_path;
                exit;
            } else {
                wp_die('Template file not found');
            }
        } else {
            wp_die('Member not found or inactive');
        }
    }
}
