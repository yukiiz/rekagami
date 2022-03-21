<?php get_header(); ?>




<section class="sec-404">

	<div class="sec-inner">

		<div class="sec-headline">
			<h1 class="page-tit <?php echo $slug; ?>-tit text-center">404 Not Found</h1>
		</div>

		<p class="text-center mb60">お探しのページは見つかりませんでした。</p>
		<p class="text-center"><a href="<?php echo esc_url(home_url());?>" class="btn-block">TOPへ戻る</a></p>

	</div>

</section>



<?php get_footer(); ?>​
