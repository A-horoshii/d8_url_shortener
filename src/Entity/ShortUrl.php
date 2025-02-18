<?php
namespace Drupal\url_shortener\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the ShortUrl entity class.
 *
 *
 * @ContentEntityType(
 *   id = "url_shortner_short_url",
 *   label = @Translation("Short url"),
 *   label_collection = @Translation("Short url"),
 *   label_singular = @Translation("Short url"),
 *   label_plural = @Translation("Short urls"),
 *   label_count = @PluralTranslation(
 *     singular = "@count short url",
 *     plural = "@count short urls",
 *   ),
 *   handlers = {
 *     "storage" = "Drupal\url_shortener\Storage\ShortUrlStorage",
 *     "list_builder" = "Drupal\url_shortener\ShortUrlListBuilder",
 *     "form" = {
 *       "default" = "Drupal\url_shortener\Form\ShortUrlForm",
 *       "create" = "Drupal\url_shortener\Form\ShortUrlCreateForm",
 *       "delete" = "Drupal\url_shortener\Form\ShortUrlDeleteForm"
 *     }
 *   },
 *   admin_permission = "administer short_urls",
 *   base_table = "url_shortener_urls",
 *   translatable = FALSE,
 *   entity_keys = {
 *     "id" = "id",
 *     "hash" = "hash",
 *     "label" = "id"
 *   },
 *   links = {
 *     "canonical" = "/admin/short_urls/{url_shortner_short_url}",
 *     "edit-form" = "/admin/short_urls/{url_shortner_short_url}/edit",
 *     "delete-form" = "/admin/short_urls/{url_shortner_short_url}/delete",
 *     "collection" = "/admin/short_urls",
 *   },
 *   common_reference_target = TRUE
 * )
 */
class ShortUrl  extends ContentEntityBase implements ShortUrlInterface
{
    use EntityChangedTrait;

    /**
     * {@inheritdoc}
     */
    public function isNew() {
        return !empty($this->enforceIsNew) || $this->id() === NULL;
    }

    /**
     * {@inheritdoc}
     */
    public function label() {
        return $this->get('hash')->value;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->get('id')->value;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->get('hash')->value;
    }

    /**
     * @param string $hash
     *
     * @return $this
     */
    public function setHash($hash)
    {
        $this->get('hash')->value = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->get('url')->value;
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setUrl(string $url)
    {
        $this->get('url')->value = $url;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeLifeEnd(): int
    {
        return $this->get('time_life_end')->value;
    }

    /**
     * @param int $timestamp
     *
     * @return $this
     */
    public function setTimeLifeEnd(int $timestamp): self
    {
        $this->get('time_life_end')->value = $timestamp;

        return $this;
    }

    /**
     * @return int
     */
    public function getRedirectQuantity(): ?int
    {
        return  $this->get('redirect_quantity')->value;
    }

    /**
     * @param int $redirectQuantity
     *
     * @return $this
     */
    public function setRedirectQuantity(int $redirectQuantity)
    {
        $this->get('redirect_quantity')->value = $redirectQuantity;

        return $this;
    }

    /**
     * @param int $inc
     *
     * @return $this
     */
    public function addRedirectQuantity($inc = 1)
    {
        $this->get('redirect_quantity')->value += $inc;

        return $this;
    }

    /**
     * Gets the timestamp of the entity created.
     *
     * @return int
     *   The timestamp of the last entity save operation.
     */
    public function getCreated() {
        return $this->get('created')->value;
    }

    /**
     * Sets the timestamp of the entity created.
     *
     * @param int $timestamp
     *   The timestamp of the last entity save operation.
     *
     * @return $this
     */
    public function setCreated($timestamp) {
        $this->set('created', $timestamp);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
        /** @var \Drupal\Core\Field\BaseFieldDefinition[] $fields */
        $fields = parent::baseFieldDefinitions($entity_type);
        $fields['id'] = BaseFieldDefinition::create('integer')
            ->setLabel(t('id'));

        $fields['hash'] = BaseFieldDefinition::create('string')
            ->setLabel(t('hash'));

        $fields['url'] = BaseFieldDefinition::create('string')
            ->setRequired(true)
            ->setPropertyConstraints(
                'value', ['Regex' => [
                    'pattern' => '%^(?:(?:https?|ftp)://)(?:\S+(?::\S*)?@|\d{1,3}(?:\.\d{1,3}){3}|(?:(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)(?:\.(?:[a-z\d\x{00a1}-\x{ffff}]+-?)*[a-z\d\x{00a1}-\x{ffff}]+)*(?:\.[a-z\x{00a1}-\x{ffff}]{2,6}))(?::\d+)?(?:[^\s]*)?$%iu',
                    'message' => t('Url field not valid.'),
                ]]
            )
            ->setLabel(t('url'));

        $fields['redirect_quantity'] = BaseFieldDefinition::create('integer')
            ->setDefaultValue(0)
            ->setLabel(t('Redirect quantity'));

        $fields['time_life_end'] = BaseFieldDefinition::create('timestamp')
            ->setRequired(true)
            ->setLabel(t('Time life end'))
            ->setDescription(t('The time off redirect by short url.'));

        $fields['created'] = BaseFieldDefinition::create('created')
            ->setLabel(t('Created'))
            ->setDescription(t('The time that the Short url was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
            ->setLabel(t('Changed'))
            ->setDescription(t('The time that the Short url was last edited.'));
        return $fields;
    }


    /**
     * {@inheritdoc}
     */
    public function postSave(EntityStorageInterface $storage, $update = TRUE) {
        parent::postSave($storage, $update);
        if (empty($this->getHash())) {
            $shortUrlService = \Drupal::getContainer()
                ->get('url_shortener.service.short_url');
            $this->setHash($shortUrlService->shortUrlGetHash($this));
            $this->save();
        }
    }
}
