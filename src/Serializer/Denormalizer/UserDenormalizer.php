<?php

namespace App\Serializer\Denormalizer;

use App\Entity\Department;
use App\Entity\User;
use App\Services\DenormalizeHelper;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class UserDenormalizer implements ContextAwareDenormalizerInterface
{

    private ObjectNormalizer $normalizer;
    private EntityManagerInterface $em;

    public function __construct(
        ObjectNormalizer       $normalizer,
        EntityManagerInterface $em
    )
    {
        $this->normalizer = $normalizer;
        $this->em = $em;
    }

    public function supportsDenormalization($data, string $type, string $format = null, array $context = []):bool
    {
        return $type === User::class;
    }

    /**
     * @throws ExceptionInterface
     * @throws ORMException
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        /** @var User $object */
        $object = $context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? null;

        if ($object) {
          if (isset($data['department']['id'])) {
              $refObject = $this->em->getReference(Department::class, $data['department']['id']);
              $object->setDepartment($refObject);
          }
        }

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }
}