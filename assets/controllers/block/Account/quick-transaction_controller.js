import { Controller } from "@hotwired/stimulus";

/**
 * App\Form\Account\Type\QuickTransactionType controller.
 */
export default class extends Controller {
  static targets = ['balance'];

  /**
   * {@inheritDoc}
   */
  connect() {
    /** @var {HTMLInputElement} balance */
    const balance = this.balanceTarget;

    balance.select();
  }
}