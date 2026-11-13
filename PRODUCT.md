# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

- **Boutique Gym & Combat Sports Academy Owners**: Operators of combat sports academies (MMA, Boxing, Muay Thai, BJJ), CrossFit boxes, and boutique strength clubs in the Philippines and Southeast Asia. They manage multi-branch or single-facility gym subscriptions, member dues, cashflow, and front-desk staff.
- **Gym Front-Desk Staff & Trainers**: Desk attendants operating front-desk tablets, laptops, or smartphones to scan member passes, verify attendance in real-time, enroll walk-ins, log manual GCash payments, and check class rosters.
- **Gym Members (End-Users)**: Everyday gym-goers, fighters, and fitness enthusiasts who need immediate, zero-friction access to their digital membership pass on their mobile browser, scan check-ins, track active attendance streaks, and settle dues via GCash without downloading any native mobile app.
- **Platform SuperAdmin**: Central SaaS operator configuring tiered platform pricing (monthly, annual, trial), reviewing incoming gym registration applications, verifying GCash receipts, granting access, and overseeing platform-wide transactions.

## Product Purpose

FitLedger is an athletic, multi-tenant gym management and member pass operating system designed specifically for high-intensity, combat sports, and boutique fitness facilities. It eliminates paper waivers, complex legacy software bloat, and manual payment tracking by unifying instant turnstile/kiosk QR verification, seamless GCash dues settlements, and multi-tenant gym operations into a single cohesive platform.

Success means:
- Front-desk check-in throughput under 2 seconds per scan.
- Zero manual reconciliation headache for GCash dues and platform subscriptions.
- 100% web-based accessibility where members never need to install an app from an app store.

## Positioning

Unlike bloated, slow legacy gym software (Mindbody, Zen Planner) that burdens members with app store downloads and charges exorbitant international merchant fees, FitLedger delivers an ultra-fast, zero-friction mobile browser digital pass coupled with turnstile-grade QR scanning and native Philippine GCash payment automation, tailored specifically to the athletic energy of boutique combat and fitness gyms.

## Operating Context

- **Front-Desk Kiosks & Hardware**: Front-desk tablets, counter laptops, or smartphones mounted at entrances running the FitLedger QR Scanner engine (`/admin/{gym}/scan-qr-code`) connected via API scanner key.
- **Mobile Web Browsers**: Members access passes on iOS Safari, Android Chrome, and mobile messaging webviews via unique hashed pass URLs (`/portal/pass/{qr_code}`) or phone lookup.
- **GCash Payment Ecosystem**: Both SaaS subscription billing (gym owner to platform admin) and membership dues (member to gym) operate via GCash Express Send reference numbers (13-digit) or automated PayMongo checkouts with screenshot receipt attachments and verification queues.
- **Multi-Tenant Navigation**: Gym owners with multiple locations can seamlessly switch active tenants from the sidebar switcher while preserving independent subscription lifecycles.

## Capabilities and Constraints

- **Multi-Tenancy & Tenant Isolation**: Each gym operates as an isolated tenant with separate memberships, plans, classes, subscriptions, schedules, and branding.
- **Turnstile / Kiosk Attendance Engine**: High-speed QR scanner with immediate color-coded entry response: Green (Active/Paid), Yellow (Expiring/Outstanding dues), Red (Expired/Inactive/Unauthorized).
- **Automated Dues Reminders & Grace Periods**: Automated dues tracking with configurable reminder windows and email/SMS alerts.
- **Platform SaaS Subscription Lifecycle**: Gym registration requires plan selection (Trial, Monthly, Annual) and SuperAdmin confirmation/denial before full operations unlock. SuperAdmin can update pricing and revoke access.
- **Schedule & Hours Enforcement**: Tenant-configurable operating days and opening/closing hours with real-time "Open Now" / "Closed" schedule indicators.
- **Technical Constraints**: Built on Laravel 12, Filament v3, Livewire, Tailwind CSS, and SQLite/MySQL; requires public storage symlink for uploaded avatars and gym logos.

## Brand Commitments

- **Product Name**: FitLedger.
- **Brand Emblem**: Vibrant athletic `FL` badge (`public/images/fitledger-logo.jpg`).
- **Brand Personality**: High-energy, athletic, bold, disciplined, precise, and professional.
- **Aesthetic Direction**: Deep charcoal/OLED dark surfaces (`#0a0c10`, `#12151b`), high-energy athletic orange accents (`#FF7A00`, `#FF5722`), crisp typography (`Barlow Condensed` for bold athletic headers, `Plus Jakarta Sans` for body, `JetBrains Mono` for numeric metrics and badges).

## Evidence on Hand

- **Existing Web Application**: Fully operational Laravel 12 + Filament v3 application with 62 passing feature & unit tests.
- **Brand Assets**: Official FitLedger brand logo asset in `public/images/fitledger-logo.jpg`.
- **Pre-seeded Facilities**: Real combat & fitness test tenants (`Bakbakan MMA & Fitness`, `PowerGym Central`) with active memberships, plans, check-in history, and payment transactions.
- **Digital Pass Surface**: Working public digital pass portal at `/portal` and `/portal/pass/{qr_code}` with SVG QR code generation.

## Product Principles

1. **Frictionless Velocity**: Front-desk operations, scan checks, and member pass lookups must happen in seconds with zero delay.
2. **Athletic Clarity**: Clean, high-contrast, uncluttered interfaces where primary actions and statuses (Active, Due, Days Left) are unmistakable at a glance.
3. **No App Store Barriers**: Members should never be forced to download a 100MB mobile app just to enter their gym or renew their pass.
4. **Resilient Payment Truth**: Every transaction (GCash reference numbers, PayMongo webhooks, manual receipt approvals) must maintain an immutable audit trail with clear pending, confirmed, or rejected states.

## Accessibility & Inclusion

- High contrast text and badge labels designed for dim gym environments and brightly lit front-desk monitors.
- Explicit semantic status badges (combining color, icons, and text labels) so critical access statuses (Green/Yellow/Red) are never conveyed by color alone.
- Responsive layout supporting mobile screens, front-desk tablets, and desktop workstations.
