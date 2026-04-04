import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

const debounceTimers = new WeakMap();

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
