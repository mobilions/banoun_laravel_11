<script src="{{asset('/assets')}}/libs/jquery/jquery.min.js"></script>
<script src="{{asset('/assets')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('/assets')}}/libs/metismenu/metisMenu.min.js"></script>
<script src="{{asset('/assets')}}/libs/simplebar/simplebar.min.js"></script>
<script src="{{asset('/assets')}}/libs/node-waves/waves.min.js"></script>
<script src="{{asset('/assets')}}/js/app.js"></script>
{{-- Vite handles the application's JS (app.js) via @vite in the head. --}}
<script>
(function () {
    var meta = document.querySelector('meta[name="csrf-token"]');
    if (!meta) {
        return;
    }
    window.csrfToken = meta.getAttribute('content');
    if (window.jQuery) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': window.csrfToken
            }
        });
    }
})();
</script>
<script>
(function () {
    var lastAppliedTerm = '';
    function debounce(fn, delay) {
        var timer = null;
        return function () {
            var context = this;
            var args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                fn.apply(context, args);
            }, delay);
        };
    }
    function normalizeTerm(value) {
        return (value || '').trim().toLowerCase();
    }
    function getSearchableTables() {
        var explicit = document.querySelectorAll('[data-global-searchable="true"]');
        if (explicit.length) {
            return explicit;
        }
        return document.querySelectorAll('.table:not(.dataTable)');
    }
    function filterPlainTables(term) {
        var tables = getSearchableTables();
        if (!tables.length) {
            return;
        }
        Array.prototype.forEach.call(tables, function (table) {
            var bodies = table.tBodies || [];
            Array.prototype.forEach.call(bodies, function (tbody) {
                Array.prototype.forEach.call(tbody.rows, function (row) {
                    if (!row.dataset.globalSearchLocked) {
                        var shouldShow = !term || row.textContent.toLowerCase().indexOf(term) !== -1;
                        row.style.display = shouldShow ? '' : 'none';
                    }
                });
            });
        });
    }
    function filterDataTables(term) {
        if (!window.jQuery || !$.fn.dataTable) {
            return false;
        }
        var tables = $.fn.dataTable.tables({ api: true });
        if (!tables.length) {
            return false;
        }
        tables.search(term).draw();
        return true;
    }
    function applySearch(rawTerm) {
        var term = normalizeTerm(rawTerm);
        if (term === lastAppliedTerm) {
            return;
        }
        lastAppliedTerm = term;
        filterDataTables(term);
        filterPlainTables(term);
    }
    function bindSearchForm(form) {
        if (!form) {
            return;
        }
        var input = form.querySelector('.global-search-input');
        if (!input) {
            return;
        }
        var trigger = debounce(function () {
            applySearch(input.value);
        }, 180);
        input.addEventListener('input', trigger);
        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                input.value = '';
                applySearch('');
            }
        });
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            applySearch(input.value);
        });
    }
    document.addEventListener('DOMContentLoaded', function () {
        var forms = document.querySelectorAll('.global-search-form');
        if (!forms.length) {
            return;
        }
        Array.prototype.forEach.call(forms, bindSearchForm);
    });
})();
</script>
<script>
// Ensure all handlers are properly initialized
document.addEventListener('DOMContentLoaded', function() {
    // Sidebar toggle handler (backup in case app.js doesn't load)
    var verticalMenuBtn = document.getElementById('vertical-menu-btn');
    if (verticalMenuBtn) {
        verticalMenuBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('sidebar-enable');
            if (window.innerWidth >= 992) {
                document.body.classList.toggle('vertical-collpsed');
            } else {
                document.body.classList.remove('vertical-collpsed');
            }
        });
    }

    // Fullscreen handler (backup)
    var fullscreenBtns = document.querySelectorAll('[data-bs-toggle="fullscreen"]');
    fullscreenBtns.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('fullscreen-enable');
            
            if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement) {
                if (document.documentElement.requestFullscreen) {
                    document.documentElement.requestFullscreen();
                } else if (document.documentElement.mozRequestFullScreen) {
                    document.documentElement.mozRequestFullScreen();
                } else if (document.documentElement.webkitRequestFullscreen) {
                    document.documentElement.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
                }
            } else {
                if (document.cancelFullScreen) {
                    document.cancelFullScreen();
                } else if (document.mozCancelFullScreen) {
                    document.mozCancelFullScreen();
                } else if (document.webkitCancelFullScreen) {
                    document.webkitCancelFullScreen();
                }
            }
        });
    });

    // Language switching handler
    var languageLinks = document.querySelectorAll('.language[data-lang]');
    var currentLangSpan = document.getElementById('current-lang');
    
    languageLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            var lang = this.getAttribute('data-lang');
            
            // Update current language display
            if (currentLangSpan) {
                currentLangSpan.textContent = lang === 'ar' ? 'Ar' : 'En';
            }
            
            // Update HTML lang attribute
            document.documentElement.setAttribute('lang', lang);
            
            // Update direction for Arabic
            if (lang === 'ar') {
                document.documentElement.setAttribute('dir', 'rtl');
            } else {
                document.documentElement.removeAttribute('dir');
            }
            
            // Store in localStorage
            localStorage.setItem('language', lang);
            
            // Close dropdown
            var dropdown = bootstrap.Dropdown.getInstance(document.querySelector('[data-bs-toggle="dropdown"]'));
            if (dropdown) {
                dropdown.hide();
            }
        });
    });

    // Load saved language preference
    var savedLang = localStorage.getItem('language');
    if (savedLang && currentLangSpan) {
        currentLangSpan.textContent = savedLang === 'ar' ? 'Ar' : 'En';
        document.documentElement.setAttribute('lang', savedLang);
        if (savedLang === 'ar') {
            document.documentElement.setAttribute('dir', 'rtl');
        }
    }

    // Fullscreen change event handlers
    function handleFullscreenChange() {
        if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement) {
            document.body.classList.remove('fullscreen-enable');
        }
    }
    
    document.addEventListener('fullscreenchange', handleFullscreenChange);
    document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
    document.addEventListener('mozfullscreenchange', handleFullscreenChange);

    // Initialize Bootstrap dropdowns
    if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
        var dropdownElementList = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
        dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
    }
});
</script>
@yield('ScriptContent')
@if ($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    var errorBag = @json($errors->toArray());
    var cssEscape = window.CSS && CSS.escape ? function (value) { return CSS.escape(value); } : function (value) { return value.replace(/([ #;?%&,.+*~:'"!^$[\]()=>|\/@])/g, '\\$1'); };
    Object.keys(errorBag).forEach(function (field) {
        var selector = '[name="' + cssEscape(field) + '"]';
        var nodes = document.querySelectorAll(selector);
        if (!nodes.length) {
            return;
        }
        nodes.forEach(function (node) {
            var type = (node.type || '').toLowerCase();
            if (type !== 'hidden' && type !== 'file') {
                node.classList.add('is-invalid');
            }
            var message = errorBag[field][0] || '';
            if (!message) {
                return;
            }
            var sibling = node.nextElementSibling;
            if (!sibling || !sibling.classList || !sibling.classList.contains('invalid-feedback')) {
                var feedback = document.createElement('div');
                feedback.className = 'invalid-feedback d-block';
                feedback.textContent = message;
                node.insertAdjacentElement('afterend', feedback);
            } else {
                sibling.textContent = message;
            }
        });
    });
});
</script>
@endif
@if ($errors->any() && count(session()->getOldInput() ?? []))
<script>
document.addEventListener('DOMContentLoaded', function () {
    var oldInput = @json(session()->getOldInput());
    var cssEscape = window.CSS && CSS.escape ? function (value) { return CSS.escape(value); } : function (value) { return value.replace(/([ #;?%&,.+*~:'"!^$[\]()=>|\/@])/g, '\\$1'); };
    Object.keys(oldInput).forEach(function (field) {
        var value = oldInput[field];
        var selector = '[name="' + cssEscape(field) + '"]';
        var nodes = document.querySelectorAll(selector);
        if (!nodes.length) {
            return;
        }
        nodes.forEach(function (node) {
            var tag = (node.tagName || '').toLowerCase();
            var type = (node.type || '').toLowerCase();
            if (type === 'checkbox') {
                var values = Array.isArray(value) ? value : [value];
                node.checked = values.map(String).includes(node.value);
            } else if (type === 'radio') {
                node.checked = String(value) === String(node.value);
            } else if (tag === 'select' && node.multiple && Array.isArray(value)) {
                Array.from(node.options).forEach(function (option) {
                    option.selected = value.map(String).includes(option.value);
                });
            } else if (tag === 'select') {
                node.value = value;
            } else if (type !== 'file') {
                node.value = value;
            }
        });
    });
});
</script>
@endif