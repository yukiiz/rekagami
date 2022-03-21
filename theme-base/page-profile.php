<?php get_header(); ?>

<section class="user-area">
	<div class="sec-inner">
		<?php
			$user = wp_get_current_user();
			echo $user->ID; //ユーザーID
			echo $user->user_login; //ログインID
			?>
		<?php echo do_shortcode('[wpmem_profile]'); ?>
	</div>
</section>
<?php
$page = get_post( get_the_ID() );
$slug = $page->post_name;
?>
<section class="sec-<?php echo $slug; ?>">
	<div class="sec-inner">
		<div class="link">
			<a href="<?php echo get_author_posts_url( $user->ID ); ?>">私のカルテ一覧</a>
		</div>
		<div class="sec-headline">
			<h1 class="page-tit <?php echo $slug; ?>-tit text-center"><?php the_title(); ?></h1>
		</div>
		<?php echo do_shortcode('[wpuf_form id="67"]'); ?>
	</div>
</section>

<?php get_sidebar(); ?>
<?php get_footer(); ?>​​