<?php

namespace Drupal\url_shortener;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Routing\RedirectDestinationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a class to build a listing of ShortUrl entities.
 *
 * @see \Drupal\url_shortener\Entity\ShortUrl
 */
class ShortUrlListBuilder extends EntityListBuilder
{

    /**
     * The date formatter service.
     *
     * @var \Drupal\Core\Datetime\DateFormatterInterface
     */
    protected $dateFormatter;

    /**
     * The redirect destination service.
     *
     * @var \Drupal\Core\Routing\RedirectDestinationInterface
     */
    protected $redirectDestination;

    /**
     * Constructs a new ShortUrlListBuilder object.
     *
     * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
     *   The entity type definition.
     * @param \Drupal\Core\Entity\EntityStorageInterface $storage
     *   The entity storage class.
     * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
     *   The date formatter service.
     * @param \Drupal\Core\Routing\RedirectDestinationInterface $redirect_destination
     *   The redirect destination service.
     */
    public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatterInterface $date_formatter, RedirectDestinationInterface $redirect_destination)
    {
        parent::__construct($entity_type, $storage);
        $this->dateFormatter = $date_formatter;
        $this->redirectDestination = $redirect_destination;
    }

    /**
     * {@inheritdoc}
     */
    public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type)
    {
        return new static(
            $entity_type,
            $container->get('entity_type.manager')->getStorage($entity_type->id()),
            $container->get('date.formatter'),
            $container->get('redirect.destination')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildHeader()
    {
        $header = [
            'id' => [
                'data' => $this->t('id'),
                'field' => 'id',
                'specifier' => 'id',
            ],
            'hash' => [
                'data' => $this->t('hash'),
                'field' => 'hash',
                'specifier' => 'hash',
            ],
            'url' => [
                'data' => $this->t('url'),
                'field' => 'url',
                'specifier' => 'url',
            ],
            'redirect_quantity' => [
                'data' => $this->t('Redirect quantity'),
                'field' => 'redirect_quantity',
                'specifier' => 'redirect_quantity',
            ],
            'time_life_end' => [
                'data' => $this->t('time life end'),
                'field' => 'time_life_end',
                'specifier' => 'time_life_end',
            ],
            'created' => [
                'data' => $this->t('created'),
                'field' => 'created',
                'specifier' => 'created',
            ],
            'updated' => [
                'data' => $this->t('updated'),
                'field' => 'updated',
                'specifier' => 'updated',
            ],

        ];
        return $header + parent::buildHeader();
    }
    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity) {
        /* @var $entity \Drupal\url_shortener\Entity\ShortUrl */
        $row['id'] = $entity->getId();
        $row['hash'] = $entity->getHash();
        $row['url'] = $entity->getUrl();
        $row['time_life_end'] = $entity->getTimeLifeEnd();
        $row['created'] = $entity->getCreated();
        $row['redirect_quantity'] = $entity->getRedirectQuantity();
        return $row + parent::buildRow($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function getOperations(EntityInterface $entity)
    {
        $operations = parent::getOperations($entity);
        if (isset($operations['edit'])) {
            $destination = $this->redirectDestination->getAsArray();
            $operations['edit']['query'] = $destination;
        }
        return $operations;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $build = parent::render();
        $build['table']['#empty'] = $this->t('No urls available.');
        return $build;
    }

}
