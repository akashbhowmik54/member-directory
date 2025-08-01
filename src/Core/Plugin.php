<?php
namespace MemberDirectory\Core;

use MemberDirectory\Controllers\PostTypes\MemberPostType;
use MemberDirectory\Controllers\PostTypes\TeamPostType;
use MemberDirectory\MetaBoxes\MemberMetaBox;
use MemberDirectory\MetaBoxes\TeamMetaBox;
use MemberDirectory\Admin\AdminAssets;
use MemberDirectory\Core\Hooks;
use MemberDirectory\Admin\MemberAdminColumns;
use MemberDirectory\Handlers\ContactFormHandler;
use MemberDirectory\Handlers\MemberSubmissionHandler;
use MemberDirectory\Frontend\FrontendAssets;

class Plugin {
    public static function init(): void {
        self::load_dependencies();

        // Register CPTs
        (new MemberPostType())->register();
        (new TeamPostType())->register();

        // Register Metaboxes
        (new MemberMetaBox())->register();
        (new TeamMetaBox())->register();

        // Register Assets
        (new AdminAssets())->register();
        (new FrontendAssets())->register();

        // Register Hooks
        (new Hooks())->register();

        (new MemberAdminColumns())->register();

        (new MemberSubmissionHandler())->register();

        ContactFormHandler::init();

        
    }

    

    private static function load_dependencies(): void {
        // Future includes: CPTs, hooks, shortcodes, etc.
    }
}
