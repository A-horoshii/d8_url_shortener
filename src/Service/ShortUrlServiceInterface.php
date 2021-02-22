<?php
namespace Drupal\url_shortener\Service;

use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\url_shortener\Entity\ShortUrlInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Defines an interface for ShortUrlService classes.
 */
interface ShortUrlServiceInterface
{
    /**
     * find ShortUrl by hash
     * @param string $hash
     * @return  TrustedRedirectResponse|null
     */
    public function shortUrlRedirect(string $hash): ?TrustedRedirectResponse;

    /**
     * set hash to ShortUrl
     * @param  ShortUrlInterface $shortUrl
     * @return  string
     */
    public function shortUrlGetHash(ShortUrlInterface $shortUrl): string;

}