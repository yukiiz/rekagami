<?php
/*
Template Name: register thanks
*/
?>
<?php get_header(); ?>
<?php if(have_posts()): while(have_posts()):the_post();
$page = get_post( get_the_ID() );
$slug = $page->post_name;
?>

<section class="sec-<?php echo $slug; ?>">
	<div class="sec-inner">
		<div class="sec-headline">
			<h1 class="page-tit text-center"><?php the_title(); ?></h1>
		</div>
		<!-- contents -->
		<?php
		$author_id = $_GET['author_id'];
		?>
		<div class="text-center">
			<div class="btn-block">
				<a href="<?php echo esc_url(home_url('/record_form/?author_id='.$author_id));?>">カルテを作成する</a>
			</div>
		</div>
	</div><!-- /contents -->
</section>
<?php endwhile; endif; ?>

<?php get_sidebar(); ?>
<?php get_footer(); ?>