<?php

declare(strict_types=1);

namespace App\Serialization\Denormalizer;

use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * @method array getSupportedTypes(?string $format)
 */
class UserDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;

    /**
     * @inheritDoc
     */
    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        // TODO: Implement denormalize() method.
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization(mixed $data, string $type, string $format = null)
    {
        // TODO: Implement supportsDenormalization() method.
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method array getSupportedTypes(?string $format)
    }
}