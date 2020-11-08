<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$post = new Timber\Post();
$args = array(
	'post_type' => 'post',
	'orderby' => 'date',
	'order' => 'DESC',
);
$context['posts'] = Timber::get_posts($args);
$context['post'] = $context['post'] = $post;
$context['sidebar'] = Timber::get_widgets('blog-sidebar');
$context['home'] = $post->slug === 'home' || $post->slug === 'trang-chu';
$templates = [
	'pages/' . $post->id . '.twig',
	'pages/' . $post->post_name . '.twig',
	'pages/default.twig',
];
if ($post->slug == 'keo-tran-hot') {
    $categories = [
        'soi-keo-bong-da-tay-ban-nha-la-liga',
        'soi-keo-bong-da-anh-premier-league',
        'soi-keo-bong-da-c1-champions-league',
        'soi-keo-cup-c2-europa-league',
        'soi-keo-euro-2020',
        'soi-keo-bong-da-phap-ligue-1',
        'soi-keo-bong-da-y-serie-a'
    ];
    /*foreach ($categories as $category) {
        $context['categories'][] = new Timber\Term($category);
    }*/
    $context['posts'] = Timber::get_posts([
        'post_type' => 'post',
        'orderby' => 'date',
        'order' => 'DESC',
        'hide_empty' => 0,
        'post_status' => 'publish',
        'category_name' => implode(',',$categories),
    ]);
}
$context['templates'] = $templates;

Timber::render('page.twig', $context);
