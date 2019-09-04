<?php
/**
 * Created by PhpStorm.
 * User: julien
 * Date: 04.09.19
 * Time: 11:41
 */

namespace Drupal\src\hvvconnection\Form;


use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class HVVConnectionForm extends FormBase
{
    public function getFormId()
    {
        return 'hvvconnection_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {
        $form['select'] = [
            '#type' => 'select',
            '#title' => $this->t('Route'),
            '#options' => [
                22 => 'Route1',
                23 => 'Route2',
            ],
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit')
        ];


    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        drupal_set_message('Route @route wurde ausgewählt.', array('@route' => $form_state['select']));
        $form_state->setRebuild();
    }

}