<style>
    .wizard-page {
        display: flex;
        align-items: flex-start;
        width: 100%;
        flex: 1;
        min-height: 100%;
    }

    .wizard-sidebar {
        position: sticky;
        top: var(--app-topbar-height, 4rem);
        z-index: 20;
        width: 260px;
        flex-shrink: 0;
        background: #f8f9fa;
        border-right: 1px solid #e5e7eb;
        display: flex;
        flex-direction: column;
        align-self: flex-start;
        height: calc(100vh - var(--app-topbar-height, 4rem));
        max-height: calc(100vh - var(--app-topbar-height, 4rem));
        overflow-y: auto;
        overscroll-behavior: contain;
    }

    .wizard-back-link {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1.25rem 1.5rem;
        color: #111827;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        border-bottom: 1px solid #e5e7eb;
        transition: color 0.2s ease;
        flex-shrink: 0;
    }

    .wizard-back-link:hover {
        color: #ff7200;
    }

    .wizard-steps-nav {
        display: flex;
        flex-direction: column;
        padding: 0;
        flex: 1;
    }

    .wizard-step-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        width: 100%;
        height: 60px;
        min-height: 60px;
        max-height: 60px;
        padding: 0 1.25rem;
        border: none;
        border-left: 3px solid transparent;
        background: transparent;
        text-align: left;
        cursor: default;
        transition: background 0.2s ease, border-color 0.2s ease;
        flex-shrink: 0;
    }

    .wizard-step-item.is-active {
        background: #fff;
        border-left-color: #ff7200;
        cursor: default;
    }

    .wizard-step-item.is-done {
        cursor: pointer;
    }

    .wizard-step-item.is-done:hover {
        background: #fff;
    }

    .wizard-step-marker {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 700;
        flex-shrink: 0;
        background: #e5e7eb;
        color: #64748b;
    }

    .wizard-step-item.is-active .wizard-step-marker {
        background: #ff7200;
        color: #fff;
    }

    .wizard-step-item.is-done .wizard-step-marker {
        background: #6b7280;
        color: #fff;
    }

    .wizard-step-text {
        font-size: 13px;
        line-height: 1.25;
        color: #64748b;
        font-weight: 500;
        padding-top: 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .wizard-step-item.is-active .wizard-step-text {
        color: #111827;
        font-weight: 600;
    }

    .wizard-step-item.is-done .wizard-step-text {
        color: #334155;
    }

    .wizard-main {
        flex: 1;
        min-width: 0;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .wizard-content {
        flex: 1;
        min-width: 0;
        background: #fff;
    }

    .wizard-content .step {
        padding: 30px;
    }

    .wizard-page .progress-bar,
    .wizard-page .step-indicator {
        display: none !important;
    }

    .wizard-page--success .wizard-sidebar {
        display: none;
    }

    .wizard-page--success .wizard-main {
        width: 100%;
    }

    @media (max-width: 768px) {
        .wizard-page {
            flex-direction: column;
        }

        .wizard-sidebar {
            position: sticky;
            top: var(--app-topbar-height, 4rem);
            width: 100%;
            height: auto;
            max-height: none;
            min-height: auto;
            border-right: none;
            border-bottom: 1px solid #e5e7eb;
            overflow-y: visible;
        }

        .wizard-steps-nav {
            flex-direction: row;
            overflow-x: auto;
            padding: 0.5rem;
            gap: 0.25rem;
        }

        .wizard-step-item {
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-width: 96px;
            width: auto;
            height: 60px;
            min-height: 60px;
            max-height: 60px;
            padding: 0 0.5rem;
            border-left: none;
            border-bottom: 3px solid transparent;
            text-align: center;
        }

        .wizard-step-item.is-active {
            border-bottom-color: #ff7200;
        }

        .wizard-step-text {
            font-size: 11px;
            padding-top: 0;
        }
    }
</style>
