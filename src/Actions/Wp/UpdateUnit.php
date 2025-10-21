<?php
namespace Holray\Plugin\Actions\Wp;

use Holray\Plugin\Actions\Action;
use Holray\Plugin\Plugin;
use Holray\Plugin\Util\Request;

class UpdateUnit extends Action
{

    /**
     * The action name
     * 
     * @var string
     */
    public $action = "save_post";

    /**
     * Is a native wordpress action?
     * 
     * @var boolean
     */
    public $is_native = true;
    

    /**
     * Handle the action
     * 
     * @return void
     */
    public function handle() {
        global $post; 
        if ($post->post_type != 'holray_unit'){
            return;
        }

        // Prevent saving during an autosave (we don't always get these fields sent through)
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }


        // Verify this user has permission to save these fields.
        if (!current_user_can('edit_post', $post->ID)) {
            return;
        }

        // Save fields
        $unit_fields = [
            "holray_class",
            "holray_min_berth",
            "holray_max_berth",
            "holray_layout",
            "holray_max_pets",
            "holray_min_nights",
        ];
        foreach ($unit_fields as $meta_key) {
            if(isset($_POST[$meta_key])) {
                $value = sanitize_text_field($_POST[$meta_key]);
                if(get_post_meta($post->ID, $meta_key, true)) {
                    update_post_meta($post->ID, $meta_key, $value);
                } else {
                    add_post_meta($post->ID, $meta_key, $value, true);
                }
            }
        }
    }
}