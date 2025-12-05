<script src="{{asset('/assets')}}/libs/jquery/jquery.min.js"></script>
<script src="{{asset('/assets')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('/assets')}}/libs/metismenu/metisMenu.min.js"></script>
<script src="{{asset('/assets')}}/libs/simplebar/simplebar.min.js"></script>
<script src="{{asset('/assets')}}/libs/node-waves/waves.min.js"></script>
<script src="{{asset('/assets')}}/js/app.js"></script>
<script>
// Fix handlers - ensure they work even if app.js has errors
(function() {
    function initHandlers() {
        // Wait for jQuery
        if (typeof jQuery === 'undefined' || typeof window.$ === 'undefined') {
            setTimeout(initHandlers, 100);
            return;
        }
        
        var $ = window.jQuery || window.$;
        
        // Wait for DOM and all scripts to be ready
        $(window).on('load', function() {
            console.log('Initializing custom handlers...');
            
            // Sidebar toggle - use direct click handler
            var $menuBtn = $('#vertical-menu-btn');
            if ($menuBtn.length) {
                $menuBtn.off('click.custom').on('click.custom', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    console.log('Menu button clicked');
                    $('body').toggleClass('sidebar-enable');
                    if ($(window).width() >= 992) {
                        $('body').toggleClass('vertical-collpsed');
                    } else {
                        $('body').removeClass('vertical-collpsed');
                    }
                    return false;
                });
                console.log('Menu button handler attached');
            } else {
                console.log('Menu button not found');
            }

            // Language switching handler
            var $langLinks = $('.language[data-lang]');
            if ($langLinks.length) {
                $langLinks.off('click.custom').on('click.custom', function(e) {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    var lang = $(this).attr('data-lang');
                    console.log('Language changed to:', lang);
                    var $currentLangSpan = $('#current-lang');
                    
                    // Update current language display
                    if ($currentLangSpan.length) {
                        $currentLangSpan.text(lang === 'ar' ? 'Ar' : 'En');
                    }
                    
                    // Update HTML lang attribute
                    $('html').attr('lang', lang);
                    
                    // Update direction for Arabic
                    if (lang === 'ar') {
                        $('html').attr('dir', 'rtl');
                    } else {
                        $('html').removeAttr('dir');
                    }
                    
                    // Store in localStorage
                    localStorage.setItem('language', lang);
                    
                    // Close dropdown
                    var $dropdown = $(this).closest('.dropdown');
                    var $dropdownBtn = $dropdown.find('button[data-bs-toggle="dropdown"]');
                    if ($dropdownBtn.length && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                        var dropdownInstance = bootstrap.Dropdown.getInstance($dropdownBtn[0]);
                        if (dropdownInstance) {
                            dropdownInstance.hide();
                        }
                    }
                    return false;
                });
                console.log('Language handler attached to', $langLinks.length, 'links');
            } else {
                console.log('Language links not found');
            }

            // Load saved language preference
            var savedLang = localStorage.getItem('language');
            var $currentLangSpan = $('#current-lang');
            if (savedLang && $currentLangSpan.length) {
                $currentLangSpan.text(savedLang === 'ar' ? 'Ar' : 'En');
                $('html').attr('lang', savedLang);
                if (savedLang === 'ar') {
                    $('html').attr('dir', 'rtl');
                }
            }

            // Initialize Bootstrap dropdowns
            if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
                $('[data-bs-toggle="dropdown"]').each(function() {
                    var existingInstance = bootstrap.Dropdown.getInstance(this);
                    if (!existingInstance) {
                        new bootstrap.Dropdown(this);
                    }
                });
                console.log('Bootstrap dropdowns initialized');
            } else {
                console.log('Bootstrap not available');
            }
        });
        
        // Also try on document ready as backup
        $(document).ready(function() {
            // Sidebar toggle backup
            $('#vertical-menu-btn').off('click.backup').on('click.backup', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                $('body').toggleClass('sidebar-enable');
                if ($(window).width() >= 992) {
                    $('body').toggleClass('vertical-collpsed');
                }
                return false;
            });
            
            // Language switching backup
            $('.language[data-lang]').off('click.backup').on('click.backup', function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();
                var lang = $(this).attr('data-lang');
                $('#current-lang').text(lang === 'ar' ? 'Ar' : 'En');
                $('html').attr('lang', lang);
                if (lang === 'ar') {
                    $('html').attr('dir', 'rtl');
                } else {
                    $('html').removeAttr('dir');
                }
                localStorage.setItem('language', lang);
                return false;
            });
        });
    }
    
    // Start initialization
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initHandlers);
    } else {
        initHandlers();
    }
})();
</script>
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