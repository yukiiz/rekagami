<?php
//is_user_logged_in でユーザーがログイン済みか判断する
if (!is_user_logged_in()) {
	//未ログインの場合、auth_redirect() でログインページにリダイレクト
	wp_redirect( home_url('/login/') );
}
?>
<?php get_header(); ?>


<?php $userId = get_query_var('author'); ?>
<?php $user = get_userdata($userId); ?>

<article class="archive">
	<div class="archive-inner">
		<section class="sec-author">
			<div class="sec-inner">
				<div class="sec-headline">
					<h1 class="user-name text-center"><?php echo $user->display_name; ?></h1>
					<h2 class="page-title text-center">カルテ一覧</h2>
				</div>
				<?php if (!empty($user->description)) { ?>
				<p><?php echo $user->description; ?></p>
				<?php } ?>
				<?php
				$author_args = array(
					'post_type' => 'record',
					'post_status' => 'publish',
					'posts_per_page' => -1,
					'author' => $user->ID,
				);
				?>
				<?php $author_wp_query = new WP_Query($author_args);
				if ($author_wp_query->have_posts()) { ?>
				<!--archive-box-->
				<ul class="archive-box box">
					<?php while ($author_wp_query->have_posts()) : $author_wp_query->the_post(); ?>
					<li class="box-item">
						<a href="<?php the_permalink(); ?>">
							<div class="box-inner">
								<div class="box-img">
									<div class="box-img_headline">
										<img src="<?php echo eyecatch_image(); ?>">
									</div>
								</div>
								<div class="box-mask"></div>
							</div>
							<div class="box-tit">
								<p class="box-date"><?php the_time('Y.m.d');?></p>
								<p class="bold">
									<?php
										if(mb_strlen($post->post_title, 'UTF-8')>55){
											$title= mb_substr($post->post_title, 0, 55, 'UTF-8');
											echo $title.'……';
										}else{
											echo $post->post_title;
										}
									?></p>
							</div>
						</a>
					</li>

					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</ul>
				<?php } ?>
			</div>
		</section>
	</div>
</article>
<section class="user-area">
	<div class="sec-inner">
		<?php if(current_user_can('author')){ ?>
			<a href="<?php echo home_url('/change_password/');?>" class="btn-block">パスワード変更</a>
		<?php } else { //管理者、編集者の場合カルテ新規作成ボタンを表示 ?>
			<a href="<?php echo esc_url(home_url('/record_form/?author_id='.$userId));?>" class="btn-block">カルテを作成する</a>
		<?php } ?>
	</div>
</section>

<?php get_footer(); ?>