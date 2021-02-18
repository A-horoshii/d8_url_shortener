<?php
namespace Drupal\url_shortener\Service;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Drupal\url_shortener\Entity\ShortUrlInterface;

/**
 * .
 */
class ShortUrlService implements ShortUrlServiceInterface
{

    /**
     * The entity type manager.
     *
     * @var \Drupal\Core\Entity\EntityTypeManagerInterface
     */
    protected $entityTypeManager;

    /**
     * The hash generator service.
     *
     * @var \Drupal\url_shortener\Service\HashGeneratorServiceInterface
     */
    protected $hashGenerator;

    /**
     * Constructs a UserAuth object.
     *
     * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
     *   The entity type manager.
     * @param \Drupal\url_shortener\Service\HashGeneratorServiceInterface $hashGenerator
     *   The hashGenerator service.
     */
    public function __construct(EntityTypeManagerInterface $entityTypeManager, HashGeneratorServiceInterface $hashGenerator) {
        $this->entityTypeManager = $entityTypeManager;
        $this->hashGenerator = $hashGenerator;
    }

    /**
     * find ShortUrl by hash add +1 redirectQuantity and return TrustedRedirectResponse
     * @param string $hash
     * @return  TrustedRedirectResponse|null
     * @throws
     */
    public function shortUrlRedirect(string $hash): ?TrustedRedirectResponse
    {
        $redirectResponse = null;
        $shortUrl = $this->entityTypeManager->getStorage('url_shortner_short_url')->findByHash($hash);
        if ($shortUrl instanceof ShortUrlInterface && $shortUrl->getTimeLifeEnd()>time()) {
           $shortUrl->addRedirectQuantity(1);
           $shortUrl->save();
           $redirectResponse = new TrustedRedirectResponse($shortUrl->getUrl());
           $redirectResponse->setTrustedTargetUrl($shortUrl->getUrl());
        }
        return $redirectResponse;
    }

    /**
     * generate hash for ShortUrl
     * @param  ShortUrlInterface $shortUrl
     * @return  string
     */
    public function shortUrlGetHash(ShortUrlInterface $shortUrl): string
    {
        return $this->hashGenerator->generateHash($shortUrl->getId());
    }
}