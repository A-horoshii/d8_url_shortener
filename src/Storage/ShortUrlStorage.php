<?php
namespace Drupal\url_shortener\Storage;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\url_shortener\Entity\ShortUrlInterface;

/**
 * Storage class for ShortUrl.
 *
 * This extends the Drupal\Core\Entity\Sql\SqlContentEntityStorage class,
 * adding required special handling for user objects.
 */
class ShortUrlStorage extends SqlContentEntityStorage implements ShortUrlStorageInterface
{
    /**
     * find ShortUrl by hash
     * @param string $hash
     * @return  \Drupal\url_shortener\Entity\ShortUrlInterface|null
     */
    public function findByHash(string $hash): ?ShortUrlInterface
    {
        var_dump($this->entityType->getBaseTable());
       $obj = $this->database->select($this->entityType->getBaseTable(), 'su')
            ->fields('su')
            ->condition('su.hash', $hash)
            ->range(0, 1)
            ->execute()
            ->fetchObject();
        var_dump($obj);
       return $obj;
    }
}