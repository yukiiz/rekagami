<?php
/*
Template Name: register Frontend Form
*/
?>
<?php if (!is_user_logged_in()) auth_redirect(); ?>
<?php get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
<?php
$page = get_post( get_the_ID() );
$slug = $page->post_name;
?>
<section class="sec-<?php echo $slug; ?>">
	<div class="sec-inner">
		<div class="sec-headline">
			<h1 class="page-tit text-center">お客様登録</h1>
		</div>
		<!-- Registration -->
		<div id="register-form">
			<form class="my_form" name="my_signup_form" id="my_signup_form" action="" method="post">
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
<?php endwhile; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>