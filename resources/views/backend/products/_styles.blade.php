{{--
    Shared styles for the Category/Product admin pages.
    Included with @include('backend.products._styles') at the top of every
    view in this feature so the look stays consistent. Safe to include more
    than once per request (id guard below prevents duplicate <style> tags).
--}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style id="cp-styles">
    :root {
        --cp-primary: #C86B4A;
        --cp-primary-dark: #a85c3e;
        --cp-bg: #f8f9fc;
        --cp-border: #e9ecf2;
        --cp-radius: 14px;
        --cp-radius-sm: 10px;
        --cp-shadow: 0 2px 10px rgba(17, 24, 39, .05);
        --cp-shadow-hover: 0 8px 24px rgba(17, 24, 39, .09);
    }

    /* ---------- page shell ---------- */
    .cp-wrap {
        max-width: 1180px;
        margin: 0 auto;
        padding: 1.25rem clamp(.75rem, 2vw, 1.5rem) 3rem;
    }

    .cp-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .cp-header h1 {
        font-size: clamp(1.25rem, 2.4vw, 1.6rem);
        font-weight: 700;
        margin: 0;
        color: #111827;
        letter-spacing: -.01em;
    }

    .cp-header .cp-subtitle {
        color: #6b7280;
        font-size: .92rem;
        margin-top: .15rem;
    }

    .cp-breadcrumb {
        font-size: .82rem;
        color: #9ca3af;
        margin-bottom: .4rem;
    }

    .cp-breadcrumb a {
        color: #6b7280;
        text-decoration: none;
    }

    .cp-breadcrumb a:hover {
        color: var(--cp-primary);
    }

    /* ---------- buttons ---------- */
    .cp-btn {
        display: inline-flex;
        align-items: center;
        gap: .4rem;
        border: 1px solid transparent;
        border-radius: 10px;
        padding: .55rem 1.1rem;
        font-size: .88rem;
        font-weight: 600;
        line-height: 1.1;
        cursor: pointer;
        text-decoration: none;
        transition: .15s ease;
        white-space: nowrap;
    }

    .cp-btn-primary {
        background: var(--cp-primary);
        color: #fff;
        box-shadow: 0 1px 2px rgba(79, 70, 229, .3);
    }

    .cp-btn-primary:hover {
        background: var(--cp-primary-dark);
        color: #fff;
        transform: translateY(-1px);
    }

    .cp-btn-outline {
        background: #fff;
        color: #374151;
        border-color: var(--cp-border);
    }

    .cp-btn-outline:hover {
        background: #f3f4f6;
        color: #111827;
    }

    .cp-btn-danger {
        background: #fff;
        color: #dc2626;
        border-color: #fecaca;
    }

    .cp-btn-danger:hover {
        background: #fef2f2;
    }

    .cp-btn-sm {
        padding: .35rem .7rem;
        font-size: .78rem;
        border-radius: 8px;
    }

    .cp-btn-icon {
        width: 34px;
        height: 34px;
        padding: 0;
        justify-content: center;
        border-radius: 8px;
    }

    /* ---------- cards ---------- */
    .cp-card {
        background: #fff;
        border: 1px solid var(--cp-border);
        border-radius: var(--cp-radius);
        box-shadow: var(--cp-shadow);
        margin-bottom: 1.25rem;
        overflow: hidden;
    }

    .cp-card-body {
        padding: clamp(1rem, 2.5vw, 1.75rem);
    }

    .cp-card-header {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--cp-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: .75rem;
        flex-wrap: wrap;
    }

    .cp-card-header h2 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        color: #111827;
    }

    .cp-card-header .cp-hint {
        font-size: .8rem;
        color: #9ca3af;
    }

    /* ---------- alerts ---------- */
    .cp-alert {
        border-radius: var(--cp-radius-sm);
        padding: .85rem 1.1rem;
        margin-bottom: 1.1rem;
        font-size: .9rem;
        display: flex;
        gap: .6rem;
        align-items: flex-start;
        border: 1px solid transparent;
    }

    .cp-alert-success {
        background: #ecfdf5;
        color: #065f46;
        border-color: #a7f3d0;
    }

    .cp-alert-danger {
        background: #fef2f2;
        color: #991b1b;
        border-color: #fecaca;
    }

    .cp-alert ul {
        margin: .15rem 0 0;
        padding-left: 1.1rem;
    }

    /* ---------- responsive table ---------- */
    .cp-table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .cp-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 640px;
    }

    .cp-table thead th {
        text-align: left;
        font-size: .72rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6b7280;
        background: var(--cp-bg);
        padding: .75rem 1rem;
        white-space: nowrap;
        border-bottom: 1px solid var(--cp-border);
    }

    .cp-table tbody td {
        padding: .7rem 1rem;
        border-bottom: 1px solid var(--cp-border);
        font-size: .88rem;
        color: #1f2937;
        vertical-align: middle;
    }

    .cp-table tbody tr:last-child td {
        border-bottom: none;
    }

    .cp-table tbody tr:hover {
        background: #fafbff;
    }

    .cp-table tbody tr.cp-row-child td {
        background: #fafbfc;
    }

    .cp-table tbody tr.cp-row-child td:nth-child(2) {
        color: #4b5563;
    }

    .cp-row-actions {
        display: flex;
        gap: .4rem;
        flex-wrap: nowrap;
    }

    /* stacked cards on very small screens instead of a squeezed table */
    @media (max-width:576px) {
        .cp-table-scroll {
            border: 1px solid var(--cp-border);
            border-radius: var(--cp-radius-sm);
        }
    }

    /* ---------- thumbnails ---------- */
    .cp-thumb {
        width: 44px;
        height: 44px;
        border-radius: 9px;
        object-fit: cover;
        border: 1px solid var(--cp-border);
        background: #f3f4f6;
        display: block;
    }

    .cp-thumb-placeholder {
        width: 44px;
        height: 44px;
        border-radius: 9px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #c7cbd4;
        font-size: 1.1rem;
    }

    /* ---------- badges ---------- */
    .cp-badge {
        display: inline-flex;
        align-items: center;
        gap: .3rem;
        padding: .25rem .6rem;
        border-radius: 999px;
        font-size: .74rem;
        font-weight: 600;
    }

    .cp-badge-green {
        background: #ecfdf5;
        color: #059669;
    }

    .cp-badge-gray {
        background: #f3f4f6;
        color: #6b7280;
    }

    .cp-badge-red {
        background: #fef2f2;
        color: #dc2626;
    }

    .cp-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
    }

    /* ---------- empty state ---------- */
    .cp-empty {
        text-align: center;
        padding: 3.5rem 1.5rem;
        color: #9ca3af;
    }

    .cp-empty i {
        font-size: 2.2rem;
        color: #d1d5db;
        /* margin-bottom: .75rem; */
        display: block;
    }

    .cp-empty p {
        margin: .25rem 0 1rem;
    }

    /* ---------- forms ---------- */
    .cp-label {
        font-weight: 600;
        font-size: .85rem;
        color: #374151;
        margin-bottom: .35rem;
        display: block;
    }

    .cp-label .cp-optional {
        font-weight: 400;
        color: #9ca3af;
    }

    .cp-help {
        font-size: .78rem;
        color: #9ca3af;
        margin-top: .3rem;
    }

    .cp-input,
    .cp-select,
    textarea.cp-input {
        width: 100%;
        border: 1px solid var(--cp-border);
        border-radius: 9px;
        padding: .6rem .8rem;
        font-size: .9rem;
        color: #111827;
        background: #fff;
        transition: .15s;
        outline: none;
    }

    .cp-input:focus,
    .cp-select:focus,
    textarea.cp-input:focus {
        border-color: var(--cp-primary);
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
    }

    .cp-input.is-invalid,
    .cp-select.is-invalid {
        border-color: #dc2626;
    }

    .cp-invalid-feedback {
        color: #dc2626;
        font-size: .78rem;
        margin-top: .3rem;
    }

    .cp-switch {
        display: flex;
        align-items: center;
        gap: .6rem;
    }

    .cp-switch input {
        width: 2.4rem;
        height: 1.35rem;
        cursor: pointer;
        accent-color: var(--cp-primary);
    }

    .cp-switch label {
        font-size: .88rem;
        color: #374151;
        font-weight: 600;
        cursor: pointer;
    }

    .cp-color-row {
        border: 1px solid var(--cp-border);
        border-radius: var(--cp-radius-sm);
        padding: 1rem;
        margin-bottom: .85rem;
        background: #fbfbfe;
        position: relative;
    }

    .cp-color-row .cp-remove-btn {
        position: absolute;
        top: .6rem;
        right: .6rem;
    }

    .cp-existing-color {
        border: 1px solid var(--cp-border);
        border-radius: var(--cp-radius-sm);
        padding: .9rem 1rem;
        margin-bottom: .6rem;
        background: #fff;
    }

    .cp-swatch {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        display: inline-block;
        border: 1px solid rgba(0, 0, 0, .1);
        vertical-align: middle;
        margin-left: .35rem;
    }

    /* Quill editor container polish */
    .cp-editor-wrap .ql-toolbar {
        border-radius: 9px 9px 0 0;
        border-color: var(--cp-border);
        background: var(--cp-bg);
    }

    .cp-editor-wrap .ql-container {
        border-radius: 0 0 9px 9px;
        border-color: var(--cp-border);
        font-size: .9rem;
    }

    .cp-grid {
        display: grid;
        gap: 1rem;
    }

    .cp-grid-2 {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }

    .cp-grid-3 {
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    }

    .cp-form-actions {
        display: flex;
        gap: .6rem;
        justify-content: flex-end;
        flex-wrap: wrap;
        padding-top: 1rem;
        margin-top: .5rem;
        border-top: 1px solid var(--cp-border);
    }

    @media (max-width:576px) {
        .cp-form-actions {
            justify-content: stretch;
        }

        .cp-form-actions .cp-btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>
