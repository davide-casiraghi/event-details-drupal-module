<?php
/**
 * @file
 * Contains \Drupal\event_details\Plugin\Block\EventDetailsBlock
 */
 namespace Drupal\event_details\Plugin\Block;
 
 use Drupal\Core\Block\BlockBase;
 use Drupal\Core\Session\AccountInterface;
 use Drupal\Core\Access\AccessResult;
 
 use Drupal\Core\Database\Database;


 /**
 * Provides a 'Event Details' Block
 *
 * @Block(
 *   id = "event_details_block",
 *   admin_label = @Translation("Event Details Block"),
 *   category = @Translation("Blocks")
 * )
 */  
class EventDetailsBlock extends BlockBase {


  /**
   * {@inheritdoc}
   */
  public function build() {

    $node = \Drupal::routeMatch()->getParameter('node');
    $nid = $node->nid->value;


    $select = Database::getConnection()->select('node_field_data', 'n');
    $select->join('node__field_event_date', 'd', 'n.nid = d.entity_id');
    $select->addField('d', 'field_event_date_value');
    $select->condition('n.nid', $nid);
    $entries = $select->execute()->fetchAll(\PDO::FETCH_ASSOC);
    
    $event_date = new \DateTime($entries[0]['field_event_date_value']);
    
  
    $service = \Drupal::service('event_details.custom_services');
    $todayDateDiff = $service->getDateTodayDiff($event_date); 
    
    if ($todayDateDiff > 0){
      $description = $todayDateDiff." days left until event starts.";
      
    }
    elseif($todayDateDiff < 0){
      $description = "This event already passed.";
    }
    else{
      $description = "This event is happening today.";
    }
    
    //var_dump($description);
      
    $build = [];
    $build['#theme'] = 'block__event_details_custom';
    $build['#description'] = $description;
    $build['#cache']['max-age'] = 0;  //disable cache
    
    return $build;
  }
}
