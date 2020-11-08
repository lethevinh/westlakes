<?php
namespace App;

use Timber\Menu;

class FilterTheme
{
    protected $site;

    public function __construct($site)
    {
        $this->site = $site;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function breadcrumb($name) {
        if ( function_exists('yoast_breadcrumb') ) {
            return yoast_breadcrumb( '<div class="breadcrumb-wrapper-inner">','</div>' );
        }
    }

    public function menus($name = '') {
        return new Menu($name);
    }
}
