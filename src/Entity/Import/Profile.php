<?php

namespace App\Entity\Import;

use App\Form\Type\ClassChoiceType;
use App\Model\Entity\Generic\CodeEntityInterface;
use App\Model\Entity\Generic\CodeEntityTrait;
use App\Model\Entity\Generic\EntityInterface;
use App\Model\Entity\Generic\EntityTrait;
use App\Model\Entity\Generic\NamedEntityInterface;
use App\Model\Entity\Generic\NamedEntityTrait;
use App\Repository\Import\ProfileRepository;
use App\Translation\Translator\AdminTranslatorStrategy;
use Doctrine\ORM\Mapping as ORM;
use Neimheadh\SonataAnnotationBundle\Annotation\Sonata;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Import profile.
 */
#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ORM\Table(name: 'app_import_profile')]
#[Sonata\Admin(labelTranslatorStrategy: AdminTranslatorStrategy::class)]
class Profile implements EntityInterface,
                         NamedEntityInterface,
                         CodeEntityInterface
{

    use EntityTrait;
    use NamedEntityTrait;
    use CodeEntityTrait;

    /**
     * Import configuration.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'text', nullable: true)]
    #[Sonata\FormField(
        type: TextareaType::class,
        options: [
            'required' => false,
            'attr' => ['rows' => 15, 'class' => 'monospace']
        ]
    )]
    private ?string $configuration = null;

    /**
     * Profile code.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 128, unique: true)]
    #[Sonata\FormField(position: 1)]
    private ?string $code = null;

    /**
     * Profile name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 256)]
    #[Sonata\FormField(position: 3)]
    private ?string $name = null;

    /**
     * Processor class.
     *
     * @var string|null
     */
    #[Sonata\FormField(
        type: ClassChoiceType::class,
        position: 5,
        options: [
            ClassChoiceType::OPTION_NAMESPACE => 'App\Import\Processor',
        ],
    )]
    #[ORM\Column(type: 'string', length: 128)]
    private ?string $processor = null;

    /**
     * Reader class.
     *
     * @var string|null
     */
    #[Sonata\FormField(
        type: ClassChoiceType::class,
        position: 4,
        options: [
            ClassChoiceType::OPTION_NAMESPACE => 'App\Import\Reader',
        ],
    )]
    #[ORM\Column(type: 'string', length: 128)]
    private ?string $reader = null;

    /**
     * Writer class.
     *
     * @var string|null
     */
    #[Sonata\FormField(
        type: ClassChoiceType::class,
        position: 6,
        options: [
            ClassChoiceType::OPTION_NAMESPACE => 'App\Import\Writer',
        ],
    )]
    #[ORM\Column(type: 'string', length: 128)]
    private ?string $writer = null;

    /**
     * Get import configuration.
     *
     * @return string|null
     */
    public function getConfiguration(): ?string
    {
        return $this->configuration;
    }

    /**
     * Get processor class.
     *
     * @return string|null
     */
    public function getProcessor(): ?string
    {
        return $this->processor;
    }

    /**
     * Get reader class.
     *
     * @return string|null
     */
    public function getReader(): ?string
    {
        return $this->reader;
    }

    /**
     * Get writer class.
     *
     * @return string|null
     */
    public function getWriter(): ?string
    {
        return $this->writer;
    }

    /**
     * Set import configuration.
     *
     * @param string|null $configuration Import configuration.
     *
     * @return $this
     */
    public function setConfiguration(?string $configuration): self
    {
        $this->configuration = $configuration;

        return $this;
    }

    /**
     * Set processor class.
     *
     * @param string|null $processor Processor class.
     *
     * @return $this
     */
    public function setProcessor(?string $processor): self
    {
        $this->processor = $processor;

        return $this;
    }

    /**
     * Set reader class.
     *
     * @param string|null $reader Reader class.
     *
     * @return $this
     */
    public function setReader(?string $reader): self
    {
        $this->reader = $reader;

        return $this;
    }

    /**
     * Set writer class.
     *
     * @param string|null $writer Writer class.
     *
     * @return $this
     */
    public function setWriter(?string $writer): self
    {
        $this->writer = $writer;

        return $this;
    }

}