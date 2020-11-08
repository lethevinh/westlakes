<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */


$context = Timber::get_context();
$post = Timber::query_post();

if (!empty($photos = $post->meta('_'.$post->post_type.'_image_gallery'))) {
	$photos = explode(",", $photos);
	$images = [];
	foreach ($photos as $key => $photo) {
		$images[] = new Timber\Image($photo);
	}
	$post->photos = $images;
}
$post->relateds = Timber::get_posts([
    'post_type' => $post->post_type,
    'posts_per_page' => 5,
    'orderby' => 'date',
    'order' => 'DESC',
    'caller_get_posts'=>1,
    'post__not_in' => [$post->ID],
    'tax_query' => [
        array(
            'taxonomy' => $post->post_type == 'post' ? 'category' : 'category-' . $post->post_type,
            'field' => 'ids',
            'terms' => $post->post_type
        )
    ],
]);
$context['post'] = $post;
$context['sidebar'] = Timber::get_widgets('blog-sidebar');
$templates = array('singles/default.twig', 'index.twig');
$post_type = $post->post_type;
if (post_password_required($post->ID)) {
	Timber::render('pages/single-password.twig', $context);
} else {
	array_unshift($templates, 'singles/' . $post->id . '.twig');
	array_unshift($templates, 'singles/' . $post_type . '.twig');
}
array_unshift($templates, 'singles/' . $post_type . '-' . $post->slug . '.twig');

$context['templates'] = $templates;
$context['type'] = $post_type;

Timber::render( ['single.twig', 'default.twig'], $context );
