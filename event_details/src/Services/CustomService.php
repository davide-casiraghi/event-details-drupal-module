<?php

namespace Drupal\event_details\Services;

/**
 * Class CustomService.
 */
class CustomService {

  /**
   * Constructs a new CustomService object.
   */
  public function __construct() {

  }

  /**
   * Return the date difference between the date parameter and today
   */
  public function getDateTodayDiff($date) {
    
    $now = new \DateTime();
    
    return (int)$now->diff($date)->format("%r%a");
  }
  
  
}
