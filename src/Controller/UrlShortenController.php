<?php

namespace Drupal\url_shortener\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for the url shortener module.
 */
class UrlShortenController extends ControllerBase
{

    /**
     * The ShortUrl service.
     *
     * @var \Drupal\url_shortener\Service\ShortUrlService
     */
    protected $shortUrlService;

    public function __construct($shortUrlService)
    {
        $this->shortUrlService = $shortUrlService;
    }

    public static function create(ContainerInterface $container) {
        $shortUrlService = $container->get('url_shortener.service.short_url');
        return new static ($shortUrlService);
    }

    /**
     * Redirect action from short url to long path. If short url not founded, return 404.
     * @param string $hash
     * @return RedirectResponse|NotFoundHttpException
     */
    public function redirectAction(string $hash)
    {
        $redirectResponse = $this->shortUrlService->shortUrlRedirect($hash);
        if ($redirectResponse instanceof TrustedRedirectResponse) {
            return $redirectResponse;
        } else {
            throw new NotFoundHttpException("Page not found");
        }
    }
}
