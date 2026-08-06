/*!
 * nEditor v1.0 — Premium Custom Rich Text Editor
 * Trigger: add class="nEditor" to any <textarea>
 * Zero dependencies. Pure vanilla JS.
 * (c) MIT License
 */
(function (W) {
    'use strict';

    /* ─────────────────────────────────────────
       ICONS  (inline SVG, no external deps)
    ───────────────────────────────────────── */
    function ic(d) {
        return '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="pointer-events:none;flex-shrink:0">' + d + '</svg>';
    }
    var I = {
        bold: ic('<path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/><path d="M6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/>'),
        italic: ic('<line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/>'),
        underline: ic('<path d="M6 3v7a6 6 0 0 0 6 6 6 6 0 0 0 6-6V3"/><line x1="4" y1="21" x2="20" y2="21"/>'),
        strike: ic('<line x1="5" y1="12" x2="19" y2="12"/><path d="M16 6C16 6 14.5 4 12 4s-5 1.5-5 4c0 1.3.7 2.4 1.8 3"/><path d="M8 18c0 0 1.5 2 4 2s5-1.5 5-4c0-1.3-.7-2.4-1.8-3"/>'),
        sub: ic('<text x="2" y="18" font-size="11" font-weight="700" stroke="none" fill="currentColor">A</text><text x="12" y="22" font-size="9" stroke="none" fill="currentColor">2</text>'),
        sup: ic('<text x="2" y="16" font-size="11" font-weight="700" stroke="none" fill="currentColor">A</text><text x="12" y="10" font-size="9" stroke="none" fill="currentColor">2</text>'),
        code: ic('<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>'),
        ul: ic('<line x1="9" y1="6" x2="20" y2="6"/><line x1="9" y1="12" x2="20" y2="12"/><line x1="9" y1="18" x2="20" y2="18"/><circle cx="4" cy="6" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="12" r="1.5" fill="currentColor" stroke="none"/><circle cx="4" cy="18" r="1.5" fill="currentColor" stroke="none"/>'),
        ol: ic('<line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><path d="M4 6h1v4"/><path d="M4 10h2"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/>'),
        quote: ic('<path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V20c0 1 0 1 1 1z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3c0 1 0 1 1 1z"/>'),
        pre: ic('<rect x="2" y="3" width="20" height="18" rx="3"/><path d="m8 10-3 2 3 2"/><path d="m16 10 3 2-3 2"/><path d="m12 8-2 8"/>'),
        hr: ic('<line x1="3" y1="12" x2="21" y2="12"/>'),
        link: ic('<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>'),
        unlink: ic('<path d="M18.84 12.25l1.72-1.71h-.02a5.004 5.004 0 0 0-.12-7.07 5.006 5.006 0 0 0-6.95 0l-1.72 1.71"/><path d="M5.17 11.75l-1.71 1.71a5.004 5.004 0 0 0 .12 7.07 5.006 5.006 0 0 0 6.95 0l1.72-1.71"/><line x1="8" y1="2" x2="8" y2="5"/><line x1="2" y1="8" x2="5" y2="8"/><line x1="16" y1="19" x2="16" y2="22"/><line x1="19" y1="16" x2="22" y2="16"/>'),
        img: ic('<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/>'),
        video: ic('<polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/>'),
        table: ic('<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18M15 3v18"/>'),
        al: ic('<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="15" y2="12"/><line x1="3" y1="18" x2="18" y2="18"/>'),
        ac: ic('<line x1="3" y1="6" x2="21" y2="6"/><line x1="6" y1="12" x2="18" y2="12"/><line x1="4" y1="18" x2="20" y2="18"/>'),
        ar: ic('<line x1="3" y1="6" x2="21" y2="6"/><line x1="9" y1="12" x2="21" y2="12"/><line x1="6" y1="18" x2="21" y2="18"/>'),
        aj: ic('<line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>'),
        indI: ic('<path d="M3 8h12M3 14h12M3 4h18M3 20h18"/><polyline points="16 10 19 13 16 16" stroke-width="2"/>'),
        indO: ic('<path d="M9 8h12M9 14h12M3 4h18M3 20h18"/><polyline points="7 10 4 13 7 16" stroke-width="2"/>'),
        undo: ic('<path d="M9 14 4 9l5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 0 11H11"/>'),
        redo: ic('<path d="m15 14 5-5-5-5"/><path d="M20 9H9.5a5.5 5.5 0 0 0 0 11H13"/>'),
        search: ic('<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>'),
        print: ic('<polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/>'),
        html: ic('<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>'),
        close: ic('<line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>'),
        chevron: ic('<polyline points="6 9 12 15 18 9"/>'),
        color: ic('<path d="M12 2a10 10 0 1 0 10 10"/><path d="M12 2v10l4.24 4.24"/><circle cx="18" cy="6" r="3" fill="currentColor" stroke="none" opacity=".4"/>'),
        hilite: ic('<path d="m9 11-6 6v3h3l6-6"/><path d="m22 12-4.6 4.6a2 2 0 0 1-2.8 0l-5.2-5.2a2 2 0 0 1 0-2.8L14 4"/>'),
        clear: ic('<path d="M20 5H9l-7 7 7 7h11a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2z"/><line x1="18" y1="9" x2="12" y2="15"/><line x1="12" y1="9" x2="18" y2="15"/>'),
        copy: ic('<rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/>'),
        cut: ic('<circle cx="6" cy="20" r="2"/><circle cx="6" cy="4" r="2"/><line x1="6" y1="6" x2="6" y2="18"/><line x1="21" y1="4" x2="6" y2="20"/><line x1="6" y1="4" x2="21" y2="20"/>'),
        task: ic('<polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/>'),
        emoji: ic('<circle cx="12" cy="12" r="10"/><path d="M8 13s1.5 2 4 2 4-2 4-2"/><line x1="9" y1="9" x2="9.01" y2="9"/><line x1="15" y1="9" x2="15.01" y2="9"/>'),
        special: ic('<text x="3" y="18" font-size="14" font-weight="700" stroke="none" fill="currentColor">Ω</text>'),
    };

    /* ─────────────────────────────────────────
       DATA
    ───────────────────────────────────────── */
    var COLORS = [
        '#000000', '#434343', '#666666', '#999999', '#b7b7b7', '#cccccc', '#d9d9d9', '#ffffff',
        '#ff0000', '#ff9900', '#ffff00', '#00ff00', '#00ffff', '#0000ff', '#9900ff', '#ff00ff',
        '#f4cccc', '#fce5cd', '#fff2cc', '#d9ead3', '#d0e4f7', '#cfe2f3', '#d9d2e9', '#ead1dc',
        '#ea9999', '#f9cb9c', '#ffe599', '#b6d7a8', '#a2c4c9', '#9fc5e8', '#b4a7d6', '#d5a6bd',
        '#e06666', '#f6b26b', '#ffd966', '#93c47d', '#76a5af', '#6fa8dc', '#8e7cc3', '#c27ba0',
        '#cc0000', '#e69138', '#f1c232', '#6aa84f', '#45818e', '#3d85c8', '#674ea7', '#a64d79',
        '#990000', '#b45309', '#bf9000', '#38761d', '#134f5c', '#1155cc', '#351c75', '#741b47'
    ];

    var FONTS = ['Default', 'Arial', 'Georgia', 'Times New Roman', 'Courier New', 'Verdana', 'Trebuchet MS', 'Impact', 'Palatino Linotype', 'Tahoma'];

    var SIZES = ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '28', '32', '36', '42', '48', '56', '64', '72', '96'];

    var SPECIAL_CHARS = ['©', '®', '™', '€', '£', '¥', '¢', '§', '¶', '†', '‡', '•', '…', '—', '–', '«', '»',
        '←', '→', '↑', '↓', '↔', '↵', '⇒', '⇐', '⇑', '⇓', '⇔',
        '∞', '≈', '≠', '≤', '≥', '±', '÷', '×', '√', '∑', '∏', '∫',
        'α', 'β', 'γ', 'δ', 'ε', 'θ', 'λ', 'μ', 'π', 'σ', 'τ', 'φ', 'ψ', 'ω', 'Ω',
        '°', '′', '″', '‰', '⁰', '¹', '²', '³', '⁴', '½', '¼', '¾'];

    var EMOJIS = ['😀', '😁', '😂', '😊', '😍', '🤔', '😎', '😢', '😡', '👍', '👎', '👏', '🙏', '❤️',
        '🔥', '⭐', '✅', '❌', '⚠️', '💡', '🎉', '🎊', '🚀', '💯', '🔒', '🔓', '📧', '📞', '🌍'];

    /* ─────────────────────────────────────────
       MODAL FACTORY
    ───────────────────────────────────────── */
    function makeModal(title, bodyHtml, submitLabel) {
        var el = document.createElement('div');
        el.className = 'ne-overlay';
        el.style.display = 'none';
        el.innerHTML =
            '<div class="ne-modal" role="dialog" aria-modal="true">' +
            '<div class="ne-modal-hd">' +
            '<span class="ne-modal-title">' + title + '</span>' +
            '<button type="button" class="ne-modal-x" aria-label="Close">' + I.close + '</button>' +
            '</div>' +
            '<div class="ne-modal-bd">' + bodyHtml + '</div>' +
            (submitLabel ?
                '<div class="ne-modal-ft">' +
                '<button type="button" class="ne-ghost">Cancel</button>' +
                '<button type="button" class="ne-primary">' + submitLabel + '</button>' +
                '</div>' : '') +
            '</div>';
        document.body.appendChild(el);
        return el;
    }

    function openModal(overlay, onSubmit) {
        overlay.style.display = 'flex';
        var first = overlay.querySelector('input, textarea');
        if (first) setTimeout(function () { first.focus(); }, 80);

        function close() {
            overlay.style.display = 'none';
            overlay.querySelectorAll('input').forEach(function (i) { i.value = ''; });
        }

        overlay.querySelector('.ne-modal-x').onclick = close;
        var cancel = overlay.querySelector('.ne-ghost');
        if (cancel) cancel.onclick = close;
        overlay.onclick = function (e) { if (e.target === overlay) close(); };

        var primary = overlay.querySelector('.ne-primary');
        if (primary && onSubmit) {
            primary.onclick = function () { if (onSubmit() !== false) close(); };
        }
        overlay.onkeydown = function (e) {
            if (e.key === 'Escape') close();
            if (e.key === 'Enter' && primary) primary.click();
        };
    }

    /* ─────────────────────────────────────────
       COLOR PICKER PANEL
    ───────────────────────────────────────── */
    function makeColorPanel(id, cmd) {
        var html = '<div class="ne-dd" id="' + id + '" style="display:none;min-width:200px;padding:8px">';
        html += '<div style="display:grid;grid-template-columns:repeat(8,22px);gap:3px;margin-bottom:6px">';
        COLORS.forEach(function (c) {
            html += '<div class="ne-swatch" style="background:' + c + '" data-color="' + c + '" data-cmd="' + cmd + '" title="' + c + '"></div>';
        });
        html += '</div>';
        html += '<div style="display:flex;align-items:center;gap:6px;padding-top:5px;border-top:1px solid #e2e5ec">';
        html += '<span style="font-size:11px;color:#94a3b8;flex-shrink:0">Custom</span>';
        html += '<input type="color" class="ne-color-native" data-cmd="' + cmd + '" value="#000000" style="width:28px;height:24px;border:1.5px solid #e2e5ec;border-radius:5px;padding:1px;cursor:pointer;background:none">';
        html += '</div></div>';
        return html;
    }

    /* ─────────────────────────────────────────
       TABLE PICKER PANEL
    ───────────────────────────────────────── */
    function makeTablePanel(id) {
        var html = '<div class="ne-dd" id="' + id + '" style="display:none;padding:8px">';
        html += '<div style="display:grid;grid-template-columns:repeat(8,22px);gap:2px" id="' + id + '-grid"></div>';
        html += '<div style="text-align:center;font-size:11px;color:#94a3b8;margin-top:5px" id="' + id + '-lbl">Hover to select</div>';
        html += '</div>';
        return html;
    }

    /* ─────────────────────────────────────────
       SPECIAL CHARS PANEL
    ───────────────────────────────────────── */
    function makeSpecialPanel(id) {
        var html = '<div class="ne-dd" id="' + id + '" style="display:none;padding:8px;max-width:280px">';
        html += '<div style="display:flex;flex-wrap:wrap;gap:3px">';
        SPECIAL_CHARS.forEach(function (ch) {
            html += '<button type="button" class="ne-char-btn" data-char="' + ch + '" title="' + ch + '">' + ch + '</button>';
        });
        html += '</div></div>';
        return html;
    }

    /* ─────────────────────────────────────────
       EMOJI PANEL
    ───────────────────────────────────────── */
    function makeEmojiPanel(id) {
        var html = '<div class="ne-dd" id="' + id + '" style="display:none;padding:8px;max-width:280px">';
        html += '<div style="display:flex;flex-wrap:wrap;gap:3px">';
        EMOJIS.forEach(function (e) {
            html += '<button type="button" class="ne-char-btn" data-char="' + e + '" style="font-size:18px">' + e + '</button>';
        });
        html += '</div></div>';
        return html;
    }

    /* ─────────────────────────────────────────
       TOOLBAR HTML
    ───────────────────────────────────────── */
    function mkBtn(icon, cmd, tip) {
        return '<button type="button" class="ne-b" data-c="' + cmd + '" data-tip="' + tip + '">' + icon + '</button>';
    }
    function mkBtnW(label, cmd, tip) {
        return '<button type="button" class="ne-b ne-bw" data-c="' + cmd + '" data-tip="' + tip + '">' +
            '<span class="ne-bw-lbl">' + label + '</span>' + I.chevron + '</button>';
    }
    function sep() { return '<span class="ne-sep"></span>'; }

    function buildToolbar(p) {
        /* Row 1: history, block style, font, size, text format, colors */
        var row1 = '<div class="ne-toolbar-row">';

        /* History */
        row1 += '<div class="ne-g">' + mkBtn(I.undo, 'undo', 'Undo (Ctrl+Z)') + mkBtn(I.redo, 'redo', 'Redo (Ctrl+Y)') + '</div>' + sep();

        /* Block style dropdown */
        row1 += '<div class="ne-g ne-has-dd">' +
            mkBtnW('Paragraph', 'dd:block', 'Block style') +
            '<div class="ne-dd" id="' + p + '-dd-block" style="display:none;min-width:160px">' +
            '<button class="ne-dd-item" data-c="block:p">Paragraph</button>' +
            '<button class="ne-dd-item" data-c="block:h1" style="font-size:1.3em;font-weight:700;line-height:1.2">Heading 1</button>' +
            '<button class="ne-dd-item" data-c="block:h2" style="font-size:1.15em;font-weight:700">Heading 2</button>' +
            '<button class="ne-dd-item" data-c="block:h3" style="font-size:1.05em;font-weight:600">Heading 3</button>' +
            '<button class="ne-dd-item" data-c="block:h4" style="font-weight:600">Heading 4</button>' +
            '<button class="ne-dd-item" data-c="block:h5" style="font-size:.9em;font-weight:600">Heading 5</button>' +
            '<button class="ne-dd-item" data-c="block:h6" style="font-size:.85em;font-weight:600;color:#64748b">Heading 6</button>' +
            '</div></div>' + sep();

        /* Font family */
        row1 += '<div class="ne-g ne-has-dd">' +
            mkBtnW('Font', 'dd:font', 'Font family') +
            '<div class="ne-dd" id="' + p + '-dd-font" style="display:none;min-width:180px">';
        FONTS.forEach(function (f) {
            row1 += '<button class="ne-dd-item" data-c="font:' + f + '"' + (f !== 'Default' ? ' style="font-family:' + f + '"' : '') + '>' + f + '</button>';
        });
        row1 += '</div></div>';

        /* Font size */
        row1 += '<div class="ne-g ne-has-dd">' +
            mkBtnW('Size', 'dd:size', 'Font size') +
            '<div class="ne-dd" id="' + p + '-dd-size" style="display:none;min-width:100px">' +
            '<div style="padding:5px 8px"><input class="ne-size-inp" id="' + p + '-sz-inp" type="number" placeholder="px" min="6" max="200" style="width:100%;height:30px;padding:0 8px;font-size:13px;border:1.5px solid #e2e5ec;border-radius:6px;outline:none;font-family:inherit;box-sizing:border-box"></div>' +
            '<div style="height:1px;background:#e2e5ec;margin:2px 0"></div>';
        SIZES.forEach(function (s) {
            row1 += '<button class="ne-dd-item" data-c="size:' + s + '">' + s + 'px</button>';
        });
        row1 += '</div></div>' + sep();

        /* Text format */
        row1 += '<div class="ne-g">' +
            mkBtn(I.bold, 'bold', 'Bold (Ctrl+B)') +
            mkBtn(I.italic, 'italic', 'Italic (Ctrl+I)') +
            mkBtn(I.underline, 'underline', 'Underline (Ctrl+U)') +
            mkBtn(I.strike, 'strike', 'Strikethrough') +
            mkBtn(I.sub, 'sub', 'Subscript') +
            mkBtn(I.sup, 'sup', 'Superscript') +
            mkBtn(I.clear, 'clearformat', 'Clear formatting') +
            '</div>' + sep();

        /* Text colour */
        row1 += '<div class="ne-g ne-has-dd">' +
            '<button type="button" class="ne-b ne-bw" data-c="dd:fcolor" data-tip="Text colour">' +
            I.color + '<span id="' + p + '-fbar" style="width:13px;height:3px;background:#e11d48;border-radius:2px;display:block;margin-top:1px"></span>' +
            I.chevron + '</button>' +
            makeColorPanel(p + '-dd-fcolor', 'fcolor') +
            '</div>';

        /* Highlight */
        row1 += '<div class="ne-g ne-has-dd">' +
            '<button type="button" class="ne-b ne-bw" data-c="dd:hcolor" data-tip="Highlight">' +
            I.hilite + '<span id="' + p + '-hbar" style="width:13px;height:3px;background:#fbbf24;border-radius:2px;display:block;margin-top:1px"></span>' +
            I.chevron + '</button>' +
            makeColorPanel(p + '-dd-hcolor', 'hcolor') +
            '</div>';

        row1 += '</div>'; /* /row1 */

        /* Row 2: lists, align, indent, insert, table, chars, clipboard, utils */
        var row2 = '<div class="ne-toolbar-row">';

        /* Lists + block elements */
        row2 += '<div class="ne-g">' +
            mkBtn(I.ul, 'ul', 'Bullet list') +
            mkBtn(I.ol, 'ol', 'Numbered list') +
            mkBtn(I.task, 'task', 'Task list') +
            mkBtn(I.quote, 'quote', 'Blockquote') +
            mkBtn(I.hr, 'hr', 'Horizontal rule') +
            '</div>' + sep();

        /* Align */
        row2 += '<div class="ne-g">' +
            mkBtn(I.al, 'jl', 'Align left') +
            mkBtn(I.ac, 'jc', 'Align centre') +
            mkBtn(I.ar, 'jr', 'Align right') +
            mkBtn(I.aj, 'jj', 'Justify') +
            '</div>' + sep();

        /* Indent */
        row2 += '<div class="ne-g">' +
            mkBtn(I.indO, 'outdent', 'Outdent') +
            mkBtn(I.indI, 'indent', 'Indent') +
            '</div>' + sep();

        /* Insert */
        row2 += '<div class="ne-g">' +
            mkBtn(I.link, 'link', 'Insert link (Ctrl+K)') +
            mkBtn(I.unlink, 'unlink', 'Remove link') +
            mkBtn(I.img, 'image', 'Insert image') +
            mkBtn(I.video, 'video', 'Embed video') +
            '</div>';

        /* Table picker */
        row2 += '<div class="ne-g ne-has-dd">' +
            mkBtn(I.table, 'dd:table', 'Insert table') +
            makeTablePanel(p + '-dd-table') +
            '</div>' + sep();

        /* Special chars & emoji */
        row2 += '<div class="ne-g ne-has-dd">' +
            mkBtn(I.special, 'dd:special', 'Special characters') +
            makeSpecialPanel(p + '-dd-special') +
            '</div>';

        row2 += '<div class="ne-g ne-has-dd">' +
            mkBtn(I.emoji, 'dd:emoji', 'Emoji') +
            makeEmojiPanel(p + '-dd-emoji') +
            '</div>' + sep();

        /* Clipboard */
        row2 += '<div class="ne-g">' +
            mkBtn(I.copy, 'copy', 'Copy') +
            mkBtn(I.cut, 'cut', 'Cut') +
            '</div>' + sep();

        /* Utils — fullscreen REMOVED */
        row2 += '<div class="ne-g">' +
            mkBtn(I.search, 'find', 'Find & Replace (Ctrl+F)') +
            mkBtn(I.print, 'print', 'Print') +
            mkBtn(I.html, 'viewsource', 'View/edit source HTML') +
            '</div>';

        row2 += '</div>'; /* /row2 */

        var h = '<div class="ne-toolbar">' + row1 + row2 + '</div>';

        /* Find bar */
        h += '<div class="ne-findbar" id="' + p + '-fb" style="display:none">' +
            '<label class="ne-fb-label">Find</label>' +
            '<input class="ne-fb-inp" id="' + p + '-fi" type="text" placeholder="Search…">' +
            '<span class="ne-fb-cnt" id="' + p + '-fc"></span>' +
            '<button type="button" class="ne-b" data-c="fprev" data-tip="Previous" style="width:26px;height:26px">▴</button>' +
            '<button type="button" class="ne-b" data-c="fnext" data-tip="Next" style="width:26px;height:26px">▾</button>' +
            '<label class="ne-fb-label" style="margin-left:10px">Replace</label>' +
            '<input class="ne-fb-inp" id="' + p + '-ri" type="text" placeholder="Replace…">' +
            '<button type="button" class="ne-b ne-bw" data-c="replone" style="height:26px;padding:0 8px">Replace</button>' +
            '<button type="button" class="ne-b ne-bw" data-c="replall" style="height:26px;padding:0 8px">All</button>' +
            '<button type="button" class="ne-b" data-c="closefind" style="margin-left:auto;width:26px;height:26px">' + I.close + '</button>' +
            '</div>';

        return h;
    }

    /* ─────────────────────────────────────────
       MAIN INIT
    ───────────────────────────────────────── */
    function init(ta) {
        if (ta._ne) return;
        ta._ne = true;

        var p = 'ne' + (Math.random() * 1e6 | 0);
        var name = ta.getAttribute('name') || 'content';
        var ph = ta.getAttribute('placeholder') || 'Start writing…';
        var init = ta.value || '';

        /* Shell */
        var shell = document.createElement('div');
        shell.className = 'ne-shell';
        ta.parentNode.insertBefore(shell, ta);
        shell.appendChild(ta);
        ta.style.cssText = 'display:none!important';

        /* Toolbar + findbar */
        shell.insertAdjacentHTML('beforeend', buildToolbar(p));

        /* Editable */
        var ed = document.createElement('div');
        ed.className = 'ne-ed';
        ed.id = p + '-ed';
        ed.contentEditable = 'true';
        ed.spellcheck = true;
        ed.setAttribute('data-ph', ph);
        if (init) ed.innerHTML = init; else ed.innerHTML = '';
        shell.appendChild(ed);

        /* Statusbar */
        var sbar = document.createElement('div');
        sbar.className = 'ne-sbar';
        sbar.innerHTML =
            '<div class="ne-sbar-l">' +
            '<span id="' + p + '-wc">0 words</span>' +
            '<span id="' + p + '-cc">0 chars</span>' +
            '</div>' +
            '<div class="ne-sbar-r">' +
            '<span id="' + p + '-sel"></span>' +
            '</div>';
        shell.appendChild(sbar);

        /* ── Modals ── */
        var linkModal = makeModal('Insert Link',
            '<div class="ne-field"><label>URL</label><input class="ne-inp" id="' + p + '-lurl" type="url" placeholder="https://…"></div>' +
            '<div class="ne-field"><label>Display text (optional)</label><input class="ne-inp" id="' + p + '-ltxt" placeholder="Link text…"></div>' +
            '<div class="ne-field"><label>Open in</label>' +
            '<select class="ne-inp" id="' + p + '-ltgt" style="cursor:pointer"><option value="">Same tab</option><option value="_blank">New tab</option></select>' +
            '</div>', 'Insert Link');

        var imgModal = makeModal('Insert Image',
            '<div class="ne-field"><label>Image URL</label><input class="ne-inp" id="' + p + '-iurl" type="url" placeholder="https://…/photo.jpg"></div>' +
            '<div class="ne-field"><label>Alt text</label><input class="ne-inp" id="' + p + '-ialt" placeholder="Describe the image…"></div>' +
            '<div class="ne-field"><label>Width (optional)</label><input class="ne-inp" id="' + p + '-iwid" placeholder="e.g. 100% or 400px"></div>',
            'Insert Image');

        var videoModal = makeModal('Embed Video',
            '<div class="ne-field"><label>YouTube / Vimeo / direct URL</label><input class="ne-inp" id="' + p + '-vurl" type="url" placeholder="https://youtube.com/watch?v=…"></div>' +
            '<div class="ne-field"><label>Width</label><input class="ne-inp" id="' + p + '-vw" placeholder="100%" value="100%"></div>' +
            '<div class="ne-field"><label>Height</label><input class="ne-inp" id="' + p + '-vh" placeholder="400" value="400"></div>',
            'Embed Video');

        var srcModal = makeModal('HTML Source', '', 'Apply');
        var srcTa = document.createElement('textarea');
        srcTa.style.cssText = 'width:100%;height:300px;font-family:"SF Mono",Menlo,monospace;font-size:12.5px;padding:12px;border:1.5px solid #e2e5ec;border-radius:8px;resize:vertical;outline:none;color:#0d1117;line-height:1.55;box-sizing:border-box;display:block';
        srcModal.querySelector('.ne-modal-bd').appendChild(srcTa);

        /* Context menu */
        var ctx = document.createElement('div');
        ctx.className = 'ne-ctx';
        ctx.style.display = 'none';
        ctx.innerHTML = [
            ['bold', 'bold', 'Bold'], ['italic', 'italic', 'Italic'], ['underline', 'underline', 'Underline'],
            ['|'],
            ['copy', 'copy', 'Copy'], ['cut', 'cut', 'Cut'],
            ['|'],
            ['link', 'link', 'Insert link'],
            ['|'],
            ['clearformat', 'clear', 'Clear formatting']
        ].map(function (r) {
            if (r[0] === '|') return '<div class="ne-ctx-sep"></div>';
            return '<button type="button" class="ne-ctx-item" data-c="' + r[0] + '">' +
                (I[r[1]] || '') + '<span>' + r[2] + '</span></button>';
        }).join('');
        document.body.appendChild(ctx);

        /* ── Refs ── */
        var toolbar = shell.querySelector('.ne-toolbar');

        /* Move all dropdowns to <body> so they are never clipped by overflow:auto on toolbar rows */
        toolbar.querySelectorAll('.ne-dd').forEach(function (dd) {
            document.body.appendChild(dd);
        });

        /* ── JS Tooltip (escapes all overflow contexts) ── */
        var tipEl = document.createElement('div');
        tipEl.className = 'ne-tip';
        tipEl.style.display = 'none';
        document.body.appendChild(tipEl);

        function showTip(btn) {
            var tip = btn.getAttribute('data-tip');
            if (!tip) return;
            tipEl.textContent = tip;
            tipEl.style.display = 'block';
            var r = btn.getBoundingClientRect();
            var tw = tipEl.offsetWidth;
            var left = r.left + r.width / 2 - tw / 2;
            /* clamp within viewport */
            left = Math.max(6, Math.min(left, window.innerWidth - tw - 6));
            tipEl.style.left = left + 'px';
            tipEl.style.top = (r.top - tipEl.offsetHeight - 7) + 'px';
        }
        function hideTip() {
            tipEl.style.display = 'none';
        }

        toolbar.addEventListener('mouseover', function (e) {
            var btn = e.target.closest('.ne-b[data-tip]');
            if (btn) showTip(btn);
        });
        toolbar.addEventListener('mouseout', function (e) {
            var btn = e.target.closest('.ne-b[data-tip]');
            if (btn) hideTip();
        });
        toolbar.addEventListener('mousedown', hideTip);

        var findbar = document.getElementById(p + '-fb');
        var fiInp = document.getElementById(p + '-fi');
        var riInp = document.getElementById(p + '-ri');
        var fcEl = document.getElementById(p + '-fc');
        var szInp = document.getElementById(p + '-sz-inp');
        var fbar = document.getElementById(p + '-fbar');
        var hbar = document.getElementById(p + '-hbar');

        var saved = null, fMatches = [], fIdx = -1;

        /* ── Dropdown logic ── */
        var openDd = null;
        var openTrigger = null;

        function positionDd(el, trigger) {
            var rect = trigger.getBoundingClientRect();
            el.style.top = (rect.bottom + 6) + 'px';
            var left = rect.left;
            /* clamp right edge within viewport */
            var ddW = el.offsetWidth;
            if (left + ddW > window.innerWidth - 8) {
                left = window.innerWidth - ddW - 8;
            }
            if (left < 6) left = 6;
            el.style.left = left + 'px';
        }

        function toggleDd(id) {
            var el = document.getElementById(id);
            if (!el) return;
            /* Check BEFORE closing so re-clicking the same button truly closes */
            var isOpen = el.style.display !== 'none';
            closeAllDd();
            if (isOpen) return; /* was open → just close, done */

            var ddKey = id.replace(p + '-dd-', '');
            var trigger = toolbar.querySelector('[data-c="dd:' + ddKey + '"]');
            if (!trigger) return;

            el.style.display = 'block';
            openDd = el;
            openTrigger = trigger;
            positionDd(el, trigger);
            /* Fine-tune after render (width may not be known until paint) */
            requestAnimationFrame(function () {
                if (openDd === el) positionDd(el, trigger);
            });
        }

        function closeAllDd() {
            document.querySelectorAll('.ne-dd').forEach(function (d) { d.style.display = 'none'; });
            openDd = null;
            openTrigger = null;
        }

        /* Reposition open dropdown on ANY scroll (toolbar row scroll, page scroll, etc.) */
        function onScroll() {
            if (openDd && openTrigger) {
                positionDd(openDd, openTrigger);
            }
        }

        /* Capture-phase scroll catches all scrollable containers including toolbar rows */
        window.addEventListener('scroll', onScroll, true);
        window.addEventListener('resize', function () {
            if (openDd && openTrigger) positionDd(openDd, openTrigger);
            else closeAllDd();
        });

        /* ── Save/restore selection ── */
        function saveSel() {
            var s = W.getSelection();
            saved = s && s.rangeCount ? s.getRangeAt(0).cloneRange() : null;
        }
        function restSel() {
            if (!saved) return;
            var s = W.getSelection();
            s.removeAllRanges();
            s.addRange(saved);
        }

        /* ── Sync to textarea ── */
        function sync() {
            ta.value = ed.innerHTML;
            updatePh();
            updateStats();
        }

        function updatePh() {
            var empty = !ed.textContent.trim() && !ed.querySelector('img,table,hr,iframe');
            ed.dataset.empty = empty ? '1' : '';
        }

        function updateStats() {
            var txt = ed.textContent.trim();
            var words = txt ? txt.split(/\s+/).filter(Boolean).length : 0;
            var wc = document.getElementById(p + '-wc');
            var cc = document.getElementById(p + '-cc');
            if (wc) wc.textContent = words + (words === 1 ? ' word' : ' words');
            if (cc) cc.textContent = txt.length + (txt.length === 1 ? ' char' : ' chars');
        }

        /* ── Active state ── */
        function updateActive() {
            var cmds = {
                'bold': 'bold', 'italic': 'italic', 'underline': 'underline', 'strike': 'strikeThrough',
                'sub': 'subscript', 'sup': 'superscript',
                'ul': 'insertUnorderedList', 'ol': 'insertOrderedList',
                'jl': 'justifyLeft', 'jc': 'justifyCenter', 'jr': 'justifyRight', 'jj': 'justifyFull'
            };
            toolbar.querySelectorAll('.ne-b[data-c]').forEach(function (b) {
                var k = cmds[b.getAttribute('data-c')];
                if (k) { try { b.classList.toggle('ne-on', document.queryCommandState(k)); } catch (e) { } }
            });

            /* heading label */
            var sel = W.getSelection();
            if (sel && sel.rangeCount) {
                var node = sel.getRangeAt(0).startContainer;
                if (node.nodeType === 3) node = node.parentNode;
                var tags = ['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'P'];
                var found = null;
                for (var i = 0; i < tags.length && !found; i++) {
                    found = node.closest ? node.closest(tags[i]) : null;
                }
                var labels = { P: 'Paragraph', H1: 'Heading 1', H2: 'Heading 2', H3: 'Heading 3', H4: 'Heading 4', H5: 'Heading 5', H6: 'Heading 6' };
                var lblEl = toolbar.querySelector('[data-c="dd:block"] .ne-bw-lbl');
                if (lblEl && found) lblEl.textContent = labels[found.tagName] || 'Paragraph';

                /* selection count */
                var selStr = sel.toString();
                var selEl = document.getElementById(p + '-sel');
                if (selEl) selEl.textContent = selStr.length ? selStr.length + ' selected' : '';
            }
        }

        /* ── exec wrapper ── */
        function ex(cmd, val) {
            ed.focus();
            try { document.execCommand(cmd, false, val || null); } catch (e) { }
            sync(); updateActive();
        }

        /* ── inline code toggle ── */
        function inlineCode() {
            var s = W.getSelection();
            if (!s || s.isCollapsed) return;
            var r = s.getRangeAt(0);
            var anc = r.commonAncestorContainer;
            var c = (anc.nodeType === 3 ? anc.parentNode : anc).closest('code');
            if (c) {
                var t = document.createTextNode(c.textContent);
                c.parentNode.replaceChild(t, c);
            } else {
                var code = document.createElement('code');
                try { r.surroundContents(code); } catch (e) {
                    code.appendChild(r.extractContents());
                    r.insertNode(code);
                }
            }
            sync();
        }

        /* ── code block toggle ── */
        function codeBlock() {
            var s = W.getSelection();
            if (!s || !s.rangeCount) return;
            var r = s.getRangeAt(0);
            var anc = r.commonAncestorContainer;
            if (anc.nodeType === 3) anc = anc.parentNode;
            var existing = anc.closest ? anc.closest('pre') : null;
            if (existing) {
                var p2 = document.createElement('p');
                p2.textContent = existing.textContent;
                existing.parentNode.replaceChild(p2, existing);
            } else {
                var pre = document.createElement('pre');
                var code2 = document.createElement('code');
                code2.textContent = s.toString() || 'code';
                pre.appendChild(code2);
                r.deleteContents();
                r.insertNode(pre);
                var br = document.createElement('p');
                br.innerHTML = '<br>';
                pre.parentNode.insertBefore(br, pre.nextSibling);
            }
            sync();
        }

        /* ── blockquote toggle ── */
        function blockquote() {
            var s = W.getSelection();
            if (!s || !s.rangeCount) return;
            var anc = s.getRangeAt(0).commonAncestorContainer;
            if (anc.nodeType === 3) anc = anc.parentNode;
            var bq = anc.closest ? anc.closest('blockquote') : null;
            if (bq) {
                var frag = document.createDocumentFragment();
                while (bq.firstChild) frag.appendChild(bq.firstChild);
                bq.parentNode.replaceChild(frag, bq);
            } else {
                ex('formatBlock', 'blockquote');
                return;
            }
            sync();
        }

        /* ── task list ── */
        function taskList() {
            var ul = document.createElement('ul');
            ul.setAttribute('data-type', 'taskList');
            var li = document.createElement('li');
            var cb = document.createElement('input');
            cb.type = 'checkbox';
            li.appendChild(cb);
            li.appendChild(document.createTextNode(' Task item'));
            ul.appendChild(li);
            restSel();
            var s = W.getSelection();
            if (s && s.rangeCount) {
                var r = s.getRangeAt(0);
                r.deleteContents();
                r.insertNode(ul);
            } else {
                ed.appendChild(ul);
            }
            sync();
        }

        /* ── apply font size ── */
        function applySize(px) {
            var s = W.getSelection();
            if (!s || s.isCollapsed) return;
            var r = s.getRangeAt(0);

            /* If the selection is already inside a font-size span we created,
               just update its value directly — no re-wrapping needed */
            var anc = r.commonAncestorContainer;
            if (anc.nodeType === 3) anc = anc.parentNode;
            var existing = (anc.tagName === 'SPAN' && anc.style.fontSize) ? anc : null;
            if (existing) {
                existing.style.fontSize = px + 'px';
                /* Re-select the span's contents so the next change still works */
                var nr = document.createRange();
                nr.selectNodeContents(existing);
                s.removeAllRanges();
                s.addRange(nr);
                sync();
                return;
            }

            /* Wrap selected content in a new span */
            var span = document.createElement('span');
            span.style.fontSize = px + 'px';
            try { r.surroundContents(span); } catch (e) {
                span.appendChild(r.extractContents());
                r.insertNode(span);
            }

            /* Re-select the span's inner contents so repeated size picks keep working */
            var nr2 = document.createRange();
            nr2.selectNodeContents(span);
            s.removeAllRanges();
            s.addRange(nr2);

            sync();
        }

        /* ── table insert ── */
        function insertTable(rows, cols) {
            var html = '<table><thead><tr>';
            for (var c = 0; c < cols; c++) html += '<th>Header ' + (c + 1) + '</th>';
            html += '</tr></thead><tbody>';
            for (var r = 0; r < rows; r++) {
                html += '<tr>';
                for (var c2 = 0; c2 < cols; c2++) html += '<td>&nbsp;</td>';
                html += '</tr>';
            }
            html += '</tbody></table><p><br></p>';
            restSel();
            ex('insertHTML', html);
        }

        /* ── embed URL resolver ── */
        function embedUrl(url) {
            var yt = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s?]+)/);
            if (yt) return 'https://www.youtube.com/embed/' + yt[1];
            var vi = url.match(/vimeo\.com\/(\d+)/);
            if (vi) return 'https://player.vimeo.com/video/' + vi[1];
            return url;
        }

        /* ── find & replace ── */
        function clearMarks() {
            ed.querySelectorAll('mark.ne-fm').forEach(function (m) {
                m.replaceWith(document.createTextNode(m.textContent));
            });
            ed.normalize();
            fMatches = []; fIdx = -1;
            if (fcEl) fcEl.textContent = '';
        }

        function doFind(term) {
            clearMarks();
            if (!term) return;
            var walker = document.createTreeWalker(ed, NodeFilter.SHOW_TEXT, null, false);
            var nodes = [], n;
            while ((n = walker.nextNode())) {
                if (n.parentNode.closest('script,style')) continue;
                nodes.push(n);
            }
            var re = new RegExp(term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
            nodes.forEach(function (node) {
                var txt = node.textContent, parts = [], last = 0, m;
                re.lastIndex = 0;
                while ((m = re.exec(txt)) !== null) {
                    if (m.index > last) parts.push(document.createTextNode(txt.slice(last, m.index)));
                    var mk = document.createElement('mark');
                    mk.className = 'ne-fm';
                    mk.textContent = m[0];
                    parts.push(mk);
                    fMatches.push(mk);
                    last = re.lastIndex;
                }
                if (parts.length) {
                    if (last < txt.length) parts.push(document.createTextNode(txt.slice(last)));
                    var frag = document.createDocumentFragment();
                    parts.forEach(function (pp) { frag.appendChild(pp); });
                    node.parentNode.replaceChild(frag, node);
                }
            });
            if (fcEl) fcEl.textContent = fMatches.length + ' match' + (fMatches.length !== 1 ? 'es' : '');
            fIdx = -1;
            if (fMatches.length) scrollMark(0);
        }

        function scrollMark(i) {
            if (!fMatches.length) return;
            if (fIdx >= 0 && fMatches[fIdx]) fMatches[fIdx].classList.remove('ne-fc');
            fIdx = ((i % fMatches.length) + fMatches.length) % fMatches.length;
            var m = fMatches[fIdx];
            if (m) { m.classList.add('ne-fc'); m.scrollIntoView({ block: 'nearest', behavior: 'smooth' }); }
        }

        function doReplace(all) {
            var term = fiInp ? fiInp.value : '';
            var rep = riInp ? riInp.value : '';
            if (!term) return;
            clearMarks();
            var re = new RegExp(term.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), all ? 'gi' : 'i');
            ed.innerHTML = ed.innerHTML.replace(re, rep);
            sync();
            if (!all && fiInp) doFind(fiInp.value);
        }

        /* ── print ── */
        function print() {
            var w = window.open('', '_blank');
            w.document.write('<!DOCTYPE html><html><head><title>Print</title><style>' +
                'body{font-family:-apple-system,BlinkMacSystemFont,system-ui,sans-serif;padding:40px;max-width:800px;margin:0 auto;line-height:1.7;color:#0d1117}' +
                'h1,h2,h3,h4,h5,h6{margin:1em 0 .3em;letter-spacing:-.02em}' +
                'table{border-collapse:collapse;width:100%}th,td{border:1px solid #ddd;padding:8px 12px}' +
                'th{background:#f8f9fb;font-weight:700}' +
                'pre{background:#f4f4f4;padding:16px;border-radius:6px;font-size:13px;overflow-x:auto}' +
                'code{background:#f1f3f8;color:#e11d48;padding:1px 5px;border-radius:4px;font-size:.88em}' +
                'blockquote{border-left:3px solid #6366f1;margin:1em 0;padding-left:18px;color:#4b5563;font-style:italic}' +
                'img{max-width:100%;height:auto;border-radius:6px}' +
                '</style></head><body>' + ed.innerHTML + '</body></html>');
            w.document.close(); w.focus(); w.print();
        }

        /* ═══════════════════════════════════════
           COMMAND DISPATCHER
        ═══════════════════════════════════════ */
        function dispatch(cmd) {
            /* Dropdown toggles */
            if (cmd.indexOf('dd:') === 0) {
                var key = cmd.slice(3);
                saveSel();
                toggleDd(p + '-dd-' + key);
                return;
            }
            /* Block format */
            if (cmd.indexOf('block:') === 0) {
                restSel(); ex('formatBlock', cmd.slice(6)); closeAllDd(); return;
            }
            /* Font */
            if (cmd.indexOf('font:') === 0) {
                var fnt = cmd.slice(5);
                restSel(); ex('fontName', fnt === 'Default' ? 'inherit' : fnt);
                var fl = toolbar.querySelector('[data-c="dd:font"] .ne-bw-lbl');
                if (fl) fl.textContent = fnt;
                closeAllDd(); return;
            }
            /* Size */
            if (cmd.indexOf('size:') === 0) {
                var sz = parseInt(cmd.slice(5));
                restSel(); applySize(sz);
                /* Update saved selection to the re-selected span contents */
                saveSel();
                var sl = toolbar.querySelector('[data-c="dd:size"] .ne-bw-lbl');
                if (sl) sl.textContent = sz + 'px';
                closeAllDd(); return;
            }
            /* Colour */
            if (cmd.indexOf('fcolor:') === 0) {
                var fc = cmd.slice(7);
                restSel(); ex('foreColor', fc);
                if (fbar) fbar.style.background = fc;
                closeAllDd(); return;
            }
            if (cmd.indexOf('hcolor:') === 0) {
                var hc = cmd.slice(7);
                restSel(); ex('hiliteColor', hc);
                if (hbar) hbar.style.background = hc;
                closeAllDd(); return;
            }
            /* Insert char / emoji */
            if (cmd.indexOf('char:') === 0) {
                restSel();
                ex('insertText', cmd.slice(5));
                closeAllDd(); return;
            }

            switch (cmd) {
                case 'undo': ex('undo'); break;
                case 'redo': ex('redo'); break;
                case 'bold': ex('bold'); break;
                case 'italic': ex('italic'); break;
                case 'underline': ex('underline'); break;
                case 'strike': ex('strikeThrough'); break;
                case 'sub': ex('subscript'); break;
                case 'sup': ex('superscript'); break;
                case 'clearformat': ex('removeFormat'); break;
                case 'ul': ex('insertUnorderedList'); break;
                case 'ol': ex('insertOrderedList'); break;
                case 'task': taskList(); break;
                case 'quote': blockquote(); break;
                case 'hr': ex('insertHorizontalRule'); break;
                case 'jl': ex('justifyLeft'); break;
                case 'jc': ex('justifyCenter'); break;
                case 'jr': ex('justifyRight'); break;
                case 'jj': ex('justifyFull'); break;
                case 'indent': ex('indent'); break;
                case 'outdent': ex('outdent'); break;
                case 'unlink': ex('unlink'); break;
                case 'copy': ex('copy'); break;
                case 'cut': ex('cut'); break;
                case 'print': print(); break;

                case 'link':
                    saveSel();
                    var sl2 = W.getSelection();
                    var ltx = document.getElementById(p + '-ltxt');
                    if (ltx && sl2) ltx.value = sl2.toString();
                    openModal(linkModal, function () {
                        var url = (document.getElementById(p + '-lurl') || {}).value || '';
                        var txt = (document.getElementById(p + '-ltxt') || {}).value || '';
                        var tgt = (document.getElementById(p + '-ltgt') || {}).value || '';
                        if (!url) return false;
                        restSel();
                        var s2 = W.getSelection();
                        if (txt && s2 && s2.isCollapsed) {
                            var a = document.createElement('a');
                            a.href = url; a.textContent = txt;
                            if (tgt) a.target = tgt;
                            ex('insertHTML', a.outerHTML);
                        } else {
                            ex('createLink', url);
                            if (tgt) {
                                ed.querySelectorAll('a[href="' + url + '"]').forEach(function (a2) { a2.target = tgt; });
                            }
                        }
                    });
                    break;

                case 'image':
                    saveSel();
                    openModal(imgModal, function () {
                        var url = (document.getElementById(p + '-iurl') || {}).value || '';
                        var alt = (document.getElementById(p + '-ialt') || {}).value || '';
                        var wid = (document.getElementById(p + '-iwid') || {}).value || '';
                        if (!url) return false;
                        restSel();
                        var tag = '<img src="' + url + '" alt="' + alt + '"' +
                            (wid ? ' style="width:' + wid + ';max-width:100%"' : ' style="max-width:100%"') + '>';
                        ex('insertHTML', tag);
                    });
                    break;

                case 'video':
                    saveSel();
                    openModal(videoModal, function () {
                        var url = (document.getElementById(p + '-vurl') || {}).value || '';
                        var vw = (document.getElementById(p + '-vw') || {}).value || '100%';
                        var vh = (document.getElementById(p + '-vh') || {}).value || '400';
                        if (!url) return false;
                        restSel();
                        var eu = embedUrl(url);
                        var ifr = '<div style="margin:1em 0"><iframe src="' + eu +
                            '" width="' + vw + '" height="' + vh +
                            '" frameborder="0" allowfullscreen style="border-radius:8px;display:block;max-width:100%"></iframe></div>';
                        ex('insertHTML', ifr);
                    });
                    break;

                case 'viewsource':
                    srcTa.value = ed.innerHTML;
                    openModal(srcModal, function () {
                        ed.innerHTML = srcTa.value;
                        sync();
                    });
                    break;

                case 'find':
                    if (findbar.style.display === 'none') {
                        findbar.style.display = 'flex';
                        if (fiInp) fiInp.focus();
                    } else {
                        findbar.style.display = 'none';
                        clearMarks();
                    }
                    break;
                case 'fprev': scrollMark(fIdx - 1); break;
                case 'fnext': scrollMark(fIdx + 1); break;
                case 'replone': doReplace(false); break;
                case 'replall': doReplace(true); break;
                case 'closefind':
                    findbar.style.display = 'none'; clearMarks(); break;
            }
            updateActive();
        }

        /* ── Toolbar click ── */
        toolbar.addEventListener('mousedown', function (e) {
            var btn = e.target.closest('[data-c]');
            if (!btn) return;
            e.preventDefault();
            dispatch(btn.getAttribute('data-c'));
        });

        /* Findbar input */
        if (fiInp) {
            fiInp.addEventListener('input', function () { doFind(fiInp.value); });
            fiInp.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') scrollMark(fIdx + 1);
                if (e.key === 'Escape') { findbar.style.display = 'none'; clearMarks(); }
            });
        }

        /* Find/replace btns in findbar */
        findbar.addEventListener('mousedown', function (e) {
            var btn = e.target.closest('[data-c]');
            if (!btn) return;
            e.preventDefault();
            dispatch(btn.getAttribute('data-c'));
        });

        /* ── Dropdown item clicks (dropdowns live in <body>, so listen on document) ── */
        document.addEventListener('mousedown', function (e) {
            /* dd-item buttons (block, font, size) */
            var item = e.target.closest('.ne-dd-item');
            if (item) {
                e.preventDefault();
                restSel();
                dispatch(item.getAttribute('data-c'));
                return;
            }
            /* Color swatches */
            var sw = e.target.closest('.ne-swatch');
            if (sw) {
                e.preventDefault();
                restSel();
                var c = sw.getAttribute('data-color');
                var cmd = sw.getAttribute('data-cmd');
                dispatch(cmd + ':' + c);
                return;
            }
            /* Special chars / emoji */
            var cb = e.target.closest('.ne-char-btn');
            if (cb) {
                e.preventDefault();
                restSel();
                dispatch('char:' + cb.getAttribute('data-char'));
                return;
            }
            /* Close dropdown if click is outside toolbar and outside any open dropdown */
            if (!e.target.closest('.ne-has-dd') && !e.target.closest('.ne-dd')) {
                closeAllDd();
            }
        });

        /* ── Native color inputs (in body-appended dropdowns) ── */
        document.addEventListener('input', function (e) {
            if (!e.target.classList.contains('ne-color-native')) return;
            var cmd2 = e.target.getAttribute('data-cmd');
            var c2 = e.target.value;
            restSel();
            dispatch(cmd2 + ':' + c2);
        });

        /* ── Table picker ── */
        var tblGrid = document.getElementById(p + '-dd-table-grid');
        var tblLbl = document.getElementById(p + '-dd-table-lbl');
        if (tblGrid) {
            var cells = [];
            for (var rr = 0; rr < 8; rr++) {
                for (var cc = 0; cc < 8; cc++) {
                    (function (row, col) {
                        var cell = document.createElement('div');
                        cell.style.cssText = 'width:20px;height:20px;border:1.5px solid #e2e5ec;border-radius:3px;cursor:pointer;background:#f7f8fa;transition:background .1s,border-color .1s';
                        cell.dataset.r = row + 1; cell.dataset.c = col + 1;
                        cell.addEventListener('mouseenter', function () {
                            if (tblLbl) tblLbl.textContent = (row + 1) + ' × ' + (col + 1);
                            cells.forEach(function (cl) {
                                var on = parseInt(cl.dataset.r) <= row + 1 && parseInt(cl.dataset.c) <= col + 1;
                                cl.style.background = on ? '#eef2ff' : '#f7f8fa';
                                cl.style.borderColor = on ? '#a5b4fc' : '#e2e5ec';
                            });
                        });
                        cell.addEventListener('mousedown', function (e) {
                            e.preventDefault();
                            insertTable(row + 1, col + 1);
                            closeAllDd();
                        });
                        tblGrid.appendChild(cell);
                        cells.push(cell);
                    })(rr, cc);
                }
            }
        }

        /* ── Size input enter ── */
        if (szInp) {
            szInp.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    var v = parseInt(szInp.value);
                    if (v > 0) { restSel(); applySize(v); saveSel(); closeAllDd(); }
                }
            });
        }

        /* ── Context menu ── */
        ed.addEventListener('contextmenu', function (e) {
            e.preventDefault();
            ctx.style.cssText = 'display:block;left:' + e.clientX + 'px;top:' + e.clientY + 'px';
        });
        ctx.querySelectorAll('[data-c]').forEach(function (b) {
            b.addEventListener('mousedown', function (e) {
                e.preventDefault();
                dispatch(b.getAttribute('data-c'));
                ctx.style.display = 'none';
            });
        });
        document.addEventListener('mousedown', function (e) {
            if (!ctx.contains(e.target)) ctx.style.display = 'none';
        });

        /* ── Keyboard shortcuts ── */
        ed.addEventListener('keydown', function (e) {
            var ctrl = e.ctrlKey || e.metaKey;
            if (ctrl) {
                switch (e.key.toLowerCase()) {
                    case 'b': e.preventDefault(); dispatch('bold'); break;
                    case 'i': e.preventDefault(); dispatch('italic'); break;
                    case 'u': e.preventDefault(); dispatch('underline'); break;
                    case 'k': e.preventDefault(); dispatch('link'); break;
                    case 'f': e.preventDefault(); dispatch('find'); break;
                    case 'p': e.preventDefault(); print(); break;
                    case 'z': e.preventDefault(); dispatch(e.shiftKey ? 'redo' : 'undo'); break;
                    case 'y': e.preventDefault(); dispatch('redo'); break;
                }
            }
            if (e.key === 'Tab') {
                e.preventDefault();
                dispatch(e.shiftKey ? 'outdent' : 'indent');
            }
        });

        /* ── Paste: clean ── */
        ed.addEventListener('paste', function (e) {
            e.preventDefault();
            var html = (e.clipboardData || W.clipboardData).getData('text/html');
            var text = (e.clipboardData || W.clipboardData).getData('text/plain');
            if (html) {
                var tmp = document.createElement('div');
                tmp.innerHTML = html;
                tmp.querySelectorAll('script,style,meta,link,[style*="font-family:Calibri"],[style*="mso-"]').forEach(function (n) { n.removeAttribute('style'); });
                tmp.querySelectorAll('script,meta,link').forEach(function (n) { n.remove(); });
                ex('insertHTML', tmp.innerHTML);
            } else {
                ex('insertText', text);
            }
        });

        /* ── Content events ── */
        ed.addEventListener('input', function () { sync(); updateActive(); });
        ed.addEventListener('keyup', function () { updateActive(); });
        ed.addEventListener('mouseup', function () { updateActive(); });
        ed.addEventListener('focus', function () { updateActive(); });

        /* ── Init ── */
        updatePh(); updateStats(); updateActive();
        if (init) sync();
    }

    /* ─────────────────────────────────────────
       BOOT
    ───────────────────────────────────────── */
    function boot() {
        document.querySelectorAll('textarea.nEditor').forEach(init);
    }

    W.nEditor = { init: init, boot: boot };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }

})(window);