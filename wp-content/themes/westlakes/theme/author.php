<?php
/**
 * The template for displaying Author Archive pages
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

global $wp_query;

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();
if ( isset( $wp_query->query_vars['author'] ) ) {
	$author            = new Timber\User( $wp_query->query_vars['author'] );
	$author->avatar =  get_avatar_url( $author->ID );
	$context['author'] = $author;
	$context['title']  = 'Tác Giả: ' . $author->name();
}
$context['sidebar'] = Timber::get_widgets('blog-sidebar');
$context['templates'] = array( 'archives/author.twig', 'archives/default.twig' );

Timber::render( ['author.twig','default.twig'], $context );
