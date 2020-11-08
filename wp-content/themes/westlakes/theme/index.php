<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */
$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();

$templates        = array( 'index.twig' );
if (is_front_page() && is_home()) {
	// Default homepage
	array_unshift($templates, 'index.twig');
} elseif (is_front_page()) {
	//Static homepage
	array_unshift($templates, 'pages/home.twig');
	array_unshift($templates, 'archives/home.twig');
} elseif (is_home()) {
	//Blog page
	$context['sidebar'] = Timber::get_widgets('blog-sidebar');
	array_unshift($templates, 'pages/blog.twig');
	array_unshift($templates, 'archives/blog.twig');
} else {
	//everything else
	array_unshift($templates, 'pages/home.twig');
	array_unshift($templates, 'archives/home.twig');
}
$context['templates'] = $templates;
Timber::render( 'default.twig', $context );
