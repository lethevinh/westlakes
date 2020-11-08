<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

$templates = array('archives/default.twig', 'index.twig');

$context = Timber::context();

$context['title'] = 'Archive';

$term = new Timber\Term();
if (is_day()) {
	$context['title'] = 'Archive: ' . get_the_date('D M Y');
} elseif (is_month()) {
	$context['title'] = 'Archive: ' . get_the_date('M Y');
} elseif (is_year()) {
	$context['title'] = 'Archive: ' . get_the_date('Y');
} elseif (is_tag()) {
	$context['title'] = single_tag_title('', false);
} elseif (is_post_type_archive()) {
	$context['title'] = post_type_archive_title('', false);
	$archive = get_queried_object();
	array_unshift($templates, 'archives/' . get_post_type() . '.twig');
	array_unshift($templates, 'archives/' . $archive->rewrite['slug'] . '.twig');
} elseif (!empty($term->id)) {
	$context['title'] = $term->title;
	if (!empty(get_post_type())) {
		array_unshift($templates, 'archives/' . get_post_type() . '.twig');
	}
	array_unshift($templates, 'archives/' . $term->taxonomy . '.twig');
	array_unshift($templates, 'archives/' . $term->taxonomy . '-' . $term->slug . '.twig');
	array_unshift($templates, 'archives/' . $term->taxonomy . '-' . $term->id . '.twig');
}
$pts = new Timber\PostQuery();
$posts = [];
foreach ($pts as $key => $post) {
	if (!empty($photos = $post->meta('_' . $post->post_type . '_image_gallery'))) {
		$photos = explode(",", $photos);
		$images = [];
		foreach ($photos as $photo) {
			$images[] = new Timber\Image($photo);
		}
		$post->photos = $images;
	}
	$posts[] = $post;
}
$context['posts'] = $pts;

$context['sidebar'] = Timber::get_widgets('blog-sidebar');
$context['templates'] = $templates;
Timber::render(['archive.twig', 'default.twig'], $context);
