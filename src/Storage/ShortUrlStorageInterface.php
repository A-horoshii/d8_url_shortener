<?php
namespace Drupal\url_shortener\Storage;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\url_shortener\Entity\ShortUrlInterface;

/**
 * Defines an interface for ShortUrl entity storage classes.
 */
interface ShortUrlStorageInterface extends ContentEntityStorageInterface
{
    /**
     * find ShortUrl by hash
     * @param string $hash
     * @return  \Drupal\url_shortener\Entity\ShortUrlInterface|null
     */
    public function findByHash(string $hash): ?ShortUrlInterface;
}