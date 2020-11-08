<?php
namespace App\Widgets;

class DealerWidget extends \WP_Widget {
	function __construct() {

		parent::__construct(
			'dealer_widget',
			__('Top Nhà Cái', 'sieukeo'),
			[
				'description' => __('Danh sách Nhà cái', 'sieukeo'),
			]

		);
	}

	public function widget($args, $instance) {

		$title = apply_filters('widget_title', $instance['title']);
		$dealer = apply_filters('widget_dealer', $instance['dealer']);

		echo $args['before_widget'];

		if (!empty($title)) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if (!empty($dealer)) {
            $context = [];
            $context['posts'] = \Timber::get_posts(array(
                'post_type' => 'dealer',
                'posts_per_page' => 5,
                'post_status' => 'publish',
                'orderby' => ['post_date' => 'DESC'],
                'order' => 'DESC',
            ));
            echo \Timber::compile('widgets/dealer.twig', $context);
		}
		echo $args['after_widget'];
	}

	public function form($instance) {
		if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = __('Top Nhà Cái', 'sieukeo');
		}
		$dealer = '';
		if (isset($instance['dealer'])) {
			$dealer = $instance['dealer'];
		}

		$instance['widget'] = $this;
		$instance['value_title'] = esc_attr($title);
		$instance['value_dealer'] = esc_attr($dealer);
		echo \Timber::compile('widgets/dealer-form.twig', $instance);
	}

	public function update($new_instance, $old_instance) {

		$instance = array();

		$instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';

		$instance['dealer'] = (!empty($new_instance['dealer'])) ? strip_tags($new_instance['dealer']) : '';

		return $instance;

	}
}
?>