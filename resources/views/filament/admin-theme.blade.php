<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,600;0,700;0,800;0,900;1,700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

<style>
    /* Font overrides */
    body, .fi-body {
        font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif !important;
    }

    .font-athletic,
    .fi-header-heading,
    .fi-ta-header-heading,
    .fi-section-header-heading,
    .fi-topbar-brand-name {
        font-family: 'Barlow Condensed', sans-serif !important;
        letter-spacing: 0.02em !important;
        text-transform: uppercase !important;
    }

    /* Dark Mode Deep Charcoal / OLED Styling */
    html.dark body,
    html.dark .fi-body {
        background-color: #0a0c10 !important;
        color: #f1f5f9 !important;
    }

    /* Top bar */
    html.dark .fi-topbar {
        background-color: #0e1117 !important;
        border-bottom-color: rgba(255, 255, 255, 0.06) !important;
    }

    /* Sidebar */
    html.dark .fi-sidebar {
        background-color: #0e1117 !important;
        border-right-color: rgba(255, 255, 255, 0.06) !important;
    }

    html.dark .fi-sidebar-header {
        border-bottom-color: rgba(255, 255, 255, 0.05) !important;
    }

    /* Sidebar Items */
    html.dark .fi-sidebar-item-active .fi-sidebar-item-button {
        background: linear-gradient(90deg, rgba(255, 87, 34, 0.18) 0%, rgba(255, 87, 34, 0.05) 100%) !important;
        color: #FF7A00 !important;
        border-left: 3px solid #FF5722 !important;
        border-radius: 0.75rem !important;
        font-weight: 700 !important;
    }

    html.dark .fi-sidebar-item-active .fi-sidebar-item-icon {
        color: #FF7A00 !important;
    }

    /* Cards, Sections & Widgets */
    html.dark .fi-section,
    html.dark .fi-widget,
    html.dark .fi-ta-ctn,
    html.dark .fi-wi-stats-overview-stat {
        background-color: #12151b !important;
        border: 1px solid rgba(255, 255, 255, 0.06) !important;
        border-radius: 1.25rem !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.4) !important;
    }

    /* Stats Overview Widget Tiles (Exact mirror of reference athletic stats) */
    html.dark .fi-wi-stats-overview-stat {
        padding: 1.25rem !important;
        transition: transform 0.2s ease, border-color 0.2s ease !important;
    }

    html.dark .fi-wi-stats-overview-stat:hover {
        transform: translateY(-2px) !important;
        border-color: rgba(255, 87, 34, 0.3) !important;
    }

    html.dark .fi-wi-stats-overview-stat-value {
        font-family: 'Barlow Condensed', sans-serif !important;
        font-size: 2.25rem !important;
        font-weight: 900 !important;
        letter-spacing: -0.01em !important;
        color: #ffffff !important;
        line-height: 1 !important;
    }

    html.dark .fi-wi-stats-overview-stat-label {
        font-size: 0.75rem !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        color: #94a3b8 !important;
    }

    /* Table styling */
    html.dark .fi-ta-header-cell {
        background-color: #171a22 !important;
        color: #94a3b8 !important;
        font-size: 0.7rem !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.06em !important;
        padding-top: 0.85rem !important;
        padding-bottom: 0.85rem !important;
    }

    html.dark .fi-ta-row {
        transition: background-color 0.15s ease !important;
    }

    html.dark .fi-ta-row:hover {
        background-color: rgba(255, 255, 255, 0.02) !important;
    }

    /* Primary Buttons -> Vibrant Orange Gradient */
    html.dark .fi-btn-primary,
    .fi-btn-primary {
        background: linear-gradient(135deg, #FF6D00 0%, #FF3D00 100%) !important;
        box-shadow: 0 4px 14px -2px rgba(255, 61, 0, 0.4) !important;
        border-radius: 0.75rem !important;
        font-weight: 700 !important;
        border: none !important;
        transition: transform 0.15s ease, box-shadow 0.15s ease !important;
    }

    html.dark .fi-btn-primary:hover,
    .fi-btn-primary:hover {
        background: linear-gradient(135deg, #FF7D1A 0%, #FF501A 100%) !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 6px 18px -2px rgba(255, 61, 0, 0.5) !important;
    }

    /* Form Inputs */
    html.dark .fi-input-wrp {
        background-color: #0a0c10 !important;
        border-color: rgba(255, 255, 255, 0.1) !important;
        border-radius: 0.75rem !important;
    }

    html.dark .fi-input-wrp:focus-within {
        border-color: #FF5722 !important;
        box-shadow: 0 0 0 1px #FF5722 !important;
    }

    /* Badges */
    .fi-badge {
        font-weight: 800 !important;
        letter-spacing: 0.04em !important;
        border-radius: 0.5rem !important;
    }

    /* Split-Screen Auth Page Layout */
    .auth-split-wrapper {
        min-height: 100vh;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        background-color: #0a0c10;
        box-sizing: border-box;
    }

    .auth-split-card {
        max-width: 1040px;
        width: 100%;
        min-height: 600px;
        display: flex;
        flex-direction: row;
        border-radius: 1.5rem;
        overflow: hidden;
        background-color: #12151b;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.85);
    }

    .auth-form-column {
        flex: 1 1 50%;
        max-width: 50%;
        padding: 3rem 2.75rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background-color: #12151b;
        box-sizing: border-box;
        z-index: 2;
    }

    .auth-image-column {
        flex: 1 1 50%;
        max-width: 50%;
        position: relative;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100%;
        display: block;
    }

    .auth-image-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(10, 12, 16, 0.25) 0%, rgba(10, 12, 16, 0.70) 100%);
        pointer-events: none;
    }

    @media (max-width: 860px) {
        .auth-split-card {
            flex-direction: column;
            max-width: 480px;
            min-height: auto;
        }

        .auth-form-column {
            flex: 1 1 100%;
            max-width: 100%;
            padding: 2.25rem 1.75rem;
        }

        .auth-image-column {
            display: none;
        }
    }
</style>
