<?php
namespace App\Widgets;

class BannerWidget extends \WP_Widget {
	function __construct() {

		parent::__construct(
			'banner_widget',
			__('Banner Ads', 'sieukeo'),
			[
				'description' => __('Banner Ads  Widget', 'sieukeo'),
			]
		);
	}

	public function widget($args, $instance) {

		$title = apply_filters('widget_title', $instance['title']);
		$category = apply_filters('widget_category', $instance['category']);

		echo $args['before_widget'];

		if (!empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		$categories = explode(',', $category);
		if (!empty($category)) {
			$instance['categories'] = \Timber::get_terms(array(
				'taxonomy' => 'category',
				'orderby' => 'date',
				'order' => 'DESC',
				'hide_empty' => false,
				'slug' => $categories,
			));
		}
		echo \Timber::compile('widgets/banner.twig', $instance);

		echo $args['after_widget'];

	}

	public function form($instance) {
		if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = __('Categoies', 'sieukeo');
		}
		$category = '';
		if (isset($instance['category'])) {
			$category = $instance['category'];
		}

		$instance['widget'] = $this;
		$instance['value_title'] = esc_attr($title);
		$instance['value_category'] = esc_attr($category);
		echo \Timber::compile('widgets/news-form.twig', $instance);
	}

	public function update($new_instance, $old_instance) {

		$instance = array();

		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

		$instance['category'] = (!empty($new_instance['category'])) ? strip_tags($new_instance['category']) : '';

		return $instance;

	}
}
?>