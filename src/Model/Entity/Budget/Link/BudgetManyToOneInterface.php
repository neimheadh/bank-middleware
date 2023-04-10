<?php

namespace App\Model\Entity\Budget\Link;

use App\Entity\Budget\Budget;

/**
 * Entity with budget.
 */
interface BudgetManyToOneInterface
{

    /**
     * Get budget.
     *
     * @return Budget|null
     */
    public function getBudget(): ?Budget;

    /**
     * Set budget.
     *
     * @param Budget|null $budget Budget.
     *
     * @return $this
     */
    public function setBudget(?Budget $budget): self;
}