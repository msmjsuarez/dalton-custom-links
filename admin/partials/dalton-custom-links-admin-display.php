<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/msmjsuarez
 * @since      1.0.0
 *
 * @package    Dalton_Custom_Links
 * @subpackage Dalton_Custom_Links/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php
/**
 * The form to be loaded on the plugin's admin page
 */

if (current_user_can('edit_users')) {

?>

    <div id="wrap">

        <h1><?php echo $title; ?> button</h1>

        <?php echo 'Page: <a href="' . $page_url . '" target="_blank"> ' . $page_url . ' </a>'; ?>

        <p>Copy the code below and paste to the Page <strong>excerpt</strong>.</p>
        <blockquote class="dalton_custom_links_bl">
            &amp;#91;<?php echo $page; ?>&amp;#93;
        </blockquote>
        <p>Copy the code below and paste anywhere on the Page/Post.</p>
        <blockquote class="dalton_custom_links_bl">
            [<?php echo $page; ?>]
        </blockquote>

        <?php
        //print status message 
        if ($_GET['msg'] == 'true') {
            echo '<p style="color: #429900;">Successfully updated.</p>';
        } elseif ($_GET['msg'] == 'false') {
            echo '<p style="color: #ff0000;">Something is wrong.</p>';
        } else {
        }
        ?>

        <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="dalton_custom_links_form">

            <input type="hidden" name="action" value="dalton_custom_links_form">
            <input type="hidden" name="dalton_custom_links_nonce" value="<?php echo $page_id_nonce ?>" />
            <input type="hidden" name="dalton_custom_link_position" value="<?php echo $page; ?>" />

            <div>
                <br>
                <label for="<?php echo $page . '-text'; ?>"> <?php _e('Button text', $this->plugin_name); ?> </label><br>
                <input class="dalton_custom_links_input" required id="<?php echo $page . '-text'; ?>" type="text" name="text" value="<?php echo $text; ?>" placeholder="<?php _e('Enter button text', $this->plugin_name); ?>" /><br>
            </div>

            <div>
                <label for="<?php echo $page . '-url'; ?>"> <?php _e('Button URL', $this->plugin_name); ?> </label><br>
                <input class="dalton_custom_links_input" required id="<?php echo $page . '-url'; ?>" type="text" name="url" value="<?php echo $url; ?>" placeholder="<?php _e('Enter button URL', $this->plugin_name); ?>" /><br>
            </div>

            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Submit"></p>

        </form>

    </div>

<?php

} else {
?>
    <p> <?php __("You are not authorized to perform this operation.", $this->plugin_name) ?> </p>
<?php
}

?>
