<?php
if (!defined('ABSPATH')) exit;
function holray_format_price($amount) {
  $amount = floatval($amount);
  $sym  = get_option('holray_units_currency_symbol', '£');
  $pos  = get_option('holray_units_currency_position', 'left');
  $tsep = get_option('holray_units_thousand_sep', ',');
  $dsep = get_option('holray_units_decimal_sep', '.');
  $decs = intval(get_option('holray_units_decimals', 2));
  $num = number_format($amount, $decs, $dsep, $tsep);
  return $pos === 'right' ? ($num . $sym) : ($sym . $num);
}
