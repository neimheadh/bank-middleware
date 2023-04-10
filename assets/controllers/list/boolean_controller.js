import {Controller} from "@hotwired/stimulus";

/**
 * List boolean switch controller.
 */
export default class extends Controller {
  static values = {
    url: String,
    pk: String
  }
  static targets = ['input'];

  initialize() {
    $(this.inputTarget).on('change', (event) => {
      event.preventDefault();
      event.stopPropagation();

      const value = event.target.checked;
      const pk = this.pkValue;

      $.post(this.urlValue, {pk, value})
    })
  }
}