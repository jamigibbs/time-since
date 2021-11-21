<?php
/**
 * Plugin Name: Time Since
 * Plugin URI: https://github.com/jamigibbs/time-since
 * Description: Display the years, months, and days since a specific date.
 * Version: 1.0.0
 * Author: Jami Gibbs
 * Author URI: https://jamigibbs.com/
 * Tested up to: 5.8.2
 * Text Domain: time-since
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Basic stop of brute force use.
defined('ABSPATH') or die('Unauthorized Access!');

/**
 * Implement the plugin. Let your post time travel.
 */
class TimeSince {
  /**
   * A basic constructor.
   */
  public function __construct() {
    add_shortcode('time-since', array($this, 'shortcode_time_since'));
    add_action('plugins_loaded',  array($this, 'load_plugin_textdomain'));
  }

  /**
   * Load gettext translate for text domain.
   *
   * @since 1.0.0
   *
   * @return void
   */
  function load_plugin_textdomain() {
    load_plugin_textdomain('time-since');
  }

  /**
   * Generate year label.
   *
   * @since 1.0.0
   *
   * @return string
   */
  public function year_label_string_return($year) {
    if ($year === 0 || $year > 1) {
      return __('Years,', 'time-since');
    } else {
      return __('Year,', 'time-since');
    }
  }

  /**
   * Generate month label.
   *
   * @since 1.0.0
   *
   * @return string
   */
  public function month_label_string_return($month) {
    if ($month === 0 || $month > 1) {
      return __('Months,', 'time-since');
    } else {
      return __('Month,', 'time-since');
    }
  }

  /**
   * Generate day label.
   *
   * @since 1.0.0
   *
   * @return string
   */
  public function day_label_string_return($month) {
    if ($month === 0 || $month > 1) {
      return __('Days', 'time-since');
    } else {
      return __('Day', 'time-since');
    }
  }

  /**
   * Add markup to title and return from shortcode attribute.
   *
   * @since 1.0.0
   *
   * @return string
   */
  public function title_string($title) {
    return "<h2>" . $title . "</h2>";
  }

  /**
   * A method to return the markup that replaces the shortcode.
   * @param array $atts
   * @return string
   */
  public function shortcode_time_since($atts) {
    // Bail if no year argument was passed.
    if (!isset($atts['y'])) {
      return __('Year is required.', 'time-since');
    }
    // Bail if year value is not numeric.
    if (!is_numeric($atts['y'])) {
      return __('Year must be numeric.', 'time-since');
    }
    // Bail if year value is not 4 digits long.
    if (strlen($atts['y']) !== 4) {
      return __('Year must be 4 digits.', 'time-since');
    }
    // Bail if year is in the future.
    if ($atts['y'] > date('Y')) {
      return __('Year cannot be greater than current year.', 'time-since');
    }

    // Cast the year value as an integer.
    $y = (int)$atts['y'];

    // Ensure month and day values are integers, if set.
    $m = (isset($atts['m'])) ? (int)$atts['m'] : 1;
    $d = (isset($atts['d'])) ? (int)$atts['d'] : 1;

    // Get today's date.
    $now = date("Y-m-d H:i:s");
    // Get the past date from shortcode attributes.
    $pastDate = "$y-$m-$d";
    $date1 = new DateTime($now);
    $date2 = new DateTime($pastDate);
    // Calculate span between start date and today.
    $interval = $date1->diff($date2); 

    // Display the title and time since values.
    return $this->title_string($atts['title']) . $interval->y . " " . $this->year_label_string_return($interval->y) . " " . $interval->m .  " " . $this->month_label_string_return($interval->m) . " " . $interval->d . " "  . $this->day_label_string_return($interval->d); 
  }
}
new TimeSince;