<?php
namespace App;

use Timber\Menu;
use Timber\Timber;

class Option
{
    protected $site;

    public function __construct($site)
    {
        $this->site = $site;
	    add_action("admin_menu", [$this, 'addMenu']);
    }

    public function addMenu(){
	    add_menu_page("Site Options", "Site Options", "manage_options", "site-options", [$this, 'siteSettingPage'], null, 99);
	    add_action("admin_init", [$this, 'displayOptionFields']);
    }

    public function siteSettingPage() {
	    settings_fields("section");
	    echo '<div class="wrap vi site-options"><h1>Site Options Panel</h1><form method="post" action="options.php"><div class="form-row">';
	    settings_fields("section");
	    echo Timber::compile('admin/options.twig');
	    do_settings_sections("site-options");
	    submit_button();
	    echo '</div></form></div>';
    }

    public function displayElement($field) {
    	$option = get_option($field['id']);
    	$type = $field['type'];
	    $templates = [ 'admin/forms/' . $type . '.twig', 'admin/forms/default.twig' ];
	    echo Timber::compile( $templates, [
		    'name'  => $field['id'],
		    'label' => $field['title'],
		    'type'  => $field['type'],
		    'value' => $option
	    ] );
    }

    public function displayOptionFields() {
	    add_settings_section("section", "", null, "site-options");
    	$options = $this->site->configs['options'];
    	foreach ($options as $option) {
		    add_settings_field($option['id'], $option['title'], function () use ($option) {
			    $this->displayElement($option);
		    }, "site-options", "section");
		    register_setting("section", $option['id']);
	    }
	    //add_settings_section("section", "All Settings", null, "site-options");
    }

}
