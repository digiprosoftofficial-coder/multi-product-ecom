<footer class="bg-dark text-light mt-5 py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5>{{ config('app.name', 'Multi Ecommerce') }}</h5>
                <p class="text-muted">
                    @php
                        try {
                            echo \App\Models\Setting::get('footer_text', 'Your trusted online shopping destination.');
                        } catch (\Exception $e) {
                            echo 'Your trusted online shopping destination.';
                        }
                    @endphp
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>

