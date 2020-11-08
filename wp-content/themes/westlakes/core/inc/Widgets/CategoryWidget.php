<?php
namespace App\Widgets;

class CategoryWidget extends \WP_Widget {
	function __construct() {

		parent::__construct(
			'categories_widget',
			__('List Category', 'sieukeo'),
			[
				'description' => __('Categories  Widget', 'sieukeo'),
			]
		);
	}

	public function widget($args, $instance) {

		$title = apply_filters('widget_title', $instance['title']);
		$category = apply_filters('widget_category', $instance['category']);
		$template = apply_filters( 'widget_template', $instance['template'] );
		$template = $template ? $template : "category";
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
		echo \Timber::compile( 'widgets/' . $template . '.twig', $instance );

		echo $args['after_widget'];

	}

	public function form($instance) {
		if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = __('Categories', 'sieukeo');
		}
		$category = '';
		$template = '';
		if (isset($instance['category'])) {
			$category = $instance['category'];
		}
		if (isset($instance['template'])) {
			$template = $instance['template'];
		}

		$instance['widget'] = $this;
		$instance['value_title'] = esc_attr($title);
		$instance['value_category'] = esc_attr($category);
		$instance['value_template'] = esc_attr($template);
		$instance['templates'] = [
			[
				'key' => 'post_page',
				'value' => 'Post Page'
			],
			[
				'key' => 'category_page',
				'value' => 'Category Page'
			],
			[
				'key' => 'home_page',
				'value' => 'Home Page'
			],
			[
				'key' => 'hot_tour',
				'value' => 'KÈO HOT'
			],
			[
				'key' => 'news/template-1',
				'value' => 'Template1'
			],
		];
		echo \Timber::compile('widgets/category-form.twig', $instance);
	}

	public function update($new_instance, $old_instance) {

		$instance = array();

		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

		$instance['category'] = (!empty($new_instance['category'])) ? strip_tags($new_instance['category']) : '';
		$instance['template'] = (!empty($new_instance['template'])) ? strip_tags($new_instance['template']) : '';

		return $instance;

	}
}
?>
