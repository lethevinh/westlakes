<?php


namespace App;


class ShortCode
{

    protected $site;

    public function __construct($site)
    {
        $this->site = $site;
        $this->register();
    }

    protected function register()
    {
        add_shortcode('term_type', [$this, 'registerCategoryShortCode']);
        add_shortcode('post_type', [$this, 'registerPostShortCode']);
        add_shortcode('tour_today', [$this, 'registerTourShortCode']);
        add_shortcode('schedule_today', [$this, 'registerScheduleShortCode']);
        add_shortcode('banner_ads', [$this, 'registerBannerShortCode']);
        add_shortcode('widget_render', [$this, 'registerWidgetShortCode']);
        add_shortcode('page_render', [$this, 'registerPageShortCode']);
        add_shortcode('post_type_tour_today', [$this, 'registerPostTourShortCode']);
    }

    public function registerCategoryShortCode($attr, $content)
    {
        global $paged;
        if (!isset($paged) || !$paged) {
            $paged = 1;
        }

        $limit = !empty($attr['limit']) ? $attr['limit'] : 10000;
        $postType = isset($attr['type']) ? $attr['type'] : "post";
        $postTerm = isset($attr['term']) ? $attr['term'] : "category";

        $args = array(
            'post_type' => $postType,
            'posts_per_page' => $limit,
            'paged' => $paged,
            'orderby' => 'post_date',
            'order' => 'DESC',
        );
        $context['categories'] = \Timber::get_terms([
            'taxonomy' => $postTerm,
        ]);

        $context['posts'] = \Timber::get_posts($args);
        $context['content'] = $content;

        $template = isset($attr['template']) ? $attr['template'] : "default";

        $templates = array(
            'shortcodes/' . $postType . '/' . $template . ".twig",
            'shortcodes/' . $postType . '/default.twig',
        );
        return \Timber::compile($templates, $context);
    }

    public function registerPostShortCode($attr, $content)
    {
        global $paged;
        if (!isset($paged) || !$paged) {
            $paged = 1;
        }

        $postType = isset($attr['type']) ? $attr['type'] : "post";
        $category = isset($attr['category']) ? $attr['category'] : "";

        $limit = !empty($attr['limit']) ? $attr['limit'] : 10000;

        $args = array(
            'post_type' => $postType,
            'posts_per_page' => $limit,
            'paged' => $paged,
            'post_status' => 'publish',
            'orderby' => ['post_date' => 'DESC'],
            'order' => 'DESC',
        );
        if (!empty($attr['meta'])) {
            foreach (explode(',', $attr['meta']) as $item) {
                $condition = explode('=', $item);
                if ($condition && acf_get_field($condition[0])) {
                    list($key, $value) = $condition;
                    $args['meta_query'][$key] = [
                        'key' => $key,
                        'value' => $value,
                        'compare' => '='
                    ];
                }
            }
        }
        if ($category) {
            $taxonomy = $postType== 'post' ? 'category': 'category-'.$postType;
            $args['tax_query'] = array(
                array(
                    'taxonomy' => $taxonomy,
                    'field' => 'slug',
                    'terms' => array( $category ),
                    'include_children' => true,
                    'operator' => 'IN'
                )
            );
        }
        $context['posts'] = new \Timber\PostQuery($args);

        $context['content'] = $content;

        $template = isset($attr['template']) ? $attr['template'] : "default";

        $templates = array(
            'shortcodes/' . $postType . '/' . $template . ".twig",
            'shortcodes/' . $postType . '/default.twig',
            'shortcodes/' . $postType . '.twig',
            'shortcodes/default.twig',
        );
        $context['template'] = $template;
        return \Timber::compile($templates, $context);
    }

    public function registerTourShortCode($attr, $content)
    {
        $limit = !empty($attr['limit']) ? $attr['limit'] : 23;

	    $args = array(
		    'category_name'  => 'keo-tran-hot',
		    'post_type'      => 'post',
		    'posts_per_page' => $limit,
		    'meta_query'     => array(
			    'relation'       => 'AND',
			    'tour_position'  => array(
				    'key'     => 'tour_position',
				    'type'    => 'NUMERIC',
				    'compare' => 'EXISTS',
			    ),
			    'tour_position1' => array(
				    'key'     => 'tour_position',
				    'type'    => 'NUMERIC',
				    'value'   => 100,
				    'compare' => '<',
			    ),
		    ),
		    'orderby'        => array(
			    'tour_position'  => 'ASC',
			    'tour_position1' => 'ASC',
			    'post_date'      => 'DESC'
		    ),
	    );

        $context['posts'] = new \Timber\PostQuery($args);

        $context['content'] = $content;

        return \Timber::compile('collection/tour.twig', $context);
    }

    public function registerScheduleShortCode($attr, $content)
    {
        $limit = !empty($attr['limit']) ? $attr['limit'] : 23;

        $args = array(
            'name'        => 'keo-tran-hot',
            'post_type'   => 'post',
            'post_status' => 'publish',
            'numberposts' => 1
        );

        $context['posts'] = new \Timber\PostQuery($args);

        $context['content'] = $content;

        return \Timber::compile('collection/schedule.twig', $context);
    }

    public function registerBannerShortCode($attr, $content)
    {
        $position = !empty($attr['position']) ? $attr['position'] : 23;

        $args = array(
            'post_type' => 'banner',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'position',
                    'value' => $position,
                    'compare' => 'IN',
                ),
            ),
            'numberposts' => 1
        );

        $context['posts'] = new \Timber\PostQuery($args);
        $context['content'] = $content;

        return \Timber::compile(['item/banner-ads/' . $position . '.twig', 'item/banner-ads/default.twig'], $context);
    }

    public function registerWidgetShortCode($attr, $content)
    {
        $name = !empty($attr['sidebar_name']) ? $attr['sidebar_name'] : 'blog-sidebar';
		$sidebar = \Timber::get_widgets($name);
	    $context['sidebar'] = $sidebar;
	    $context['name'] = $name;
        return \Timber::compile(['sidebar_shortcode.twig'], $context);
    }

    public function registerPageShortCode($attr, $content)
    {
        $name = !empty($attr['page_slug']) ? $attr['page_slug'] : 'home';
        $template = !empty($attr['template']) ? $attr['template'] : 'sidebar_shortcode';
	    $args = array(
		    'name'        => $name,
		    'post_type'   => 'page',
		    'post_status' => 'publish',
		    'numberposts' => 1
	    );
	    $page = \Timber::get_post($args);
	    $context['page'] = $page;
	    $context['sidebar'] = $page->content;
	    $context['name'] = $name;
        return \Timber::compile([$template. '.twig', 'sidebar_shortcode.twig'], $context);
    }

    public function registerPostTourShortCode($attr, $content)
    {
        $name = !empty($attr['page_slug']) ? $attr['page_slug'] : 'home';
        $template = !empty($attr['template']) ? $attr['template'] : 'sidebar_shortcode';
        $args = array(
            'name'        => $name,
            'post_type'   => 'tour_to_day',
            'post_status' => 'publish',
            'numberposts' => 1
        );
        $page = \Timber::get_post($args);

        $argsPost = array(
            'post_type' => 'post',
            'cat'       => 'keo-tran-hot',
            'post__in' => $page->tour_posts,
            'order_by' => 'post__in',
        );
        $posts =  \Timber::get_posts($argsPost);
        $postsData = [];
        if (count($page->tour_posts) > 0 && count($posts) > 0) {
            foreach ($page->tour_posts as $tourId) {
                foreach ($posts as $p) {
                    if ($tourId == $p->id) {
                        $postsData[] = $p;
                    }
                }
            }
        }
        $context['posts'] = $postsData;
        $context['name'] = $name;
        return \Timber::compile([$template. '.twig'], $context);
    }
}
