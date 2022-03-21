<?php
/*
Template Name: record Frontend Form
*/
?>

<?php acf_form_head(); ?>
<?php get_header(); ?>

<section class="user-area">
	<div class="sec-inner">
		<?php
			// $user = wp_get_current_user();
			// echo $user->ID; //ユーザーID
			// echo $user->user_login; //ログインID
		?>
		<?php //echo do_shortcode('[wpmem_profile]'); ?>
	</div>
</section>
<main>
	<section class="sec-<?php echo $slug; ?>">
		<div class="sec-inner">
			<h1 class="page-tit <?php echo $slug; ?>-tit text-center"><?php the_title(); ?></h1>
			<?php while ( have_posts() ) : the_post(); ?>
			<div id="content">
				<?php
				acf_form(array(
					'post_id'		=> 'new_post',
					'post_title'	=> true,
					'post_content'	=> false,
					'submit_value' => 'カルテ登録',
					'new_post'		=> array(
						'post_type'		=> 'record',
						'post_status'	=> 'publish',
					)
				));

				//お客様欄をselectedにする用
				$author_id = $_GET['author_id'];
				$user_data = get_userdata($author_id);
				$val = $user_data->user_login.' ('.$user_data->first_name.' '.$user_data->last_name.')';
				echo '<input type="hidden" name="user_displayname" value="'.$val.'">';
				?>
			</div>
			<?php endwhile; ?>
		</div>
	</section>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>