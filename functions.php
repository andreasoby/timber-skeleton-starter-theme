<?php

/* let's move timber plugin into independent theme */
require_once(get_template_directory().'/timber-library/timber.php');

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		} );
	return;
}

/* Standard wp functions */


/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}

if ( ! function_exists( 'bmj_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function bmj_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on bmj, use a find and replace
	 * to change 'bmj' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'bmj', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.

	function my_wp_nav_menu_args( $args = '' ) {
		$args['container'] = false;
		return $args;
	}
	add_filter( 'wp_nav_menu_args', 'my_wp_nav_menu_args' );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'bmj' ),
		'footer' => __( 'Footer Menu', 'bmj' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'bmj_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // bmj_setup
add_action( 'after_setup_theme', 'bmj_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function bmj_widgets_init() {
	register_sidebar( array(
		'name'          => 'Content left',
		'id'            => 'mainleft',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => 'Content middle',
		'id'            => 'mainmiddle',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );
	register_sidebar( array(
		'name'          => 'Content right',
		'id'            => 'mainright',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );
  register_sidebar( array(
		'name'          => 'Footer left',
		'id'            => 'footleft',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );
  register_sidebar( array(
		'name'          => 'Footer right',
		'id'            => 'footright',
		'before_widget' => '<div class="widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	) );

}
add_action( 'widgets_init', 'bmj_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function bmj_scripts() {
 wp_enqueue_style( 'animate', get_template_directory_uri().'/js/animate.css');

// 	wp_enqueue_script( 'bmj-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );
  wp_register_script( 'jquery', get_template_directory_uri().'/js/lib/jquery.js');
  wp_register_script( 'bootstrap', get_template_directory_uri().'/js/lib/bootstrap.min.js');
  wp_register_script( 'fancybox', get_template_directory_uri().'/js/lib/jquery.fancybox.pack.js');
  wp_register_script( 'jquery-ui', get_template_directory_uri().'/js/lib/jquery-ui.min.js');
  wp_register_script( 'functions', get_template_directory_uri().'/js/functions.min.js');
  wp_register_script( 'efekty', get_template_directory_uri().'/js/effekty.js');



	wp_enqueue_script( 'bmj-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'bmj_scripts' );

function oenology_add_menu_parent_class( $items ) {

	$parents = array();
	foreach ( $items as $item ) {
		if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
			$parents[] = $item->menu_item_parent;
		}
	}

	foreach ( $items as $item ) {
		if ( in_array( $item->ID, $parents ) ) {
			$item->classes[] = 'has-children';
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_objects', 'oenology_add_menu_parent_class' );


/* timber related stuff */

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
    $context['primarymenu'] = new TimberMenu('primary');
    $context['footermenu'] = new TimberMenu('footer');
    $context['widget_mainleft'] = Timber::get_widgets('mainleft');
    $context['widget_mainmiddle'] = Timber::get_widgets('mainmiddle');
    $context['widget_mainright'] = Timber::get_widgets('mainright');
    $context['widget_footleft'] = Timber::get_widgets('footleft');
    $context['widget_footright'] = Timber::get_widgets('footright');
		$context['site'] = $this;
		return $context;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own fuctions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter( 'myfoo', new Twig_Filter_Function( 'myfoo' ) );
		return $twig;
	}

}

new StarterSite();

function myfoo( $text ) {
	$text .= ' bar!';
	return $text;
}

add_filter( 'comment_form_defaults', 'afn_custom_comment_form' );
function afn_custom_comment_form($fields) {
// 	$fields['comment_notes_before'] = ''; // Removes comment before notes
	$fields['comment_notes_after'] = '<p class="form-allowed-tags">' . sprintf(__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), '<pre><code> &lt;a href="" title=""&gt; &lt;blockquote&gt; &lt;pre&gt;&lt;code&gt; &lt;del datetime=""&gt; &lt;em&gt; &lt;i&gt; &lt;strike&gt; &lt;strong&gt; </code></pre>') . '</p>'; // Removes comment after notes
    return $fields;
}
