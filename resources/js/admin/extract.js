/**
 * extract.js
 *
 * renderExtractedData(json, containerId, schema)
 *   Builds a Bootstrap 5 form driven by the schema returned from the API.
 *
 * extractFormData(containerId)
 *   Returns the current form state as a plain object (same shape as json).
 *
 * Supported schema field types:
 *   text      → <input type="text">
 *   number    → <input type="number">  (format:"round" applies Math.round on store)
 *   date      → <input type="date">
 *   select    → <select> populated from options[]
 *   computed  → read-only input; live-evaluated from formula (e.g. "a + b")
 *   array     → repeatable card group with Add / Remove buttons
 *   object    → single flat group of sub-fields (no Add / Remove)
 *
 * Schema hints fully supported:
 *   required_if          → show/hide field when sibling matches value(s)
 *   enabled_if           → show/hide array/object section when sibling matches value(s)
 *   format: "round"      → Math.round value before storing (number fields)
 *   derive_from_array_length → auto-select based on nested array length
 */

(function (global) {

  // ─── Internal state ───────────────────────────────────────────────────────
  const _state   = {};  // live data, keyed by containerId
  const _schemas = {};  // schema, keyed by containerId

  // ─── Helpers ──────────────────────────────────────────────────────────────

  function formatLabel(key) {
    return key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
  }

  function resolveLabel(key, fieldSchema) {
    return (fieldSchema && fieldSchema.label) ? fieldSchema.label : formatLabel(key);
  }

  function blankFromSchema(itemSchema) {
    const obj = {};
    for (const k in itemSchema) {
      const t = itemSchema[k].type;
      obj[k] = t === 'array' ? [] : t === 'object' ? {} : null;
    }
    return obj;
  }

  function applyFormat(value, fieldSchema) {
    if (!fieldSchema || !fieldSchema.format) return value;
    if (fieldSchema.format === 'round' && value !== null && value !== undefined && value !== '') {
      return Math.round(parseFloat(value));
    }
    return value;
  }

  function evalFormula(formula, context) {
    try {
      const keys = formula.match(/[a-zA-Z_][a-zA-Z0-9_]*/g) || [];
      let expr = formula;
      keys.forEach(k => {
        const v = parseFloat(context[k]) || 0;
        expr = expr.replace(new RegExp('\\b' + k + '\\b', 'g'), v);
      });
      if (/^[\d\s+\-*/().]+$/.test(expr)) {
        return Function('"use strict";return (' + expr + ')')();
      }
    } catch (_) {}
    return null;
  }

  /**
   * Check whether a field should be VISIBLE given required_if / enabled_if rules
   * against a sibling-state object.
   *
   * @param {Object} fieldSchema  - the field's schema definition
   * @param {Object} siblingState - the object containing sibling field values
   * @param {string} ruleKey      - 'required_if' | 'enabled_if'
   * @returns {boolean} true = visible
   */
  function isVisible(fieldSchema, siblingState, ruleKey) {
    const rule = fieldSchema && fieldSchema[ruleKey];
    if (!rule) return true; // no rule → always visible

    return Object.entries(rule).every(([k, expected]) => {
      const actual = String(siblingState[k] ?? '');
      if (Array.isArray(expected)) return expected.map(String).includes(actual);
      return actual === String(expected);
    });
  }

  // ─── Visibility refresh ───────────────────────────────────────────────────

  /**
   * Register a visibility rule on an element, storing everything it needs
   * to self-evaluate — including a direct reference to its own siblingState.
   * This avoids scope confusion when nested structures have different contexts.
   */
  function registerVisibility(el, ruleKey, fieldSchema, siblingState) {
    el.dataset.visibilityRule   = ruleKey;
    el.dataset.visibilitySchema = JSON.stringify({ [ruleKey]: fieldSchema[ruleKey] });
    el._visibilitySiblingState  = siblingState; // live reference — always current
    applyVisibility(el);
  }

  function applyVisibility(el) {
    const ruleKey = el.dataset.visibilityRule;
    const schema  = JSON.parse(el.dataset.visibilitySchema || '{}');
    const state   = el._visibilitySiblingState || {};
    const visible = isVisible(schema, state, ruleKey);
    el.style.display = visible ? '' : 'none';
    el.querySelectorAll('input,select').forEach(input => {
      input.disabled = !visible;
    });
  }

  /** Re-evaluate all registered visibility rules inside a container. */
  function refreshVisibility(containerEl) {
    containerEl.querySelectorAll('[data-visibility-rule]').forEach(applyVisibility);
  }

  // ─── Computed refresh ─────────────────────────────────────────────────────

  function refreshComputedFields(containerEl, itemState) {
    containerEl.querySelectorAll('[data-computed="true"]').forEach(col => {
      const formula = col.dataset.formula;
      const input   = col.querySelector('input');
      if (!formula || !input) return;
      const result  = evalFormula(formula, itemState);
      input.value   = (result !== null && result !== undefined) ? result : '';
      const key     = col.dataset.fieldKey;
      if (key) itemState[key] = result;
    });
  }

  // ─── Field builder ────────────────────────────────────────────────────────

  /**
   * Builds one col div containing a label + input/select.
   *
   * @param {string}   fieldKey
   * @param {*}        value
   * @param {Object}   fieldSchema
   * @param {Object}   stateTarget   - object to write updates into
   * @param {string}   stateKey      - key inside stateTarget
   * @param {Object}   siblingState  - full sibling object (for computed + visibility refresh)
   * @param {Function} onchange      - called after every value change: (key, newValue)
   */
  function buildField(fieldKey, value, fieldSchema, stateTarget, stateKey, siblingState, onchange) {
    const type    = (fieldSchema && fieldSchema.type) ? fieldSchema.type : guessType(fieldKey, value);
    const options = fieldSchema && fieldSchema.options;
    const lbl     = resolveLabel(fieldKey, fieldSchema);

    const col = document.createElement('div');
    col.className = 'col-12 col-md-6';
    col.dataset.fieldKey = fieldKey;

    // ── required_if visibility ────────────────────────────────────────────
    const reqRule = fieldSchema && fieldSchema.required_if;
    if (reqRule && siblingState) {
      registerVisibility(col, 'required_if', fieldSchema, siblingState);
    }

    const labelEl = document.createElement('label');
    labelEl.className = 'form-label';
    labelEl.textContent = lbl;
    col.appendChild(labelEl);

    // ── computed ──────────────────────────────────────────────────────────
    if (type === 'computed') {
      const input     = document.createElement('input');
      input.type      = 'text';
      input.className = 'form-control bg-body-secondary';
      input.name      = fieldKey;
      input.readOnly  = true;
      input.tabIndex  = -1;
      const computed  = (siblingState && fieldSchema.formula)
        ? evalFormula(fieldSchema.formula, siblingState) : value;
      input.value       = (computed !== null && computed !== undefined) ? computed : '';
      stateTarget[stateKey] = computed;
      col.dataset.computed  = 'true';
      col.dataset.formula   = fieldSchema.formula || '';
      col.appendChild(input);
      return col;
    }

    // ── select ────────────────────────────────────────────────────────────
    if (type === 'select' && options) {
      const select      = document.createElement('select');
      select.className  = 'form-select';
      select.name       = fieldKey;

      options.forEach(opt => {
        const o       = document.createElement('option');
        o.value       = opt.value;
        o.textContent = opt.label;
        if (String(opt.value) === String(value)) o.selected = true;
        select.appendChild(o);
      });

      if (value !== null && value !== undefined && value !== '') {
        const found = options.some(o => String(o.value) === String(value));
        if (!found) {
          const extra       = document.createElement('option');
          extra.value       = value;
          extra.textContent = value;
          extra.selected    = true;
          select.appendChild(extra);
        }
      }

      select.addEventListener('change', () => {
        stateTarget[stateKey] = select.value === '' ? null : select.value;
        if (onchange) onchange(fieldKey, stateTarget[stateKey]);
      });

      col.appendChild(select);
      return col;
    }

    // ── text / number / date ──────────────────────────────────────────────
    const input       = document.createElement('input');
    input.type        = (type === 'select') ? 'text' : type;
    input.className   = 'form-control';
    input.name        = fieldKey;
    input.value       = (value !== null && value !== undefined) ? value : '';
    input.placeholder = (value === null) ? '—' : '';

    input.addEventListener('input', () => {
      let v = input.value;
      if (type === 'number') {
        v = (v === '') ? null : applyFormat(parseFloat(v), fieldSchema);
      } else {
        v = (v === '') ? null : v;
      }
      stateTarget[stateKey] = v;
      if (onchange) onchange(fieldKey, v);
    });

    col.appendChild(input);
    return col;
  }

  function guessType(key, value) {
    if (key.includes('fecha') || key.includes('date')) return 'date';
    if (typeof value === 'number') return 'number';
    return 'text';
  }

  // ─── Generic group renderer ───────────────────────────────────────────────
  // Handles any flat object of fields (used for both object sections and array item cards).
  // Supports required_if, enabled_if, computed, nested arrays and objects recursively.

  /**
   * @param {Object}   fieldsSchema  - { fieldKey: fieldSchema, ... }
   * @param {Object}   data          - current data values
   * @param {Object}   stateTarget   - object to write changes into
   * @param {string}   namePrefix    - input name prefix
   * @param {string}   containerId
   * @param {Function} onchange      - called after any field change
   * @returns {HTMLElement} a div.row.g-3 with all rendered fields
   */
  function buildFieldGroup(fieldsSchema, data, stateTarget, namePrefix, containerId, onchange) {
    const wrapper = document.createElement('div');
    wrapper.className = 'row g-3';

    const fields = Object.keys(fieldsSchema);

    // Callback that refreshes visibility + computed after any sibling changes
    function handleChange(changedKey, newValue) {
      refreshVisibility(wrapper);
      refreshComputedFields(wrapper, stateTarget);
      if (onchange) onchange(changedKey, newValue);
    }

    fields.forEach(fieldKey => {
      const fs    = fieldsSchema[fieldKey];
      const ftype = fs.type || guessType(fieldKey, data[fieldKey]);

      // Ensure state slot exists
      if (stateTarget[fieldKey] === undefined) {
        stateTarget[fieldKey] = ftype === 'array' ? [] : ftype === 'object' ? {} : null;
      }

      // ── Nested array ──────────────────────────────────────────────────
      if (ftype === 'array') {
        if (!Array.isArray(stateTarget[fieldKey])) stateTarget[fieldKey] = [];
        const items   = Array.isArray(data[fieldKey]) ? data[fieldKey] : [];
        const section = buildArraySection(
          fieldKey, items, fs, stateTarget, fieldKey,
          namePrefix, containerId, handleChange
        );

        // enabled_if on a nested array
        if (fs.enabled_if) {
          registerVisibility(section, 'enabled_if', fs, stateTarget);
        }

        wrapper.appendChild(section);
        return;
      }

      // ── Nested object ─────────────────────────────────────────────────
      if (ftype === 'object') {
        if (!stateTarget[fieldKey] || typeof stateTarget[fieldKey] !== 'object') stateTarget[fieldKey] = {};
        const subData   = (data[fieldKey] && typeof data[fieldKey] === 'object') ? data[fieldKey] : {};
        const section   = buildObjectSection(
          fieldKey, subData, fs, stateTarget, fieldKey,
          namePrefix, containerId, handleChange
        );

        if (fs.enabled_if) {
          registerVisibility(section, 'enabled_if', fs, stateTarget);
        }

        wrapper.appendChild(section);
        return;
      }

      // ── Regular scalar field ──────────────────────────────────────────
      const value = (data[fieldKey] !== undefined) ? data[fieldKey] : null;

      // Apply format on initial load
      const formatted = (ftype === 'number' && value !== null)
        ? applyFormat(value, fs) : value;
      stateTarget[fieldKey] = formatted;

      const col = buildField(
        fieldKey, formatted, fs,
        stateTarget, fieldKey,
        stateTarget,  // siblingState = same object
        handleChange
      );
      col.querySelector('input,select') &&
        (col.querySelector('input,select').name = namePrefix ? `${namePrefix}[${fieldKey}]` : fieldKey);
      wrapper.appendChild(col);
    });

    // Initial visibility pass
    refreshVisibility(wrapper);
    refreshComputedFields(wrapper, stateTarget);

    return wrapper;
  }

  // ─── Object section ───────────────────────────────────────────────────────

  /**
   * Renders a type=object field as a card with a labelled header.
   * Fully recursive — sub-fields can themselves be array or object.
   */
  function buildObjectSection(fieldKey, data, fieldSchema, parentState, parentKey, namePrefix, containerId, onchange) {
    const itemSchema   = fieldSchema ? fieldSchema.itemSchema : null;
    const sectionLabel = resolveLabel(fieldKey, fieldSchema);

    const section = document.createElement('div');
    section.className = 'col-12';
    section.dataset.objectSection = fieldKey;

    const lbl = document.createElement('div');
    lbl.className = 'col-12';
    lbl.innerHTML = `
      <div class="d-flex align-items-center gap-2 mt-2 mb-1">
        <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.06em">${sectionLabel}</span>
        <hr class="flex-grow-1 my-0 border-secondary">
      </div>`;
    section.appendChild(lbl);

    if (!itemSchema) return section;

    const card = document.createElement('div');
    card.className = 'card border border-secondary-subtle';
    const body = document.createElement('div');
    body.className = 'card-body';

    // Ensure state object exists
    if (!parentState[parentKey] || typeof parentState[parentKey] !== 'object') {
      parentState[parentKey] = {};
    }
    const stateTarget = parentState[parentKey];

    const subPrefix = namePrefix ? `${namePrefix}[${fieldKey}]` : fieldKey;
    const group = buildFieldGroup(
      itemSchema, data, stateTarget, subPrefix, containerId,
      (k, v) => {
        if (onchange) onchange(k, v);
      }
    );
    body.appendChild(group);
    card.appendChild(body);
    section.appendChild(card);

    return section;
  }

  // ─── Array section ────────────────────────────────────────────────────────

  /**
   * Renders a type=array field as a card-per-item group with Add / Remove.
   * Can be top-level or nested inside an object.
   *
   * @param {string}   arrKey
   * @param {Array}    items
   * @param {Object}   arrFieldSchema
   * @param {Object}   parentState      - object that holds the array at parentStateKey
   * @param {string}   parentStateKey
   * @param {string}   namePrefix
   * @param {string}   containerId
   * @param {Function} onchange
   */
  function buildArraySection(arrKey, items, arrFieldSchema, parentState, parentStateKey, namePrefix, containerId, onchange) {
    const itemSchema   = arrFieldSchema ? arrFieldSchema.itemSchema : null;
    const sectionLabel = resolveLabel(arrKey, arrFieldSchema);

    const section = document.createElement('div');
    section.className = 'col-12';
    section.dataset.arraySection = arrKey;

    const header = document.createElement('div');
    header.className = 'd-flex align-items-center justify-content-between mb-3';
    header.innerHTML = `
      <div>
        <h6 class="mb-0 fw-semibold">
          <i class="fas fa-layer-group text-primary me-2"></i>${sectionLabel}
        </h6>
        <small class="text-muted array-count">${items.length} elemento(s)</small>
      </div>
      <button type="button" class="btn btn-sm btn-outline-primary add-item-btn">
        <i class="fas fa-plus me-1"></i>Agregar
      </button>`;
    section.appendChild(header);

    const itemsContainer = document.createElement('div');
    itemsContainer.className = 'array-items-container';
    section.appendChild(itemsContainer);

    // Initial render
    renderArrayItems(arrKey, parentState[parentStateKey], itemSchema, itemsContainer,
                     namePrefix, containerId, onchange);

    // Add item
    header.querySelector('.add-item-btn').addEventListener('click', () => {
      const arr     = parentState[parentStateKey];
      const newItem = itemSchema ? blankFromSchema(itemSchema) : {};
      arr.push(newItem);
      renderArrayItems(arrKey, arr, itemSchema, itemsContainer, namePrefix, containerId, onchange);
      updateCount(section, arr.length);
    });

    return section;
  }

  function renderArrayItems(arrKey, arr, itemSchema, container, namePrefix, containerId, onchange) {
    container.innerHTML = '';
    if (!arr || arr.length === 0) {
      container.innerHTML = `<div class="text-muted small fst-italic py-2">Sin elementos. Usa "Agregar" para añadir uno.</div>`;
      return;
    }
    arr.forEach((item, idx) => {
      container.appendChild(
        buildArrayItemCard(arrKey, idx, item, itemSchema, arr, namePrefix, containerId, onchange)
      );
    });
    attachRemoveListeners(container, arrKey, arr, itemSchema, namePrefix, containerId, onchange);
  }

  function buildArrayItemCard(arrKey, idx, item, itemSchema, arr, namePrefix, containerId, onchange) {
    const card = document.createElement('div');
    card.className = 'card border border-secondary-subtle mb-3';
    card.dataset.itemIndex = idx;

    const header = document.createElement('div');
    header.className = 'd-flex align-items-center justify-content-between px-3 py-2 bg-body-secondary border-bottom';
    header.innerHTML = `
      <small class="text-muted fw-semibold">#${idx + 1}</small>
      <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 remove-item-btn"
              data-arr-key="${arrKey}" data-item-index="${idx}">
        <i class="fas fa-times me-1"></i>Eliminar
      </button>`;
    card.appendChild(header);

    const body = document.createElement('div');
    body.className = 'card-body';

    const fields = itemSchema ? Object.keys(itemSchema) : Object.keys(item);
    const schema = itemSchema || {};
    const subPrefix = namePrefix ? `${namePrefix}[${arrKey}][${idx}]` : `${arrKey}[${idx}]`;

    // Ensure state item is correctly initialized
    if (!arr[idx] || typeof arr[idx] !== 'object') arr[idx] = {};

    const group = buildFieldGroup(
      // Build a schema object even if itemSchema is null
      fields.reduce((acc, k) => { acc[k] = schema[k] || {}; return acc; }, {}),
      item, arr[idx], subPrefix, containerId,
      (k, v) => { if (onchange) onchange(k, v); }
    );

    body.appendChild(group);
    card.appendChild(body);
    return card;
  }

  function attachRemoveListeners(container, arrKey, arr, itemSchema, namePrefix, containerId, onchange) {
    container.querySelectorAll('.remove-item-btn').forEach(btn => {
      const fresh = btn.cloneNode(true);
      btn.parentNode.replaceChild(fresh, btn);
      fresh.addEventListener('click', () => {
        const idx = parseInt(fresh.dataset.itemIndex, 10);
        arr.splice(idx, 1);
        renderArrayItems(arrKey, arr, itemSchema, container, namePrefix, containerId, onchange);
        // Update count badge in the parent section
        const section = container.closest('[data-array-section]');
        if (section) updateCount(section, arr.length);
      });
    });
  }

  function updateCount(sectionEl, count) {
    const small = sectionEl.querySelector(':scope > div > .array-count');
    if (small) small.textContent = `${count} elemento(s)`;
  }

  // ─── Divider ──────────────────────────────────────────────────────────────

  function buildDivider(text) {
    const col = document.createElement('div');
    col.className = 'col-12';
    col.innerHTML = `
      <div class="d-flex align-items-center gap-2 mt-2 mb-1">
        <span class="text-muted small fw-semibold text-uppercase" style="letter-spacing:.06em">${text}</span>
        <hr class="flex-grow-1 my-0 border-secondary">
      </div>`;
    return col;
  }

  // ─── Main ─────────────────────────────────────────────────────────────────

  function renderExtractedData(json, containerId, schema) {
    const container = document.getElementById(containerId);
    if (!container) {
      console.error(`[renderExtractedData] Element #${containerId} not found.`);
      return;
    }

    _state[containerId]   = JSON.parse(JSON.stringify(json));
    _schemas[containerId] = schema || null;

    container.innerHTML = '';
    const root = document.createElement('div');
    root.className = 'row g-4';

    // Key order: schema first (preserves order + includes keys missing from data)
    const schemaKeys = schema ? Object.keys(schema) : [];
    const jsonKeys   = Object.keys(json);
    const allKeys    = [...new Set([...schemaKeys, ...jsonKeys])];

    const scalars = [];
    const groups  = [];

    allKeys.forEach(key => {
      const fs   = schema ? schema[key] : null;
      const type = fs ? fs.type
        : Array.isArray(json[key]) ? 'array'
        : (json[key] !== null && typeof json[key] === 'object') ? 'object'
        : 'scalar';

      if (type === 'array') {
        if (!Array.isArray(_state[containerId][key])) _state[containerId][key] = [];
        groups.push({ key, type: 'array' });
      } else if (type === 'object') {
        if (!_state[containerId][key] || typeof _state[containerId][key] !== 'object') {
          _state[containerId][key] = {};
        }
        groups.push({ key, type: 'object' });
      } else {
        if (_state[containerId][key] === undefined) _state[containerId][key] = null;
        scalars.push(key);
      }
    });

    // Top-level change callback — re-evaluates any top-level required_if / enabled_if
    const onTopChange = () => {
      refreshVisibility(root);
    };

    // ── Scalar fields ─────────────────────────────────────────────────────
    if (scalars.length) {
      root.appendChild(buildDivider('Información general'));

      // Build a schema subset for scalars so buildFieldGroup can handle them
      const scalarSchema = {};
      scalars.forEach(k => { scalarSchema[k] = (schema && schema[k]) || {}; });

      const scalarData = {};
      scalars.forEach(k => { scalarData[k] = json[k] !== undefined ? json[k] : null; });

      const wrapper = document.createElement('div');
      wrapper.className = 'col-12';

      const group = buildFieldGroup(
        scalarSchema, scalarData, _state[containerId], '', containerId, onTopChange
      );
      wrapper.appendChild(group);
      root.appendChild(wrapper);
    }

    // ── Arrays & Objects ──────────────────────────────────────────────────
    groups.forEach(({ key, type }) => {
      const fs = schema ? schema[key] : null;

      if (type === 'array') {
        const items = Array.isArray(json[key]) ? json[key] : [];
        root.appendChild(buildDivider(resolveLabel(key, fs)));
        const section = buildArraySection(
          key, items, fs,
          _state[containerId], key,
          '', containerId, onTopChange
        );
        root.appendChild(section);

      } else if (type === 'object') {
        const data = (json[key] && typeof json[key] === 'object') ? json[key] : {};
        const section = buildObjectSection(
          key, data, fs,
          _state[containerId], key,
          '', containerId, onTopChange
        );
        root.appendChild(section);
      }
    });

    container.appendChild(root);

    // Initial full visibility pass
    refreshVisibility(root);
  }

  // ─── Extractor ────────────────────────────────────────────────────────────

  function extractFormData(containerId) {
    if (!_state[containerId]) {
      console.warn(`[extractFormData] No state for #${containerId}. Call renderExtractedData first.`);
      return null;
    }
    return JSON.parse(JSON.stringify(_state[containerId]));
  }

  // ─── Exports ──────────────────────────────────────────────────────────────

  global.renderExtractedData = renderExtractedData;
  global.extractFormData     = extractFormData;

})(window);