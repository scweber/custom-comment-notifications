<?php
/**
 *
 * @package Custom_Comment_Notifications
 * @version 1.0.0
 */
/*
Plugin Name: Custom Comment Notifications
Plugin URI: https://github.com/scweber/custom-comment-notifications
Description: This plugin allows for the comment e-mail notifications that are sent to the comment moderator as well as the post author to be completely customized.
Author: Scott Weber
Version: 1.0.0
Author URI: https://github.com/scweber
*/

/*  Copyright 2014  Scott Weber  (email : scweber@novell.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
// Default Subjects
define('CCN_DEFAULT_AUTHOR_COMMENT_SUBJECT', '[BLOG_NAME] Comment: "P_TITLE"');
define('CCN_DEFAULT_AUTHOR_TRACKBACK_SUBJECT', '[BLOG_NAME] Trackback: "P_TITLE"');
define('CCN_DEFAULT_AUTHOR_PINGBACK_SUBJECT', '[BLOG_NAME] Pingback: "P_TITLE"');
define('CCN_DEFAULT_MODERATOR_COMMENT_SUBJECT', '[BLOG_NAME] Comment: Awaiting Your Approval');
define('CCN_DEFAULT_MODERATOR_TRACKBACK_SUBJECT', '[BLOG_NAME] Trackback: Awaiting Your Approval');
define('CCN_DEFAULT_MODERATOR_PINGBACK_SUBJECT', '[BLOG_NAME] Pingback: Awaiting Your Approval');

// Default Content
define('CCN_DEFAULT_AUTHOR_COMMENT', 
    'New comment on your post \"P_LINK\"
         
    Author: C_AUTHOR (IP: C_AUTHOR_IP , C_AUTHOR_DOMAIN)
    E-mail: C_AUTHOR_EMAIL
    URL: C_AUTHOR_URL\'
    Whois: C_AUTHOR_ARIN_LOOKUP\'

    Comment:    
    C_CONTENT

    You can see the comment on this post here:  P_LINK_COMMENT

    DELETE_TRASH_COMMENT_LINK | SPAM_COMMENT_LINK');
define('CCN_DEFAULT_AUTHOR_TRACKBACK', 
        'New trackback on your post "P_LINK"\r\n'
        . 'Website: C_AUTHOR (IP: C_AUTHOR_IP , C_AUTHOR_DOMAIN)\r\n'
        . 'URL: C_AUTHOR_URL\r\n'
        . 'Trackback Excerpt:\r\n'
        . 'C_CONTENT\r\n\r\n'
        . 'You can see the trackback on this post here:\r\n'
        . 'P_LINK_COMMENT\r\n\r\n'
        . 'DELETE_TRASH_COMMENT_LINK\r\n'
        . 'SPAM_COMMENT_LINK\r\n');
define('CCN_DEFAULT_AUTHOR_PINGBACK', 
        'New pingback on your post "P_LINK"\r\n'
        . 'Website: C_AUTHOR (IP: C_AUTHOR_IP , C_AUTHOR_DOMAIN)\r\n'
        . 'URL: C_AUTHOR_URL\r\n'
        . 'Pingback Excerpt:\r\n'
        . 'C_CONTENT\r\n\r\n'
        . 'You can see the pingback on this post here:\r\n'
        . 'P_LINK_COMMENT\r\n\r\n'
        . 'DELETE_TRASH_COMMENT_LINK\r\n'
        . 'SPAM_COMMENT_LINK\r\n');
define('CCN_DEFAULT_MODERATOR_COMMENT', 
        'A new comment on "P_LINK" is waiting for your approval\r\n'
        . 'Author: C_AUTHOR (IP: C_AUTHOR_IP , C_AUTHOR_DOMAIN)\r\n'
        . 'E-mail: C_AUTHOR_EMAIL\r\n'
        . 'URL: C_AUTHOR_URL\r\n'
        . 'Whois: C_AUTHOR_ARIN_LOOKUP\r\n'
        . 'Comment:\r\n'
        . 'C_CONTENT\r\n\r\n'
        . 'APPROVE_COMMENT_LINK |'
        . 'DELETE_TRASH_COMMENT_LINK |'
        . 'SPAM_COMMENT_LINK\r\n');
define('CCN_DEFAULT_MODERATOR_TRACKBACK', 
        'A new trackback on "P_LINK" is waiting for your approval\r\n'
        . 'Website: C_AUTHOR (IP: C_AUTHOR_IP , C_AUTHOR_DOMAIN)\r\n'
        . 'URL: C_AUTHOR_URL\r\n'
        . 'Trackback Excerpt:\r\n'
        . 'C_CONTENT\r\n\r\n'
        . 'You can see the trackback on this post here:\r\n'
        . 'P_LINK_COMMENT\r\n\r\n'
        . 'APPROVE_COMMENT_LINK |'
        . 'DELETE_TRASH_COMMENT_LINK |'
        . 'SPAM_COMMENT_LINK\r\n');
define('CCN_DEFAULT_MODERATOR_PINGBACK', 
        'A new pingback on "P_LINK" is waiting for your approval\r\n'
        . 'Website: C_AUTHOR (IP: C_AUTHOR_IP , C_AUTHOR_DOMAIN)\r\n'
        . 'URL: C_AUTHOR_URL\r\n'
        . 'Pingback Excerpt:\r\n'
        . 'C_CONTENT\r\n\r\n'
        . 'APPROVE_COMMENT_LINK |'
        . 'DELETE_TRASH_COMMENT_LINK |'
        . 'SPAM_COMMENT_LINK\r\n');

// Settings Menu
function ccn_settings_menu() {
    // Include the CSS and JS files
    wp_enqueue_style('custom-comment-notifications-stylesheet', plugins_url('css/custom-comment-notifications.css', __FILE__));
    wp_enqueue_script('custom-comment-notifications-javascript', plugins_url('js/custom-comment-notifications.js', __FILE__), array('jquery'), '1.0', true);
    
    // Get the values that were just submitted
    if(isset($_POST['ccn_save']) && $_POST['ccn_save']) {
        if(isset($_POST['ccn_author_comment_subject']) && isset($_POST['ccn_author_comment'])) {
            update_option('ccn_author_comment_subject', $_POST['ccn_author_comment_subject']);
            update_option('ccn_author_comment', $_POST['ccn_author_comment']);
            update_option('ccn_protect_comment_author', $_POST['ccn_protect_comment_author']);
            $saved_template = 'Author Comment';
        } else if (isset($_POST['ccn_author_trackback_subject']) && isset($_POST['ccn_author_trackback'])) {
            update_option('ccn_author_trackback_subject', $_POST['ccn_author_trackback_subject']);
            update_option('ccn_author_trackback', $_POST['ccn_author_trackback']);
            $saved_template = 'Author Trackback';
        } else if (isset($_POST['ccn_author_pingback_subject']) && isset($_POST['ccn_author_pingback'])) {
            update_option('ccn_author_pingback_subject', $_POST['ccn_author_pingback_subject']);
            update_option('ccn_author_pingback', $_POST['ccn_author_pingback']);
            $saved_template = 'Author Pingback';
        } else if (isset($_POST['ccn_moderator_comment_subject']) && isset($_POST['ccn_moderator_comment'])) {
            update_option('ccn_moderator_comment_subject', $_POST['ccn_moderator_comment_subject']);
            update_option('ccn_moderator_comment', $_POST['ccn_moderator_comment']);
            $saved_template = 'Moderator Comment';
        } else if (isset($_POST['ccn_moderator_trackback_subject']) && isset($_POST['ccn_moderator_trackback'])) {
            update_option('ccn_moderator_trackback_subject', $_POST['ccn_moderator_trackback_subject']);
            update_option('ccn_moderator_trackback', $_POST['ccn_moderator_trackback']);
            $saved_template = 'Moderator Trackback';
        } else if (isset($_POST['ccn_moderator_pingback_subject']) && isset($_POST['ccn_moderator_pingback'])) {
            update_option('ccn_moderator_pingback_subject', $_POST['ccn_moderator_pingback_subject']);
            update_option('ccn_moderator_pingback', $_POST['ccn_moderator_pingback']);
            $saved_template = 'Moderator Pingback';
        } else {
            ?> <div id="message" class="error">
                <p><strong><?php _e('Error Saving Template Settings') ?> </strong></p>
            </div>
            <?php
        }
        update_option('ccn_email_format', $_POST['ccn_email_format']);
        
        ?><div id="message" class="updated">
                <p><strong><?php _e($saved_template.' Settings Saved') ?></strong></p>
            </div>
        <?php
    }

    $protect_comment_author = get_option('ccn_protect_comment_author', 0);
    $email_format = get_option('ccn_email_format', 'html');
    
    echo '<div class="wrap">';
        echo '<h2>' . __('Custom Comment Notifications Settings','custom-comment-notifications') . '</h2>';
    echo '</div>';
    ?>
    <p>
        <?php _e('Select the template you wish to customize and then save before selecting the next template.<br/>**Switching the selection without saving will result in losing unsaved changes.'); ?>
    </p>

    <form method="post" id="ccn_save_options">
        <div id='ccn-general-settings-container'>
            <hr/><h3>General Settings</h3><hr/>
            <table id="ccn-general-settings-table">
                <tr valign="top" id='ccn-email-format'><td>Format to display e-mail:</td>
                    <td>
                        <input type="radio" id="ccn-email-format-plaintext" name="ccn_email_format" value="plaintext" <?php echo $email_format == 'plaintext' ? 'checked="checked"' : ''; ?> /><label for="ccn-email-format-plaintext"><?php _e('Plain Text','custom-comment-notifications'); ?></label>
                        <input type="radio" id="ccn-email-format-html" name="ccn_email_format" value="html" <?php echo $email_format == 'html' ? 'checked="checked"' : ''; ?> /><label for="ccn-email-format-html"><?php _e('HTML','custom-comment-notifications'); ?></label>
                    </td>
                </tr>
                <tr valign="top" id='ccn-protect-author' style='display:none;'><td>Protect Comment Author Info in Post Author Email:</td>
                    <td>
                        <input type="radio" id="protect-author-info-true" name="ccn_protect_comment_author" value="1" <?php echo $protect_comment_author == 1 ? 'checked="checked"' : ''; ?> /><label for="protect-author-info-true"><?php _e('Yes','custom-comment-notifications'); ?></label>
                        <input type="radio" id="protect-author-info-false" name="ccn_protect_comment_author" value="0" <?php echo $protect_comment_author == 0 ? 'checked="checked"' : ''; ?> /><label for="protect-author-info-false"><?php _e('No','custom-comment-notifications'); ?></label>
                    </td>
                </tr>
            </table>
        </div>
        <div id="ccn-template-selector">
            <table id="ccn-template-table">
                <tr valign="top"><td>
                    <select name="ccn_template" id="ccn-template">
                        <option value="" select="selected" ><?php _e('-- Select Template --','custom-comment-notifications'); ?></option>
                        <option value="author_comment"><?php _e('Author Comment','custom-comment-notifications'); ?></option>
                        <option value="author_trackback"><?php _e('Author Trackback','custom-comment-notifications'); ?></option>
                        <option value="author_pingback"><?php _e('Author Pingback','custom-comment-notifications'); ?></option>
                        <option value="moderator_comment"><?php _e('Moderator Comment','custom-comment-notifications'); ?></option>
                        <option value="moderator_trackback"><?php _e('Moderator Trackback','custom-comment-notifications'); ?></option>
                        <option value="moderator_pingback"><?php _e('Moderator Pingback','custom-comment-notifications'); ?></option>
                    </select>
                </td></tr>
            </table>
        </div>
        <div id="ccn-editor-container">
            <table id="ccn-editor-table">
                <tr valign="top"><th>Subject:</th><td><input type="text" id="ccn-editor-subject" size="90"></input></td></tr>
                <tr valign="top"><th>Content:</th><td><textarea rows="20" cols="90" id="ccn-editor-content"></textarea></td></tr>
            </table>
        </div>
        <div id="ccn-save-container">
            <table id="ccn-save-table">
                <tr valign="top"><td><input type="submit" name="ccn_save" class="button-primary" value="<?php _e('Save Changes','custom-comment-notifications'); ?>" /></td></tr>
            </table>     
        </div>
    </form>
    <div id="ccn-variables-container">
        <hr/><h3>Post Variables</h3><hr/>
        <table id="ccn-post-variables-table" class="form-table">
            <thead><tr><th>Variable</th><th>Description</th><th>Type</th></tr></thead>
            <tr><td id="ccn-variable">P_ID</td><td id="ccn-variable-description">ID of post that was commented on</td><td id="ccn-variable-type">Text</td></tr>
            <tr><td id="ccn-variable">P_TITLE</td><td id="ccn-variable-description">Title of post that was commented on</td><td id="ccn-variable-type">Text</td></tr>
            <tr><td id="ccn-variable">P_LINK</td><td id="ccn-variable-description">Link to post that was commented on</td><td id="ccn-variable-type">Anchor</td></tr>
            <tr><td id="ccn-variable">P_LINK_COMMENT</td><td id="ccn-variable-description">Link to post that was commented on (navigate to current comment)</td><td id="ccn-variable-type">Anchor</td></tr>
            <tr><td id="ccn-variable">P_LINK_COMMENTS</td><td id="ccn-variable-description">Link to the post that was commented on (navigate to comments section)</td><td id="ccn-variable-type">Anchor</td></tr>
        </table>
        <hr/><h3>Comment Variables</h3><hr/>
        <table id="ccn-comment-variables-table" class="form-table">
            <thead><tr><th>Variable</th><th>Description</th><th>Type</th></tr></thead>
            <tr><td id="ccn-variable">C_AUTHOR</td><td id="ccn-variable-description">Name of comment author</td><td id="ccn-variable-type">Text</td></tr>
            <tr><td id="ccn-variable">C_AUTHOR_EMAIL</td><td id="ccn-variable-description">Email of comment author</td><td id="ccn-variable-type">Mail Link</td></tr>
            <tr><td id="ccn-variable">C_AUTHOR_IP</td><td id="ccn-variable-description">IP of comment author</td><td id="ccn-variable-type">Text</td></tr>
            <tr><td id="ccn-variable">C_AUTHOR_DOMAIN</td><td id="ccn-variable-description">Domain lookup of comment author's IP</td><td id="ccn-variable-type">Anchor</td></tr>
            <tr><td id="ccn-variable">C_AUTHOR_URL</td><td id="ccn-variable-description">URL of comment author</td><td id="ccn-variable-type">Anchor</td></tr>
            <tr><td id="ccn-variable">C_AUTHOR_ARIN_LOOKUP</td><td id="ccn-variable-description"><a href="https://www.arin.net/">ARIN Whois</a> lookup of comment author's IP</td><td id="ccn-variable-type">Anchor</td></tr>
            <tr><td id="ccn-variable">C_CONTENT</td><td id="ccn-variable-description">Content of the comment</td><td id="ccn-variable-type">Text</td></tr>
            <tr><td id="ccn-variable">C_WAITING_MODERATION</td><td id="ccn-variable-description">Number of comments waiting moderation (only valid on Moderator Template)</td><td id="ccn-variable-type">Integer</td></tr>
        </table>
        <hr/><h3>Moderation Variables</h3><hr/>
        <table id="ccn-moderation-variables-table" class="form-table">
            <thead><tr><th>Variable</th><th>Description</th><th>Type</th></tr></thead>
            <tr><td id="ccn-variable">DELETE_TRASH_COMMENT_LINK</td><td id="ccn-variable-description">Link to trash or delete Comment (depends on <a href=https://codex.wordpress.org/Trash_status>EMPTY_TRASH_DAYS</a> variable)</td><td id="ccn-variable-type">Anchor</td></tr>
            <tr><td id="ccn-variable">APPROVE_COMMENT_LINK</td><td id="ccn-variable-description">Link to approve comment (only valid on Moderator Template)</td><td id="ccn-variable-type">Anchor</td></tr>
            <tr><td id="ccn-variable">SPAM_COMMENT</td><td id="ccn-variable-description">Link to mark comment as spam</td><td id="ccn-variable-type">Anchor</td></tr>
            <tr><td id="ccn-variable">MODERATION_PANEL</td><td id="ccn-variable-description">Link to Moderation Panel (only valid on Moderator Template)</td><td id="ccn-variable-type">Anchor</td></tr>
        </table>
        <hr/><h3>Site Variables</h3><hr/>
        <table id="ccn-moderation-variables-table" class="form-table">
            <thead><tr><th>Variable</th><th>Description</th><th>Type</th></tr></thead>
            <tr><td id="ccn-variable">SITE_LINK</td><td id="ccn-variable-description">Link to Site</td><td id="ccn-variable-type">Anchor</td></tr>
            <tr><td id="ccn-variable">BLOG_NAME</td><td id="ccn-variable-description">Blog Name</td><td id="ccn-variable-type">Text</td></tr>
        </table>
        <span>**If Plain Text Email format selection is chosen, then anchor tags will be split out and the URL will immediately follow the corresponding text.</span>
    </div>
    
<?php
} //End ccn_settings_menu()

// AJAX Functions
add_action('wp_ajax_ccn_update_editor_content', 'ccn_update_editor_content_callback');
function ccn_update_editor_content_callback() {
    switch($_POST['template']) {
        case 'author_comment':
            $html = get_option('ccn_author_comment', CCN_DEFAULT_AUTHOR_COMMENT);
            break;
        case 'author_trackback':
            $html = get_option('ccn_author_trackback', CCN_DEFAULT_AUTHOR_TRACKBACK);
            break;
        case 'author_pingback':
            $html = get_option('ccn_author_pingback', CCN_DEFAULT_AUTHOR_PINGBACK);
            break;
        case 'moderator_comment':
            $html = get_option('ccn_moderator_comment', CCN_DEFAULT_MODERATOR_COMMENT);
            break;
        case 'moderator_trackback':
            $html = get_option('ccn_moderator_trackback', CCN_DEFAULT_MODERATOR_TRACKBACK);
            break;
        case 'moderator_pingback':
            $html = get_option('ccn_moderator_pingback', CCN_DEFAULT_MODERATOR_PINGBACK);
            break;
        default:
            $html = '';
            break;
    }
    
    echo $html;
    
    die();
}

add_action('wp_ajax_ccn_update_editor_subject', 'ccn_update_editor_subject_callback');
function ccn_update_editor_subject_callback() {
     switch($_POST['template']) {
        case 'author_comment':
            $html = get_option('ccn_author_comment_subject', CCN_DEFAULT_AUTHOR_COMMENT_SUBJECT);
            break;
        case 'author_trackback':
            $html = get_option('ccn_author_trackback_subject', CCN_DEFAULT_AUTHOR_TRACKBACK_SUBJECT);
            break;
        case 'author_pingback':
            $html = get_option('ccn_author_pingback_subject', CCN_DEFAULT_AUTHOR_PINGBACK_SUBJECT);
            break;
        case 'moderator_comment':
            $html = get_option('ccn_moderator_comment_subject', CCN_DEFAULT_MODERATOR_COMMENT_SUBJECT);
            break;
        case 'moderator_trackback':
            $html = get_option('ccn_moderator_trackback_subject', CCN_DEFAULT_MODERATOR_TRACKBACK_SUBJECT);
            break;
        case 'moderator_pingback':
            $html = get_option('ccn_moderator_pingback_subject', CCN_DEFAULT_MODERATOR_PINGBACK_SUBJECT);
            break;
        default:
            $html = '';
            break;
    }
    
    echo $html;
    
    die();
}

// Set up Plugin Menu
function ccn_plugin_menu() {//Set up the plugin menu
        add_submenu_page('options-general.php',__('Custom Comment Notifications Options','custom-comment-notifications'),__('Custom Comment Notifications','custom-comment-notifications'),'edit_plugins',basename(__FILE__),'ccn_settings_menu');
} //End ccn_plugin_menu

function ccn_setup($blog_id) {
    global $wpdb;
    
    if($blog_id !== NULL && $blog_id != $wpdb->blogid) {
        switch_to_blog($blog_id);
    }
    
    update_option('ccn_author_comment_subject', CCN_DEFAULT_AUTHOR_COMMENT_SUBJECT);
    update_option('ccn_author_comment', CCN_DEFAULT_AUTHOR_COMMENT);
    update_option('ccn_author_trackback_subject', CCN_DEFAULT_AUTHOR_TRACKBACK_SUBJECT);
    update_option('ccn_author_trackback', CCN_DEFAULT_AUTHOR_TRACKBACK);
    update_option('ccn_author_pingback_subject', CCN_DEFAULT_AUTHOR_PINGBACK_SUBJECT);
    update_option('ccn_author_pingback', CCN_DEFAULT_AUTHOR_PINGBACK);
    update_option('ccn_moderator_comment_subject', CCN_DEFAULT_MODERATOR_COMMENT_SUBJECT);
    update_option('ccn_moderator_comment', CCN_DEFAULT_MODERATOR_COMMENT);
    update_option('ccn_moderator_trackback_subject', CCN_DEFAULT_MODERATOR_TRACKBACK_SUBJECT);
    update_option('ccn_moderator_trackback', CCN_DEFAULT_MODERATOR_TRACKBACK);
    update_option('ccn_moderator_pingback_subject', CCN_DEFAULT_MODERATOR_PINGBACK_SUBJECT);
    update_option('ccn_moderator_pingback', CCN_DEFAULT_MODERATOR_PINGBACK);
    update_option('ccn_protect_comment_author', 0);
    update_option('ccn_email_format', 'html');
}

// Activation Hook
function ccn_activate() {
    if(function_exists( 'is_multisite' ) && is_multisite() && isset($_GET['networkwide']) && $_GET['networkwide'] == 1) {
        global $wpdb; 
        $blogList = $wpdb->get_results("SELECT blog_id, domain, path FROM ".$wpdb->blogs);
        foreach($blogList as $blog) {
            ccn_setup($blog->blog_id);
        }
    } else {
        ccn_setup(NULL);
    }
} //End ccn_activation_hook()
register_activation_hook(__FILE__, 'ccn_activate');

function ccn_destroy($blog_id) {
    global $wpdb;   
    
    if($blog_id !== NULL && $blog_id != $wpdb->blogid) {
        switch_to_blog($blog_id);
    }
    
    delete_option('ccn_author_comment_subject');
    delete_option('ccn_author_comment');
    delete_option('ccn_author_trackback_subject');
    delete_option('ccn_author_trackback');
    delete_option('ccn_author_pingback_subject');
    delete_option('ccn_author_pingback');
    delete_option('ccn_moderator_comment_subject');
    delete_option('ccn_moderator_comment');
    delete_option('ccn_moderator_trackback_subject');
    delete_option('ccn_moderator_trackback');
    delete_option('ccn_moderator_pingback_subject');
    delete_option('ccn_moderator_pingback');
    delete_option('ccn_protect_comment_author');
    delete_option('ccn_email_format');
}

// Deactivation Hook
function ccn_uninstall() {
    if(function_exists( 'is_multisite' ) && is_multisite()) {
     global $wpdb; 
     $blogList = $wpdb->get_results("SELECT blog_id, domain, path FROM ".$wpdb->blogs);
     foreach($blogList as $blog) {
         ccn_destroy($blog->blog_id);
     }
    } else {
        ccn_destroy(NULL);
    }
} //End ccn_deactivation_hook()
register_uninstall_hook(__FILE__, 'ccn_uninstall');

//Action Hooks
add_action('admin_menu', 'ccn_plugin_menu');
add_action('admin_footer', 'ccn_javascript');

function ccn_javascript() { ?>
    <script type='text/javascript'>
        jQuery(document).ready(function($) {
            $('#ccn-template').change(function() { 
                var template = $('#ccn-template').val();
                var data_content = { action: 'ccn_update_editor_content', template: template };
                var data_subject = { action: 'ccn_update_editor_subject', template: template };

                $.post(ajaxurl, data_content, function(response) {
                   $("#ccn-editor-content").val(response);
                });
                $.post(ajaxurl, data_subject, function(response) {
                   $("#ccn-editor-subject").val(response); 
                });
                
                $("#ccn-editor-content").attr("name", "ccn_"+template);
                $("#ccn-editor-subject").attr("name", "ccn_"+template+"_subject");
            });
        });
    </script>
<?php } 

// These funcions will be used in place of the global functions found in /wp-includes/pluggable.php
if(!function_exists('wp_notify_postauthor')) :
    function wp_notify_postauthor($comment_id, $deprecated = null) {
        if(null !== $deprecated) {
            _deprecated_argument(__FUNCTION__, '3.8');
        }
    
        $author_comment_subject = get_option('ccn_author_comment_subject', CCN_DEFAULT_AUTHOR_COMMENT_SUBJECT);
        $author_comment = get_option('ccn_author_comment', CCN_DEFAULT_AUTHOR_COMMENT);
        $author_trackback_subject = get_option('ccn_author_trackback_subject', CCN_DEFAULT_AUTHOR_TRACKBACK_SUBJECT);
        $author_trackback = get_option('ccn_author_trackback', CCN_DEFAULT_AUTHOR_TRACKBACK);
        $author_pingback_subject = get_option('ccn_author_pingback_subject', CCN_DEFAULT_AUTHOR_PINGBACK_SUBJECT);
        $author_pingback = get_option('ccn_author_pingback', CCN_DEFAULT_AUTHOR_PINGBACK);
        $protect_comment_author = get_option('ccn_protect_comment_author', 0);
        $email_format = get_option('ccn_email_format', 'html');
        
        $comment = get_comment($comment_id);
        if(empty($comment)) { // No comment found with that ID
            return false;
        }
        $post = get_post($comment->comment_post_ID);
        $postAuthor = get_userdata($post->post_author);
        
        // Who needs to be notified?  We'll start with the Post Author, other's can be added later
        $recipients = array($postAuthor->user_email);
        
        // Filter the list of e-mails to receive a comment notification
        $recipients = apply_filters('comment_notification_recipients', $recipients, $comment_id);
        $recipients = array_filter($recipients);
        
        if(!count($recipients)) {
            return false;
        }
        
        // Flip the array to facilitate unsetting the post author
        $recipients = array_flip($recipients);
        
        // Filter whether to notify post authors of their comments on their own posts
        $notify_author = apply_filters('comment_notification_notify_author', false, $comment_id);
        
        // The comment was left by the post author
        if(!$notify_author && $comment->user_id == $post->post_author) {
            unset($recipients[$postAuthor->user_email]);
        }
        // The author moderated a comment on their own post
        if(!$notify_author && $post->post_author == get_current_user_id()) {
            unset($recipients[$postAuthor->user_email]);
        }
        // The post author is no longer a member of the blog
        if(!$notify_author && !user_can($post->post_author, 'read_post', $post->ID)) {
            unset($recipients[$postAuthor->user_email]);
        }
        
        // If there's no email to send the comment to, bail, otherwise flip array back around for use below
        if(!count($recipients)) {
            return false;
        } else {
            $recipients = array_flip($recipients);
        }
        
        switch($comment->comment_type) {
            case 'trackback':
                $notify_message = $author_trackback;
                $subject = $author_trackback_subject;
                break;
            case 'pingback':
                $notify_message = $author_pingback;
                $subject = $author_pingback_subject;
                break;
            default: // Comments
                $notify_message = $author_comment;
                $subject = $author_comment_subject;
                break;
        }
        
        // Get the Post Variables
        $P_ID = $comment->comment_post_ID;
        $P_TITLE = $post->post_title;
        if($email_format === 'html') {
            $P_LINK = '<a href="'.get_permalink($P_ID).'">'.$P_TITLE.'</a>';
            $P_LINK_COMMENT = '<a href="'.get_permalink($P_ID).'#comment-'.$comment_id.'">'.$P_TITLE.'</a>';
            $P_LINK_COMMENTS = '<a href="'.get_permalink($P_ID).'#comments">'.$P_TITLE.'</a>';
        } else {
            $P_LINK = $P_TITLE.' - '.get_permalink($P_ID);
            $P_LINK_COMMENT = $P_TITLE.' - '.get_permalink($P_ID).'#comment-'.$comment_id;
            $P_LINK_COMMENTS = $P_TITLE.' - '.get_permalink($P_ID).'#comments';
        }
        
        // Get the Comment Variables
        $C_AUTHOR = $comment->comment_author;
        if($protect_comment_author == 1) {
            $C_AUTHOR_IP = 'xxx.xxx.xxx.xxx';
            $C_AUTHOR_DOMAIN = 'xxxxx://xxxxxxxxxxx.xxx';
            $C_AUTHOR_URL = 'xxxxx://xxxxxxxxxxx.xxx';
            $C_AUTHOR_EMAIL = 'xxxxxxxxxx@xxxxxx.xxx';
            $C_AUTHOR_ARIN_LOOKUP = "Comment Author is protected";
        } else {
            $C_AUTHOR_IP = $comment->comment_author_IP;
            $C_AUTHOR_DOMAIN = @gethostbyaddr($comment->comment_author_IP);
            $C_AUTHOR_URL = $comment->comment_author_url; 
            if($email_format === 'html') {
                $C_AUTHOR_EMAIL = '<a href="mailto:'.$comment->comment_author_email.'">'.$comment->comment_author_email.'</a>';
                $C_AUTHOR_ARIN_LOOKUP = '<a href="http://ws.arin.net/cgi-bin/whois.pl?queryinput='.$comment->comment_author_IP.'">Lookup IP</a>';
            } else {
                $C_AUTHOR_EMAIL = $comment->comment_author_email;
                $C_AUTHOR_ARIN_LOOKUP = 'Lookup IP - http://ws.arin.net/cgi-bin/whois.pl?queryinput='.$comment->comment_author_IP;
            }
        }
        if($email_format === 'html') {
            $C_CONTENT = str_replace("\r\n", "<br/>", $comment->comment_content);
        } else {
            $C_CONTENT = $comment->comment_content;
        }

        // Get the Moderation Variables    
        if($email_format == 'html') {
            $SPAM_COMMENT = '<a href="'.admin_url('comment.php?action=spam&c=$comment_id').'">Spam Comment</a>';
            if (EMPTY_TRASH_DAYS) {
                $DELETE_TRASH_COMMENT_LINK = '<a href="'.admin_url('comment.php?action=trash&c=$comment_id').'>Trash Comment</a>';
            } else {
                $DELETE_TRASH_COMMENT_LINK = '<a href="'.admin_url('comment.php?action=delete&c=$comment_id').'>Delete Comment</a>';
            }
        } else {
            $SPAM_COMMENT = 'Spam Comment - '.admin_url('comment.php?action=spam&c=$comment_id');
            if (EMPTY_TRASH_DAYS) {
                $DELETE_TRASH_COMMENT_LINK = 'Trash Comment - '.admin_url('comment.php?action=trash&c=$comment_id');
            } else {
                $DELETE_TRASH_COMMENT_LINK = 'Delete Comment - '.admin_url('comment.php?action=delete&c=$comment_id');
            }
        }    
        
        if (!user_can($post->post_author, 'edit_comment', $comment_id)) { // Reset the Moderation Settings if post author can't moderate
            $DELETE_TRASH_COMMENT_LINK = '';
            $SPAM_COMMENT = '';
        }        
        
        // Get the Site Variables
        if($email_format == 'html') {
            $BLOG_NAME = get_option('blogname');
            $SITE_LINK = get_option('siteurl');
        } else {
            $BLOG_NAME = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            $SITE_LINK = get_option('siteurl');
        }

        //REPLACE VARIABLES
        $notify_message = str_replace("P_ID", $P_ID, $notify_message);
        $notify_message = str_replace("P_TITLE", $P_TITLE, $notify_message);
        $notify_message = str_replace("P_LINK", $P_LINK, $notify_message);
        $notify_message = str_replace("P_LINK_COMMENT", $P_LINK_COMMENT, $notify_message);
        $notify_message = str_replace("P_LINK_COMMENTS", $P_LINK_COMMENTS, $notify_message);
        $notify_message = str_replace("C_AUTHOR", $C_AUTHOR, $notify_message);
        $notify_message = str_replace("C_AUTHOR_EMAIL", $C_AUTHOR_EMAIL, $notify_message);
        $notify_message = str_replace("C_AUTHOR_IP", $C_AUTHOR_IP, $notify_message);
        $notify_message = str_replace("C_AUTHOR_DOMAIN", $C_AUTHOR_DOMAIN, $notify_message);
        $notify_message = str_replace("C_AUTHOR_URL", $C_AUTHOR_URL, $notify_message);
        $notify_message = str_replace("C_AUTHOR_ARIN_LOOKUP", $C_AUTHOR_ARIN_LOOKUP, $notify_message);
        $notify_message = str_replace("C_CONTENT", $C_CONTENT, $notify_message);
        $notify_message = str_replace("DELETE_TRASH_COMMENT_LINK", $DELETE_TRASH_COMMENT_LINK, $notify_message);
        $notify_message = str_replace("SPAM_COMMENT", $SPAM_COMMENT, $notify_message);
        $notify_message = str_replace("SITE_LINK", $SITE_LINK, $notify_message);
        $notify_message = str_replace("BLOG_NAME", $BLOG_NAME, $notify_message);        
        
        $sender = 'webmaster@'.preg_replace('#^www.#', '', strtolower($_SERVER['SERVER_NAME']));        
        if ($comment->comment_author == '' || $protect_comment_author == 1) {
            $from = "From: \"$BLOG_NAME\" <$sender>";
	} else if ($protect_comment_author == 0) {
            $from = "From: \"$comment->comment_author\" <$sender>";
            if ($comment->comment_author_email != '') {
                $reply_to = "Reply-To: \"$comment->comment_author_email\" <$comment->comment_author_email>";
            }
	} else {
            $from = "From: \"$BLOG_NAME\" <$sender>";
        }
        
        $message_headers = array(
            'MIME-Version: 1.0',
            '$from');
        
        if(isset($reply_to)) {
            array_push($message_headers, $reply_to); 
        }
        
        if($email_format === 'html') {
            $content_type = 'Content-Type: text/html; charset="'.get_option('blog_charset').'"';
        } else {
            $content_type = 'Content-Type: text/plain; charset="'.get_option('blog_charset').'"';
        }        
        array_push($message_headers, $content_type);
        
        $notify_message = apply_filters('comment_notification_text', $notify_message, $comment_id);
        $subject = apply_filters('comment_notification_subject', $subject, $comment_id);
        $message_headers = apply_filters('comment_notification_headers', $message_headers, $comment_id);
        
        foreach($recipients as $recipient) {
            @wp_mail($recipient, $subject, $notify_message, $message_headers);
        }
        
        return true;
    }    
endif;

if(!function_exists('wp_notify_moderator')) :
    function wp_notify_moderator($comment_id) {
        global $wpdb;
        
        $moderator_comment_subject = get_option('ccn_moderator_comment_subject', CCN_DEFAULT_MODERATOR_COMMENT_SUBJECT);
        $moderator_comment = get_option('ccn_moderator_comment', CCN_DEFAULT_MODERATOR_COMMENT);
        $moderator_trackback_subject = get_option('ccn_moderator_trackback_subject', CCN_DEFAULT_MODERATOR_TRACKBACK_SUBJECT);
        $moderator_trackback = get_option('ccn_moderator_trackback', CCN_DEFAULT_MODERATOR_TRACKBACK);
        $moderator_pingback_subject = get_option('ccn_moderator_pingback_subject', CCN_DEFAULT_MODERATOR_PINGBACK_SUBJECT);
        $moderator_pingback = get_option('ccn_moderator_pingback', CCN_DEFAULT_MODERATOR_PINGBACK);
        $protect_comment_author = get_option('ccn_protect_comment_author', 0);
        $email_format = get_option('ccn_email_format', 'html');
    
        if(0 == get_option('moderation_notify')) {
            return true;
        }
        
        $comment = get_comment($comment_id);
        $post = get_post($comment->comment_post_ID);
        $postAuthor = get_userdata($post->post_author);
        
        // Send to the administration and to the post author if the author can modify the comment.
        $recipients = array(get_option('admin_email'));        
        if(user_can($postAuthor->ID, 'edit_comment', $comment_id) && !empty($postAuthor->user_email)) {
            if(0 !== strcasecmp($postAuthor->user_email, get_option('admin_email'))) {
                $recipients[] = $postAuthor->user_email;
            }
        }
        
        $comment_author_domain = @gethostbyaddr($comment->comment_author_IP);
        $comments_waiting = $wpdb->get_var("SELECT count(comment_id) FROM $wpdb->comments WHERE comment_approved = '0'");
        $comment_html = str_replace("\r\n","<br/>",$comment->comment_content);
        $blogname = get_option('blogname');
        $siteurl = get_option('siteurl');
        
        switch($comment->comment_type) {
            case 'trackback':
                $notify_message = sprintf(__('Heads up!  Someone has just submitted a new trackback on: <a href="%1$s">"%2$s"</a>'), $siteurl, $blogname)."<br/>";
                $notify_message .= sprintf(__('Website: %1$s (IP: %2$s, %3$s'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain)."<br/>";
                $notify_message .= sprintf(__('URL: <a href="%1$s">%1$s</a>'), $comment->comment_author_url)."<br/>";
                $notify_message .= sprintf(__('Excerpt: ')."<br/><br/>%s<br/><br/>", $comment_html);
                $subject = sprintf(__('Trackback pending moderation on "%s"'), $blogname);
                break;
            case 'pingback':
                $notify_message = sprintf(__('Heads up!  Someone has just submitted a new pingback on: <a href="%1$s">"%2$s"</a>'), $siteurl, $blogname)."<br/>";
                $notify_message .= sprintf(__('Website: %1$s (IP: %2$s, %3$s'), $comment->comment_author, $comment->comment_author_IP, $comment_author_domain)."<br/>";
                $notify_message .= sprintf(__('URL: <a href="%1$s">%1$s</a>'), $comment->comment_author_url)."<br/>";
                $notify_message .= sprintf(__('Excerpt: ')."<br/><br/>%s<br/><br/>", $comment_html);
                $subject = sprintf(__('Pingback pending moderation on "%s"'), $blogname);
                break;
            default: // Comments
                $notify_message = sprintf(__('Heads up!  Someone has just submitted a new comment on: <a href="%1$s">"%2$s"</a>'), $siteurl, $blogname)."<br/><br/>";
                $notify_message .= sprintf(__('<a href="%1$s">%2$s</a>'), get_permalink($comment->comment_post_ID), $post->post_title)."<br/>";
                $notify_message .= sprintf(__('%s'), $comment->comment_author)."<br/>";
                $notify_message .= sprintf(__('Comment: ').'<br/><br/>"%s"<br/><br/>', $comment_html);
                $subject = sprintf(__('Comment pending moderation on "%s"'), $blogname);
                break;
        }
        
        // Get the Post Variables
        $P_ID = $comment->comment_post_ID;
        $P_TITLE = $post->post_title;
        if($email_format === 'html') {
            $P_LINK = '<a href="'.get_permalink($P_ID).'">'.$P_TITLE.'</a>';
            $P_LINK_COMMENT = '<a href="'.get_permalink($P_ID).'#comment-'.$comment_id.'">'.$P_TITLE.'</a>';
            $P_LINK_COMMENTS = '<a href="'.get_permalink($P_ID).'#comments">'.$P_TITLE.'</a>';
        } else {
            $P_LINK = $P_TITLE.' - '.get_permalink($P_ID);
            $P_LINK_COMMENT = $P_TITLE.' - '.get_permalink($P_ID).'#comment-'.$comment_id;
            $P_LINK_COMMENTS = $P_TITLE.' - '.get_permalink($P_ID).'#comments';
        }
        
        // Get the Comment Variables
        $C_AUTHOR = $comment->comment_author;
        $C_AUTHOR_IP = $comment->comment_author_IP;
        $C_AUTHOR_DOMAIN = @gethostbyaddr($comment->comment_author_IP);
        $C_AUTHOR_URL = $comment->comment_author_url;
        $C_WAITING_MODERATION = $wpdb->get_var("SELECT count(comment_id) FROM $wpdb->comments WHERE comment_approved = '0'");
        if($email_format === 'html') {
            $C_AUTHOR_EMAIL = '<a href="mailto:'.$comment->comment_author_email.'">'.$comment->comment_author_email.'</a>';
            $C_AUTHOR_ARIN_LOOKUP = '<a href="http://ws.arin.net/cgi-bin/whois.pl?queryinput='.$comment->comment_author_IP.'">Lookup IP</a>';
            $C_CONTENT = str_replace("\r\n", "<br/>", $comment->comment_content);
        } else {
            $C_AUTHOR_EMAIL = $comment->comment_author_email;
            $C_AUTHOR_ARIN_LOOKUP = 'Lookup IP - http://ws.arin.net/cgi-bin/whois.pl?queryinput='.$comment->comment_author_IP;
            $C_CONTENT = $comment->comment_content;
        }

        // Get the Moderation Variables    
        if($email_format == 'html') {
            $SPAM_COMMENT = '<a href="'.admin_url('comment.php?action=spam&c=$comment_id').'">Spam Comment</a>';
            $APPROVE_COMMENT = '<a href="'.admin_url('comment.php?action=approve&c=$comment_id').'">Approve Comment</a>';
            $MODERATION_PANEL = '<a href="'.admin_url('edit-comments.php?comment_status=moderated').'">Moderation Panel</a>';
            if (EMPTY_TRASH_DAYS) {
                $DELETE_TRASH_COMMENT_LINK = '<a href="'.admin_url('comment.php?action=trash&c=$comment_id').'>Trash Comment</a>';
            } else {
                $DELETE_TRASH_COMMENT_LINK = '<a href="'.admin_url('comment.php?action=delete&c=$comment_id').'>Delete Comment</a>';
            }
        } else {
            $SPAM_COMMENT = 'Spam Comment - '.admin_url('comment.php?action=spam&c=$comment_id');
            $APPROVE_COMMENT = 'Approve Comment - '.admin_url('comment.php?action=approve&c=$comment_id');
            $MODERATION_PANEL = 'Moderation Panel - '.admin_url('edit-comments.php?comment_status=moderated');
            if (EMPTY_TRASH_DAYS) {
                $DELETE_TRASH_COMMENT_LINK = 'Trash Comment - '.admin_url('comment.php?action=trash&c=$comment_id');
            } else {
                $DELETE_TRASH_COMMENT_LINK = 'Delete Comment - '.admin_url('comment.php?action=delete&c=$comment_id');
            }
        }    
        
        if (!user_can($post->post_author, 'edit_comment', $comment_id)) { // Reset the Moderation Settings if post author can't moderate
            $DELETE_TRASH_COMMENT_LINK = '';
            $SPAM_COMMENT = '';
        }        
        
        // Get the Site Variables
        if($email_format == 'html') {
            $BLOG_NAME = get_option('blogname');
            $SITE_LINK = get_option('siteurl');
        } else {
            $BLOG_NAME = wp_specialchars_decode(get_option('blogname'), ENT_QUOTES);
            $SITE_LINK = get_option('siteurl');
        }

        //REPLACE VARIABLES
        $notify_message = str_replace("P_ID", $P_ID, $notify_message);
        $notify_message = str_replace("P_TITLE", $P_TITLE, $notify_message);
        $notify_message = str_replace("P_LINK", $P_LINK, $notify_message);
        $notify_message = str_replace("P_LINK_COMMENT", $P_LINK_COMMENT, $notify_message);
        $notify_message = str_replace("P_LINK_COMMENTS", $P_LINK_COMMENTS, $notify_message);
        $notify_message = str_replace("C_AUTHOR", $C_AUTHOR, $notify_message);
        $notify_message = str_replace("C_AUTHOR_EMAIL", $C_AUTHOR_EMAIL, $notify_message);
        $notify_message = str_replace("C_AUTHOR_IP", $C_AUTHOR_IP, $notify_message);
        $notify_message = str_replace("C_AUTHOR_DOMAIN", $C_AUTHOR_DOMAIN, $notify_message);
        $notify_message = str_replace("C_AUTHOR_URL", $C_AUTHOR_URL, $notify_message);
        $notify_message = str_replace("C_AUTHOR_ARIN_LOOKUP", $C_AUTHOR_ARIN_LOOKUP, $notify_message);
        $notify_message = str_replace("C_CONTENT", $C_CONTENT, $notify_message);
        $notify_message = str_replace("C_WAITING_MODERATION", $C_WAITING_MODERATION, $notify_message);
        $notify_message = str_replace("DELETE_TRASH_COMMENT_LINK", $DELETE_TRASH_COMMENT_LINK, $notify_message);
        $notify_message = str_replace("APPROVE_COMMENT_LINK", $APPROVE_COMMENT_LINK, $notify_message);
        $notify_message = str_replace("SPAM_COMMENT", $SPAM_COMMENT, $notify_message);
        $notify_message = str_replace("MODERATION_PANEL", $MODERATION_PANEL, $notify_message);
        $notify_message = str_replace("SITE_LINK", $SITE_LINK, $notify_message);
        $notify_message = str_replace("BLOG_NAME", $BLOG_NAME, $notify_message);
        
        $sender = 'webmaster@'.preg_replace('#^www.#', '', strtolower($_SERVER['SERVER_NAME']));
        if ( '' == $comment->comment_author ) {
            $from = "From: \"$blogname\" <$wp_email>";
            if ( '' != $comment->comment_author_email ) {
                $reply_to = "Reply-To: $comment->comment_author_email";
            }
	} else {
            $from = "From: \"$comment->comment_author\" <$wp_email>";
            if ( '' != $comment->comment_author_email ) {
                $reply_to = "Reply-To: \"$comment->comment_author_email\" <$comment->comment_author_email>"; 
            }
	}
        
         $message_headers = array(
            'MIME-Version: 1.0',
            '$from');
        
        if(isset($reply_to)) {
            array_push($message_headers, $reply_to); 
        }
        
        if($email_format === 'html') {
            $content_type = 'Content-Type: text/html; charset="'.get_option('blog_charset').'"';
        } else {
            $content_type = 'Content-Type: text/plain; charset="'.get_option('blog_charset').'"';
        }
        array_push($message_headers, $content_type);
        
        $recipients = apply_filters('comment_moderation_recipients', $recipients, $comment_id);
        $notify_message = apply_filters('comment_moderation_text', $notify_message, $comment_id);
        $subject = apply_filters('comment_moderation_subject', $subject, $comment_id);
        $message_headers = apply_filters('comment_moderation_headers', $message_headers, $comment_id);
        
        foreach($recipients as $recipient) {
            @wp_mail($recipient, $subject, $notify_message, $message_headers);
        }
        
        return true;
    }    
endif;
?>