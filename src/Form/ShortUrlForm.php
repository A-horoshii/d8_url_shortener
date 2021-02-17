<?php
namespace Drupal\url_shortener\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

class ShortUrlForm extends ContentEntityForm {

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $formState) {
        /* @var $entity \Drupal\url_shortener\Entity\ShortUrl */
        $form = parent::buildForm($form, $formState);
        $entity = $this->entity;
        $form['short_url']['url'] = [
            '#title' => $this->t('Url'),
            '#type' => 'textfield',
            '#default_value' => $entity->getUrl(),
        ];
        $form['short_url']['time_life_end'] = [
            '#title' => $this->t('Time life end'),
            '#type' => 'datetime',
            '#default_value' => $entity->getTimeLifeEnd()?DrupalDateTime::createFromTimestamp($entity->getTimeLifeEnd()):'',
        ];
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $formState) {
        $formState->setRedirect('entity.short_url.collection');
        $entity = $this->getEntity();
        $entity->save();
    }

}
