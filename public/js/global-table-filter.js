(() => {
  function textOf(el) {
    return (el?.textContent || '').trim();
  }

  function normalize(value) {
    return String(value ?? '').toLowerCase().trim();
  }

  function createToolbar() {
    const wrap = document.createElement('div');
    wrap.className = 'mb-6 bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-slate-200 dark:border-slate-700 p-4';

    const row = document.createElement('div');
    row.className = 'grid grid-cols-1 md:grid-cols-4 gap-3 items-end';

    const searchBox = document.createElement('div');
    searchBox.className = 'md:col-span-2';
    searchBox.innerHTML = `
      <label class="block text-xs font-semibold text-slate-500 mb-1">Search</label>
      <input type="text" class="js-smart-search w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent rounded-lg px-3 py-2 text-sm text-slate-900 dark:text-white" placeholder="Search table...">
    `;

    const filtersHost = document.createElement('div');
    filtersHost.className = 'md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3';

    const resetWrap = document.createElement('div');
    resetWrap.className = 'sm:col-span-2 flex justify-end';
    resetWrap.innerHTML = '<button type="button" class="js-smart-reset bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-800 dark:text-white text-sm font-medium rounded-lg px-3 py-2">Reset</button>';

    row.appendChild(searchBox);
    row.appendChild(filtersHost);
    wrap.appendChild(row);
    wrap.appendChild(resetWrap);

    return { wrap, row, searchInput: searchBox.querySelector('.js-smart-search'), filtersHost, resetBtn: resetWrap.querySelector('.js-smart-reset') };
  }

  function setupSmartTable(table) {
    if (!table || table.dataset.smartReady === '1') return;
    if (table.dataset.smartMode === 'manual') return;

    const tbody = table.tBodies?.[0];
    if (!tbody) return;

    const rows = () => Array.from(tbody.querySelectorAll('tr'));
    if (rows().length === 0) return;

    const tableContainer = table.closest('.overflow-x-auto') || table.parentElement;
    if (!tableContainer) return;

    const existingToolbar = tableContainer.previousElementSibling;
    if (existingToolbar && existingToolbar.classList.contains('js-smart-toolbar')) {
      table.dataset.smartReady = '1';
      return;
    }

    const toolbar = createToolbar();
    toolbar.wrap.classList.add('js-smart-toolbar');
    tableContainer.parentNode.insertBefore(toolbar.wrap, tableContainer);

    const fields = (table.dataset.filterFields || '')
      .split(',')
      .map((x) => x.trim())
      .filter(Boolean);

    const selects = [];

    function buildSelect(field) {
      const col = document.createElement('div');
      col.innerHTML = `
        <label class="block text-xs font-semibold text-slate-500 mb-1">${field.charAt(0).toUpperCase() + field.slice(1)}</label>
        <select class="js-smart-filter w-full bg-slate-50 dark:bg-slate-700 border border-slate-300 dark:border-transparent rounded-lg px-3 py-2 text-sm text-slate-900 dark:text-white" data-field="${field}">
          <option value="">All</option>
        </select>
      `;
      const select = col.querySelector('select');
      toolbar.filtersHost.appendChild(col);
      selects.push(select);
      return select;
    }

    function rebuildFilterOptions() {
      selects.forEach((select) => {
        const field = select.dataset.field;
        const current = select.value;
        const values = new Set();

        rows().forEach((tr) => {
          const value = normalize(tr.dataset[field]);
          if (value) values.add(value);
        });

        const sorted = Array.from(values).sort((a, b) => a.localeCompare(b));
        select.innerHTML = '<option value="">All</option>' + sorted.map((v) => `<option value="${v}">${v}</option>`).join('');
        if (current && sorted.includes(current)) select.value = current;
      });
    }

    function applyFilters() {
      const q = normalize(toolbar.searchInput.value);
      const activeFilters = selects
        .map((s) => ({ field: s.dataset.field, value: normalize(s.value) }))
        .filter((f) => f.value);

      rows().forEach((tr) => {
        const rowText = normalize(textOf(tr));
        const searchMatch = !q || rowText.includes(q);
        const filtersMatch = activeFilters.every((f) => normalize(tr.dataset[f.field]) === f.value);
        tr.style.display = searchMatch && filtersMatch ? '' : 'none';
      });
    }

    fields.forEach((field) => buildSelect(field));
    rebuildFilterOptions();

    toolbar.searchInput.addEventListener('input', applyFilters);
    selects.forEach((s) => s.addEventListener('change', applyFilters));

    toolbar.resetBtn.addEventListener('click', () => {
      toolbar.searchInput.value = '';
      selects.forEach((s) => { s.value = ''; });
      applyFilters();
    });

    const observer = new MutationObserver(() => {
      rebuildFilterOptions();
      applyFilters();
    });
    observer.observe(tbody, { childList: true, subtree: true });

    table.dataset.smartReady = '1';
  }

  function initSmartTables() {
    document.querySelectorAll('table.js-smart-table').forEach(setupSmartTable);
  }

  window.initSmartTables = initSmartTables;
  document.addEventListener('DOMContentLoaded', initSmartTables);
  document.addEventListener('content:updated', initSmartTables);
  document.addEventListener('ajax:success', initSmartTables);
  document.addEventListener('livewire:navigated', initSmartTables);
})();
