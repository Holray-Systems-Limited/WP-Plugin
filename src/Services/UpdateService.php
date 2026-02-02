<?php

namespace Holray\Plugin\Services;

class UpdateService
{

    /**
     * The service initialisation
     */
    public function __construct() {

        add_filter('pre_set_site_transient_update_plugins', [$this, "check_updates"]);
        add_filter('upgrader_source_selection', [$this, 'rename_git_folder'], 10, 4);
    }


    /**
     * Check for updates for the Holray Plugin in GitHub
     */
    public function check_updates($transient)
    {
        if (empty($transient->checked)) {
            return $transient;
        }

        $plugin_slug = 'holray-units';
        $plugin_file = HOLRAY_UNITS_PLUGIN_PATH;

        $request = wp_remote_get(
            "https://api.github.com/repos/Holray-Systems-Limited/WP-Plugin/releases/latest",
            [
                'headers' => [
                    'Accept'     => 'application/vnd.github+json',
                    'User-Agent' => 'Holray WordPress'
                ]
            ]
        );

        if (is_wp_error($request)) {
            return $transient;
        }

        $data = json_decode(wp_remote_retrieve_body($request));

        if (empty($data->tag_name)) {
            return $transient;
        }

        $latest_version = ltrim($data->tag_name, 'v');

        if (version_compare(HOLRAY_UNITS_VERSION, $latest_version, '<')) {
            $transient->response[$plugin_file] = (object) [
                'slug'        => $plugin_slug,
                'new_version' => $latest_version,
                'url'         => $data->html_url,
                'package'     => $data->zipball_url
            ];
        }

        return $transient;
    }

    /**
     * Handle the renaming of the plugin folder so we make sure we update this plugin and not
     * install another version of it.
     */
    function rename_git_folder($source, $remote_source, $upgrader, $hook_extra) {
        if (empty($hook_extra['holray-units'])) {
            return $source;
        }

        if ($hook_extra['holray-units'] !== HOLRAY_UNITS_PLUGIN_PATH) {
            return $source;
        }

        $corrected_source = trailingslashit($remote_source) . 'holray-units';

        if (!rename($source, $corrected_source)) {
            return new \WP_Error('rename_failed', 'Could not rename Holray Plugin GitHub folder.');
        }

        return $corrected_source;
    }
}
