<?php get_header(); ?>


<?php if(have_posts()): while(have_posts()):the_post(); ?>

<section class="sec-<?php echo $slug; ?>">

	<div class="sec-inner">

		<div class="sec-headline">
			<h1 class="page-tit <?php echo $slug; ?>-tit text-center"><?php the_title(); ?></h1>
		</div>

		<!--<time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>-->
		<?php get_the_content(); ?>

	</div>

</section>

<?php endwhile; endif; ?>


<?php get_sidebar(); ?>

<?php get_footer(); ?>