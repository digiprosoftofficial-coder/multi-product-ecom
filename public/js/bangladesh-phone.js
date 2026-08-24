/**
 * Live Bangladesh mobile validation (matches App\Rules\BangladeshPhone).
 * Accepts 01XXXXXXXXX, +8801XXXXXXXXX, 8801XXXXXXXXX (spaces/dashes ok).
 */
(function () {
    'use strict';

    var MESSAGE = 'Enter a valid Bangladesh mobile number (e.g. 01XXXXXXXXX).';
    var REQUIRED_MESSAGE = 'This phone number is required.';
    var SELECTOR = [
        'input[data-bd-phone]',
        'input[name="phone"]',
        'input[name="customer_phone"]',
        'input[name="payment_sender_phone"]',
        'input[name="contact_phone"]',
        'input[name="social_whatsapp"]',
        'input[name="payment_bkash_number"]',
        'input[name="payment_nagad_number"]',
        'input[name="payment_rocket_number"]',
    ].join(',');

    function digitsOnly(value) {
        return String(value || '').replace(/\D+/g, '');
    }

    function isValid(value) {
        return /^(?:88)?01[3-9]\d{8}$/.test(digitsOnly(value));
    }

    /** True while the user may still be typing a valid number. */
    function isTypingPrefix(value) {
        var digits = digitsOnly(value);
        if (!digits) {
            return true;
        }
        if (digits.length > 13) {
            return false;
        }
        if (digits.indexOf('88') === 0) {
            return digits.length < 13 && /^(?:88|880|8801|8801[3-9]\d{0,8})$/.test(digits);
        }
        if (digits.charAt(0) === '0') {
            return digits.length < 11 && /^(?:0|01|01[3-9]\d{0,8})$/.test(digits);
        }
        return false;
    }

    function isFieldVisible(input) {
        if (input.disabled) {
            return false;
        }
        if (input.type === 'hidden') {
            return false;
        }
        var node = input;
        while (node && node !== document.body) {
            if (node.classList && node.classList.contains('d-none')) {
                return false;
            }
            node = node.parentElement;
        }
        return !!(input.offsetWidth || input.offsetHeight || input.getClientRects().length);
    }

    function getFeedback(input) {
        var parent = input.parentElement;
        if (!parent) {
            return null;
        }
        var el = parent.querySelector(':scope > .bd-phone-feedback');
        if (el) {
            return el;
        }
        el = document.createElement('div');
        el.className = 'invalid-feedback bd-phone-feedback';
        input.insertAdjacentElement('afterend', el);
        return el;
    }

    function showError(input, message) {
        input.classList.add('is-invalid');
        input.setAttribute('aria-invalid', 'true');
        var parent = input.parentElement;
        if (parent) {
            parent.querySelectorAll('.invalid-feedback:not(.bd-phone-feedback)').forEach(function (el) {
                el.style.display = 'none';
            });
        }
        var el = getFeedback(input);
        if (el) {
            el.textContent = message;
            el.style.display = 'block';
        }
    }

    function clearError(input) {
        input.classList.remove('is-invalid');
        input.removeAttribute('aria-invalid');
        var parent = input.parentElement;
        var el = parent && parent.querySelector(':scope > .bd-phone-feedback');
        if (el) {
            el.textContent = '';
            el.style.display = '';
        }
    }

    function validateInput(input, options) {
        options = options || {};
        var strict = !!options.strict;
        var value = (input.value || '').trim();
        var required = input.required || input.getAttribute('aria-required') === 'true';

        if (!value) {
            if (required && strict && isFieldVisible(input)) {
                showError(input, REQUIRED_MESSAGE);
                return false;
            }
            clearError(input);
            return true;
        }

        if (isValid(value)) {
            clearError(input);
            return true;
        }

        if (!strict && isTypingPrefix(value)) {
            clearError(input);
            return true;
        }

        showError(input, MESSAGE);
        return false;
    }

    function bindInput(input) {
        if (input.dataset.bdPhoneBound === '1') {
            return;
        }
        input.dataset.bdPhoneBound = '1';
        input.setAttribute('data-bd-phone', '');
        input.setAttribute('inputmode', 'tel');
        input.setAttribute('autocomplete', input.getAttribute('autocomplete') || 'tel');

        input.addEventListener('input', function () {
            validateInput(input, { strict: false });
        });

        input.addEventListener('blur', function () {
            validateInput(input, { strict: true });
        });
    }

    function bindForm(form) {
        if (form.dataset.bdPhoneFormBound === '1') {
            return;
        }
        form.dataset.bdPhoneFormBound = '1';

        form.addEventListener('submit', function (e) {
            var inputs = form.querySelectorAll(SELECTOR);
            var firstInvalid = null;
            inputs.forEach(function (input) {
                if (!isFieldVisible(input) && !(input.value || '').trim()) {
                    clearError(input);
                    return;
                }
                if (!validateInput(input, { strict: true })) {
                    if (!firstInvalid) {
                        firstInvalid = input;
                    }
                }
            });
            if (firstInvalid) {
                e.preventDefault();
                e.stopPropagation();
                firstInvalid.focus();
            }
        });
    }

    function init(root) {
        root = root || document;
        root.querySelectorAll(SELECTOR).forEach(function (input) {
            bindInput(input);
            if (input.form) {
                bindForm(input.form);
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            init();
        });
    } else {
        init();
    }

    window.BangladeshPhoneValidation = {
        init: init,
        isValid: isValid,
    };
})();
