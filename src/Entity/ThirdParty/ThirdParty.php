<?php

namespace App\Entity\ThirdParty;

use App\Form\Generic\Attribute\AutocompletedEntity;
use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Model\Entity\Generic\NamedEntityInterface;
use App\Model\Entity\Generic\NamedEntityTrait;
use App\Repository\ThirdParty\ThirdPartyRepository;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;

/**
 * Account third party.
 */
#[AutocompletedEntity(field: 'name')]
#[ORM\Entity(repositoryClass: ThirdPartyRepository::class)]
#[ORM\Table(name: 'app_thirdparty_thirdparty')]
#[Sonata\Admin(
    formFields: [
        'name' => new Sonata\FormField(),
    ],
)]
class ThirdParty implements EntityInterface, NamedEntityInterface
{

    use EntityTrait;
    use NamedEntityTrait;
}