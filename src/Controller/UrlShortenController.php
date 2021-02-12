<?php

namespace Drupal\url_shortener\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\NodeInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for the url shortener module.
 */

class UrlShortenController extends ControllerBase
{

  /**
   * Redirect action from short url to long path. If short url not founded, return 404.
   * @param string $hash
   * @param Request $request
   * @return RedirectResponse|NotFoundHttpException
   */
  public function redirectAction(string $hash, Request $request)
  {
    var_dump($hash);
    $redirectResponse = new RedirectResponse('https://translate.google.com/');
    if ($redirectResponse instanceof RedirectResponse) {
      return $redirectResponse;
    } else {
      throw new NotFoundHttpException("Page not found");
    }
  }
}
