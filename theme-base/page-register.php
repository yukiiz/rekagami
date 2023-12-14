<?php
/*
Template Name: register Frontend Form
*/
?>
<?php if (!is_user_logged_in()) auth_redirect(); ?>
<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
	<?php
	$page = get_post(get_the_ID());
	$slug = $page->post_name;
	?>
	<section class="sec-<?php echo $slug; ?>">
		<div class="sec-inner">
			<div class="sec-headline">
				<h1 class="page-tit text-center">お客様登録</h1>
			</div>
			<!-- Registration -->
			<div id="register-form" class="form-box">
				<form class="my_form" name="my_signup_form" id="my_signup_form" action="" method="post">
					<div class="signup_item">
						<label for="signup_client_name">お客様氏名</label>
						<input id="signup_client_name" name="client_name" type="text" class="regular_text" placeholder="例）東京　太郎" required>
					</div>
					<div class="signup_item">
						<label for="signup_last_name">性別</label>
						<div class="signup_choice_box">
							<label>男<input name="gender" type="radio" value="男"></label>
							<label>女<input name="gender" type="radio" value="女"></label>
							<label>その他<input name="gender" type="radio" value="その他"></label>
						</div>
					</div>
					<div class="signup_item">
						<label for="signup_user_phone">電話番号</label>
						<input id="signup_user_phone" name="user_phone" type="tel" placeholder="例）00000000000" class="input" />
					</div>
					<div class="form-submit">
						<button type="submit" name="my_submit" class="my_submit_btn btn-block" value="signup">お客様登録</button>
					</div>
					<?php wp_nonce_field('my_nonce_action', 'my_nonce_name'); ?>
				</form>
			</div>
		</div><!-- /Registration -->
	</section>
<?php endwhile; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>