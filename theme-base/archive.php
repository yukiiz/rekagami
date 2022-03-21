<?php get_header(); ?>
<h1 class="page-title text-center">カルテ一覧</h1>
<article class="archive">
	<div class="archive-inner">
		<!--archive-box-->
		<ul class="archive-box box">
			<?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                            $my_query = new WP_Query(
                                array('paged' => $paged, 'posts_per_page' => 9, 'post_type' => 'custom_entry')
                            );
                            ?>
			<?php if ($my_query->have_posts()) :
                                while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<li class="box-item">
				<a href="<?php the_permalink(); ?>">
					<div class="box-inner">
						<div class="box-img">
							<div class="box-img_headline">
								<?php the_post_thumbnail('full'); ?>
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
		</ul>
		<?php else : //記事が1つも無い場合
        ?>
		<p>現在表示できる記事はありません。</p><br>
		<div class="btn">
			<a href="<?php echo esc_url(home_url()); ?>/">TOPへ戻る</a>
		</div>
		<?php endif; ?>
	</div>
	</div>
</article>
<?php get_footer(); ?>