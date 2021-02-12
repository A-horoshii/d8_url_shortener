<?php

namespace Drupal\url_shortener\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Routing\TrustedRedirectResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for the url shortener module.
 */
class UrlShortenController extends ControllerBase
{

    /**
     * Redirect action from short url to long path. If short url not founded, return 404.
     * @param string $hash
     * @return RedirectResponse|NotFoundHttpException
     */
    public function redirectAction(string $hash)
    {
        $redirectResponse = new TrustedRedirectResponse('https://translate.google.com/');
        $redirectResponse->setTrustedTargetUrl('https://translate.google.com/');
        if ($redirectResponse instanceof TrustedRedirectResponse) {
            return $redirectResponse;
        } else {
            throw new NotFoundHttpException("Page not found");
        }
    }
}
