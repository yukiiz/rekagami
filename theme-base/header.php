<?php
//ログインしていない場合ログインページに移動
if(!is_page('login')){
	if (!is_user_logged_in()){
	    wp_redirect(home_url('/login/'));
	}
} ?>
<!DOCTYPE html>
<html lang="ja">
<meta charset="<?php bloginfo('charset'); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width,initial-scale=1.0,user-scalable=yes" name="viewport">
<title><?php bloginfo( 'name' ); ?> </title>
<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/images/favicon.ico">
<link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet" />
<link type="text/css" href="<?php echo get_template_directory_uri(); ?>/common/slick/css/slick-theme.css" rel="stylesheet" media="all" />
<link type="text/css" href="<?php echo get_template_directory_uri(); ?>/common/slick/css/slick.css" rel="stylesheet" media="all" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/common/css/style.css" type="text/css" media="all" rel="stylesheet">
<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!-- [if lt IE 9]>
<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<?php if ( is_singular() ) wp_enqueue_script('comment-reply'); ?>
<?php wp_head(); ?>
</head>
<body <?php body_class('layer-page '); ?>>
	<div class="content-wrap">
		<!-- header -->
		<header class="header" id="head_wrap">
			<div class="header-inner">
				<div class="header-mobile" id="mobile-head">
					<h1 class="header-logo">
							<a href="<?php echo esc_url(home_url());?>"><span>TE</span>KAGAMI</a>
						</h1>
					<div class="header-toggle" id="nav-toggle">
						<div>
							<span></span>
							<span></span>
							<span></span>
						</div>
					</div>
				</div>
				<nav class="header-nav" id="global-nav">
					<ul>
						<li><a href="<?php echo esc_url(home_url('/concept/'));?>">コンセプト</a></li>
						<li><a href="<?php echo esc_url(home_url('/info/'));?>">できること</a></li>
						<?php if(current_user_can('administrator') || current_user_can('editor')){ ?>
							<li><a href="<?php echo esc_url(home_url('/record_form/'));?>">カルテの登録</a></li>
						<?php } ?>
						<li><a href="<?php echo esc_url(home_url('/change_password/'));?>">パスワード変更</a></li>
						<li><a href="<?php echo wp_logout_url(); ?>">logout</a></li>
					</ul>
				</nav>
			</div>
		</header>
		<main id="main">
			<div class="main-inner">