<?php get_header(); ?>


<?php if(have_posts()): while(have_posts()):the_post();
$page = get_post( get_the_ID() );
$slug = $page->post_name;
?>

<section class="sec-<?php echo $slug; ?>">

	<div class="sec-inner">

		<div class="sec-headline">
			<h1 class="page-tit <?php echo $slug; ?>-tit text-center"><?php the_title(); ?></h1>
		</div>

		<!--<time datetime="<?php the_time('Y-m-d'); ?>"><?php the_time('Y.m.d'); ?></time>-->
		<p><?php the_content(); ?></p>

	</div>

</section>

<?php endwhile; endif; ?>


<?php get_sidebar(); ?>

<?php get_footer(); ?>​