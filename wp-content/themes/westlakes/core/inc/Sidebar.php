<?php

namespace App;

class Sidebar {

	protected $site;

	public function __construct($site) {
		$this->site = $site;
		$this->register();
	}

	protected function register() {
		$widgets = $this->site->configs['widgets'];
		foreach ($widgets as $widget) {
			register_sidebar(array(
				'name' => __($widget['name'], 'a'),
				'id' => $widget['id'],
				'description' => __('Add widgets here to appear in your sidebar on blog posts and archive pages.', 'a'),
				'class' => 'widget',
				'before_widget' => '<div id="%1$s" class="widget widget-list-dealer  widget-%2$s %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<section class="section-head"><h2>',
				'after_title' => '</h2></section>',
			));
		}
	}
}
