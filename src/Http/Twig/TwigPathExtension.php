<?php

namespace App\Http\Twig;

use App\Infrastructure\Image\ImageResizer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Vich\UploaderBundle\Storage\StorageInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class TwigPathExtension extends AbstractExtension
{
    /**
     * @var ImageResizer
     */
    private $imageResizer;
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;
    private StorageInterface $storage;

    public function __construct(
        ImageResizer $imageResizer,
        UploaderHelper $uploaderHelper,
        StorageInterface $storage
    )
    {
        $this->imageResizer   = $imageResizer;
        $this->uploaderHelper = $uploaderHelper;
        $this->storage        = $storage;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('image_url', [$this, 'imageUrl'])
        ];
    }

    public function imageUrl(?object $entity, ?int $width, ?int $height, $fieldName = NULL): ?string
    {
        if(null === $entity) {
            return null;
        }

        $path = $this->uploaderHelper->asset($entity);

        if(null === $path) {
            return null;
        }


        return $this->imageResizer->resize($this->uploaderHelper->asset($entity, $fieldName), $width, $height);

    }
}
