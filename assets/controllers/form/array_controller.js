/**
 * App\Form\Type\ArrayType stimulus controller.
 */
import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
  static values = {
    name: String,
    placeholder: Object,
  };

  newRow = `<tr class="app-form-type-array__row" style="display: none">`
    +   `<td class="app-form-type-array__row__key level-{{level}}" data-level="{{level}}">`
    +     `<input class="form-control" type="text" placeholder="{{placeholders.key}}" required="required" />`
    +   `</td>`
    +   `<td class="app-form-type-array__row__value">`
    +     `<input class="form-control" type="text" placeholder="{{placeholders.value}}" required="required" />`
    +   `</td>`
    +   `<td class="app-form-type-array__row__actions">`
    +     `<a class="btn btn-danger fas fa-minus reverse" data-action="form--array#remove"></a> `
    +     `<a class="btn btn-primary fas fa-plus reverse" data-action="form--array#add"></a> `
    +     `<a class="btn btn-primary fas fa-arrow-right reverse" data-action="form--array#add_level"></a> `
    +     `<a class="btn btn-primary fas fa-arrow-left reverse" data-action="form--array#remove_level"></a> `
    +   `</td>`
    + '</tr>';

  newLevel = `<tr class="app-form-type-array__row" style="display: none">`
    +   `<td class="app-form-type-array__row__key level-{{level}}" colspan="2" data-level="{{level}}">`
    +     `<input class="text-primary form-control" type="text" placeholder="{{placeholders.key}}" required="required" />`
    +   `</td>`
    +   `<td class="app-form-type-array__row__actions">`
    +     `<a class="btn btn-danger fas fa-minus reverse" data-action="form--array#remove"></a> `
    +     `<a class="btn btn-primary fas fa-plus reverse" data-action="form--array#add"></a> `
    +     `<a class="btn btn-primary fas fa-arrow-right reverse" data-action="form--array#add_level"></a> `
    +     `<a class="btn btn-primary fas fa-arrow-left reverse" data-action="form--array#remove_level"></a> `
    +   `</td>`
    + `</tr>`;

  /**
   * Rename given table inputs.
   *
   * @param {HTMLTableElement} table Inputs table.
   * @private
   */
  _refresh(table) {
    const indexes = [];

    $(table).find('.app-form-type-array__row').each((_, tr) => {
      const $key = $(tr).find('.app-form-type-array__row__key');
      const $value = $(tr).find('.app-form-type-array__row__value');
      const level = $key.data('level');
      const index = (indexes[level] ?? -1) + 1;
      let name = this.nameValue;

      if (level === undefined) {
        return;
      }

      for(let i = 0; i < level; i++) {
        name += `[${indexes[i]}][value]`;
      }
      name += `[${index}]`;
      indexes[level] = index;

      if (level === 0) {
        $(tr).find('.fa-arrow-left')
          .removeClass('btn-primary')
          .addClass('btn-default')
          .attr('disabled', 'disabled');
      }

      $key.find('input').attr('name', name + '[key]');
      $value.find('input').attr('name', name + '[value]');
    });
  }

  /**
   * @inheritDoc
   */
  connect() {
    console.log('Initialize array')
  }

  /**
   * Add array line.
   *
   * @param {Event} event Thrown event.
   */
  add(event) {
    event.preventDefault();

    const $row = $(event.target).parents('.app-form-type-array__row');
    const table = $row.parents('table').get(0);
    const placeholders = this.placeholderValue;
    let level = $row.find('.app-form-type-array__row__key').data('level') ?? 0;

    $row.after(this.newRow
      .replaceAll('{{level}}', level)
      .replaceAll('{{placeholders.key}}', placeholders.key)
      .replaceAll('{{placeholders.value}}', placeholders.value)
    );
    $row.next().fadeIn(300);

    // Input names recalculation.
    this._refresh(table);
  }

  /**
   * Add array level.
   *
   * @param {Event} event Thrown event.
   */
  add_level(event) {
    event.preventDefault();

    const $row = $(event.target).parents('.app-form-type-array__row');
    const table = $row.parents('table').get(0);
    const placeholders = this.placeholderValue;
    let level = $row.find('.app-form-type-array__row__key').data('level') ?? 0;

    $row.after(this.newLevel
      .replaceAll('{{level}}', level)
      .replaceAll('{{placeholders.key}}', placeholders.key)
    );
    $row.next().after(this.newRow
      .replaceAll('{{level}}', level + 1)
      .replaceAll('{{placeholders.key}}', placeholders.key)
      .replaceAll('{{placeholders.value}}', placeholders.value)
    );
    $row.next().fadeIn();
    $row.next().next().fadeIn();

    this._refresh(table);
  }

  /**
   * Remove array line.
   *
   * @param {Event} event Thrown event.
   */
  remove(event) {
    event.preventDefault();

    const $row = $(event.target).parents('.app-form-type-array__row');
    const level = $row.find('.app-form-type-array__row__key').data('level');

    let $el = $row.next();
    while ($el.hasClass('app-form-type-array__row')
      && $el.find('.app-form-type-array__row__key').data('level') > level
    ) {
      /** @var {HTMLTableRowElement} el */
      const el = $el.get(0);

      $el.fadeOut(300, () => el.parentNode.removeChild(el));
      $el = $el.next();
    }
    $row.fadeOut(300, () => $row.get(0).parentNode.removeChild($row.get(0)));
  }

  /**
   * Remove array level.
   *
   * @param {Event} event Thrown event.
   */
  remove_level(event) {
    event.preventDefault();

    /** @var {HTMLLinkElement} target */
    const target = event.target;

    if (target.attributes.disabled) {
      return;
    }

    let $row = $(event.target).parents('.app-form-type-array__row');
    const level = $row.find('.app-form-type-array__row__key').data('level') ?? 0;
    const table = $row.parents('table').get(0);
    const placeholders = this.placeholderValue;

    console.log(this.newRow
      .replaceAll('{{level}}', level - 1)
      .replaceAll('{{placeholders.key}}', placeholders.key)
      .replaceAll('{{placeholders.value}}', placeholders.value));
    $row.replaceWith(this.newRow
      .replace('style="display: none"', '')
      .replaceAll('{{level}}', level - 1)
      .replaceAll('{{placeholders.key}}', placeholders.key)
      .replaceAll('{{placeholders.value}}', placeholders.value)
    );

    this._refresh(table);
  }
}