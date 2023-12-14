<?php get_header(); ?>


<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

		<section class="sec-<?php echo $slug; ?>">

			<div class="sec-inner">

				<div class="sec-headline">
					<h1 class="page-tit <?php echo $slug; ?>-tit text-center"><?php the_title(); ?></h1>
				</div>


				<div class="form-box">
					<form method="post" action="<?php echo wp_login_url(); ?>?redirect_to=<?php echo esc_attr(home_url('/')); ?>">
						<div class="signup_item">
							<label for="signup_user_phone">ユーザー名</label>
							<input type="text" name="log" id="login_username" value="" />
						</div>
						<div class="signup_item">
							<label for="signup_user_phone">パスワード</label>
							<input type="password" name="pwd" id="login_password" value="" />
						</div>
						<div class="form-submit">
							<button type="submit" name="" class="my_submit_btn btn-block">ログイン</button>
						</div>
					</form>
				</div>


			</div>

		</section>

<?php endwhile;
endif; ?>


<?php get_sidebar(); ?>

<?php get_footer(); ?>