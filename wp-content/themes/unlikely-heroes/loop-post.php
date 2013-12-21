<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<h2 class="post-title">
		<a href="<?php the_permalink(); ?>"><?php the_title();  ?></a>
	</h2>
	
	<div><?php smm_posted_on(); ?></div>
	
	<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array (100, 100) ); ?></a>
	
	<div class="post-content">
		<?php if(is_single()): ?>
			<?php the_content(); ?>
		<?php else: ?>
			<?php the_excerpt(); ?>
		<?php endif; ?>
	</div>

	<div class="entry-utility">
	<?php if ( count( get_the_category() ) ) : ?>
		<span class="cat-links">
			<?php printf( __( '<span class="%1$s">Posted in</span> %2$s', 'smm' ), 'entry-utility-prep entry-utility-prep-cat-links', get_the_category_list( ', ' ) ); ?>
		</span>
		<span class="meta-sep">|</span>
	<?php endif; ?>
	
	<?php
		$tags_list = get_the_tag_list( '', ', ' );
		if ( $tags_list ):
	?>
		<span class="tag-links">
			<?php printf( __( '<span class="%1$s">Tagged</span> %2$s', 'smm' ), 'entry-utility-prep entry-utility-prep-tag-links', $tags_list ); ?>
		</span>
		<span class="meta-sep">|</span>
	<?php endif; ?>
	</div><!-- .entry-utility -->
	
	<?php if(is_single()): ?>
		<?php // get_template_part( 'author-info' ); ?>
		<?php //comments_template( '', true ); ?>
	<?php endif; ?>
</article><!-- #post -->

