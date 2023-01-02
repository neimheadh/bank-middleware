<?php

namespace App\Lifecycle\Entity\Budget;

use App\Entity\Budget\Budget;

/**
 * Balance entities lifecycle listener.
 */
class BudgetLifecycleListener
{

    /**
     * Handle budget pre-persist.
     *
     * @param Budget $budget Pre-persisted budget.
     *
     * @return void
     */
    public function prePersist(Budget $budget): void
    {
        // Set budget balance.
        $budget->setBalance($budget->getAmount());
    }
}