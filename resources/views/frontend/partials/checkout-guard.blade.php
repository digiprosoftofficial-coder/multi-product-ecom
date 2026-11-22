@php $captchaQuestion = \App\Support\CheckoutGuard::question(); @endphp
<div class="checkout-honeypot" aria-hidden="true">
    <label for="company_website">Company website</label>
    <input type="text" name="company_website" id="company_website" tabindex="-1" autocomplete="off" value="">
</div>
<div class="mb-3">
    <label for="captcha_answer" class="form-label">{{ __('storefront.captcha_label', ['question' => $captchaQuestion]) }} <span class="text-danger">*</span></label>
    <input type="text" inputmode="numeric" name="captcha_answer" id="captcha_answer" class="form-control" autocomplete="off" required>
</div>
<style>
    .checkout-honeypot {
        position: absolute;
        left: -10000px;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }
</style>
