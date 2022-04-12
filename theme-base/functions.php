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

add_action( 'after_setup_theme', 'register_menu' );
function register_menu() {
  register_nav_menu( 'primary', __( 'Primary Menu', 'theme-slug' ) );
}

/*-----------------------------------------------------------------------------------*/
// jQueryの読み込み無効
/*-----------------------------------------------------------------------------------*/
function delete_local_jquery() {
    wp_deregister_script('jquery');
    wp_enqueue_script('jquery', get_template_directory_uri().'/common/js/jquery-3.6.0.min.js', array(), '3.6.0');
}
add_action( 'wp_enqueue_scripts', 'delete_local_jquery' );

/*-----------------------------------------------------------------------------------*/
//ログイン後の遷移先
/*-----------------------------------------------------------------------------------*/
// function my_login_redirect( $redirect_to, $user_id ) {
// 	if ( current_user_can( 'editor' ) ) {
// 	return '/';
// }else{
// 	return '../?author='.$user_id;
// }
// }
// add_filter( 'wpmem_login_redirect', 'my_login_redirect', 10, 2 );

function change_login_redirect($redirect_to, $request_redirect_to, $current_user) {
    $roles = $current_user->roles[0];
    $user_id = $current_user->ID;
    if ( $roles === 'editor' ) {
        return home_url();
    } elseif ( $roles === 'author' ){
        return '/?author='.$user_id;
    } else{
        return admin_url();
    }
}
add_filter('login_redirect', 'change_login_redirect', 100, 3);


/*-----------------------------------------------------------------------------------*/
// 管理者以外はadminバー非表示・管理画面アクセス禁止
/*-----------------------------------------------------------------------------------*/
add_action( 'init', 'editor_remove_admin_bar' );
function editor_remove_admin_bar() {
    if ( ! current_user_can( 'manage_options' ) ) {
        show_admin_bar( false );
    }
}


/*-----------------------------------------------------------------------------------*/
//wp-memberプレースホルダー
/*-----------------------------------------------------------------------------------*/

add_filter( 'wpmem_register_form_rows', 'my_register_form_rows_filter', 10, 2 );
function my_register_form_rows_filter( $rows, $toggle ) {
    $rows['username']['field'] = '<input name="username" type="text" id="username" value="" class="username" placeholder="姓名（必須）">';
    $rows['staff_name']['field'] = '<input name="staff_name" type="text" id="staff_name" value="" class="textbox" placeholder="担当美容師">';
    return $rows;
}

/*-----------------------------------------------------------------------------------*/
//wp-member文言変更
/*-----------------------------------------------------------------------------------*/

add_filter( 'wpmem_default_text_strings', function($text) {
    $text = array(
        'login_username' => __( 'ID', 'wp-members' ),
    );
    return $text;
});

/*-----------------------------------------------------------------------------------*/
//bodyにページスラッグを設定
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class', 'add_page_slug_class_name' );
function add_page_slug_class_name( $classes ) {
  if ( is_page() ) {
    $page = get_post( get_the_ID() );
    $classes[] = $page->post_name;
  }
  return $classes;
}

//*-----------------------------------------------------------------------------------*/
//user項目を追加
/*-----------------------------------------------------------------------------------*/
function my_user_meta($o_note)
{
	$o_note['user_phone'] = '電話番号';
	$o_note['user_staff'] = '担当スタッフ';
	return $o_note;
}
add_filter('user_contactmethods', 'my_user_meta', 10, 1);

/*-----------------------------------------------------------------------------------*/
//会員登録処理
/*-----------------------------------------------------------------------------------*/
add_action( 'user_register', 'wedevs_save_data' );
function wedevs_save_data( $user_id ) {
	if ( ! empty( $_POST['last_name'] ) ) {
		update_user_meta( $user_id, 'last_name', trim( $_POST['last_name'] ) ) ;
	}
	if ( ! empty( $_POST['first_name'] ) ) {
		update_user_meta( $user_id, 'first_name', trim( $_POST['first_name'] ) );
	}
	if ( ! empty( $_POST['user_phone'] ) ) {
		update_user_meta( $user_id, 'user_phone', trim( $_POST['user_phone'] ) ) ;
	}
	if ( ! empty( $_POST['user_staff'] ) ) {
		update_user_meta( $user_id, 'user_staff', trim( $_POST['user_staff'] ) );
	}
}
function my_user_signup() {
	$user_last_name  = isset( $_POST['last_name'] ) ? sanitize_text_field( $_POST['last_name'] ) : '';
    $user_first_name  = isset( $_POST['first_name'] ) ? sanitize_text_field( $_POST['first_name'] ) : '';
    $user_name  = generate_username();
    $user_pass  = wp_generate_password();
    $user_email = isset( $_POST['user_email'] ) ? sanitize_text_field( $_POST['user_email'] ) : '';
    $user_phone = isset( $_POST['user_phone'] ) ? sanitize_text_field( $_POST['user_phone'] ) : '';
    $user_staff = isset( $_POST['user_staff'] ) ? sanitize_text_field( $_POST['user_staff'] ) : '';
	$error = array();
	$error = new WP_Error();
    if ( empty( $user_email ) ) {
        echo '情報が不足しています。';
    }
    $user_id = username_exists( $user_name );
    if ( $user_id !== false ) {
			$error->add('error', '※すでにユーザー名「'. $user_name .'」は登録されています');
    }
    $user_id = email_exists( $user_email );
    if ( $user_id !== false ) {
			$error->add('error', '※すでにメールアドレス「'. $user_email .'」は登録されています');
    }
    if ($error->get_error_code()) {

    get_header(); ?>
<section class="sec-register">
	<div class="sec-inner">
		<div class="sec-headline">
			<h1 class="page-tit text-center">お客様登録</h1>
		</div>
		<!-- Registration -->
		<div id="register-form">
			<form class="my_form" name="my_signup_form" id="my_signup_form" action="" method="post">
				<?php
    				if ($error->get_error_codes()) {
    					echo "<div class='error'>";
    					echo "<ul>";
    					foreach ($error->get_error_messages() as $value) {
    					echo "<li>" . esc_html($value) . "</li>";
    					}
    					echo "</ul>";
    					echo "</div>";
    				}
    				?>
				<label for="signup_last_name">姓</label>
				<input id="signup_last_name" name="last_name" type="text" class="regular_text" placeholder="例）東京" />
				<label for="signup_first_name">名</label>
				<input id="signup_first_name" name="first_name" type="text" class="regular_text" placeholder="例）太郎" />
				<!-- <label for="signup_user_name">顧客番号</label>
                    <input id="signup_user_name" name="user_name" type="text" required placeholder="半角英数字で入力"> -->
				<label for="signup_email">メールアドレス</label>
				<input id="signup_email" name="user_email" type="email" required placeholder="例）tokyo@test.mail">
				<label for="signup_user_phone">電話番号</label>
				<input id="signup_user_phone" name="user_phone" type="tel" placeholder="例）00000000000" class="input" />
				<div class="form-submit">
					<button type="submit" name="my_submit" class="my_submit_btn btn-block" value="signup">会員登録</button>
				</div>
				<?php wp_nonce_field( 'my_nonce_action', 'my_nonce_name' );?>
			</form>
		</div>
	</div><!-- /Registration -->
</section>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
<?php }

    $userdata = array(
        'last_name' => $user_last_name,       //  姓
        'first_name'  => $user_first_name,       //  名
        'user_login' => $user_name,       //  ログイン名
        'user_pass'  => $user_pass,       //  パスワード
        'user_email' => $user_email,      //  メールアドレス
        'user_phone' => $user_phone,      //  電話番号
        'user_staff' => $user_staff,      //  担当スタッフ
    );
    $user_id = wp_insert_user( $userdata );
    if ( is_wp_error( $user_id ) ) {
        echo $user_id -> get_error_code();
        echo $user_id -> get_error_message();
        exit;
    } else{
        wp_redirect(home_url('/register/register-thanks/?author_id='.$user_id));

        //新規投稿者にメール送信
        $to = $user_email;
        $user_fullname = $user_last_name.' '.$user_first_name;
        $subject = $user_fullname.'様のユーザーデータが登録されました';
        $message = $user_fullname.'様'."\r\n".
        'ご来店ありがとうございました。'."\r\n".
        $user_fullname.'様のユーザーデータを作成いたしました。'."\r\n\r\n".
        'ログインID：'.$user_email."\r\n".
        'パスワード：'.$user_pass."\r\n\r\n".
        'ログインページ：'."\r\n".esc_url(home_url('/login/'));
        $headers = 'From: TEKAGAMI <tekagami@example.jp>';
        wp_mail($to, $subject, $message, $headers);
    }
    exit;
    return;
}
/**
 * after_setup_theme に処理をフック
 */
add_action('after_setup_theme', function() {
    if ( isset( $_POST['my_submit'] ) && $_POST['my_submit'] === 'signup') {
        if ( !isset( $_POST['my_nonce_name'] ) ) return;
        if ( !wp_verify_nonce( $_POST['my_nonce_name'], 'my_nonce_action' ) ) return;
        my_user_signup();
    }
});
/**
 * ユーザーID自動生成
 */
function generate_username( $prefix = 'user_' ){
    $user_exists = 1;
    do {
        $rnd_str = sprintf("%06d", mt_rand(1, 999999));
        $user_exists = username_exists( $prefix . $rnd_str );
    } while( $user_exists > 0 );
    return $prefix . $rnd_str;
}

/**
 * ユーザー追加
 */
// function auto_post($user_id) {
// 	$user_info = get_userdata($user_id);
// 	$user_name = $user_info->last_name . ' ' . $user_info->first_name;
// 	$login_id = $user_info->user_login;
//     // $date = $user_info-> userdata;
// 	// $user_slug = $user_info->user_login. '-' .$date;
// 	// $date = $user_info-> userdata('Y年m月d日');
// 	// $my_post = array();
// 	// $my_post['post_name'] = $user_info->user_login. '-' .$date;
// 	// $my_post['post_title'] = $user_name.'様';
// 	// $my_post['post_content'] = $user_name.'様<br>ご来店ありがとうございました。<br>'.$user_name.'様のカルテを作成いたしました。';
// 	// $my_post['post_status'] = 'publish';
// 	// $my_post['post_type'] = 'record';
// 	// $my_post['comment_status'] = 'open';
// 	// $post_id = wp_insert_post( $my_post );
// 	// if($post_id){
// 	// 	update_post_meta($post_id, 'user', $user_id);
// 	// }
// 	wp_redirect(home_url('/register/register-thanks/'));

//     //新規投稿者にメール送信
//     // $to = $user_info->user_email;
//     // $subject = $user_name.'様のユーザーデータが登録されました';
//     // $message = $user_name.'様'."\r\n".
//     // 'ご来店ありがとうございました。'."\r\n".
//     // $user_name.'様のユーザーデータを作成いたしました。'."\r\n\r\n".
//     // 'ログインID：'.$login_id."\r\n".
//     // 'パスワード：'."\r\n\r\n".
//     // '※メール文面です。適宜変更してください。';
//     // $headers = 'From: TEKAGAMI <tekagami@example.jp>';
//     // wp_mail($to, $subject, $message, $headers);
// }
// add_action('user_register', 'auto_post' );


/*-----------------------------------------------------------------------------------*/
// カルテ保存時に投稿者を変更&画像をアップしたらメディア欄に登録する
/*-----------------------------------------------------------------------------------*/
add_action('acf/save_post', 'custom_acf_save_post', 20);
function custom_acf_save_post( $post_id ) {
    if ( ! is_admin() ) {
        if( empty($_POST['acf']) ) {
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
            if ( is_wp_error( $attachment_id ) ) {
                // 画像のアップロード中にエラーが起きた。
            } else {
                // 画像のアップロードに成功 !
                $data['post_content'] = wp_get_attachment_image($attachment_id, 'medium');
            }
        }

        wp_update_post( $data );
        wp_redirect(get_permalink($post_id));
        exit;
    }
}

/*-----------------------------------------------------------------------------------*/
//ACF文言変更
/*-----------------------------------------------------------------------------------*/
function wd_post_title_acf_name( $field ) {
    if( is_page('record_form') ) {
        $field['label'] = 'タイトル';
        return $field;
    } else{
        return true;
    }
}
add_filter('acf/load_field/name=_post_title', 'wd_post_title_acf_name');

// Modify ACF Form Label for Post Content Field
function wd_post_content_acf_name( $field ) {
    if( is_page('record_form') ) {
        $field['label'] = 'カルテ内容';
        return $field;
    } else{
        return true;
    }
}
add_filter('acf/load_field/name=_post_content', 'wd_post_content_acf_name');

/*-----------------------------------------------------------------------------------*/
//投稿者のドロップダウンリストの表示名を変更
/*-----------------------------------------------------------------------------------*/
add_filter('wp_dropdown_users', 'switch_user_fullname');
function switch_user_fullname($output)
{
  global $post;
  $users = get_users( array( 'meta_key'=> 'last_name','orderby' => 'meta_value' ,'order' => 'ASC' ,'role' => 'editor') );
  $output = "<select id=\"sigup_user_staff\" name=\"user_staff\" class=\"\">";
  foreach($users as $user)
  {
    $sel = ($post->post_author == $user->ID)?"selected='selected'":'';
    $name = get_the_author_meta('last_name',$user->ID).' '.get_the_author_meta('first_name',$user->ID);
    if(empty($name) || $name == ' ') {
      $name = $user->user_login;
    }
    $output .= '<option value="'.$user->ID.'"'.$sel.'>'.$name.'</option>';
  }
  $output .= "</select>";
  return $output;
}

/*-----------------------------------------------------------------------------------*/
//コメントの画像を表示
/*-----------------------------------------------------------------------------------*/
function get_attachment_url_image_comment($comment_id) {
    $meta_key = 'attachment_id';
    $attachment_id = get_comment_meta( $comment_id, $meta_key, true );
    $full_img_url = wp_get_attachment_image_url( $attachment_id, 'full' );
    return $full_img_url;
}

/*-----------------------------------------------------------------------------------*/
//コメントされたら通知
/*-----------------------------------------------------------------------------------*/
function send_email_after_comment($comment_id) {
    global $post;
    $comment = get_comment($comment_id);
    $post_id = $comment->comment_post_ID;

    //コメント主の情報
    $comment_user_ID = $comment->user_id;
    $comment_user_info = get_userdata($comment_user_ID);
    $comment_user_roles = $comment_user_info->roles[0];
    $comment_user_fullname = $comment_user_info->last_name.' '.$comment_user_info->first_name;

    //投稿主(author)の情報
    $post = get_post($post_id);
    $author_ID = $post->post_author;
    $author_info = get_userdata($author_ID);
    $author_fullname = $author_info->last_name.' '.$author_info->first_name;

    //美容師名
    $terms = get_the_terms($post->ID, 'stylist');
    if ($terms) {
        foreach ( $terms as $term ) {
            $stylist_name = $term->name;
            $stylist_email = get_field('email', $term);
        }
    }

    if($comment_user_roles === 'author'){ //コメントした人が投稿者の場合、編集者にメール送信
        if($stylist_email){ //美容師のメールが設定されていなければ管理者に送信
            $to = $stylist_email;
        } else{
            $to = get_bloginfo('admin_email');
        }
        $subject = $comment_user_fullname.'様からコメントがありました [担当美容師:'.$stylist_name.']';
        $message = $stylist_name.'さん'."\r\n\r\n";
        $message .= $comment_user_fullname.'様からコメントがありました。'."\r\n\r\n";
        $message .= '下記URLからご確認ください。'."\r\n";
        $message .= esc_url(home_url()).'?p='.$post_id."\r\n";
    } else {  //コメントした人が投稿者以外の場合、投稿者にメール送信
        $to = $author_info->user_email; //お客様
        $subject = '担当美容師からコメントがありました';
        $message = $author_fullname.'様'."\r\n\r\n";
        $message .= '担当美容師からコメントがありました。'."\r\n\r\n";
        $message .= '下記URLからご確認ください。'."\r\n";
        $message .= esc_url(home_url()).'?p='.$post_id."\r\n";
    }

    $headers = 'From: TEKAGAMI <tekagami@example.jp>';
    wp_mail($to, $subject, $message, $headers);
}
add_action('comment_post', 'send_email_after_comment');

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
add_action( 'template_redirect', 'toppage_access' );
function toppage_access() {
    if(is_front_page()){
        if ( is_user_logged_in() ) {
            global $current_user;
            $roles = $current_user->roles[0];
            $user_id = $current_user->ID;

            if ( $roles === 'author' ){
                header( 'Location: '.home_url().'/?author='.$user_id );
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
    if(is_admin()){
        if(current_user_can('author') ){
            $query->set('author', $current_user->ID);
        }
    }
}
add_action('pre_get_posts', 'show_only_authorpost');

/*-----------------------------------------------------------------------------------*/
// メディアに自分自身が投稿したファイルしか表示させない処理（投稿者ユーザー向け）
/*-----------------------------------------------------------------------------------*/
function show_only_author_mine_image( $where ){
	global $current_user;
	if(is_admin()){
		if(current_user_can('author') ){
			if( isset( $_POST['action'] ) && ( $_POST['action'] == 'query-attachments' ) ){
				$where .= ' AND post_author='.$current_user->data->ID;
			}
		}
	}
	return $where;
}
add_filter( 'posts_where', 'show_only_author_mine_image' );

/*-----------------------------------------------------------------------------------*/
// 投稿者に自分の投稿についたコメントのみ見えるようにする
/*-----------------------------------------------------------------------------------*/
function exclude_other_comments( $wp_comment_query ) {
	if ( is_admin() && !current_user_can( 'administrator' ) ) {
		$user = wp_get_current_user();
		$wp_comment_query->query_vars[ 'post_author' ] = $user->ID;
	}
}
add_action( 'pre_get_comments', 'exclude_other_comments' );

/*-----------------------------------------------------------------------------------*/
// 投稿の1枚めをアイキャッチにする
/*-----------------------------------------------------------------------------------*/
function eyecatch_image() {
    global $post, $posts;
    $first_img = '';
    ob_start();
    ob_end_clean();
    $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
    $first_img = $matches [1] [0];

    if(empty($first_img)){
        $first_img = get_template_directory_uri().'/common/img/no-img.png';
    }
    return $first_img;
}

/*-----------------------------------------------------------------------------------*/
// 自作コメントリスト（画像のACFカスタムフィールドを追加）
/*-----------------------------------------------------------------------------------*/
function my_comment_template( $comment, $args, $depth ) {
	$user = $comment->user_id;
	$user_meta = get_userdata($user);
$user_roles = $user_meta -> roles;
$bypostauthor = '';
if ( in_array( 'author', $user_roles, true ) ) {$bypostauthor = 'bypostauthor';}
	?>
<li <?php comment_class($bypostauthor); ?>>
	<?php comment_author(); ?>
	<p class="comment-meta commentmetadata"><?php echo comment_date(); ?><?php comment_time(); ?></p>
	<?php comment_text(); ?>
	<?php if (get_field('comment-images',$comment)) : ?>
	<?php while (the_repeater_field('comment-images',$comment)) : ?>
	<img src="<?php the_sub_field('comment-img',$comment); ?>">
	<?php endwhile; ?>
	<?php endif; ?>
</li>
<?php
}