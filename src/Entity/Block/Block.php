<?php

namespace App\Entity\Block;

use App\Form\Generic\Type\ArrayType;
use App\Form\Generic\Type\ClassChoiceType;
use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Repository\Block\BlockRepository;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;
use Sonata\BlockBundle\Model\Block as SonataBlock;
use Sonata\BlockBundle\Model\BlockInterface;
use Stringable;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Sonata block.
 */
#[ORM\Entity(repositoryClass: BlockRepository::class)]
#[ORM\Table(name: 'block_block')]
#[Sonata\Admin(group: 'Settings',
    formFields: [
        'class' => new Sonata\FormField(
            type: ClassChoiceType::class,
            options: [
                ClassChoiceType::OPTION_NAMESPACE => 'App\Block',
            ]
        ),
        'type' => new Sonata\FormField(
            type: ChoiceType::class,
            options: [
                'choices' => [
                    'dashboard' => 'dashboard',
                    'list' => 'list',
                    'show' => 'show',
                    'edit' => 'edit',
                ],
            ]
        ),
        'settings' => new Sonata\FormField(
            type: ArrayType::class,
            options: ['required' => false]
        ),
    ]
)]
class Block implements EntityInterface, Stringable
{

    use EntityTrait;

    /**
     * Block service class.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 256)]
    private ?string $class = null;

    /**
     * Block settings.
     *
     * @var array|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $settings = null;

    /**
     * Block type.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 256)]
    private ?string $type = null;

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {
        return "$this->class";
    }

    /**
     * Get block service class.
     *
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * Get block settings.
     *
     * @return array|null
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }

    /**
     * Get type.
     *
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * Get sonata block.
     *
     * @return BlockInterface
     */
    public function getSonataBlock(): BlockInterface
    {
        $block = new SonataBlock();
        $block->setId($this->id);
        $block->setType($this->class);
        $this->settings !== null && $block->setSettings($this->settings);

        return $block;
    }

    /**
     * Set block service class.
     *
     * @param string|null $class Block service class.
     *
     * @return $this
     */
    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Set block settings.
     *
     * @param array|null $settings Block settings.
     *
     * @return $this
     */
    public function setSettings(?array $settings): self
    {
        $this->settings = $settings;

        return $this;
    }

    /**
     * Set type.
     *
     * @param string|null $type Block type.
     *
     * @return $this
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

}