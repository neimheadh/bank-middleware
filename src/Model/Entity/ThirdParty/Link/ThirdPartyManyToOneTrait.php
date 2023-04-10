<?php

namespace App\Model\Entity\ThirdParty\Link;

use App\Entity\ThirdParty\ThirdParty;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity linked with third party trait.
 */
trait ThirdPartyManyToOneTrait
{

    /**
     * Entity third party.
     *
     * @var ThirdParty|null
     */
    #[ORM\ManyToOne(targetEntity: ThirdParty::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'third_party_id', onDelete: 'SET NULL')]
    private ?ThirdParty $thirdParty = null;

    /**
     * {@inheritDoc}
     */
    public function getThirdParty(): ?ThirdParty
    {
        return $this->thirdParty;
    }

    /**
     * {@inheritDoc}
     */
    public function setThirdParty(?ThirdParty $thirdParty): self
    {
        $this->thirdParty = $thirdParty;

        return $this;
    }

}