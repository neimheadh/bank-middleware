<?php

namespace App\Entity\Import;

use App\Form\Type\ArrayType;
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
     * Profile code.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 128, unique: true)]
    #[Sonata\FormField(position: 1)]
    #[Sonata\ListField(position: 1)]
    private ?string $code = null;

    /**
     * Profile name.
     *
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 256)]
    #[Sonata\FormField(position: 2)]
    #[Sonata\ListField(position: 2)]
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
            ClassChoiceType::OPTION_DEPTH => 0,
        ],
    )]
    #[ORM\Column(type: 'string', length: 128)]
    private ?string $processor = null;

    /**
     * Processor configuration.
     *
     * @var array
     */
    #[Sonata\FormField(type: ArrayType::class, position: 6)]
    #[ORM\Column(type: 'json', options: ['default' => '[]'])]
    private array $processorConfiguration = [];

    /**
     * Reader class.
     *
     * @var string|null
     */
    #[Sonata\FormField(
        type: ClassChoiceType::class,
        position: 3,
        options: [
            ClassChoiceType::OPTION_NAMESPACE => 'App\Import\Reader',
            ClassChoiceType::OPTION_DEPTH => 0,
        ],
    )]
    #[ORM\Column(type: 'string', length: 128)]
    private ?string $reader = null;

    /**
     * Reader configuration.
     *
     * @var array
     */
    #[Sonata\FormField(type: ArrayType::class, position: 4)]
    #[ORM\Column(type: 'json', options: ['default' => '[]'])]
    private array $readerConfiguration = [];

    /**
     * Writer class.
     *
     * @var string|null
     */
    #[Sonata\FormField(
        type: ClassChoiceType::class,
        position: 7,
        options: [
            ClassChoiceType::OPTION_NAMESPACE => 'App\Import\Writer',
            ClassChoiceType::OPTION_DEPTH => 0,
        ],
    )]
    #[ORM\Column(type: 'string', length: 128)]
    private ?string $writer = null;

    /**
     * Processor configuration.
     *
     * @var array
     */
    #[Sonata\FormField(type: ArrayType::class, position: 8)]
    #[ORM\Column(type: 'json', options: ['default' => '[]'])]
    private array $writerConfiguration = [];

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
     * Get processor configuration.
     *
     * @return array
     */
    public function getProcessorConfiguration(): array
    {
        return $this->processorConfiguration;
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
     * Reader configuration.
     *
     * @return array
     */
    public function getReaderConfiguration(): array
    {
        return $this->readerConfiguration;
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
     * Get writer configuration.
     *
     * @return array
     */
    public function getWriterConfiguration(): array
    {
        return $this->writerConfiguration;
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
     * Set processor configuration.
     *
     * @param array $processorConfiguration Processor configuration.
     *
     * @return $this
     */
    public function setProcessorConfiguration(
        array $processorConfiguration
    ): self {
        $this->processorConfiguration = $processorConfiguration;

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
     * Set reader configuration.
     *
     * @param array $readerConfiguration Reader configuration.
     *
     * @return $this
     */
    public function setReaderConfiguration(array $readerConfiguration): self
    {
        $this->readerConfiguration = $readerConfiguration;

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

    /**
     * Set writer configuration.
     *
     * @param array $writerConfiguration Writer configuration.
     *
     * @return $this
     */
    public function setWriterConfiguration(array $writerConfiguration): self
    {
        $this->writerConfiguration = $writerConfiguration;

        return $this;
    }

}