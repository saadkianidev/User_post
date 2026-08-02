<div id="themeCustomizer" class="theme-fab">
    <button id="themeToggleBtn" class="theme-toggle-btn" title="Customize theme">
        <i class="bi bi-palette-fill"></i>
    </button>

    <div id="themePanel" class="theme-panel shadow">
        <h6 class="mb-3 fw-bold">Customize Theme</h6>

        <div class="mb-2">
            <label class="form-label small mb-1">Primary</label>
            <input type="color" id="primaryColorInput" class="form-control form-control-color w-100">
        </div>
        <div class="mb-2">
            <label class="form-label small mb-1">Secondary</label>
            <input type="color" id="secondaryColorInput" class="form-control form-control-color w-100">
        </div>
        <div class="mb-3">
            <label class="form-label small mb-1">Background</label>
            <input type="color" id="bgColorInput" class="form-control form-control-color w-100">
        </div>

        <button id="themeResetBtn" class="btn btn-sm btn-outline-secondary w-100">Reset to default</button>
        <div id="themeSaveStatus" class="small text-muted mt-2 text-center"></div>
    </div>
</div>

<style>
    .theme-fab { position: fixed; bottom: 24px; right: 24px; z-index: 1050; }
    .theme-toggle-btn {
        width: 52px; height: 52px; border-radius: 50%; border: none;
        background: var(--color-primary); color: #fff; font-size: 20px;
        box-shadow: 0 4px 14px rgba(0,0,0,.25); cursor: pointer;
    }
    .theme-panel {
        display: none; position: absolute; bottom: 64px; right: 0;
        background: #fff; border-radius: 12px; padding: 16px; width: 220px;
        box-shadow: 0 8px 24px rgba(0,0,0,.15);
    }
    .theme-panel.open { display: block; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.documentElement;
    const toggleBtn = document.getElementById('themeToggleBtn');
    const panel = document.getElementById('themePanel');
    const primaryInput = document.getElementById('primaryColorInput');
    const secondaryInput = document.getElementById('secondaryColorInput');
    const bgInput = document.getElementById('bgColorInput');
    const resetBtn = document.getElementById('themeResetBtn');
    const status = document.getElementById('themeSaveStatus');

    const DEFAULTS = { primary: '#4f46e5', secondary: '#22c55e', bg: '#ffffff' };

    function current() {
        return {
            primary: getComputedStyle(root).getPropertyValue('--color-primary').trim() || DEFAULTS.primary,
            secondary: getComputedStyle(root).getPropertyValue('--color-secondary').trim() || DEFAULTS.secondary,
            bg: getComputedStyle(root).getPropertyValue('--color-bg').trim() || DEFAULTS.bg,
        };
    }

    function syncInputs() {
        const c = current();
        primaryInput.value = c.primary;
        secondaryInput.value = c.secondary;
        bgInput.value = c.bg;
    }
    syncInputs();

    toggleBtn.addEventListener('click', () => panel.classList.toggle('open'));

    // close panel when clicking outside it
    document.addEventListener('click', (e) => {
        if (!document.getElementById('themeCustomizer').contains(e.target)) {
            panel.classList.remove('open');
        }
    });

    let saveTimer = null;
    function applyAndPersist() {
        const theme = {
            primary: primaryInput.value,
            secondary: secondaryInput.value,
            bg: bgInput.value,
        };

        root.style.setProperty('--color-primary', theme.primary);
        root.style.setProperty('--color-secondary', theme.secondary);
        root.style.setProperty('--color-bg', theme.bg);

        localStorage.setItem('siteTheme', JSON.stringify(theme));

        status.textContent = 'Saving...';
        clearTimeout(saveTimer);
        saveTimer = setTimeout(() => {
            fetch('{{ route("theme.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({
                    primary_color: theme.primary,
                    secondary_color: theme.secondary,
                    bg_color: theme.bg,
                }),
            })
            .then(res => res.json())
            .then(() => status.textContent = 'Saved ✓')
            .catch(() => status.textContent = 'Save failed');
        }, 500);
    }

    [primaryInput, secondaryInput, bgInput].forEach(input =>
        input.addEventListener('input', applyAndPersist)
    );

    resetBtn.addEventListener('click', () => {
        primaryInput.value = DEFAULTS.primary;
        secondaryInput.value = DEFAULTS.secondary;
        bgInput.value = DEFAULTS.bg;
        applyAndPersist();
    });
});
</script>