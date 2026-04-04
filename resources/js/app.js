import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const debounceTimers = new WeakMap();
const modalRoot = document.querySelector('#app-modal-root');
const modalContent = modalRoot?.querySelector('[data-modal-content]') ?? null;
const modalBackdrop = modalRoot?.querySelector('[data-modal-backdrop]') ?? null;

function buildAsyncSearchUrl(form, href = null) {
    const url = new URL(href || form.action || window.location.href, window.location.origin);
    const formData = new FormData(form);

    url.search = '';

    for (const [key, value] of formData.entries()) {
        if (typeof value === 'string' && value.trim() !== '') {
            url.searchParams.set(key, value);
        }
    }

    return url;
}

async function performAsyncSearch(form, href = null, pushState = true) {
    const targetSelector = form.dataset.target;
    const target = document.querySelector(targetSelector);

    if (!target) {
        return;
    }

    const url = buildAsyncSearchUrl(form, href);

    form.classList.add('pointer-events-none', 'opacity-70');
    target.classList.add('opacity-60');

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
            },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error(`Request failed with status ${response.status}`);
        }

        target.innerHTML = await response.text();
        window.Alpine.initTree(target);

        if (pushState) {
            window.history.replaceState({}, '', url);
        }
    } catch (error) {
        console.error('Async search failed', error);
    } finally {
        form.classList.remove('pointer-events-none', 'opacity-70');
        target.classList.remove('opacity-60');
    }
}

document.addEventListener('submit', (event) => {
    const form = event.target.closest('form[data-async-search]');

    if (!form) {
        return;
    }

    event.preventDefault();
    performAsyncSearch(form);
});

document.addEventListener('input', (event) => {
    const field = event.target;
    const form = field.closest('form[data-async-search]');

    if (!form || field.tagName !== 'INPUT') {
        return;
    }

    clearTimeout(debounceTimers.get(form));

    const timer = setTimeout(() => {
        performAsyncSearch(form);
    }, 300);

    debounceTimers.set(form, timer);
});

document.addEventListener('change', (event) => {
    const form = event.target.closest('form[data-async-search]');

    if (!form) {
        return;
    }

    performAsyncSearch(form);
});

document.addEventListener('click', (event) => {
    const link = event.target.closest('[id$="-results"] .pagination a, [id$="-results"] nav a');

    if (!link) {
        return;
    }

    const container = link.closest('[id$="-results"]');
    const form = document.querySelector(`form[data-target="#${container.id}"]`);

    if (!form) {
        return;
    }

    event.preventDefault();
    performAsyncSearch(form, link.href);
});

document.addEventListener('keydown', (event) => {
    if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
        const searchInput = document.querySelector('[data-command-search]');

        if (!searchInput) {
            return;
        }

        event.preventDefault();
        searchInput.focus();
        searchInput.select?.();
    }
});

function openModalShell() {
    if (!modalRoot) {
        return;
    }

    modalRoot.classList.remove('hidden');
    document.body.classList.add('overflow-hidden');
}

function closeModalShell() {
    if (!modalRoot || !modalContent) {
        return;
    }

    modalRoot.classList.add('hidden');
    modalContent.innerHTML = '';
    document.body.classList.remove('overflow-hidden');
}

async function openCrudModal(url) {
    if (!modalContent) {
        return;
    }

    openModalShell();
    modalContent.innerHTML = `
        <div class="p-6 text-sm text-muted-foreground">
            Loading...
        </div>
    `;

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html',
            },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            throw new Error(`Request failed with status ${response.status}`);
        }

        modalContent.innerHTML = await response.text();
        modalContent.querySelector('input, select, textarea, button')?.focus();
    } catch (error) {
        console.error('Modal load failed', error);
        modalContent.innerHTML = `
            <div class="p-6">
                <div class="modal-error-summary">
                    We could not load this form right now. Please try again.
                </div>
            </div>
        `;
    }
}

function clearModalErrors(form) {
    form.querySelector('[data-modal-error-summary]')?.classList.add('hidden');
    form.querySelector('[data-modal-error-summary]')?.replaceChildren();

    form.querySelectorAll('.modal-field-error').forEach((field) => {
        field.classList.remove('modal-field-error');
    });
}

function markFieldError(form, key) {
    const normalizedKey = key.replace(/\.\d+/g, '[]');
    const selectors = [
        `[name="${normalizedKey}"]`,
        `[name="${normalizedKey.replace(/\[]$/, '')}[]"]`,
        `[name="${key}"]`,
    ];

    for (const selector of selectors) {
        const fields = form.querySelectorAll(selector);

        if (fields.length) {
            fields.forEach((field) => field.classList.add('modal-field-error'));
            return;
        }
    }
}

function renderModalErrors(form, errors) {
    const summary = form.querySelector('[data-modal-error-summary]');
    const messages = Object.values(errors).flat();

    if (summary && messages.length) {
        summary.classList.remove('hidden');
        summary.innerHTML = messages.map((message) => `<div>${message}</div>`).join('');
    }

    Object.keys(errors).forEach((key) => markFieldError(form, key));
}

async function submitCrudModalForm(form) {
    clearModalErrors(form);

    const submitButton = form.querySelector('[type="submit"]');
    submitButton?.setAttribute('disabled', 'disabled');

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        });

        if (response.ok) {
            closeModalShell();
            window.location.reload();
            return;
        }

        if (response.status === 422) {
            const payload = await response.json();
            renderModalErrors(form, payload.errors || {});
            return;
        }

        throw new Error(`Request failed with status ${response.status}`);
    } catch (error) {
        console.error('Modal submit failed', error);
        renderModalErrors(form, {
            form: ['We could not save your changes right now. Please try again.'],
        });
    } finally {
        submitButton?.removeAttribute('disabled');
    }
}

async function performAsyncAction(form) {
    const submitButton = form.querySelector('[type="submit"]');
    submitButton?.setAttribute('disabled', 'disabled');
    form.classList.add('pointer-events-none', 'opacity-70');

    try {
        const response = await fetch(form.action, {
            method: (form.querySelector('input[name="_method"]')?.value || form.method || 'POST').toUpperCase(),
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            },
            credentials: 'same-origin',
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.message || `Request failed with status ${response.status}`);
        }

        window.dispatchEvent(new CustomEvent('toast-notify', {
            detail: {
                type: 'success',
                title: 'Success',
                message: data.message
            }
        }));

        // Refresh the results container if we're in a table
        const resultsContainer = form.closest('[id$="-results"]');
        if (resultsContainer) {
            const searchForm = document.querySelector(`form[data-target="#${resultsContainer.id}"]`);
            if (searchForm) {
                performAsyncSearch(searchForm, null, false);
            }
        }
    } catch (error) {
        console.error('Async action failed', error);
        window.dispatchEvent(new CustomEvent('toast-notify', {
            detail: {
                type: 'error',
                title: 'Something went wrong',
                message: error.message
            }
        }));
    } finally {
        submitButton?.removeAttribute('disabled');
        form.classList.remove('pointer-events-none', 'opacity-70');
    }
}

document.addEventListener('click', (event) => {
    const trigger = event.target.closest('[data-modal-open]');

    if (trigger) {
        event.preventDefault();
        openCrudModal(trigger.href);
        return;
    }

    if (event.target.closest('[data-modal-close]') || (modalBackdrop && event.target === modalBackdrop)) {
        closeModalShell();
    }
});

document.addEventListener('submit', (event) => {
    const modalForm = event.target.closest('form[data-modal-form]');
    if (modalForm) {
        event.preventDefault();
        submitCrudModalForm(modalForm);
        return;
    }

    const asyncForm = event.target.closest('form[data-async-form]');
    if (asyncForm) {
        event.preventDefault();
        performAsyncAction(asyncForm);
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && modalRoot && !modalRoot.classList.contains('hidden')) {
        closeModalShell();
    }
});
