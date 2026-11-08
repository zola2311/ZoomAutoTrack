<x-filament-panels::page.simple>
    <style>
        .autofill-container {
            margin-bottom: 1.5rem;
            padding: 1rem 1.25rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        :is(.dark) .autofill-container {
            background: #18181b !important;
            border-color: #27272a !important;
        }
        .autofill-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
        }
        :is(.dark) .autofill-header {
            color: #94a3b8 !important;
        }
        .autofill-buttons-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }
        .autofill-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 8px 14px;
            font-size: 12.5px;
            font-weight: 600;
            font-family: inherit;
            border-radius: 8px;
            border: 1px solid;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-decoration: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            line-height: 1.2;
            letter-spacing: 0.01em;
        }
        .autofill-btn:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.14);
        }
        .autofill-btn:active {
            transform: translateY(0);
            filter: brightness(0.95);
        }
        .btn-admin {
            background: linear-gradient(135deg, #0C447C 0%, #1565C0 100%) !important;
            color: #ffffff !important;
            border-color: #0B3D6F !important;
        }
        .btn-receptionist {
            background: linear-gradient(135deg, #1E40AF 0%, #2563EB 100%) !important;
            color: #ffffff !important;
            border-color: #1D4ED8 !important;
        }
        .btn-mechanics {
            background: linear-gradient(135deg, #0284C7 0%, #0EA5E9 100%) !important;
            color: #ffffff !important;
            border-color: #0369A1 !important;
        }
        .btn-advisor {
            background: linear-gradient(135deg, #059669 0%, #10B981 100%) !important;
            color: #ffffff !important;
            border-color: #047857 !important;
        }
        .btn-manager {
            background: linear-gradient(135deg, #D97706 0%, #F59E0B 100%) !important;
            color: #ffffff !important;
            border-color: #B45309 !important;
        }
        .btn-cashier {
            background: linear-gradient(135deg, #7C3AED 0%, #8B5CF6 100%) !important;
            color: #ffffff !important;
            border-color: #6D28D9 !important;
        }
        .btn-inventory {
            background: linear-gradient(135deg, #EA580C 0%, #F97316 100%) !important;
            color: #ffffff !important;
            border-color: #C2410C !important;
        }
    </style>

    <div class="autofill-container">
        <div class="autofill-header">
            <span>Quick Autofill Test Role</span>
            <span style="font-weight: 500; text-transform: none; font-size: 11px; opacity: 0.85;">
                Select to fill credentials
            </span>
        </div>

        <div class="autofill-buttons-grid">
            <button
                type="button"
                wire:click="autofill('admin')"
                onclick="autofillLogin('admin@autotrack.et', 'password')"
                class="autofill-btn btn-admin"
                title="Autofill System Admin credentials"
            >
                Admin
            </button>

            <button
                type="button"
                wire:click="autofill('receptionist')"
                onclick="autofillLogin('reception@autotrack.et', 'password')"
                class="autofill-btn btn-receptionist"
                title="Autofill Receptionist credentials"
            >
                Receptionist
            </button>

            <button
                type="button"
                wire:click="autofill('mechanics')"
                onclick="autofillLogin('mechanic1@autotrack.et', 'password')"
                class="autofill-btn btn-mechanics"
                title="Autofill Mechanics credentials"
            >
                Mechanics
            </button>

            <button
                type="button"
                wire:click="autofill('advisor')"
                onclick="autofillLogin('advisor@autotrack.et', 'password')"
                class="autofill-btn btn-advisor"
                title="Autofill Service Advisor credentials"
            >
                Service Advisor
            </button>

            <button
                type="button"
                wire:click="autofill('manager')"
                onclick="autofillLogin('manager@autotrack.et', 'password')"
                class="autofill-btn btn-manager"
                title="Autofill Workshop Manager credentials"
            >
                Manager
            </button>

            <button
                type="button"
                wire:click="autofill('cashier')"
                onclick="autofillLogin('cashier@autotrack.et', 'password')"
                class="autofill-btn btn-cashier"
                title="Autofill Cashier credentials"
            >
                Cashier
            </button>

            <button
                type="button"
                wire:click="autofill('inventory')"
                onclick="autofillLogin('inventory@autotrack.et', 'password')"
                class="autofill-btn btn-inventory"
                title="Autofill Inventory Manager credentials"
            >
                Inventory
            </button>
        </div>
    </div>

    {{ $this->content }}

    <script>
        function autofillLogin(email, password = 'password') {
            const emailInput = document.querySelector('#data\\.email') ||
                               document.querySelector('input[type="email"]') ||
                               document.querySelector('input[name*="email"]') ||
                               document.querySelector('input[wire\\:model*="email"]');

            const passInput = document.querySelector('#data\\.password') ||
                              document.querySelector('input[type="password"]') ||
                              document.querySelector('input[name*="password"]') ||
                              document.querySelector('input[wire\\:model*="password"]');

            if (emailInput) {
                emailInput.value = email;
                emailInput.dispatchEvent(new Event('input', { bubbles: true }));
                emailInput.dispatchEvent(new Event('change', { bubbles: true }));
                emailInput.focus();
            }

            if (passInput) {
                passInput.value = password;
                passInput.dispatchEvent(new Event('input', { bubbles: true }));
                passInput.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    </script>
</x-filament-panels::page.simple>
