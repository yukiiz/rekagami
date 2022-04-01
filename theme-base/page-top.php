<?php
/*
Template Name: トップページ
*/
?>
<?php get_header(); ?>
<div id="overlay">
	<div class="cv-spinner">
		<span class="spinner"></span>
	</div>
</div>
<section class="top-mv mv">
	<ul class="slider">
		<li><img src="<?php echo get_template_directory_uri(); ?>/common/img/top-mv01.jpg" alt="イメージ" /></li>
		<li><img src="<?php echo get_template_directory_uri(); ?>/common/img/top-mv01.jpg" alt="イメージ" /></li>
		<li><img src="<?php echo get_template_directory_uri(); ?>/common/img/top-mv01.jpg" alt="イメージ" /></li>
	</ul>
	<div class="mv-block">
		<div class="mv-btn"><a href="<?php echo esc_url(home_url('/register/'));?>">お客様の登録</a></div>
	</div>
</section>
<section class="top-sec01 member">
	<div class="sec-inner">
		<div class="sec-headline">
			<div class="left">
				<h1 class="sec-tit member-tit">お客様一覧</h1>
			</div>
			<div class="right">
				<div class="search-area">
					<div id='search-clear' class='input-group-prepend'>
						<button class='input-group-text fas fa-trash-alt'></button>
					</div>
					<input class="member-search" type="text" id="search-text" placeholder="顧客番号・お客様氏名・担当美容師を入力">
					<div id='search-submit' class='search-submit input-group-append'>
						<button class='input-group-text fas fa-search'></button>
					</div>
				</div>
				<form>
					<input class="staff-search" type="search" id="search-select" list="keyword-list" name="search" placeholder="担当美容師名でソート" />
					<datalist id="keyword-list">
						<?php
						$taxonomy_slug = 'stylist'; // タクソノミーのスラッグを指定
						$terms = get_terms($taxonomy_slug); // タームの取得
						if( $terms && !is_wp_error($terms) ){ // タームがあれば表示
						foreach ( $terms as $value ) { // 配列の繰り返し
						?>
						<select name="name">
							<option value="<?php echo esc_html($value->name) ; ?>">
								<?php }} ?>
						</select>
					</datalist>
				</form>
			</div>
		</div>
		<!--投稿者一覧を表示-->
		<?php $users = get_users( array('orderby'=>'ID','order'=>'DESC','role' =>'author' ) ); ?>
		<div class="authors">
			<ul class="member-box box target-area">
				<?php foreach($users as $user) {
				$uid = $user->ID;
				$uid_author = get_field('user', 'user_' . $uid);
				// $image = get_the_author_meta( 'user_img', $uid );
				// $image_url = wp_get_attachment_url(get_the_author_meta( 'user_img', $uid ));
				$image_url = '';
				$args = array(
					'post_type'       => 'record',
					'author'	    => $uid,
					'posts_per_page'     => 1,
				);
				// echo '<pre>';
				// var_dump(get_posts( $args ));
				// echo '</pre>';
				// echo eyecatch_image();
				$myposts = get_posts( $args );
				if(!empty($myposts)){
					foreach( $myposts as $post ) {
						setup_postdata($post);
						$image_url = eyecatch_image();
						$terms = get_the_terms($post->ID, 'stylist');
						$staff = $terms[0]->name;
						$staff_id = $terms[0]->term_id;
					}
				} else{
					$staff = '';
					$staff_id = '';
				} ?>
				<li class="box-item">
					<a href="<?php echo get_bloginfo("url") . '/?author=' . $uid ?>">
						<p class="box-img">
							<?php if($image_url == "" ) { ?>
							<img src="<?php echo get_template_directory_uri(); ?>/common/img/no-img.png">
							<?php } else { ?>
							<img src="<?php echo $image_url; ?>" alt="">
							<?php } ?>
						</p>
						<div class="box-dec">
							<p class="box-member"><span style="display:none;"><?php echo $user->user_login;?></span><?php echo $user->last_name ; ?>　<?php echo $user->first_name ; ?></p>
							<p class="box-staff"><span style="display:none;"><?php echo $staff_id;?></span><?php echo $staff ; ?></p>
						</div>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
</section>
<?php get_footer(); ?>