<?php
namespace Drupal\url_shortener\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

interface ShortUrlInterface extends ContentEntityInterface, EntityChangedInterface
{
    public function getId();
    public function getUrl();
    public function setUrl(string $url);
    public function getHash();
    public function setHash($hash);
    public function getRedirectQuantity(): ?int;
    public function addRedirectQuantity($inc = 1);
    public function getTimeLifeEnd(): int;
}