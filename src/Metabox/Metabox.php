<?php
namespace Holray\Plugin\Metabox;

class Metabox {

    /**
     * The metabox id
     */
    public $id = 'holray-metabox';

    /**
     * The metabox title
     */
    public $title = 'Metabox';

    /**
     * The metabox post type
     */
    public $posttype = 'posts';


    /**
     * New Unit Fields Meta Box is created.
     */
    public function __construct() {
        add_action('add_meta_boxes', [$this, "init"]);
    }

    /**
     * Init the metabox
     */
    public function init()
    {
        add_meta_box(
            $this->id,
            $this->title,
            [$this, "render"],
            $this->posttype,
            'normal'
        );
    }

    /**
     * Render the metabox
     */
    public function render($post) {}

}