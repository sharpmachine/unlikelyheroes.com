<?php if ( $wp_query->max_num_pages > 1 ) : ?>
	<nav class="navigation" role="navigation">
		<div class="nav-previous alignleft"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'fivehundred' ) ); ?></div>
		<div class="nav-next alignright"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'fivehundred' ) ); ?></div>
	</nav>
<?php endif; ?>