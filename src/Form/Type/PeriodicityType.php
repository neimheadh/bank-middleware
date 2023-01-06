<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Periodicity form input.
 */
class PeriodicityType extends AbstractType implements DataTransformerInterface
{

    /**
     * Default periods.
     */
    public const DEFAULT_PERIODS = [
      'periodicity.period.hourly' => self::PERIOD_HOURLY,
      'periodicity.period.daily' => self::PERIOD_DAILY,
      'periodicity.period.weekly' => self::PERIOD_WEEKLY,
      'periodicity.period.monthly' => self::PERIOD_MONTHLY,
      'periodicity.period.yearly' => self::PERIOD_YEARLY,
    ];

    /**
     * Default period option name.
     */
    public const OPTION_PERIOD = 'period';

    /**
     * Periods option name.
     */
    public const OPTION_PERIODS = 'periods';

    /**
     * Hour period value.
     */
    public const PERIOD_HOURLY = 'hour';

    /**
     * Day period value.
     */
    public const PERIOD_DAILY = 'day';

    /**
     * Week period value.
     */
    public const PERIOD_WEEKLY = 'week';

    /**
     * Month period value.
     */
    public const PERIOD_MONTHLY = 'month';

    /**
     * Year period value.
     */
    public const PERIOD_YEARLY = 'year';

    /**
     * {@inheritDoc}
     */
    public function buildForm(
      FormBuilderInterface $builder,
      array $options
    ): void {
        $builder->add('count', IntegerType::class, [
          'data' => 1,
          'label' => false,
        ])->add('period', ChoiceType::class, [
          'choices' => $options[self::OPTION_PERIODS],
          'label' => false,
        ])->addModelTransformer($this);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('translation_domain', 'form');
        $resolver->setDefault(self::OPTION_PERIOD, null);
        $resolver->setDefault(self::OPTION_PERIODS, self::DEFAULT_PERIODS);

        $resolver->addAllowedTypes(self::OPTION_PERIOD, ['string', 'null']);
        $resolver->addAllowedTypes(self::OPTION_PERIODS, ['array']);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix(): string
    {
        return 'periodicity';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent(): string
    {
        return FormType::class;
    }

    /**
     * {@inheritDoc}
     */
    public function reverseTransform(mixed $value): ?string
    {
        if ($value !== null) {
            $value = implode(' ', $value);
        }

        return $value;
    }

    /**
     * {@inheritDoc}
     */
    public function transform(mixed $value): ?array
    {
        if ($value !== null) {
            $str = $value;
            $value = [];
            [$value['count'], $value['period']] = explode(' ', $str);
        }

        return $value;
    }

}