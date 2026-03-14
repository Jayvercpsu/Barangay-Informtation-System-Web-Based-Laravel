import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function setButtonLoadingState(button) {
    if (!button || button.dataset.loadingApplied === '1') {
        return;
    }

    button.dataset.loadingApplied = '1';
    button.dataset.originalDisabled = button.disabled ? '1' : '0';
    button.disabled = true;
    button.classList.add('opacity-80', 'cursor-not-allowed');

    if (button.tagName === 'BUTTON') {
        const originalHtml = button.innerHTML;
        button.dataset.originalHtml = originalHtml;

        const fallbackText = button.textContent?.trim() || 'Please wait...';
        const loadingText = button.dataset.loadingText || `${fallbackText}...`;

        button.innerHTML = `
            <span class="inline-flex items-center gap-2">
                <svg class="h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-opacity="0.25" stroke-width="4"></circle>
                    <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
                </svg>
                <span>${loadingText}</span>
            </span>
        `;
    }

    if (button.tagName === 'INPUT') {
        button.dataset.originalValue = button.value;
        button.value = button.dataset.loadingText || 'Please wait...';
    }
}

function disableOtherSubmitControls(form, activeSubmitter) {
    const submitControls = form.querySelectorAll('button[type="submit"], button:not([type]), input[type="submit"]');
    submitControls.forEach((control) => {
        if (control === activeSubmitter) {
            return;
        }
        control.disabled = true;
        control.classList.add('opacity-80', 'cursor-not-allowed');
    });
}

document.addEventListener('submit', (event) => {
    const form = event.target;
    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    if (form.dataset.disableLoading === 'true') {
        return;
    }

    const submitter = event.submitter
        || form.querySelector('button[type="submit"], button:not([type]), input[type="submit"]');

    if (!submitter) {
        return;
    }

    setButtonLoadingState(submitter);
    disableOtherSubmitControls(form, submitter);
}, true);
