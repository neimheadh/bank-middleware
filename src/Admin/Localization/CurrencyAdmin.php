<?php

declare(strict_types=1);

namespace App\Admin\Localization;

use App\Admin\AbstractAdmin;
use App\Model\Admin\DatedEntityAdminTrait;
use App\Model\Admin\NamedEntityAdminTrait;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

/**
 * Currency admin configurator.
 */
final class CurrencyAdmin extends AbstractAdmin
{
    use DatedEntityAdminTrait;
    use NamedEntityAdminTrait;

    protected function configureFields(
      FormMapper|DatagridMapper|ListMapper|ShowMapper $mapper
    ): void {
        $this->addNameField($mapper);

        $mapper->add('char')
            ->add('iso');

        $this->addLifecycleDateFields($mapper);
    }

}
