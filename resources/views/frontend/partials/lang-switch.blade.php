@php $langIsBn = app()->getLocale() === 'bn'; @endphp
@once
<style>
.lang-toggle {
  position: relative;
  display: inline-flex;
  align-items: center;
  width: 68px;
  height: 30px;
  padding: 3px;
  border-radius: 999px;
  background: color-mix(in srgb, var(--header-text, #fff) 16%, transparent);
  border: 1px solid color-mix(in srgb, var(--header-text, #fff) 38%, transparent);
  text-decoration: none;
  flex-shrink: 0;
  color: inherit;
}
.lang-toggle__thumb {
  position: absolute;
  top: 3px;
  left: 3px;
  width: calc(50% - 3px);
  height: calc(100% - 6px);
  border-radius: 999px;
  background: #fff;
  box-shadow: 0 1px 3px rgba(0, 0, 0, .18);
  transition: transform .2s ease;
}
.lang-toggle.is-bn .lang-toggle__thumb {
  transform: translateX(100%);
}
.lang-toggle__opt {
  position: relative;
  z-index: 1;
  flex: 1 1 50%;
  text-align: center;
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .04em;
  line-height: 1;
  color: inherit;
  opacity: .72;
}
.lang-toggle.is-en .lang-toggle__opt[lang="en"],
.lang-toggle.is-bn .lang-toggle__opt[lang="bn"] {
  color: var(--header-bg, #1f3b2c);
  opacity: 1;
}
</style>
@endonce
<a href="{{ route('locale.switch', $langIsBn ? 'en' : 'bn') }}"
   class="lang-toggle {{ $langIsBn ? 'is-bn' : 'is-en' }}"
   aria-label="{{ __('storefront.language') }}: {{ $langIsBn ? 'বাংলা' : 'English' }}">
  <span class="lang-toggle__thumb" aria-hidden="true"></span>
  <span class="lang-toggle__opt" lang="en">EN</span>
  <span class="lang-toggle__opt" lang="bn">BN</span>
</a>
