<?php
include 'functions/need-help-dashboard-widget.php';
include 'functions/customize-admin.php';

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 940;

/** Tell WordPress to run smm_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'smm_setup' );

if ( ! function_exists( 'smm_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override smm_setup() in a child theme, add your own smm_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twitter Bootstrap Framework 1.0
 */
remove_action('wp_head', 'wp_generator');  

function smm_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'smm', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'smm' ),
		) );
}
endif;

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twitter Bootstrap Framework 1.0
 */
function smm_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'smm_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twitter Bootstrap Framework 1.0
 * @return int
 */
function smm_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'smm_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Twitter Bootstrap Framework 1.0
 * @return string "Continue Reading" link
 */
function smm_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'smm' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and smm_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twitter Bootstrap Framework 1.0
 * @return string An ellipsis
 */
function smm_auto_excerpt_more( $more ) {
	return ' &hellip;' . smm_continue_reading_link();
}
add_filter( 'excerpt_more', 'smm_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twitter Bootstrap Framework 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function smm_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= smm_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'smm_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twitter Bootstrap Framework's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 * @since Twitter Bootstrap Framework 1.2
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Deprecated way to remove inline styles printed when the gallery shortcode is used.
 *
 * This function is no longer needed or used. Use the use_default_gallery_style
 * filter instead, as seen above.
 *
 * @since Twitter Bootstrap Framework 1.0
 * @deprecated Deprecated in Twitter Bootstrap Framework 1.2 for WordPress 3.1
 *
 * @return string The gallery style filter, with the styles themselves removed.
 */
function smm_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
// Backwards compatibility with WordPress 3.0.
if ( version_compare( $GLOBALS['wp_version'], '3.1', '<' ) )
	add_filter( 'gallery_style', 'smm_remove_gallery_css' );

if ( ! function_exists( 'smm_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own smm_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twitter Bootstrap Framework 1.0
 */
function smm_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
	case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, 40 ); ?>
				<?php printf( __( '%s <span class="says">says:</span>', 'smm' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
			</div><!-- .comment-author .vcard -->
			<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'smm' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
			/* translators: 1: date, 2: time */
			printf( __( '%1$s at %2$s', 'smm' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'smm' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
	break;
	case 'pingback'  :
	case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'smm' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'smm' ), ' ' ); ?></p>
		<?php
		break;
		endswitch;
	}
	endif;

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * This function uses a filter (show_recent_comments_widget_style) new in WordPress 3.1
 * to remove the default style. Using Twitter Bootstrap Framework 1.2 in WordPress 3.0 will show the styles,
 * but they won't have any effect on the widget in default Twitter Bootstrap Framework styling.
 *
 * @since Twitter Bootstrap Framework 1.0
 */
function smm_remove_recent_comments_style() {
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}
add_action( 'widgets_init', 'smm_remove_recent_comments_style' );

if ( ! function_exists( 'smm_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Twitter Bootstrap Framework 1.0
 */
function smm_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s', 'smm' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
			),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'smm' ), get_the_author() ),
			get_the_author()
			)
		);
}
endif;

if ( ! function_exists( 'smm_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twitter Bootstrap Framework 1.0
 */
function smm_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'smm' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'smm' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'smm' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
		);
}
endif;

function bootstrap_menu() { ?>
<?php wp_list_pages('title_li&show_home=1'); ?>
<?php }
add_filter( 'wp_page_menu', 'bootstrap_menu' );

// Add's classes to default wp_nav() output to utilize the Bootstraps menu
class Bootstrap_Menu_Walker extends Walker_Nav_Menu{
	function start_lvl(&$output, $depth = 0, $args = array()) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		if ( !$element )
			return;
		$id_field = $this->db_fields['id'];
		if ( is_array( $args[0] ) )
			$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );		
		if( ! empty( $children_elements[$element->$id_field] ) )
			array_push($element->classes,'dropdown');
		$cb_args = array_merge( array(&$output, $element, $depth), $args);	
		call_user_func_array(array(&$this, 'start_el'), $cb_args);
		$id = $element->$id_field;
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {
			foreach( $children_elements[ $id ] as $child ){
				if ( !isset($newlevel) ) {
					$newlevel = true;
					$cb_args = array_merge( array(&$output, $depth), $args);
					call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
				}
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}
		if ( isset($newlevel) && $newlevel ){
			$cb_args = array_merge( array(&$output, $depth), $args);
			call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
		}
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'end_el'), $cb_args);
	}
}

// Default WordPress pagination tweaked to use page numbers
function bootstrap_pagination(){
	global $wp_query;
	$total_pages = $wp_query->max_num_pages;
	if ($total_pages > 1){
		$current_page = max(1, get_query_var('paged'));
		echo paginate_links(array(
			'base' => get_pagenum_link(1) . '%_%',
			'format' => 'page/%#%',
			'current' => $current_page,
			'total' => $total_pages,
			'prev_text' => 'Prev',
			'next_text' => 'Next',
			'type' => 'list'
			));
	}
}

function the_short_title($limit) {
	$title = get_the_title($post->ID);
	if(strlen($title) > $limit) {
		$title = substr($title, 0, $limit) . '...';
	}

	echo $title;
}

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
	if (!current_user_can('administrator') && !is_admin()) {
		show_admin_bar(false);
	}
}

// Image Sizes added and Allowing to select those image sizes in Media Insert Admin
if ( function_exists( 'add_image_size' ) ) {
	add_image_size( 'thumbnail-box', 697, 463, true); // For Campaign Summary boxes
	// add_image_size( 'project-page', 720, 435, true); // For campaign page
}

add_filter( 'image_size_names_choose', 'custom_image_sizes_choose_2' );  
function custom_image_sizes_choose_2( $sizes ) {  
    $custom_sizes = array(  
        'thumbnail-box' => 'Home Page Summary Box'
    );  
    return array_merge( $sizes, $custom_sizes );  
}

// function soi_login_redirect( $redirect_to, $request, $user  ) {
// 	return ( is_array( $user->roles ) && in_array( 'administrator', $user->roles ) ) ? admin_url() : site_url('/dashboard/');
// } // end soi_login_redirect
// add_filter( 'login_redirect', 'soi_login_redirect', 10, 3 );

// function baw_no_admin_access()
// {
// 	if( !current_user_can( 'administrator' ) ) {
// 		wp_redirect( home_url() );
// 	}
// }
// add_action( 'admin_init', 'baw_no_admin_access', 1 );