<?php
//is_user_logged_in でユーザーがログイン済みか判断する
if (!is_user_logged_in()) {
	//未ログインの場合、auth_redirect() でログインページにリダイレクト
	wp_redirect(home_url('/login/'));
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

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<div class="sec-headline">
				<h1 class="page-tit text-center"><?php the_title(); ?>様　カルテ詳細</h1>
			</div>
			<div class="user-name">
				<p class="link"><a href="#respond">▼ 来店記録の登録</a></p>
			</div>


			<?php comments_template(); ?>

			<?php // カルテ作成時の画像を表示する start
			?>
			<div class="blog-detail__body">
				<div class="blog-content">
					<div class="blog-content-img"><?php the_content(); ?></div>
					<div class="blog-content-txt"><?php the_field('content'); ?></div>
				</div>
			</div>
			<?php // カルテ作成時の画像を表示する end
			?>

		<?php endwhile;
		endif; ?>
	</div>
</section>
<section class="user-area">
	<div class="sec-inner">
		<!-- <a href="<?php echo esc_url(home_url('/change_password/')); ?>" class="btn-block">パスワード変更</a> -->
	</div>
</section>
<?php get_footer(); ?>