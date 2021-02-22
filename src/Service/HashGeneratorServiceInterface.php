<?php
namespace Drupal\url_shortener\Service;

use Drupal\url_shortener\Entity\ShortUrlInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Defines an interface for HashGeneratorService classes.
 */
interface HashGeneratorServiceInterface
{
    /**
     * generate hash string from int
     * @param integer $value
     * @return  string
     */
    public function generateHash(int $value): string;

}