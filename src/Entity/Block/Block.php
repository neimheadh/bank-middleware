<?php

namespace App\Entity\Block;

use App\Form\Generic\Type\ClassChoiceType;
use App\Form\Generic\Type\YamlType;
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
#[ORM\Table(name: 'app_block_block')]
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
                    'dashboard' => Block::TYPE_DASHBOARD,
                    'list' => Block::TYPE_LIST,
                    'show' => Block::TYPE_SHOW,
                    'edit' => Block::TYPE_EDIT,
                ],
            ]
        ),
        'position' => new Sonata\FormField(
            type: ChoiceType::class,
            options: [
                'choices' => [
                    'top' => Block::POSITION_TOP,
                    'left' => Block::POSITION_LEFT,
                    'center' => Block::POSITION_CENTER,
                    'right' => Block::POSITION_RIGHT,
                    'bottom' => Block::POSITION_BOTTOM,
                ],
            ]
        ),
        'settings' => new Sonata\FormField(
            type: YamlType::class,
            options: ['required' => false]
        ),
    ]
)]
class Block implements EntityInterface, Stringable
{

    /**
     * Block bottom position.
     */
    public const POSITION_BOTTOM = 5;

    /**
     * Block center position.
     */
    public const POSITION_CENTER = 3;

    /**
     * Block left position.
     */
    public const POSITION_LEFT = 2;

    /**
     * Block right position.
     */
    public const POSITION_RIGHT = 4;

    /**
     * Block top position.
     */
    public const POSITION_TOP = 1;

    /**
     * Dashboard block.
     */
    public const TYPE_DASHBOARD = 1;

    /**
     * Edit block.
     */
    public const TYPE_EDIT = 2;

    /**
     * List block.
     */
    public const TYPE_LIST = 3;

    /**
     * Show block.
     */
    public const TYPE_SHOW = 4;

    use EntityTrait;

    /**
     * Block service class.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 256)]
    private ?string $class = null;

    /**
     * Block position.
     *
     * @var int|null
     */
    #[ORM\Column(type: 'smallint')]
    private ?int $position = null;

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
     * @var int|null
     */
    #[ORM\Column(type: 'smallint')]
    private ?int $type = null;

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
     * Get position.
     *
     * @return int|null
     */
    public function getPosition(): ?int
    {
        return $this->position;
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
     * @return int|null
     */
    public function getType(): ?int
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
     * Set position.
     *
     * @param int|null $position Position.
     *
     * @return $this
     */
    public function setPosition(?int $position): self
    {
        $this->position = $position;

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
     * @param int|null $type Block type.
     *
     * @return $this
     */
    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }

}