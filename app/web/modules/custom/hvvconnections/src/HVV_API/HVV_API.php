<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 04.09.19
 * Time: 11:37
 */

namespace Drupal\hvvconnections\HVV_API;

use Drupal\Core\Url;
use Drupal\Core\Link;
class HVV_API
{

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
}