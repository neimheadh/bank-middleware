<?php

namespace App\Model\Entity\Budget\Link;

use App\Entity\Budget\Budget;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entity with budget trait.
 */
trait BudgetManyToOneTrait
{

    /**
     * Budget.
     *
     * @var Budget|null
     */
    #[ORM\ManyToOne(targetEntity: Budget::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'budget_id', onDelete: 'SET NULL')]
    private ?Budget $budget = null;

    /**
     * {@inheritDoc}
     */
    public function getBudget(): ?Budget
    {
        return $this->budget;
    }

    /**
     * {@inheritDoc}
     */
    public function setBudget(?Budget $budget): self
    {
        $this->budget = $budget;

        return $this;
    }
}