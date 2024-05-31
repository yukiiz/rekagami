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
					<input class="member-search" type="text" id="search-text" placeholder="お客様氏名を入力">
					<div id='search-submit' class='search-submit input-group-append'>
						<button class='input-group-text fas fa-search'></button>
					</div>
				</div>
				<!-- <form>
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
				</form> -->
			</div>
		</div>
		<!--投稿者一覧を表示-->
		<?php $users = get_users( array('orderby'=>'ID','order'=>'DESC','role' =>'author' ) ); ?>
		<div class="authors">
			<ul class="member-box box target-area">
				<?php
				//Coming Soonの記事を取得
				$args = array(
					'post_type' => 'record',
					'post_status' => 'publish',
					'posts_per_page' => -1,
				);
				$wp_query = new WP_Query($args);
				if ($wp_query->have_posts()) {
					while ($wp_query->have_posts()) {
						$wp_query->the_post();
						$image_url = get_template_directory_uri() . '/common/img/no-img.png';
						$permalink = get_permalink();
						$title = get_the_title();

						//現在のクエリを一旦保存&リセット
						$temp = $wp_query;
						$wp_query = null;

						//サムネ用画像取得
						$args = array(
							'post_type' => 'visit',
							'post_status' => 'publish',
							'orderby' => 'meta_value',
							'meta_key'      => 'visit_date',
							'posts_per_page' => 1,
							'meta_query' => array(
								array(
									'key' => 'visit_karte',
									'value' => $post->ID,
									'compare' => 'LIKE',
								),
							)
						);
						$wp_query = new WP_Query($args);
						if ($wp_query->have_posts()) {
							while ($wp_query->have_posts()) {
								$wp_query->the_post();
								$image = get_field('visit_image');
								if (isset($image)) {
									$image_url = $image[0]['sizes']['medium_large'];
								}
							}
						}
						$wp_query = null;
						$wp_query = $temp;

						echo '<li class="box-item">
							<a href="'.$permalink.'">
								<p class="box-img">
									<img src="'.$image_url.'" alt="">
								</p>
								<div class="box-dec">
									<p class="box-member">'.$title.'</p>
								</div>
							</a>
						</li>';
					}
					wp_reset_query();
				} ?>
			</ul>
		</div>
	</div>
</section>
<?php get_footer(); ?>