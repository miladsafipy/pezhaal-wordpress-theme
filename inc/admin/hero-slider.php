<?php
defined( 'ABSPATH' ) || exit;

// =================================================================
//                   Add Hero
// =================================================================
function pezhaal_register_hero_post_type() {

    $labels = array(
        'name'               => 'اسلایدهای هیرو',
        'singular_name'      => 'اسلاید هیرو',
        'menu_name'          => 'اسلایدهای هیرو',
        'add_new'            => 'افزودن اسلاید',
        'add_new_item'       => 'افزودن اسلاید جدید',
        'edit_item'          => 'ویرایش اسلاید',
        'new_item'           => 'اسلاید جدید',
        'view_item'          => 'مشاهده اسلاید',
        'search_items'       => 'جستجوی اسلایدها',
        'not_found'          => 'اسلایدی پیدا نشد',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'menu_icon'          => 'dashicons-images-alt2',
        'supports'           => array('title', 'thumbnail'),
        'show_in_rest'       => true,
    );

    register_post_type('hero_slide', $args);
}

add_action('init', 'pezhaal_register_hero_post_type');


function pezhaal_hero_meta_box() {

    add_meta_box(
        'pezhaal_hero_details',
        'اطلاعات اسلاید هیرو',
        'pezhaal_hero_meta_box_callback',
        'hero_slide',
        'normal',
        'high'
    );
}

add_action('add_meta_boxes', 'pezhaal_hero_meta_box');


function pezhaal_hero_meta_box_callback($post) {

    wp_nonce_field(
        'pezhaal_save_hero',
        'pezhaal_hero_nonce'
    );

    

    $button_text = get_post_meta(
        $post->ID,
        '_hero_button_text',
        true
    );

    $button_url = get_post_meta(
        $post->ID,
        '_hero_button_url',
        true
    );
    ?>

    

    <p>
        <label for="hero_button_text">
            متن دکمه
        </label>
    </p>

    <input
        type="text"
        id="hero_button_text"
        name="hero_button_text"
        value="<?php echo esc_attr($button_text); ?>"
        style="width:100%;"
    >


    <p>
        <label for="hero_button_url">
            لینک دکمه
        </label>
    </p>

    <input
        type="url"
        id="hero_button_url"
        name="hero_button_url"
        value="<?php echo esc_url($button_url); ?>"
        style="width:100%;"
    >

    <p>
        <strong>
            تصویر هیرو:
        </strong>
        تصویر شاخص این اسلاید را انتخاب کنید.
    </p>

    <?php
}


function pezhaal_save_hero_meta($post_id) {

    if (
        !isset($_POST['pezhaal_hero_nonce']) ||
        !wp_verify_nonce(
            $_POST['pezhaal_hero_nonce'],
            'pezhaal_save_hero'
        )
    ) {
        return;
    }

    if (
        defined('DOING_AUTOSAVE') &&
        DOING_AUTOSAVE
    ) {
        return;
    }

    if (
        !current_user_can('edit_post', $post_id)
    ) {
        return;
    }



    if (isset($_POST['hero_button_text'])) {

        update_post_meta(
            $post_id,
            '_hero_button_text',
            sanitize_text_field(
                $_POST['hero_button_text']
            )
        );
    }


    if (isset($_POST['hero_button_url'])) {

        update_post_meta(
            $post_id,
            '_hero_button_url',
            esc_url_raw(
                $_POST['hero_button_url']
            )
        );
    }
}

add_action(
    'save_post_hero_slide',
    'pezhaal_save_hero_meta'
);