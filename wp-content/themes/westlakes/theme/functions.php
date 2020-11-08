<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */


/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

    add_action(
        'admin_notices',
        function() {
            echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
        }
    );

    add_filter(
        'template_include',
        function( $template ) {
            return dirname( get_stylesheet_directory() ) . '/static/no-timber.html';
        }
    );
    return;
}
/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = dirname( __DIR__ ) . '/vendor/autoload.php';
if ( file_exists( $composer_autoload ) ) {
    require_once $composer_autoload;
    $timber = new Timber\Timber();
}
if ( ! function_exists( 'acf' ) ) {

    add_action(
        'admin_notices',
        function() {
            echo '<div class="error"><p>Advanced Custom Fields not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#advanced-custom-fields' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
        }
    );

    add_filter(
        'template_include',
        function( $template ) {
            return dirname( get_stylesheet_directory() ) . '/static/no-timber.html';
        }
    );
    return;
}
/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array('../src/templates', '../src/views', '../core/views');

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;

/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
//include dirname( __DIR__ ) . '/core/inc/sieukeo.php';
class StarterSite extends Timber\Site {
	public $configs = [];
	/** Add timber support. */
	public function __construct() {
		$this->configs = $this->getConfig();
		add_action('after_setup_theme', array($this, 'theme_supports'));
		add_action('admin_enqueue_scripts', [$this, 'addAssets']);
		add_filter('timber/context', array($this, 'add_to_context'));
		add_filter('timber/twig', array($this, 'add_to_twig'));
		add_action('init', array($this, 'register_shortcodes'));
		add_action('widgets_init', [$this, 'register_widgets']);
		add_action('init', array($this, 'register_routers'));
		add_action('init', array($this, 'register_post_types'));
		add_action('init', array($this, 'register_taxonomies'));
		add_action('init', array($this, 'register_options'));
		add_filter('body_class', [$this, 'register_body_class']);
		parent::__construct();
		$this->theme = new \Timber\Theme(basename(dirname( __DIR__ )));
	}

	/** This is where you can register custom post types. */
	public function register_post_types() {
		if (empty($this->configs['types'])) {
			return;
		}

		new \App\PostType($this);
	}

	/**
	 * @param $classes
	 *
	 * @return array
	 */
	public function register_body_class($classes) {
		$classes[] = $this->configs['theme']['class']['body'];
		return $classes;
	}

	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	/** This is where you can register custom shortcodes. */
	public function register_shortcodes() {
		new \App\ShortCode($this);
	}

	/** This is where you can register custom widgets. */
	public function register_widgets() {
		new App\Sidebar($this);
		new App\Widget($this);
	}
	/** This is where you can register custom widgets. */
	public function register_options() {
		new \App\Option($this);
	}
	/** This is where you can register custom widgets. */
	public function register_routers() {

	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context($context) {
		$context['menu'] = new Timber\Menu();
		$context['site'] = $this;
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
			         * Let WordPress manage the document title.
			         * By adding theme support, we declare that this theme does not use a
			         * hard-coded <title> tag in the document head, and expect WordPress to
			         * provide it for us.
		*/
		add_theme_support('title-tag');

		/*
			         * Enable support for Post Thumbnails on posts and pages.
			         *
			         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
		add_theme_support('post-thumbnails');

		/*
			         * Switch default core markup for search form, comment form, and comments
			         * to output valid HTML5.
		*/
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
			         * Enable support for Post Formats.
			         *
			         * See: https://codex.wordpress.org/Post_Formats
		*/
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);
		// add logo support
		if ($this->configs['theme']['logo']) {
			$logo = $this->configs['theme']['logo'];
			add_theme_support('custom-logo', array(
				'height' => $logo['height'],
				'width' => $logo['width'],
				'flex-height' => true,
				'flex-width' => true,
				'header-text' => array('site-title', 'site-description'),
			));
		}
		// add menu
		$menuConfigs = $this->configs['theme']['menus'];
		if (!empty($menuConfigs)) {
			$menu = [];
			foreach ($menuConfigs as $item) {
				$menu[$item['name']] = $item['label'];
			}
			register_nav_menus(
				$menu
			);
		}
		// class menu item active
        $classMenuitemActive = $this->configs['theme']['class']['menu_active'];
        if ($classMenuitemActive) {
            add_filter('nav_menu_css_class', [$this, 'special_nav_class'], 10, 2);
        }
	}

    function special_nav_class($classes, $item)
    {
        if (in_array('current-menu-item', $classes)) {
            $classMenuitemActive = $this->configs['theme']['class']['menu_active'];
            $classes[] = $classMenuitemActive . ' ';
        }
        return $classes;
    }

	/**
	 * This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 * @return mixed
	 */
	public function add_to_twig($twig) {
		$twig->addExtension(new Twig\Extension\StringLoaderExtension());
		$filterTheme = new \App\FilterTheme($this);
		$twig->addFilter(new Twig\TwigFilter('breadcrumb', array($filterTheme, 'breadcrumb')));
		$twig->addTokenParser(new \App\SectionTokenParser());
		$twig->addTokenParser(new \App\MenuTokenParser());
		$twig->addTokenParser(new \App\SidebarTokenParser());
		$twig->addTokenParser(new \App\SnippetTokenParser());
		$twig->addTokenParser(new \App\ShortCodeTokenParser());
		$twig->addTokenParser(new \App\PostTokenParser());
		$twig->addFunction(new Timber\Twig_Function('menus', function ($name = '') {
			return (new \Timber\Menu($name))->get_items();
		}));
        $twig->addFunction(new Timber\Twig_Function('url', function ($name = '') {
            return $this->link() . '/' . $name;
        }));
        $twig->addFunction(new Timber\Twig_Function('theme_url', function ($name = '') {
            return $this->theme->link() . '/' . $name;
        }));
        $twig->addFunction(new Timber\Twig_Function('logo', function ($template = 'logo', $echo = true) {
            $custom_logo_id = get_theme_mod('custom_logo');
            $logo = new Timber\Image($custom_logo_id);
            return \Timber\Timber::compile(['item/' . $template . '.twig', 'item/logo.twig'], ['logo' => $logo, 'site' => $this]);
        }));
        $twig->addFunction(new Timber\Twig_Function('ago', function ($ptime) {
            $estimate_time = time() - strtotime($ptime);
            if ($estimate_time < 1) {
                return 'vừa xong';
            }
            $condition = array(
                12 * 30 * 24 * 60 * 60 => 'năm',
                30 * 24 * 60 * 60 => 'tháng',
                24 * 60 * 60 => 'ngày',
                60 * 60 => 'giờ',
                60 => 'phút',
                1 => 'giây'
            );
            foreach ($condition as $secs => $str) {
                $d = $estimate_time / $secs;
                if ($d >= 1) {
                    $r = round($d);
                    return '' . $r . ' ' . $str . ($r > 1 ? ' ' : '') . ' trước';
                }
            }
        }));
		return $twig;
	}

	/**
	 * @return array
	 */
	public function getConfig() {
		$configs = [];
		$dir = dirname( __DIR__ ) . '/src/config' . DIRECTORY_SEPARATOR;
		$files = scandir($dir, 1);
		foreach ($files as $file) {
			if (!strpos($file, '.json')) {
				continue;
			}
			$pathFile = $dir . $file;
			$name = str_replace('.json', '', $file);
			if (file_exists($pathFile)) {
				$configs[$name] = json_decode(file_get_contents($pathFile), 1);
			}
		}
		return $configs;
	}

	function enqueue_multiple() {
	    $requireJs = ['jquery', 'jquery-ui-tabs', 'jquery-ui-sortable', 'jquery-ui-mouse', 'jquery-ui-accordion', 'jquery-ui-widget'];
		foreach ($requireJs as $js) {
            wp_enqueue_script($js);
        }
	}

	public function addAssets() {
		if (is_admin()) {
			add_action('admin_init', [$this, 'enqueue_multiple']);
			$assetsAdmin = $this->configs['assets']['admin'];
			$this->enqueue_multiple();
			foreach ($assetsAdmin['scripts'] as $asset) {
				$this->addScript($asset);
			}
			foreach ($assetsAdmin['styles'] as $asset) {
				$this->addStyle($asset);
			}
		}
	}

	protected function addScript($path) {
		$link = get_template_directory_uri() . '/../assets/js/' . $path . '.js';
		wp_enqueue_script('vi-admin-' . $path, $link, [], rand(), true);
	}

	protected function addStyle($path) {
		$link = get_template_directory_uri() . '/../assets/css/' . $path . '.css';
		wp_register_style($path, $link, false);
		wp_enqueue_style($path, $link, false);
	}
}

new StarterSite();
