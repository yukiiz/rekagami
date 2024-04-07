<?php

if (!defined('DS')) {
    define('DS', DIRECTORY_SEPARATOR);
}
if (!empty($_SERVER['DOCUMENT_ROOT']) && !defined('WWW_ROOT')) {
    define('WWW_ROOT', $_SERVER['DOCUMENT_ROOT'] . DS);
}
function wpinc($file) {
    include get_template_directory() . DS . $file;
}

add_action('after_setup_theme', 'register_menu');
function register_menu() {
    register_nav_menu('primary', __('Primary Menu', 'theme-slug'));
}

/*-----------------------------------------------------------------------------------*/
// jQueryの読み込み無効
/*-----------------------------------------------------------------------------------*/
function delete_local_jquery() {
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri() . '/common/js/jquery-3.6.0.min.js', array(), '3.6.0');
}
add_action('wp_enqueue_scripts', 'delete_local_jquery');

/*-----------------------------------------------------------------------------------*/
//ログイン後の遷移先
/*-----------------------------------------------------------------------------------*/
function change_login_redirect($redirect_to, $request_redirect_to, $current_user) {
    if (!is_wp_error($current_user)) {
        $roles = $current_user->roles[0];
        $user_id = $current_user->ID;
        if ($roles === 'editor') {
            return home_url();
        } elseif ($roles === 'author') {
            return '/enter/?author=' . $user_id;
        } else {
            return admin_url();
        }
    }
}
add_filter('login_redirect', 'change_login_redirect', 100, 3);


/*-----------------------------------------------------------------------------------*/
// 管理者以外はadminバー非表示・管理画面アクセス禁止
/*-----------------------------------------------------------------------------------*/
add_action('init', 'editor_remove_admin_bar');
function editor_remove_admin_bar() {
    if (!current_user_can('manage_options')) {
        show_admin_bar(false);
    }
}


/*-----------------------------------------------------------------------------------*/
//bodyにページスラッグを設定
/*-----------------------------------------------------------------------------------*/

add_filter('body_class', 'add_page_slug_class_name');
function add_page_slug_class_name($classes) {
    if (is_page()) {
        $page = get_post(get_the_ID());
        $classes[] = $page->post_name;
    }
    return $classes;
}

//*-----------------------------------------------------------------------------------*/
//user項目を追加
/*-----------------------------------------------------------------------------------*/
function my_user_meta($o_note) {
    $o_note['user_phone'] = '電話番号';
    $o_note['user_staff'] = '担当スタッフ';
    return $o_note;
}
add_filter('user_contactmethods', 'my_user_meta', 10, 1);

/*-----------------------------------------------------------------------------------*/
//会員登録処理
/*-----------------------------------------------------------------------------------*/

function add_record_post() {
    $client_name = isset($_POST['client_name']) ? sanitize_text_field($_POST['client_name']) : '';
    $user_phone = isset($_POST['user_phone']) ? sanitize_text_field($_POST['user_phone']) : '';
    $gender = isset($_POST['gender']) ? sanitize_text_field($_POST['gender']) : '';
    $post_slug = date("Ymd");

    //必須項目チェック
    if (!$client_name) {
        return false;
    }

    //権限チェック：（例）roleに「user」が割り当てられているかユーザーかどうか
    if (!current_user_can('publish_posts')) {
        return false;
    }

    $args = array(
        'post_type' => 'record',
        'post_title' => $client_name,
        'post_name' => $post_slug,
        'post_content' => '',
        'post_author' => get_current_user_id(),
        'post_status' => 'publish',
        'comment_status' => 'open',
        'meta_input' => array(
            'phone' => $user_phone,
            'gender' => $gender
        )
    );
    $post_id = wp_insert_post($args);
    if (!$post_id) {
        return false;
    }

    //投稿詳細ページにリダイレクト
    wp_safe_redirect(get_the_permalink($post_id));
    exit;
}


/**
 * after_setup_theme に処理をフック
 */
add_action('after_setup_theme', function () {

    if (isset($_POST['my_submit']) && $_POST['my_submit'] === 'signup') {
        if (!isset($_POST['my_nonce_name'])) return;
        if (!wp_verify_nonce($_POST['my_nonce_name'], 'my_nonce_action')) return;
        add_record_post();
    }
});


/*-----------------------------------------------------------------------------------*/
// カルテ保存時に投稿者を変更&画像をアップしたらメディア欄に登録する
/*-----------------------------------------------------------------------------------*/
add_action('acf/save_post', 'custom_acf_save_post', 20);
function custom_acf_save_post($post_id) {
    if (!is_admin()) {
        if (empty($_POST['acf']) || strpos($post_id, 'comment') !== false) {
            return;
        }

        $data['ID'] = $post_id;
        $data['post_author'] = trim($_POST['acf']['field_6200d21f83cb3']);

        //画像処理
        if (!empty($_FILES['attachment'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            $attachment_id = media_handle_upload('attachment', $post_id);
            if (is_wp_error($attachment_id)) {
                // 画像のアップロード中にエラーが起きた。
            } else {
                // 画像のアップロードに成功 !
                $data['post_content'] = wp_get_attachment_image($attachment_id, 'medium');
            }
        }

        wp_update_post($data);
        wp_redirect(get_permalink($post_id));
        exit;
    }
}

/*-----------------------------------------------------------------------------------*/
//ACF文言変更
/*-----------------------------------------------------------------------------------*/
function wd_post_title_acf_name($field) {
    if (is_page('record_form')) {
        $field['label'] = 'タイトル';
        return $field;
    } else {
        return true;
    }
}
add_filter('acf/load_field/name=_post_title', 'wd_post_title_acf_name');

// Modify ACF Form Label for Post Content Field
function wd_post_content_acf_name($field) {
    if (is_page('record_form')) {
        $field['label'] = 'カルテ内容';
        return $field;
    } else {
        return true;
    }
}
add_filter('acf/load_field/name=_post_content', 'wd_post_content_acf_name');

/*-----------------------------------------------------------------------------------*/
//投稿者のドロップダウンリストの表示名を変更
/*-----------------------------------------------------------------------------------*/
add_filter('wp_dropdown_users', 'switch_user_fullname');
function switch_user_fullname($output) {
    global $post;
    $users = get_users(array('meta_key' => 'last_name', 'orderby' => 'meta_value', 'order' => 'ASC', 'role' => 'editor'));
    $output = "<select id=\"sigup_user_staff\" name=\"user_staff\" class=\"\">";
    foreach ($users as $user) {
        $sel = ($post->post_author == $user->ID) ? "selected='selected'" : '';
        $name = get_the_author_meta('last_name', $user->ID) . ' ' . get_the_author_meta('first_name', $user->ID);
        if (empty($name) || $name == ' ') {
            $name = $user->user_login;
        }
        $output .= '<option value="' . $user->ID . '"' . $sel . '>' . $name . '</option>';
    }
    $output .= "</select>";
    return $output;
}

/*-----------------------------------------------------------------------------------*/
//コメントの画像を表示
/*-----------------------------------------------------------------------------------*/
function get_attachment_url_image_comment($comment_id) {
    $meta_key = 'attachment_id';
    $attachment_id = get_comment_meta($comment_id, $meta_key, true);
    $full_img_url = wp_get_attachment_image_url($attachment_id, 'full');
    return $full_img_url;
}

/*-----------------------------------------------------------------------------------*/
// ログインしていない場合ログインページに移動 → header.phpに記述
/*-----------------------------------------------------------------------------------*/
// function require_login() {
//     global $pagenow;
//     if ( ! is_user_logged_in() &&
//         $pagenow !== 'wp-login.php' &&
//         ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) &&
//         ! ( defined( 'DOING_CRON' ) && DOING_CRON ) ) {
//         wp_redirect( home_url('/login/') );
//     }
// }
// add_action( 'init', 'require_login' );

/*-----------------------------------------------------------------------------------*/
// TOPページアクセス制限
/*-----------------------------------------------------------------------------------*/
add_action('template_redirect', 'toppage_access');
function toppage_access() {
    if (is_front_page()) {
        if (is_user_logged_in()) {
            global $current_user;
            $roles = $current_user->roles[0];
            $user_id = $current_user->ID;

            if ($roles === 'author') {
                header('Location: ' . home_url() . '/?author=' . $user_id);
                exit;
            }
        }
    }
}

/*-----------------------------------------------------------------------------------*/
// 投稿者に自分の投稿のみ見えるようにする
/*-----------------------------------------------------------------------------------*/
function show_only_authorpost($query) {
    global $current_user;
    if (is_admin()) {
        if (current_user_can('author')) {
            $query->set('author', $current_user->ID);
        }
    }
}
add_action('pre_get_posts', 'show_only_authorpost');

/*-----------------------------------------------------------------------------------*/
// メディアに自分自身が投稿したファイルしか表示させない処理（投稿者ユーザー向け）
/*-----------------------------------------------------------------------------------*/
function show_only_author_mine_image($where) {
    global $current_user;
    if (is_admin()) {
        if (current_user_can('author')) {
            if (isset($_POST['action']) && ($_POST['action'] == 'query-attachments')) {
                $where .= ' AND post_author=' . $current_user->data->ID;
            }
        }
    }
    return $where;
}
add_filter('posts_where', 'show_only_author_mine_image');

/*-----------------------------------------------------------------------------------*/
// 投稿の1枚めをアイキャッチにする
/*-----------------------------------------------------------------------------------*/
function eyecatch_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $first_img = $matches[1][0];

    if (empty($first_img)) {
        $first_img = get_template_directory_uri() . '/common/img/no-img.png';
    }
    return $first_img;
}

/*-----------------------------------------------------------------------------------*/
// 自作コメントリスト（画像のACFカスタムフィールドを追加）
/*-----------------------------------------------------------------------------------*/
function my_comment_template($comment, $args, $depth) {
?>
    <li <?php comment_class(); ?>>
        <?php
        $img_source = '';
        if (get_field('comment-gallery', $comment)) {
            $img_source .= '<div class="comment-images">';
            $img_list = get_field('comment-gallery', $comment);
            $img_date = array();

            foreach ($img_list as $img) {
                array_push($img_date, $img['date']);
                $img_source .= '<a href="' . $img["sizes"]["1536x1536"] . '" class="image" rel="gallery"><img src="' . $img["sizes"]["medium_large"] . '"></a>';
            }

            $img_source .= '</div>';
            $date = date("Y/m/d",  strtotime(min($img_date)));
            echo '<p class="comment-meta commentmetadata">' . $date . '</p>';
        }
        // echo 'コメント日時:';
        // comment_date();
        comment_text();
        echo $img_source;
        ?>
    </li>
<?php
}

/*-----------------------------------------------------------------------------------*/
// コメント日時変更
/*-----------------------------------------------------------------------------------*/
function save_custom_comment_field($comment_id) {
    if (!$comment = get_comment($comment_id)) return false;
    //comment-nicknameの値の保存
    $custom_key_nickname = 'comment_date';
    $nickname = esc_attr($_POST[$custom_key_nickname]);
    if ('' == get_comment_meta($comment_id, $custom_key_nickname)) {
        add_comment_meta($comment_id, $custom_key_nickname, $nickname, true);
    } else if ($nickname != get_comment_meta($comment_id, $custom_key_nickname)) {
        update_comment_meta($comment_id, $custom_key_nickname, $nickname);
    } else if ('' == $nickname) {
        delete_comment_meta($comment_id, $custom_key_nickname);
    }
    //comment-roleの値の保存
    $custom_key_role = 'comment-role';
    $role = esc_attr($_POST[$custom_key_role]);
    if ('' == get_comment_meta($comment_id, $custom_key_role)) {
        add_comment_meta($comment_id, $custom_key_role, $role, true);
    } else if ($role != get_comment_meta($comment_id, $custom_key_role)) {
        update_comment_meta($comment_id, $custom_key_role, $role);
    } else if ('' == $role) {
        delete_comment_meta($comment_id, $custom_key_role);
    }

    return false;
}
add_action('comment_post', 'save_custom_comment_field');
add_action('edit_comment', 'save_custom_comment_field');

/*-----------------------------------------------------------------------------------*/
// 各投稿ごとのメディア表示(test)
/*-----------------------------------------------------------------------------------*/
function get_only_self_media($query) {
    $user_id = get_current_user_id();
    $post_id = $_POST['post_id'];
    if ($user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts')) {
        $query['post_parent'] = $post_id;
    }
    return $query;
}
add_filter('ajax_query_attachments_args', 'get_only_self_media');

/*-----------------------------------------------------------------------------------*/
// 重複コメント
/*-----------------------------------------------------------------------------------*/
add_filter('duplicate_comment_id', 'customize_duplicate_comment_id', 10, 2);
function customize_duplicate_comment_id($dupe_id, $comment_data) {
    global $wpdb;
    $thisQuery = $wpdb->prepare(
        "SELECT comment_date FROM $wpdb->comments WHERE comment_post_ID = %d AND comment_content = %s ORDER BY comment_ID DESC LIMIT 1",
        wp_unslash($comment_data['comment_post_ID']),
        wp_unslash($comment_data['comment_content'])
    );
    $previoustime = $wpdb->get_var($thisQuery);
    $prevTime = strtotime($previoustime);
    $currentTime = current_time('timestamp');
    $compareTime = $currentTime - $prevTime;
    if ($compareTime > 30) { //10minutes
        return;
    }
    return $dupe_id;
}

/*-----------------------------------------------------------------------------------*/
// コメント削除時に更新
/*-----------------------------------------------------------------------------------*/
// function comment_status_change($comment) {
//             $file_path = __DIR__ . '/test.log';
//             $data = $comment;
//             file_put_contents($file_path, print_r($data, true));
// }
// add_action('sce_save_after', 'comment_status_change', 10, 3);

// add_filter('sce_allow_delete_confirmation', '__return_false');



