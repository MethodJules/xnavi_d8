<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 31.05.19
 * Time: 18:07
 */

namespace Drupal\xnavi_bi\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\xnavi_bi\Data\Data;
use Symfony\Component\HttpFoundation\JsonResponse;

class XNaviBIController extends ControllerBase
{
    public function content($chartType, $vocabulary) {
        global $base_url;

        $render_html = ['#markup' => '<p>C3 are coming...</p><h1>Stillen</h1><div id="chart"></div><h1>Testvokabular</h1><div id="chart2"></div>'];
        $render_html['#attached']['library'][] = 'xnavi_bi/xnavi-bi';
        $render_html['#attached']['drupalSettings']['baseUrl'] = $base_url;
        $render_html['#attached']['drupalSettings']['chartType'] = $chartType;
        $render_html['#attached']['drupalSettings']['vocabulary'] = $vocabulary;


        return $render_html;
    }

    public function getData($vocabulary) {

        $vocabularyKeys = $vocabulary;

        //$vocabulariesList = $this->getAllVocabularies();
        //$vocabularyKeys = array_keys($vocabulariesList);

        //Unset not necessary vocabularies
        //$needles = ['forums', 'tags'];
        //foreach ($needles as $needle) {
        //    if (($key = array_search($needle, $vocabularyKeys)) !== false) {
        //        unset($vocabularyKeys[$key]);
        //    }
        //}


        $data = array();
        //foreach ($vocabularyKeys as $vocabularyKey) {
            //dsm($vocabularyKey);
            //dsm($vocabularyKey);
            $terms = $this->getAllTaxonomyTermsOfAVocabulary($vocabulary);
            if(!is_null($terms)) {
                foreach ($terms as $term) {
                    //dsm($term);
                    $term_data[] =
                        [
                            'term' => $term['name'],
                            'count' => $this->getCountOfNodesByTaxonomyTerms($term['id']),
                        ];

                }
                $voc_data[$vocabulary] = $term_data;
                $data[] = $voc_data;
            }
        //}


        $c3_data = array();
        foreach ($data as $dimension) {
            foreach ($dimension as $values) {
                foreach ($values as $value) {
                    $c3_data[$value['term']] = array($value['count']);
                };

            }
        }

        return new JsonResponse($c3_data);


    }

    public function getExampleData () {
        $dataObject = new Data();
        $data = $dataObject->getData();

        return new JsonResponse($data);
    }

    public function getCountOfNodesByTaxonomyTerms($termId) {
        //cast to an array
        $termIds = (array) $termId;
        if(empty($termIds)) {
            return NULL;
        }

        //dsm($termIds);

        $query = \Drupal::database()->select('taxonomy_index', 'ti');
        $query->fields('ti', array('nid'));
        $query->condition('ti.tid', $termIds, 'IN');
        $query->distinct(TRUE);
        $result = $query->execute();

        $nodeIds = $result->fetchCol();
        $nodes = Node::loadMultiple($nodeIds);

         return count($nodes);

    }

    public function getAllTaxonomyTermsOfAVocabulary($vocabulary) {
        $vid = $vocabulary;
        $terms =\Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree($vid);
        $term_data = array();
        foreach ($terms as $term) {
            $term_data[] = array(
                'id' => $term->tid,
                'name' => $term->name
            );
        }

        return $term_data;
    }

    public function getAllVocabularies() {
        $vocabularies = Vocabulary::loadMultiple();
        $vocabulariesList = [];
        foreach ($vocabularies as $vid => $vocabulary) {
            $vocabulariesList[$vid] = $vocabulary->get('name');
        }

        //dsm($vocabulariesList);
        return $vocabulariesList;
    }

    public function getTaxonomyData() {
        $vocabulariesList = $this->getAllVocabularies();
        $termsList = $this->getAllTaxonomyTermsOfAVocabulary($vocabulariesList['stillen']);


    }


}