<?php

declare(strict_types=1);

namespace App\Serialization\Denormalizer;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class UserDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;
    public const ALREADY_CALLED = 'USER_DENORMALIZER_ALREADY_CALLED';

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher, private readonly Security $security)
    {
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = [])
    {
        $context[self::ALREADY_CALLED] = true;
        if (isset($data['password'])) {
            /** @var User $user */
            $user = $this->security->getUser();
            $data['password'] = $this->passwordHasher->hashPassword($user, $data['password']);
        }

        return $this->denormalizer->denormalize($data, $type, $format, $context);
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return !isset($context[self::ALREADY_CALLED]) && User::class === $type;
    }
}
