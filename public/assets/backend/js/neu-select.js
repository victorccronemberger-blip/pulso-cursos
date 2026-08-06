/**
 * NeuSelect.js 
 * Usage:
 *   Single: class="neu-select"
 *   Multi:  class="neu-select-multi"
 * CSS is separate — include neu-select.css in your layout
 * Version: 1.0
 */

(function (global) {
    'use strict';

    const DEFAULTS = {
        placeholder: 'Select an option',
        multiPlaceholder: 'Select options',
        searchable: true,
        searchPlaceholder: 'Search...',
        maxHeight: 260,
        noResultsText: 'No results found',
        multi: false,
        onChange: null,
    };

    class NeuSelect {
        constructor(element, options = {}) {
            if (typeof element === 'string') element = document.querySelector(element);
            if (!element || element.tagName !== 'SELECT') return;

            this.originalSelect = element;
            this.options = Object.assign({}, DEFAULTS, options);
            this.isOpen = false;
            this.searchQuery = '';
            this.focusedIndex = -1;
            this._filteredItems = [];

            // single
            this.selectedValue = null;
            this.selectedText = null;

            // multi
            this.selectedValues = [];

            this._init();
        }

        _init() {
            this._parseOptions();
            this._build();
            this._attachEvents();
            this._syncFromOriginal();
            this._hideOriginal();
        }

        _parseOptions() {
            this.items = [];
            this.originalSelect.querySelectorAll('option').forEach(opt => {
                this.items.push({
                    value: opt.value,
                    text: opt.textContent.trim(),
                    disabled: opt.disabled,
                    selected: opt.selected,
                });
            });
        }

        _build() {
            this.wrapper = document.createElement('div');
            this.wrapper.className = this.options.multi ? 'ns-wrapper ns-multi' : 'ns-wrapper';

            // ── trigger ──
            this.trigger = document.createElement('div');
            this.trigger.className = 'ns-trigger';
            this.trigger.setAttribute('tabindex', '0');
            this.trigger.setAttribute('role', 'combobox');
            this.trigger.setAttribute('aria-haspopup', 'listbox');
            this.trigger.setAttribute('aria-expanded', 'false');
            this.trigger.setAttribute('aria-multiselectable', this.options.multi ? 'true' : 'false');

            if (this.options.multi) {
                // multi: tags container
                this.tagsContainer = document.createElement('div');
                this.tagsContainer.className = 'ns-tags';

                this.tagsPlaceholder = document.createElement('span');
                this.tagsPlaceholder.className = 'ns-placeholder';
                this.tagsPlaceholder.textContent = this.originalSelect.dataset.placeholder || this.options.multiPlaceholder;
                this.tagsContainer.appendChild(this.tagsPlaceholder);

                this.trigger.appendChild(this.tagsContainer);
            } else {
                // single: text span
                this.triggerText = document.createElement('span');
                this.triggerText.className = 'ns-trigger-text ns-placeholder';
                this.triggerText.textContent = this.originalSelect.dataset.placeholder || this.options.placeholder;
                this.trigger.appendChild(this.triggerText);
            }

            this.triggerArrow = document.createElement('span');
            this.triggerArrow.className = 'ns-arrow';
            this.triggerArrow.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>`;

            this.trigger.appendChild(this.triggerArrow);
            this.wrapper.appendChild(this.trigger);

            // ── dropdown — appended to body ──
            this.dropdown = document.createElement('div');
            this.dropdown.className = 'ns-dropdown';
            this.dropdown.setAttribute('role', 'listbox');

            const searchable = this.originalSelect.dataset.searchable !== 'false' && this.options.searchable;
            if (searchable) {
                this.searchWrapper = document.createElement('div');
                this.searchWrapper.className = 'ns-search-wrapper';

                this.searchIcon = document.createElement('span');
                this.searchIcon.className = 'ns-search-icon';
                this.searchIcon.innerHTML = `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>`;

                this.searchInput = document.createElement('input');
                this.searchInput.type = 'text';
                this.searchInput.className = 'ns-search';
                this.searchInput.placeholder = this.originalSelect.dataset.searchPlaceholder || this.options.searchPlaceholder;
                this.searchInput.setAttribute('autocomplete', 'off');

                this.searchWrapper.appendChild(this.searchIcon);
                this.searchWrapper.appendChild(this.searchInput);
                this.dropdown.appendChild(this.searchWrapper);
            }

            this.optionsList = document.createElement('ul');
            this.optionsList.className = 'ns-options';
            this.optionsList.style.maxHeight = (this.originalSelect.dataset.maxHeight || this.options.maxHeight) + 'px';
            this.dropdown.appendChild(this.optionsList);

            this.noResults = document.createElement('div');
            this.noResults.className = 'ns-no-results';
            this.noResults.textContent = this.options.noResultsText;
            this.noResults.style.display = 'none';
            this.dropdown.appendChild(this.noResults);

            document.body.appendChild(this.dropdown);
            this.originalSelect.parentNode.insertBefore(this.wrapper, this.originalSelect.nextSibling);
            this._renderOptions();
        }

        _renderOptions() {
            this.optionsList.innerHTML = '';
            this.focusedIndex = -1;

            const filtered = this.items.filter(item => {
                if (!this.searchQuery) return true;
                return item.text.toLowerCase().includes(this.searchQuery.toLowerCase());
            });

            this._filteredItems = filtered;

            if (filtered.length === 0) {
                this.noResults.style.display = 'block';
                this.optionsList.style.display = 'none';
                return;
            }

            this.noResults.style.display = 'none';
            this.optionsList.style.display = 'block';

            filtered.forEach((item, index) => {
                const li = document.createElement('li');
                li.className = 'ns-option';
                li.setAttribute('role', 'option');
                li.setAttribute('data-value', item.value);
                li.setAttribute('data-index', index);

                if (item.disabled) li.classList.add('ns-option--disabled');

                const isSelected = this.options.multi
                    ? this.selectedValues.includes(item.value)
                    : item.value === this.selectedValue;

                if (isSelected) {
                    li.classList.add('ns-option--selected');
                    li.setAttribute('aria-selected', 'true');
                }

                // multi: add checkbox indicator
                if (this.options.multi) {
                    const check = document.createElement('span');
                    check.className = 'ns-check';
                    check.innerHTML = isSelected
                        ? `<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`
                        : '';
                    li.appendChild(check);
                }

                const label = document.createElement('span');
                if (this.searchQuery) {
                    const escaped = this.searchQuery.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                    const regex = new RegExp(`(${escaped})`, 'gi');
                    label.innerHTML = item.text.replace(regex, '<mark class="ns-mark">$1</mark>');
                } else {
                    label.textContent = item.text;
                }
                li.appendChild(label);

                this.optionsList.appendChild(li);
            });
        }

        _renderTags() {
            // clear existing tags (keep placeholder)
            this.tagsContainer.innerHTML = '';

            if (this.selectedValues.length === 0) {
                this.tagsPlaceholder = document.createElement('span');
                this.tagsPlaceholder.className = 'ns-placeholder';
                this.tagsPlaceholder.textContent = this.originalSelect.dataset.placeholder || this.options.multiPlaceholder;
                this.tagsContainer.appendChild(this.tagsPlaceholder);
                return;
            }

            this.selectedValues.forEach(val => {
                const item = this.items.find(i => i.value === val);
                if (!item) return;

                const tag = document.createElement('span');
                tag.className = 'ns-tag';

                const tagText = document.createElement('span');
                tagText.textContent = item.text;

                const tagRemove = document.createElement('span');
                tagRemove.className = 'ns-tag-remove';
                tagRemove.innerHTML = `<svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>`;
                tagRemove.addEventListener('click', (e) => {
                    e.stopPropagation();
                    this._deselect(val);
                });

                tag.appendChild(tagText);
                tag.appendChild(tagRemove);
                this.tagsContainer.appendChild(tag);
            });
        }

        _attachEvents() {
            this.trigger.addEventListener('click', (e) => {
                e.stopPropagation();
                this.isOpen ? this.close() : this.open();
            });

            this.trigger.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); this.isOpen ? this.close() : this.open(); }
                if (e.key === 'ArrowDown') { e.preventDefault(); this.open(); this._moveFocus(1); }
                if (e.key === 'ArrowUp') { e.preventDefault(); this.open(); this._moveFocus(-1); }
                if (e.key === 'Escape') this.close();
            });

            if (this.searchInput) {
                this.searchInput.addEventListener('input', (e) => {
                    this.searchQuery = e.target.value;
                    this._renderOptions();
                });

                this.searchInput.addEventListener('keydown', (e) => {
                    if (e.key === 'ArrowDown') { e.preventDefault(); this._moveFocus(1); }
                    if (e.key === 'ArrowUp') { e.preventDefault(); this._moveFocus(-1); }
                    if (e.key === 'Enter') { e.preventDefault(); this._selectFocused(); }
                    if (e.key === 'Escape') this.close();
                });
            }

            // Prevent clicks inside the dropdown from bubbling to document
            // so any parent "click outside to close" handlers are not triggered
            this.dropdown.addEventListener('click', (e) => {
                e.stopPropagation();
            });

            this.optionsList.addEventListener('click', (e) => {
                const option = e.target.closest('.ns-option');
                if (!option || option.classList.contains('ns-option--disabled')) return;
                const value = option.getAttribute('data-value');
                if (this.options.multi) {
                    this.selectedValues.includes(value) ? this._deselect(value) : this._selectMulti(value);
                } else {
                    this._select(value);
                }
            });

            this.optionsList.addEventListener('mousemove', (e) => {
                const option = e.target.closest('.ns-option');
                if (!option) return;
                this._setFocus(parseInt(option.getAttribute('data-index')));
            });

            document.addEventListener('click', (e) => {
                if (!this.wrapper.contains(e.target) && !this.dropdown.contains(e.target)) {
                    this.close();
                }
            });

            this._onScrollResize = () => { if (this.isOpen) this._position(); };
            window.addEventListener('scroll', this._onScrollResize, true);
            window.addEventListener('resize', this._onScrollResize);
        }

        _moveFocus(dir) {
            const opts = this.optionsList.querySelectorAll('.ns-option:not(.ns-option--disabled)');
            if (!opts.length) return;
            this.focusedIndex = Math.max(0, Math.min(opts.length - 1, this.focusedIndex + dir));
            this._setFocus(this.focusedIndex);
        }

        _setFocus(index) {
            this.optionsList.querySelectorAll('.ns-option').forEach(o => o.classList.remove('ns-option--focused'));
            const opts = this.optionsList.querySelectorAll('.ns-option');
            if (opts[index]) {
                opts[index].classList.add('ns-option--focused');
                opts[index].scrollIntoView({ block: 'nearest' });
                this.focusedIndex = index;
            }
        }

        _selectFocused() {
            const focused = this.optionsList.querySelector('.ns-option--focused');
            if (!focused) return;
            const value = focused.getAttribute('data-value');
            if (this.options.multi) {
                this.selectedValues.includes(value) ? this._deselect(value) : this._selectMulti(value);
            } else {
                this._select(value);
            }
        }

        open() {
            if (this.isOpen) return;
            this.isOpen = true;
            this.searchQuery = '';

            document.querySelectorAll('.ns-wrapper.ns-open').forEach(w => {
                if (w !== this.wrapper && w.__neuSelect) w.__neuSelect.close();
            });

            this.wrapper.classList.add('ns-open');
            this.trigger.setAttribute('aria-expanded', 'true');

            this._renderOptions();

            // Position BEFORE making visible so we measure real height
            this._position();

            this.dropdown.classList.add('ns-dropdown--visible');

            if (this.searchInput) {
                this.searchInput.value = '';
                setTimeout(() => this.searchInput.focus(), 50);
            }
        }

        close() {
            if (!this.isOpen) return;
            this.isOpen = false;

            this.wrapper.classList.remove('ns-open');
            this.trigger.setAttribute('aria-expanded', 'false');
            this.dropdown.classList.remove('ns-dropdown--visible');
            this.dropdown.classList.remove('ns-dropdown--above');

            if (this.searchInput) {
                this.searchInput.value = '';
                this.searchQuery = '';
            }

            this.trigger.focus();
        }

        _position() {
            const rect = this.trigger.getBoundingClientRect();
            const gap = 6;
            const width = rect.width;

            // Temporarily make dropdown block but invisible to measure its real height
            const prevVisibility = this.dropdown.style.visibility;
            const prevDisplay = this.dropdown.style.display;
            this.dropdown.style.visibility = 'hidden';
            this.dropdown.style.display = 'block';
            const ddHeight = this.dropdown.offsetHeight;
            // Restore
            this.dropdown.style.visibility = prevVisibility;
            this.dropdown.style.display = prevDisplay;

            const spaceBelow = window.innerHeight - rect.bottom;
            const spaceAbove = rect.top;
            const goAbove = spaceBelow < ddHeight + gap && spaceAbove > spaceBelow;

            // Clamp left so dropdown never overflows viewport right edge
            let left = rect.left;
            if (left + width > window.innerWidth - 8) {
                left = window.innerWidth - width - 8;
            }
            if (left < 8) left = 8;

            if (goAbove) {
                // Open upward: bottom of dropdown flush with top of trigger
                const top = rect.top - ddHeight - gap;
                this.dropdown.style.cssText = `position:fixed;top:${top}px;left:${left}px;width:${width}px;z-index:999999;`;
                this.dropdown.classList.add('ns-dropdown--above');
            } else {
                // Open downward: top of dropdown flush with bottom of trigger
                const top = rect.bottom + gap;
                this.dropdown.style.cssText = `position:fixed;top:${top}px;left:${left}px;width:${width}px;z-index:999999;`;
                this.dropdown.classList.remove('ns-dropdown--above');
            }
        }

        // ── Single select ────────────────────────────────────────────────────────
        _select(value) {
            const item = this.items.find(i => i.value === value);
            if (!item) return;

            this.selectedValue = value;
            this.selectedText = item.text;

            this.triggerText.textContent = item.text;
            this.triggerText.classList.remove('ns-placeholder');

            this.originalSelect.value = value;
            this.originalSelect.dispatchEvent(new Event('change', { bubbles: true }));

            if (typeof this.options.onChange === 'function') {
                this.options.onChange(value, item.text);
            }

            this.close();
        }

        // ── Multi select ─────────────────────────────────────────────────────────
        _selectMulti(value) {
            const item = this.items.find(i => i.value === value);
            if (!item || this.selectedValues.includes(value)) return;

            this.selectedValues.push(value);
            this._syncMultiToOriginal();
            this._renderTags();
            this._renderOptions();

            if (typeof this.options.onChange === 'function') {
                this.options.onChange(this.selectedValues);
            }
        }

        _deselect(value) {
            this.selectedValues = this.selectedValues.filter(v => v !== value);
            this._syncMultiToOriginal();
            this._renderTags();
            this._renderOptions();

            if (typeof this.options.onChange === 'function') {
                this.options.onChange(this.selectedValues);
            }
        }

        _syncMultiToOriginal() {
            Array.from(this.originalSelect.options).forEach(opt => {
                opt.selected = this.selectedValues.includes(opt.value);
            });
            this.originalSelect.dispatchEvent(new Event('change', { bubbles: true }));
        }

        _syncFromOriginal() {
            if (this.options.multi) {
                this.selectedValues = this.items
                    .filter(i => i.selected && i.value !== '')
                    .map(i => i.value);
                if (this.selectedValues.length > 0) this._renderTags();
            } else {
                const selected = this.items.find(i => i.selected && i.value !== '');
                if (selected) {
                    this.selectedValue = selected.value;
                    this.selectedText = selected.text;
                    this.triggerText.textContent = selected.text;
                    this.triggerText.classList.remove('ns-placeholder');
                }
            }
        }

        _hideOriginal() {
            this.originalSelect.style.cssText = 'display:none!important;position:absolute;opacity:0;pointer-events:none;';
        }

        // ── Public API ──────────────────────────────────────────────────────────
        getValue() {
            return this.options.multi ? this.selectedValues : this.selectedValue;
        }

        setValue(value) {
            if (this.options.multi) {
                if (Array.isArray(value)) {
                    this.selectedValues = [];
                    value.forEach(v => this._selectMulti(v));
                }
            } else {
                this._select(value);
            }
        }

        reset() {
            if (this.options.multi) {
                this.selectedValues = [];
                this._syncMultiToOriginal();
                this._renderTags();
                this._renderOptions();
            } else {
                this.selectedValue = null;
                this.selectedText = null;
                this.triggerText.textContent = this.originalSelect.dataset.placeholder || this.options.placeholder;
                this.triggerText.classList.add('ns-placeholder');
                this.originalSelect.value = '';
                this._renderOptions();
            }
        }

        refresh() { this._parseOptions(); this._renderOptions(); }

        destroy() {
            this.wrapper.remove();
            this.dropdown.remove();
            window.removeEventListener('scroll', this._onScrollResize, true);
            window.removeEventListener('resize', this._onScrollResize);
            this.originalSelect.style.cssText = '';
        }
    }

    // ── Auto Init ────────────────────────────────────────────────────────────────
    function autoInit() {
        // single select
        document.querySelectorAll('select.neu-select').forEach(el => {
            if (el.__neuSelectInstance) return;
            const instance = new NeuSelect(el, { multi: false });
            el.__neuSelectInstance = instance;
            if (instance.wrapper) instance.wrapper.__neuSelect = instance;
        });

        // multi select
        document.querySelectorAll('select.neu-select-multi').forEach(el => {
            if (el.__neuSelectInstance) return;
            const instance = new NeuSelect(el, { multi: true });
            el.__neuSelectInstance = instance;
            if (instance.wrapper) instance.wrapper.__neuSelect = instance;
        });

        // animate all fpb-7 groups with stagger
        document.querySelectorAll('.fpb-7').forEach((el, i) => {
            el.style.animationDelay = (i * 80) + 'ms';
            el.classList.add('ns-ready');
        });
    }

    // ── Boot ─────────────────────────────────────────────────────────────────────
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', autoInit);
    } else {
        autoInit();
    }

    global.NeuSelect = NeuSelect;
    global.NeuSelectInit = autoInit;

})(window);