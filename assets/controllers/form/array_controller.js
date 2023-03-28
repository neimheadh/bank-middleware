/**
 * App\Form\Type\ArrayType stimulus controller.
 */
import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['add', 'addLevel', 'root', 'value'];
  static values = {
    baseName: String,
    addLabel: String,
    addLevelLabel: String,
    namePlaceholder: String,
    objectTypeArray: String,
    removeLabel: String,
    typePlaceholder: String,
    types: Object,
    valuePlaceholder: String,
  };

  /**
   * Add an element.
   * @param {HTMLElement} target   Element target.
   * @param {any}         elements Element list.
   * @private
   */
  _add(target, elements) {
    for (const params of elements) {
      if (typeof value === 'object') {
        this._addLevel(target, params)
      } else {
        this._addEntry(target, params);
      }
    }
  }

  /**
   * Add an element in the array list.
   * @param {HTMLElement} body   Table body
   * @param {Object}      params Params.
   * @private
   */
  _addEntry(body, params = {}) {
    // Create new line elements.
    const row = document.createElement('tr');
    const nameCol = document.createElement('td');
    const typeCol = document.createElement('td');
    const valueCol = document.createElement('td');
    const removeCol = document.createElement('td');
    const name = document.createElement('input');
    const type = document.createElement('select');
    const value = document.createElement('input');
    const remove = document.createElement('button');

    // Append elements.
    body.append(row);
    row.append(nameCol, typeCol, valueCol, removeCol);
    nameCol.append(name);
    typeCol.append(type);
    valueCol.append(value);
    removeCol.append(remove);

    // Add type options.
    type.append(new Option(this.typePlaceholderValue, null));
    type.options[0].disabled = true;

    for (const t in this.typesValue) {
      const option = new Option(this.typesValue[t], t);
      type.append(option);
      option.selected = t === params.type;
    }

    // Set elements classes
    removeCol.classList.add('cell-minimal');
    name.classList.add('form-control');
    type.classList.add('form-control');
    value.classList.add('form-control');
    remove.classList.add('btn', 'btn-danger');

    // Set elements values
    name.placeholder = this.namePlaceholderValue;
    value.placeholder = this.valuePlaceholderValue;
    remove.innerText = this.removeLabelValue;
    name.name = this._getInputFullName(name, 'name');
    value.name = this._getInputFullName(value, 'value');
    type.name = this._getInputFullName(type, 'type');
    remove.setAttribute('data-action', 'form--array#remove');

    name.value = params.name ?? '';
    value.value = params.value ?? '';
  }

  /**
   * Add an element in the array list.
   * @param {HTMLElement} body   Table body
   * @param {Object}      params Params.
   * @private
   */
  _addLevel(body, params = {})
  {
    // Create new line elements.
    const row = document.createElement('tr');
    const nameCol = document.createElement('td');
    const typeCol = document.createElement('td');
    const removeCol = document.createElement('td');
    const name = document.createElement('input');
    const type = document.createElement('select');
    const remove = document.createElement('button');
    const tableRow = document.createElement('tr');
    const tableCol = document.createElement('td');
    const table = document.createElement('table');
    const tbody = document.createElement('tbody');
    const tfoot = document.createElement('tfoot');
    const btnRow = document.createElement('tr');
    const btnCol = document.createElement('td');
    const btnAdd = document.createElement('button');
    const btnAddLvl = document.createElement('button');

    // Append elements.
    body.append(row, tableRow);
    row.append(nameCol, typeCol, document.createElement('td'), removeCol);
    nameCol.append(name);
    typeCol.append(type);
    removeCol.append(remove);

    tableRow.append(tableCol);
    tableCol.append(table);
    table.append(tbody, tfoot);
    tfoot.append(btnRow);
    btnRow.append(btnCol);
    btnCol.append(btnAddLvl, btnAdd);

    // Add type options.
    type.append(new Option(this.objectTypeArrayValue, 'array'));
    for (const t in this.objectTypesValue) {
      const option = new Option(this.objectTypesValue[t], t);
      type.append(option);
      option.selected = t === params.type;
    }

    // Set elements classes
    removeCol.classList.add('cell-minimal');
    name.classList.add('form-control');
    type.classList.add('form-control');
    remove.classList.add('btn', 'btn-danger');
    tableRow.classList.add('--table-row');
    table.classList.add('table');
    table.classList.add('table-responsive');
    btnCol.classList.add('align-right');
    btnAdd.classList.add('btn');
    btnAdd.classList.add('btn-primary');
    btnAddLvl.classList.add('btn');
    btnAddLvl.classList.add('btn-primary');

    // Set elements values
    name.placeholder = this.namePlaceholderValue;
    remove.innerText = this.removeLabelValue;
    name.name = this._getInputFullName(name, 'name');
    type.name = this._getInputFullName(type, 'type');
    btnAdd.innerText = this.addLabelValue;
    btnAddLvl.innerText = this.addLevelLabelValue;

    remove.setAttribute('data-action', 'form--array#remove');
    btnAdd.setAttribute('data-action', 'form--array#add');
    btnAddLvl.setAttribute('data-action', 'form--array#addLevel');

    tableCol.colSpan = 3;
    btnCol.colSpan = 3;

    name.value = params.name ?? '';

    this._add(tbody, params.value);
  }

  /**
   * Get input full name.
   *
   * @param {HTMLElement} element Input element.
   * @param {string}      name    Input name.
   * @private
   *
   * @return string
   */
  _getInputFullName(element, name) {
    /** @var {HTMLTableRowElement} row */
    let row = this._getParent(element, 'tr');
    let rowNames = '';

    while (row) {
      const pos = Array.prototype.indexOf.call(row.parentNode.children, row);
      rowNames = `[${rowNames ? pos - 1 : pos}]${rowNames ? '[value]' : ''}${rowNames}`;
      row = row.parentNode && this._getParent(row.parentNode, 'tr');
    }

    return `${this.baseNameValue}${rowNames}[${name}]`;
  }

  /**
   * Get parent with given tag name.
   *
   * @param {HTMLElement} element Reference element.
   * @param {string}      tagName Tag name.
   * @private
   *
   * @return {HTMLElement}
   */
  _getParent(element, tagName) {
    if (element.tagName.toLowerCase() === tagName.toLowerCase()) {
      return element;
    }

    if (element === this.rootTarget) {
      return null;
    }

    return this._getParent(element.parentNode, tagName);
  }

  /**
   * Handle stimulus add event.
   * @param {Event} event Add event.
   */
  add(event) {
    event.preventDefault();

    /** @var {HTMLElement} rows */
    const rows = this._getParent(event.target, 'table').getElementsByTagName('tbody')[0];

    this._addEntry(rows);
  }

  /**
   * Add an element in the array list.
   * @param {Event} event
   */
  addLevel(event) {
    event.preventDefault();

    /** @var {HTMLElement} rows */
    const rows = this._getParent(event.target, 'table').getElementsByTagName('tbody')[0];

    this._addLevel(rows);
  }

  /**
   * @inheritDoc
   */
  connect() {
    const elements = JSON.parse(this.valueTarget.value);

    this._add(this.rootTarget, elements);
  }

  /**
   * Remove an element in the array list.
   * @param {Event} event
   */
  remove(event) {
    event.preventDefault();

    /** @var {HTMLButtonElement} target */
    const {target} = event;
    /** @var {HTMLTableRowElement} row */
    const row = this._getParent(target, 'tr');
    /** @var {HTMLTableRowElement} sibling */
    const sibling = row.nextSibling;

    row.parentNode.removeChild(row);

    if (sibling && sibling.nodeName === 'TR' && sibling.classList.contains('--table-row')) {
      sibling.parentNode.removeChild(sibling);
    }
  }
}