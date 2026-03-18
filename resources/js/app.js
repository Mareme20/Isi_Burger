import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

async function submitPanierForm(form) {
    const formData = new FormData(form);
    const response = await fetch(form.action, {
        method: form.method || 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken ?? '',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
        body: formData,
    });

    const data = await response.json();

    if (!response.ok) {
        throw data;
    }

    updatePanierUi(data);
}

function updatePanierUi(data) {
    const panelContent = document.querySelector('#panier-panel-content');
    if (panelContent && typeof data.html === 'string') {
        panelContent.innerHTML = data.html;
    }

    document.querySelectorAll('[data-panier-count]').forEach((element) => {
        element.textContent = data.count ?? 0;
    });

    showPanierFlash(data.message, data.type ?? 'success');
}

function showPanierFlash(message, type) {
    let flash = document.querySelector('#panier-ajax-flash');

    if (!flash) {
        flash = document.createElement('div');
        flash.id = 'panier-ajax-flash';
        flash.style.position = 'fixed';
        flash.style.top = '1rem';
        flash.style.right = '1rem';
        flash.style.zIndex = '1080';
        flash.style.maxWidth = '360px';
        document.body.appendChild(flash);
    }

    flash.className = type === 'error' ? 'auth-flash auth-flash-error' : 'auth-flash auth-flash-success';
    flash.textContent = message;

    window.clearTimeout(flash._timeoutId);
    flash._timeoutId = window.setTimeout(() => {
        flash.textContent = '';
        flash.className = '';
    }, 2600);
}

document.addEventListener('submit', async (event) => {
    const form = event.target.closest('form[data-panier-form]');
    if (!form) {
        return;
    }

    event.preventDefault();

    try {
        await submitPanierForm(form);
    } catch (error) {
        const validationMessage = error?.message
            || error?.errors?.quantite?.[0]
            || error?.errors?.burger?.[0]
            || 'Une erreur est survenue pendant la mise a jour du panier.';

        showPanierFlash(validationMessage, 'error');
    }
});
