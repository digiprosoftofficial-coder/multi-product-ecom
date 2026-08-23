<footer class="bg-dark text-light mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                @if(footer_logo_url())
                    <a href="{{ route('home') }}" class="d-inline-block mb-2">
                        <img src="{{ footer_logo_url() }}" alt="{{ site_name() }}" style="max-height: 40px; width: auto;">
                    </a>
                @endif
                <h5>{{ site_name() }}</h5>
                <p class="text-muted mb-2">{{ setting('footer_text', 'Your trusted online shopping destination.') }}</p>
                @if(setting('contact_phone'))
                    <div class="text-muted small">{{ setting('contact_phone') }}</div>
                @endif
                @if(setting('contact_email'))
                    <div class="text-muted small">{{ setting('contact_email') }}</div>
                @endif
            </div>
            <div class="col-md-6 text-md-end">
                <div class="mb-2">
                    <a href="{{ route('about') }}" class="link-light link-underline-opacity-0 me-3">About</a>
                    <a href="{{ route('contact') }}" class="link-light link-underline-opacity-0 me-3">Contact</a>
                    <a href="{{ route('delivery') }}" class="link-light link-underline-opacity-0 me-3">Delivery</a>
                    <a href="{{ route('returns') }}" class="link-light link-underline-opacity-0 me-3">Returns</a>
                    <a href="{{ route('privacy') }}" class="link-light link-underline-opacity-0 me-3">Privacy</a>
                    <a href="{{ route('terms') }}" class="link-light link-underline-opacity-0">Terms</a>
                </div>
                <p class="text-muted mb-0">&copy; {{ date('Y') }} {{ site_name() }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
