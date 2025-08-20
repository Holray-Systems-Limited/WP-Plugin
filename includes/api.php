<?php
if (!defined('ABSPATH')) exit;
function holray_api_get_options() {
  return ['base'=> rtrim(get_option('holray_units_api_base',''),'/'),'apikey'=> get_option('holray_units_api_key',''),'timeout'=> (int) apply_filters('holray_api_timeout',20)];
}
function holray_api_post($service, array $payload=[]) {
  $o = holray_api_get_options();
  if (empty($o['base']) || empty($o['apikey'])) return new WP_Error('holray_api_missing', __('Holray API base URL or API key is not set.','holray-units'));
  $resp = wp_remote_post($o['base'], ['headers'=>['Content-Type'=>'application/json'],'body'=> wp_json_encode(array_merge(['service'=>$service,'apikey'=>$o['apikey']], $payload)),'timeout'=>$o['timeout']]);
  if (is_wp_error($resp)) return $resp;
  $code = wp_remote_retrieve_response_code($resp);
  $json = json_decode(wp_remote_retrieve_body($resp), true);
  if ($code < 200 || $code > 299 || !is_array($json)) return new WP_Error('holray_api_http', sprintf(__('Holray API error (HTTP %s).','holray-units'), $code));
  if (!empty($json['status']) && strtoupper($json['status']) !== 'SUCCESS') return new WP_Error('holray_api_status', isset($json['message'][0])? $json['message'][0]: __('Unknown API error','holray-units'), $json);
  return $json;
}
function holray_api_availability(array $args) { return holray_api_post('availability',$args); }
function holray_api_features_list() {
  $k='holray_features_list_v1'; $c=get_transient($k); if ($c!==false) return $c;
  $res = holray_api_post('features',[]); if (is_wp_error($res)) return $res;
  $d = isset($res['data'])? $res['data']:[]; set_transient($k,$d,DAY_IN_SECONDS); return $d;
}
function holray_checkout_base() {
  $o = rtrim(get_option('holray_units_checkout_base',''),'/'); if ($o) return $o;
  $b = holray_api_get_options()['base']; return $b? preg_replace('~/api\\.php$~','/custbook1.php',$b):'';
}
