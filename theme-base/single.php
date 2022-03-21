<?php
//is_user_logged_in でユーザーがログイン済みか判断する
if (!is_user_logged_in()) {
//未ログインの場合、auth_redirect() でログインページにリダイレクト
wp_redirect( home_url('/login/') );
}
?>
<?php get_header(); ?>

<?php
$user = wp_get_current_user();
// echo $user->ID; //ユーザーID
// echo $user->user_login; //ログインID
// echo $user->display_name; //氏名
?>
<section class="sec-single">
	<div class="sec-inner">

		<div class="sec-headline">
			<h1 class="page-tit text-center">カルテ詳細</h1>
		</div>

		<div class="user-name">
			<?php
			$terms = get_the_terms($post->ID, 'stylist');
			if ($terms) {
			    foreach ( $terms as $term ) {
			        $stylist_name = $term->name;
			    }
			}
			$author = get_userdata($post->post_author);
			echo '<p>お客様氏名：' . $author->last_name.' '.$author->first_name;
			echo "<br />";
			echo "カルテ作成者：" . $stylist_name;
			echo "</p>";
			$author2 = get_field('author');
			?>
		</div>
		<?php
			if($user->ID==$author2 || current_user_can('administrator') || current_user_can('editor')){//作成者と現在のログイン者が同じ場合と管理者と編集者のみ
		?>
		<?php // ブログ記事を表示する start ?>
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<?php if($user->ID==$author2){//作成者と現在のログイン者が同じ場合と管理者のみ ?>
		<div class="link">
			<a href="<?php echo get_author_posts_url( $user->ID ); ?>">私のカルテ一覧</a>
		</div>
		<?php } ?>

		<?php // タイトルを表示する start ?>
		<h2 class="blog-detail__title text-center"><?php the_title(); ?></h2>
		<?php // タイトルを表示する end ?>

		<?php endwhile;
		endif; ?>
		<?php comments_template(); ?>

		<?php // カルテ作成時の画像を表示する start ?>
		<div class="blog-detail__body">
			<div class="blog-content">
				<div class="blog-content-img"><?php the_content(); ?></div>
				<div class="blog-content-txt"><?php the_field('content'); ?></div>
			</div>
		</div>
		<?php // カルテ作成時の画像を表示する end ?>

		<?php } else { ?>
		このアカウントでは閲覧できません。
		<?php } ?>
		<?php // ブログ記事を表示する end ?>
	</div>
</section>
<section class="user-area">
	<div class="sec-inner">
		<a href="<?php echo esc_url(home_url('/change_password/'));?>" class="btn-block">パスワード変更</a>
	</div>
</section>
<?php get_footer(); ?>