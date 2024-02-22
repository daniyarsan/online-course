<?php

namespace App\Serializer\Denormalizer;

use App\Entity\Department;
use App\Entity\Quiz;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class QuizDenormalizer implements ContextAwareDenormalizerInterface
{

    public const DEPARTMENTS_KEY = 'departments';

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
        return $type === Quiz::class;
    }

    /**
     * @throws ExceptionInterface
     * @throws ORMException
     */
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        /** @var Quiz $object */
        $object = $context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? null;

        if ($object) {
            $data['timeLimit'] = (int) $data['timeLimit'];

            if (isset($data[self::DEPARTMENTS_KEY])) {
                $departmentsIds = array_column($data[self::DEPARTMENTS_KEY], 'id');

                $idsToRemove = array_diff($object->getDepartmentIds(), $departmentsIds);
                foreach ($idsToRemove as $idToRemove) {
                    $relationDepartmentToRemove = $this->em->getReference(Department::class, $idToRemove);
                    $object->removeDepartment($relationDepartmentToRemove);
                }

                foreach ($departmentsIds as $departmentId) {
                    $relationDepartment = $this->em->getReference(Department::class, $departmentId);
                    $object->addDepartment($relationDepartment);
                }
            }
        }

        return $this->normalizer->denormalize($data, $type, $format, $context);
    }
}