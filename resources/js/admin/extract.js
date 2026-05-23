/**
 * extract.js
 *
 * renderExtractedData(json, containerId, schema)
 *   Builds a Bootstrap 5 form driven by the schema returned from your API.
 *
 * extractFormData(containerId)
 *   Returns the current form state as a plain object (same shape as json).
 *
 * Supported schema field types:
 *   text      → <input type="text">
 *   number    → <input type="number">
 *   date      → <input type="date">
 *   select    → <select> from options[]
 *   computed  → read-only input; recalculated live from formula (e.g. "a + b")
 *   array     → repeatable card group with Add / Remove buttons
 *   object    → single flat group of sub-fields (no Add / Remove)
 *
 * Schema hints supported:
 *   enabled_if   → show section only when a referenced field equals a value
 *   required_if  → (stored as metadata; visual enforcement is up to you)
 *   derive_from_array_length → auto-select a scalar select based on array size
 */

(function (global) {

  // ─── Internal state ───────────────────────────────────────────────────────
  const _state   = {};   // live data, keyed by containerId
  const _schemas = {};   // schema, keyed by containerId

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
      obj[k] = (itemSchema[k].type === 'array') ? [] :
               (itemSchema[k].type === 'object') ? {} : null;
    }
    return obj;
  }

  /** Safely read a nested path like "copropiedad.integrantes" from an object */
  function getNestedValue(obj, path) {
    return path.split('.').reduce((cur, k) => (cur != null ? cur[k] : undefined), obj);
  }

  /** Evaluate a simple additive formula like "isr_federacion + isr_entidad"
   *  using values from a sibling-item object. */
  function evalFormula(formula, context) {
    try {
      const keys = formula.match(/[a-zA-Z_][a-zA-Z0-9_]*/g) || [];
      let expr = formula;
      keys.forEach(k => {
        const v = parseFloat(context[k]) || 0;
        expr = expr.replace(new RegExp('\\b' + k + '\\b', 'g'), v);
      });
      // Safe eval: only numbers and + - * / ( ) .
      if (/^[\d\s+\-*/().]+$/.test(expr)) return Function('"use strict";return (' + expr + ')')();
    } catch (_) {}
    return null;
  }

  // ─── Field builder ────────────────────────────────────────────────────────

  /**
   * Renders one field (text/number/date/select/computed) into a Bootstrap col.
   *
   * @param {string} fieldKey      - key name
   * @param {*}      value         - current value from data
   * @param {Object} fieldSchema   - schema definition for this field
   * @param {Object} stateTarget   - object whose [stateKey] should be updated on change
   * @param {string} stateKey      - key inside stateTarget to update
   * @param {Object} [siblings]    - sibling state object (used by computed fields)
   * @param {Function} [onchange]  - optional callback after any value change
   */
  function buildField(fieldKey, value, fieldSchema, stateTarget, stateKey, siblings, onchange) {
    const type    = (fieldSchema && fieldSchema.type) ? fieldSchema.type : guessType(fieldKey, value);
    const options = (fieldSchema && fieldSchema.options) ? fieldSchema.options : null;
    const lbl     = resolveLabel(fieldKey, fieldSchema);

    const col = document.createElement('div');
    col.className = 'col-12 col-md-6';
    col.dataset.fieldKey = fieldKey;

    const labelEl = document.createElement('label');
    labelEl.className = 'form-label';
    labelEl.textContent = lbl;
    col.appendChild(labelEl);

    // ── computed ──────────────────────────────────────────────────────────
    if (type === 'computed') {
      const input = document.createElement('input');
      input.type      = 'text';
      input.className = 'form-control bg-body-secondary';
      input.name      = fieldKey;
      input.readOnly  = true;
      input.tabIndex  = -1;
      const computed  = (siblings && fieldSchema.formula)
        ? evalFormula(fieldSchema.formula, siblings)
        : value;
      input.value = (computed !== null && computed !== undefined) ? computed : '';
      stateTarget[stateKey] = computed;
      col.appendChild(input);
      col.dataset.computed  = 'true';
      col.dataset.formula   = fieldSchema.formula || '';
      return col;
    }

    // ── select ────────────────────────────────────────────────────────────
    if (type === 'select' && options) {
      const select = document.createElement('select');
      select.className = 'form-select';
      select.name      = fieldKey;

      options.forEach(opt => {
        const o = document.createElement('option');
        o.value       = opt.value;
        o.textContent = opt.label;
        if (String(opt.value) === String(value)) o.selected = true;
        select.appendChild(o);
      });

      // Value from data not in options → add it anyway so nothing is lost
      if (value !== null && value !== undefined && value !== '') {
        const found = options.some(o => String(o.value) === String(value));
        if (!found) {
          const extra = document.createElement('option');
          extra.value       = value;
          extra.textContent = value;
          extra.selected    = true;
          select.appendChild(extra);
        }
      }

      select.addEventListener('change', () => {
        stateTarget[stateKey] = select.value === '' ? null : select.value;
        if (onchange) onchange(fieldKey, select.value);
      });

      col.appendChild(select);
      return col;
    }

    // ── text / number / date ──────────────────────────────────────────────
    const input = document.createElement('input');
    input.type      = (type === 'select') ? 'text' : type;
    input.className = 'form-control';
    input.name      = fieldKey;
    input.value     = (value !== null && value !== undefined) ? value : '';
    input.placeholder = (value === null) ? '—' : '';

    input.addEventListener('input', () => {
      let v = input.value;
      if (type === 'number') v = (v === '') ? null : parseFloat(v);
      else                   v = (v === '') ? null : v;
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

  // ─── Computed field refresh ───────────────────────────────────────────────

  /** Re-evaluate all computed fields inside a row element given the current item state */
  function refreshComputedFields(rowEl, itemState) {
    rowEl.querySelectorAll('[data-computed="true"]').forEach(col => {
      const formula = col.dataset.formula;
      const input   = col.querySelector('input');
      if (!formula || !input) return;
      const result = evalFormula(formula, itemState);
      input.value   = (result !== null && result !== undefined) ? result : '';
      const key     = col.dataset.fieldKey;
      if (key) itemState[key] = result;
    });
  }

  // ─── Object section ───────────────────────────────────────────────────────

  /**
   * Renders a type=object field: a single flat card with sub-fields.
   * Supports nested arrays inside the object (e.g. copropiedad.integrantes).
   */
  function buildObjectSection(fieldKey, data, fieldSchema, stateTarget, stateKey, containerId, topLevelState, onTopLevelChange) {
    const itemSchema   = fieldSchema ? fieldSchema.itemSchema : null;
    const sectionLabel = resolveLabel(fieldKey, fieldSchema);

    const section = document.createElement('div');
    section.className = 'col-12';
    section.dataset.objectSection = fieldKey;

    // Divider header
    section.appendChild(buildDivider(sectionLabel));

    const card = document.createElement('div');
    card.className = 'card border border-secondary-subtle';

    const body = document.createElement('div');
    body.className = 'card-body';

    const fields = itemSchema ? Object.keys(itemSchema) : (data ? Object.keys(data) : []);

    fields.forEach(subKey => {
      const subSchema = itemSchema ? itemSchema[subKey] : null;
      const subValue  = (data && data[subKey] !== undefined) ? data[subKey] : null;
      const subType   = subSchema ? subSchema.type : guessType(subKey, subValue);

      // Ensure sub-key exists in state
      if (stateTarget[stateKey] == null) stateTarget[stateKey] = {};
      const subState = stateTarget[stateKey];

      if (subType === 'array') {
        // Nested array inside an object (e.g. copropiedad.integrantes)
        if (!Array.isArray(subState[subKey])) subState[subKey] = Array.isArray(subValue) ? JSON.parse(JSON.stringify(subValue)) : [];

        const nestedSection = buildNestedArraySection(
          fieldKey, subKey, subState[subKey], subSchema, containerId, topLevelState, onTopLevelChange
        );
        body.appendChild(nestedSection);

      } else {
        // Regular scalar/select/computed field
        if (subState[subKey] === undefined) subState[subKey] = subValue;

        const row = document.createElement('div');
        row.className = 'row g-3 mb-3';

        const col = buildField(subKey, subValue, subSchema, subState, subKey, subState, (k, v) => {
          if (onTopLevelChange) onTopLevelChange(k, v);
        });
        row.appendChild(col);
        body.appendChild(row);
      }
    });

    card.appendChild(body);
    section.appendChild(card);
    return section;
  }

  // ─── Nested array (inside an object) ─────────────────────────────────────

  /**
   * Builds an array section that lives inside a type=object parent.
   * e.g. copropiedad.integrantes
   */
  function buildNestedArraySection(parentKey, arrKey, items, arrFieldSchema, containerId, topLevelState, onTopLevelChange) {
    const itemSchema   = arrFieldSchema ? arrFieldSchema.itemSchema : null;
    const sectionLabel = resolveLabel(arrKey, arrFieldSchema);
    const enabledIf    = arrFieldSchema ? arrFieldSchema.enabled_if : null;

    const wrapper = document.createElement('div');
    wrapper.dataset.nestedArraySection = `${parentKey}.${arrKey}`;

    // Header
    const header = document.createElement('div');
    header.className = 'd-flex align-items-center justify-content-between mb-2 mt-2';
    header.innerHTML = `
      <div>
        <strong class="small">${sectionLabel}</strong>
        <small class="text-muted ms-2 nested-array-count">${items.length} elemento(s)</small>
      </div>
      <button type="button" class="btn btn-sm btn-outline-primary add-nested-btn">
        <i class="fas fa-plus me-1"></i>Agregar
      </button>`;
    wrapper.appendChild(header);

    const itemsContainer = document.createElement('div');
    itemsContainer.className = 'nested-items-container';
    wrapper.appendChild(itemsContainer);

    renderNestedItems(parentKey, arrKey, items, itemSchema, itemsContainer, containerId, onTopLevelChange);

    header.querySelector('.add-nested-btn').addEventListener('click', () => {
      const newItem = itemSchema ? blankFromSchema(itemSchema) : {};
      // Access nested state: _state[containerId][parentKey][arrKey]
      _state[containerId][parentKey][arrKey].push(newItem);
      const liveItems = _state[containerId][parentKey][arrKey];
      renderNestedItems(parentKey, arrKey, liveItems, itemSchema, itemsContainer, containerId, onTopLevelChange);
      const countEl = wrapper.querySelector('.nested-array-count');
      if (countEl) countEl.textContent = `${liveItems.length} elemento(s)`;
    });

    // Handle enabled_if: show/hide based on a top-level field value
    if (enabledIf) {
      applyEnabledIf(wrapper, enabledIf, topLevelState);
    }

    return wrapper;
  }

  function renderNestedItems(parentKey, arrKey, items, itemSchema, container, containerId, onTopLevelChange) {
    container.innerHTML = '';
    if (!items || items.length === 0) {
      container.innerHTML = `<div class="text-muted small fst-italic py-1">Sin elementos. Usa "Agregar" para añadir uno.</div>`;
      return;
    }
    items.forEach((item, idx) => {
      const card = buildNestedItemCard(parentKey, arrKey, idx, item, itemSchema, containerId, onTopLevelChange);
      container.appendChild(card);
    });
    attachNestedRemoveListeners(container, parentKey, arrKey, itemSchema, containerId, onTopLevelChange);
  }

  function buildNestedItemCard(parentKey, arrKey, idx, item, itemSchema, containerId, onTopLevelChange) {
    const stateArr = _state[containerId][parentKey][arrKey];

    const card = document.createElement('div');
    card.className = 'card border border-secondary-subtle mb-2';
    card.dataset.itemIndex = idx;

    const header = document.createElement('div');
    header.className = 'd-flex align-items-center justify-content-between px-3 py-1 bg-body-secondary border-bottom';
    header.innerHTML = `
      <small class="text-muted fw-semibold">#${idx + 1}</small>
      <button type="button" class="btn btn-sm btn-outline-danger py-0 px-2 remove-nested-btn"
              data-parent-key="${parentKey}" data-arr-key="${arrKey}" data-item-index="${idx}">
        <i class="fas fa-times"></i>
      </button>`;
    card.appendChild(header);

    const body = document.createElement('div');
    body.className = 'card-body py-2';
    const row = document.createElement('div');
    row.className = 'row g-2';

    const fields = itemSchema ? Object.keys(itemSchema) : Object.keys(item);
    fields.forEach(field => {
      const fieldSchema = itemSchema ? itemSchema[field] : null;
      const value       = (item && item[field] !== undefined) ? item[field] : null;
      const proxy       = stateArr[idx];

      const col = buildField(field, value, fieldSchema, proxy, field, proxy, (k, v) => {
        refreshComputedFields(row, proxy);
        if (onTopLevelChange) onTopLevelChange(k, v);
      });
      col.querySelector('input,select') && (col.querySelector('input,select').name = `${parentKey}[${arrKey}][${idx}][${field}]`);
      row.appendChild(col);
    });

    body.appendChild(row);
    card.appendChild(body);
    return card;
  }

  function attachNestedRemoveListeners(container, parentKey, arrKey, itemSchema, containerId, onTopLevelChange) {
    container.querySelectorAll('.remove-nested-btn').forEach(btn => {
      const fresh = btn.cloneNode(true);
      btn.parentNode.replaceChild(fresh, btn);
      fresh.addEventListener('click', () => {
        const idx = parseInt(fresh.dataset.itemIndex, 10);
        _state[containerId][parentKey][arrKey].splice(idx, 1);
        const liveItems = _state[containerId][parentKey][arrKey];

        // Find wrapper and re-render
        const root    = document.getElementById(containerId);
        const wrapper = root.querySelector(`[data-nested-array-section="${parentKey}.${arrKey}"]`);
        if (!wrapper) return;
        const itemsContainer = wrapper.querySelector('.nested-items-container');
        renderNestedItems(parentKey, arrKey, liveItems, itemSchema, itemsContainer, containerId, onTopLevelChange);
        const countEl = wrapper.querySelector('.nested-array-count');
        if (countEl) countEl.textContent = `${liveItems.length} elemento(s)`;
      });
    });
  }

  // ─── Top-level array ──────────────────────────────────────────────────────

  function buildArraySection(arrKey, items, arrFieldSchema, containerId, onTopLevelChange) {
    const itemSchema   = arrFieldSchema ? arrFieldSchema.itemSchema : null;
    const sectionLabel = resolveLabel(arrKey, arrFieldSchema);

    const section = document.createElement('div');
    section.className = 'col-12';
    section.dataset.arraySection = arrKey;

    const sectionHeader = document.createElement('div');
    sectionHeader.className = 'd-flex align-items-center justify-content-between mb-3';
    sectionHeader.innerHTML = `
      <div>
        <h6 class="mb-0 fw-semibold">
          <i class="fas fa-layer-group text-primary me-2"></i>${sectionLabel}
        </h6>
        <small class="text-muted array-count">${items.length} elemento(s)</small>
      </div>
      <button type="button" class="btn btn-sm btn-outline-primary add-item-btn" data-arr-key="${arrKey}">
        <i class="fas fa-plus me-1"></i>Agregar
      </button>`;
    section.appendChild(sectionHeader);

    const itemsContainer = document.createElement('div');
    itemsContainer.className = 'array-items-container';
    itemsContainer.dataset.arrKey = arrKey;
    section.appendChild(itemsContainer);

    renderArrayItems(arrKey, items, itemSchema, itemsContainer, containerId, onTopLevelChange);

    sectionHeader.querySelector('.add-item-btn').addEventListener('click', () => {
      const newItem = itemSchema
        ? blankFromSchema(itemSchema)
        : (_state[containerId][arrKey][0] ? blankFromSchema(_state[containerId][arrKey][0]) : {});
      _state[containerId][arrKey].push(newItem);
      const liveItems = _state[containerId][arrKey];
      renderArrayItems(arrKey, liveItems, itemSchema, itemsContainer, containerId, onTopLevelChange);
      updateCount(section, liveItems.length);
    });

    return section;
  }

  function renderArrayItems(arrKey, items, itemSchema, container, containerId, onTopLevelChange) {
    container.innerHTML = '';
    if (!items || items.length === 0) {
      container.innerHTML = `<div class="text-muted small fst-italic py-2">Sin elementos. Usa "Agregar" para añadir uno.</div>`;
      return;
    }
    items.forEach((item, idx) => {
      container.appendChild(buildArrayItemCard(arrKey, idx, item, itemSchema, containerId, onTopLevelChange));
    });
    attachRemoveListeners(container, arrKey, itemSchema, containerId, onTopLevelChange);
  }

  function buildArrayItemCard(arrKey, idx, item, itemSchema, containerId, onTopLevelChange) {
    const stateArr = _state[containerId][arrKey];

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
    const row = document.createElement('div');
    row.className = 'row g-3';

    const fields = itemSchema ? Object.keys(itemSchema) : Object.keys(item);
    fields.forEach(field => {
      const fieldSchema = itemSchema ? itemSchema[field] : null;
      const value       = (item && item[field] !== undefined) ? item[field] : null;
      const proxy       = stateArr[idx];

      const col = buildField(field, value, fieldSchema, proxy, field, proxy, (k, v) => {
        refreshComputedFields(row, proxy);
        if (onTopLevelChange) onTopLevelChange(k, v);
      });

      const el = col.querySelector('input,select');
      if (el) el.name = `${arrKey}[${idx}][${field}]`;
      row.appendChild(col);
    });

    // Initial computed pass
    refreshComputedFields(row, stateArr[idx]);

    body.appendChild(row);
    card.appendChild(body);
    return card;
  }

  function attachRemoveListeners(container, arrKey, itemSchema, containerId, onTopLevelChange) {
    container.querySelectorAll('.remove-item-btn').forEach(btn => {
      const fresh = btn.cloneNode(true);
      btn.parentNode.replaceChild(fresh, btn);
      fresh.addEventListener('click', () => {
        const idx = parseInt(fresh.dataset.itemIndex, 10);
        _state[containerId][arrKey].splice(idx, 1);
        const liveItems = _state[containerId][arrKey];
        const root    = document.getElementById(containerId);
        const section = root.querySelector(`[data-array-section="${arrKey}"]`);
        const itemsContainer = section.querySelector('.array-items-container');
        renderArrayItems(arrKey, liveItems, itemSchema, itemsContainer, containerId, onTopLevelChange);
        updateCount(section, liveItems.length);
      });
    });
  }

  function updateCount(sectionEl, count) {
    const small = sectionEl.querySelector('.array-count');
    if (small) small.textContent = `${count} elemento(s)`;
  }

  // ─── enabled_if helper ────────────────────────────────────────────────────

  /**
   * Show/hide an element immediately based on enabled_if rules,
   * and register a watcher so it reacts when the controlling field changes.
   */
  function applyEnabledIf(element, enabledIf, topLevelState) {
    function check() {
      const visible = Object.entries(enabledIf).every(([k, v]) => String(topLevelState[k]) === String(v));
      element.style.display = visible ? '' : 'none';
    }
    check();
    // Store so we can re-evaluate on any top-level change
    element._enabledIf      = enabledIf;
    element._enabledIfState = topLevelState;
    element._checkEnabled   = check;
  }

  /** Called after any top-level scalar field changes — re-evaluates all enabled_if watchers */
  function refreshEnabledIf(containerId) {
    const root = document.getElementById(containerId);
    if (!root) return;
    root.querySelectorAll('[data-nested-array-section],[data-object-section]').forEach(el => {
      if (el._checkEnabled) el._checkEnabled();
    });
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
    const row = document.createElement('div');
    row.className = 'row g-4';

    // Build ordered key list: schema order first, then any extra json keys
    const schemaKeys = schema ? Object.keys(schema) : [];
    const jsonKeys   = Object.keys(json);
    const allKeys    = [...new Set([...schemaKeys, ...jsonKeys])];

    const scalars = [];
    const groups  = []; // arrays + objects

    allKeys.forEach(key => {
      const fieldSchema = schema ? schema[key] : null;
      const type = fieldSchema
        ? fieldSchema.type
        : (Array.isArray(json[key]) ? 'array' : (typeof json[key] === 'object' && json[key] !== null ? 'object' : 'scalar'));

      if (type === 'array') {
        if (!Array.isArray(_state[containerId][key])) _state[containerId][key] = [];
        groups.push({ key, type: 'array' });
      } else if (type === 'object') {
        if (typeof _state[containerId][key] !== 'object' || _state[containerId][key] === null) {
          _state[containerId][key] = {};
        }
        groups.push({ key, type: 'object' });
      } else {
        if (_state[containerId][key] === undefined) _state[containerId][key] = null;
        scalars.push(key);
      }
    });

    // Top-level change callback — refreshes enabled_if and derive_from_array_length
    const onTopLevelChange = (changedKey, newValue) => {
      refreshEnabledIf(containerId);
    };

    // ── Scalar fields ──
    if (scalars.length) {
      row.appendChild(buildDivider('Información general'));
      const scalarRow = document.createElement('div');
      scalarRow.className = 'col-12';
      const innerRow = document.createElement('div');
      innerRow.className = 'row g-3';

      scalars.forEach(key => {
        const fieldSchema = schema ? schema[key] : null;
        const value       = json[key] !== undefined ? json[key] : null;
        const col = buildField(key, value, fieldSchema, _state[containerId], key, null, onTopLevelChange);
        innerRow.appendChild(col);
      });

      scalarRow.appendChild(innerRow);
      row.appendChild(scalarRow);
    }

    // ── Arrays and Objects ──
    groups.forEach(({ key, type }) => {
      const fieldSchema = schema ? schema[key] : null;

      if (type === 'array') {
        const items = Array.isArray(json[key]) ? json[key] : [];
        row.appendChild(buildDivider(resolveLabel(key, fieldSchema)));
        row.appendChild(buildArraySection(key, items, fieldSchema, containerId, onTopLevelChange));

      } else if (type === 'object') {
        const data = (json[key] && typeof json[key] === 'object') ? json[key] : {};
        row.appendChild(
          buildObjectSection(key, data, fieldSchema, _state[containerId], key, containerId, _state[containerId], onTopLevelChange)
        );
      }
    });

    container.appendChild(row);

    // Initial enabled_if pass (e.g. hide integrantes if existe_copropiedad !== "1")
    refreshEnabledIf(containerId);
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