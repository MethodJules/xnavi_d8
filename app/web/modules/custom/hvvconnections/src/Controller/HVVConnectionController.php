<?php

namespace Drupal\hvvconnections\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Class HVVConnectionController.
 */
class HVVConnectionController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello($name) {


    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: hello with parameter(s): $name'),
    ];
  }



  public function showStations() {
      $stations = $this->getStations(22);
      //dsm($stations);


      for($i=0; $i<count($stations); $i++) {
       if($i < count($stations)-1) {
           $start = $stations[$i]['streetname'];
           $destination = $stations[$i+1]['streetname'];
           $data[] = [

               $stations[$i]['station_name'],
               $stations[$i]['streetname'],
               $this->getHVVLink($start, $destination),
           ];
       } else {

           $data[] = [

               $stations[$i]['station_name'],
               $stations[$i]['streetname'],
               $this->t('No Link'),
           ];
       }
      }

      //create table
      //first image|monument name|address|public transport link
      //return table
      $build['table'] = [
          '#type' => 'table',
          '#caption' => $this->t('Routenplan'),
          '#header' => [$this->t('Gedenkstätte'), $this->t('Adresse'), $this->t('ÖPNV')],
          '#rows' => $data,
          ];

      return $build;
  }

  public function getHVVLink($start, $destination) {
      $client = \Drupal::httpClient();
      $request = $client->post('https://www.hvv.de/linking-service/create', [
          'json' => [
              'destination' => $start,
              'destinationRegion' => 'Hamburg',
              'language' => 'DE',
              'start' => $destination,
              'startRegion' => 'Hamburg',
          ]
      ]);

      $response = json_decode($request->getBody());

      //Provide a link in the drupal way
      $link = Link::fromTextAndUrl($this->t('HVV zur nächsten Gedenkstätte'), Url::fromUri($response->link, array('attributes' => array('target' => '_blank'))));
      return $link;

  }

  public function getStations($nid) {
      //$nid = 22;     // example value
      $node_storage = \Drupal::entityTypeManager()->getStorage('node');
      $node = $node_storage->load($nid);


      foreach ($node->field_station as $reference) {

          // if you chose "Entity ID" as the display mode for the entity reference field,
          // the target_id is the ONLY value you will have access to
           $station['nid'] = $reference->target_id;    // 1 (a node's nid)

          // if you chose "Rendered Entity" as the display mode, you'll be able to
          // access the rest of the node's data.
          $station['station_name'] = $reference->entity->title->value;    // "Moby Dick"
          //$nid_of_the_station = $reference->target_id;
          //Get location point
          $station_node = $node_storage->load($reference->target_id);
          $station['location_point'] = $station_node->field_location->value;
          //get the street name
          $station['streetname'] = $this->getStreetname($reference->target_id);

          $stations[] = $station;
      }


      //dsm($stations);

      return $stations;

  }

  public function getStreetname($entity_id) {
      $connec = \Drupal::database();
      $query = $connec->select('node__field_adresse', 'a');
      $query->condition('entity_id', $entity_id, '=');
      $query->fields('a', ['field_adresse_address_line1']);
      $result = $query->execute();

      foreach($result as $record) {
          $station_streetname = $record->field_adresse_address_line1;
      };

      return $station_streetname;
  }

}
