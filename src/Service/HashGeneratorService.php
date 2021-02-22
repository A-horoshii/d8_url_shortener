<?php
namespace Drupal\url_shortener\Service;

use Drupal\Core\Config\ConfigFactory;
use Hashids\Hashids;


/**
 * Hash generator service for help generate hash from value.
 */
class HashGeneratorService implements HashGeneratorServiceInterface
{

    /**
     * The config of url_shortener.
     *
     * @var ConfigFactory
     */
    protected $urlShortenerConfig;

    /**
     * The hashId class.
     *
     * @var Hashids
     */
    protected $hashId;

    /**
     * Constructs a HashGeneratorService object.
     *
     * @param ConfigFactory $config
     */
    public function __construct(ConfigFactory $config) {
        $this->urlShortenerConfig = $config->getEditable('url_shortener.settings');
        $this->hashId = new Hashids($this->urlShortenerConfig->get('hash_salt'),$this->urlShortenerConfig->get('hash_min_length'),$this->urlShortenerConfig->get('hash_alphabet'));
    }

    /**
     * generate hash string from int
     * @param  int $value
     * @return  string
     */
    public function generateHash(int $value): string
    {
        return $this->hashId->encode($value);
    }

}