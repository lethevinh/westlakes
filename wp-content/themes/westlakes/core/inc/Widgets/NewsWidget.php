<?php
namespace App\Widgets;

use Timber\Timber;

class NewsWidget extends \WP_Widget {
	function __construct() {

		parent::__construct(
			'news_widget',
			__('Posts by Category', 'sieukeo'),
			[
				'description' => __('Posts by Category  Widget', 'sieukeo'),
			]

		);
	}

	public function widget($args, $instance) {

		$title = apply_filters('widget_title', $instance['title']);
		$category = apply_filters('widget_category', $instance['category']);
        $template = apply_filters( 'widget_template', $instance['template'] );
        $template = $template ? $template : "news";
		echo $args['before_widget'];

		if (!empty($title) && $template == 'news/template-2') {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		if (!empty($category)) {
            $category = trim($category);
		    $categories = explode(',', $category);
            if (count($categories) == 1) {
                $instance['category'] = $term = new \Timber\Term($category);
                $instance['posts'] = $term->posts();
            }else{
                $instance['posts'] = \Timber::get_posts([
                    'post_type' => 'post',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'hide_empty' => 0,
                    'post_status' => 'publish',
                    'category_name' => implode(',',$categories),
                ]);
            }
		}else{
		    $instance['posts'] = \Timber::get_posts(array(
                'post_type' => 'post',
                'orderby' => 'date',
                'order' => 'DESC',
            ));
        }
        echo \Timber::compile( 'widgets/' . $template . '.twig', $instance );

		echo $args['after_widget'];

	}

	public function form($instance) {
		if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = __('Default Title', 'sieukeo');
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
                'key' => 'news/template-1',
                'value' => 'Template 1'
            ],
            [
                'key' => 'news/template-2',
                'value' => 'Template 2'
            ],
            [
                'key' => 'news/template-3',
                'value' => 'Template 3'
            ],
            [
                'key' => 'news/template-4',
                'value' => 'Template 4'
            ],
        ];
		echo \Timber::compile('widgets/news-form.twig', $instance);
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