<?php

namespace App;

class Widget {

	protected $site;

	public function __construct($site) {
		$this->site = $site;
		$this->register();
	}

	protected function register() {
		$widgets = scandir(__DIR__ . '/Widgets/', 1);
		foreach ($widgets as $key => $widget) {
			if (strpos($widget, '.php')) {
				$className = "App\Widgets\\" . str_replace('.php', '', $widget);
				if (class_exists($className)) {
					register_widget($className);
				}
			}
		}
	}
}
