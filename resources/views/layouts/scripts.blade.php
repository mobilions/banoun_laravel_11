<script src="{{asset('/assets')}}/libs/jquery/jquery.min.js"></script>
<script src="{{asset('/assets')}}/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{asset('/assets')}}/libs/metismenu/metisMenu.min.js"></script>
<script src="{{asset('/assets')}}/libs/simplebar/simplebar.min.js"></script>
<script src="{{asset('/assets')}}/libs/node-waves/waves.min.js"></script>
<script src="{{asset('/assets')}}/js/app.js"></script>
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