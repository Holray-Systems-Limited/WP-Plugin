<?php
namespace Holray\Plugin\Pages;
/**
 * Pages base page class
 */

class Page
{

    /**
     * The parent slug to the menu item
     * 
     * @var string
     */
    public $parent_slug;

    /**
     * The pages title
     * 
     * @var string
     */
    public $title;

    /**
     * The pages slug
     * 
     * @var string
     */
    public $slug;

    /**
     * The menu title
     * 
     * @var string
     */
    public $menu_title;

    /**
     * The permissions for the user to access this page.
     * 
     * @var string
     */
    public $capability;


    /**
     * On new page created
     * 
     * @return void
     */
    public function __construct()
    {
        add_action( 'admin_menu', [$this, 'register_menu'] );
    }

    /**
     * Register the menu
     * 
     * @return void
     */
    public function register_menu() {
        add_submenu_page(
            $this->parent_slug,
            $this->title,
            $this->menu_title,
            $this->capability,
            $this->slug,
            [$this, "render"]
        );
    }
}