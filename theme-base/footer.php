				</div>
				</main>
				<!-- footer -->
				<footer id="footer" class="footer">
					<div class="footer01">
						<div class="footer01-inner">
							<nav class="footer-nav">
								<ul class="footer-nav01">
									<div class="footer-logo">
										<a href="<?php echo esc_url(home_url());?>"><span>TE</span>KAGAMI</a>
									</div>
								</ul>
								<ul class="footer-nav02">
									<li><a href="<?php echo esc_url(home_url('/concept/'));?>">コンセプト</a></li>
									<li><a href="<?php echo esc_url(home_url('/info/'));?>">できること</a></li>
								</ul>
							</nav>
						</div>
					</div>
					<div class="footer02">
						<div class="footer02-inner">
							<div class="copy">Copyright &copy; <?php echo date('Y');?> <?php bloginfo( 'name' ); ?> All Rights Reserved.</div>
						</div>
					</div>
					<?php wp_footer(); ?>
				</footer>
				</div>
				<!-- <script src="<?php echo get_template_directory_uri(); ?>/common/js/jquery-3.6.0.min.js"></script> -->
				<!--
				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
				<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
				-->
				<script src="<?php echo get_template_directory_uri(); ?>/common/slick/slick/slick.min.js"></script>
				<script src="<?php echo get_template_directory_uri(); ?>/common/js/common.js"></script>
				</body>
				</html>