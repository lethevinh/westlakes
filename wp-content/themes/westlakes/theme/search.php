<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$templates = array( 'archives/search.twig', 'archives/blog.twig', 'index.twig' );

$context          = Timber::context();
$context['title'] = 'Search results for ' . get_search_query();
$query_args = array('s' => get_search_query());
$context['posts'] = Timber::get_posts($query_args);
$context['sidebar'] = Timber::get_widgets('blog-sidebar');
$context['templates'] = $templates;
Timber::render( ['search.twig','default.twig'], $context );
