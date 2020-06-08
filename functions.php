<?php
/**
 * Plugin Name:       WEBerlei No Title
 * Plugin URI:        https://beberlei.de
 * Description:       Checkbox to disable rendering the title on a page
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.0
 * Author:            Benjamin Eberlei
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

function weberlei_notitle_add_metabox() {
    add_meta_box(
        'weberlei-notitle-metabox',
        'Hide Title On Page',
        'weberlei_notitle_metabox_cb',
        'page',
        'side',
        'low'
    );
}

add_action('add_meta_boxes', 'weberlei_notitle_add_metabox');

function weberlei_notitle_metabox_cb($post) {
    $value = get_post_meta($post->ID, '_weberlei_notitle', true);
?>
    <div class="components-base-control__field">
        <label class="components-base-control__label" for="weberlei_notitle">Hide the title?</label>
        <select name="weberlei_notitle" id="weberlei_notitle" class="components-select-control__input" style="width: 80%">
            <option value="0" <?php selected($value, '0'); ?>>No (Show Title)</option>
            <option value="1" <?php selected($value, '1'); ?>>Yes (Hide Title)</option>
        </select>
    </div>
<?php
}

function weberlei_notitle_save_postdata($post_id) {
    if (array_key_exists('weberlei_notitle', $_POST)) {
        update_post_meta(
            $post_id,
            '_weberlei_notitle',
            (int) $_POST['weberlei_notitle']
        );
    }
}

add_action('save_post', 'weberlei_notitle_save_postdata');

function weberlei_the_title_hide($title, $id = null) {
    if (is_admin()) {
        return $title;
    }

    $hideTitle = (bool) get_post_meta($id, '_weberlei_notitle', true);

    if ($hideTitle) {
        return '';
    }

    return $title;
}

add_filter('the_title', 'weberlei_the_title_hide', 10, 2);
