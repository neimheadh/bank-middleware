<?php

namespace App\Model\Entity\ThirdParty\Link;

use App\Entity\ThirdParty\ThirdParty;

/**
 * Entity linked with third party.
 */
interface ThirdPartyManyToOneInterface
{

    /**
     * Get third party.
     *
     * @return ThirdParty|null
     */
    public function getThirdParty(): ?ThirdParty;

    /**
     * Set third party.
     *
     * @param ThirdParty|null $thirdParty Third party.
     *
     * @return $this
     */
    public function setThirdParty(?ThirdParty $thirdParty): self;
}