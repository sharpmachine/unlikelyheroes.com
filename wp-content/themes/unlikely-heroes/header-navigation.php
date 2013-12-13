<header id="header">
	<?php get_template_part('news','ticker' ); ?>
	<div class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?php bloginfo('url') ?>"></a>
			</div>
		</div>
		<div class="collapse navbar-collapse">
			<ul class="nav navbar-nav">
				<?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'menu_class' => 'nav', 'items_wrap' => '%3$s', 'walker' => new Bootstrap_Menu_Walker ) ); ?>
			</ul>
		</div><!--/.nav-collapse -->
	</div>
</header>