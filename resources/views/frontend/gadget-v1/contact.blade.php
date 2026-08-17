<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Contact – {{ config('app.name') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('gadget-v1/style.css') }}">
</head>
<body class="gadget-v1">
  @include('frontend.gadget-v1.partials.header')

  <main class="container py-4">
    <h1 class="h4 mb-4">Contact</h1>
    <div class="row">
      <div class="col-md-6 mb-4">
        <div class="card gadget-card border-secondary">
          <div class="card-body">
            <h2 class="h6 mb-3">Get in touch</h2>
            <p class="text-muted small mb-2"><i class="fas fa-envelope text-accent me-2"></i> support@gadgetstore.example</p>
            <p class="text-muted small mb-2"><i class="fas fa-phone text-accent me-2"></i> +1 (555) 000-0000</p>
            <p class="text-muted small mb-0"><i class="fas fa-map-marker-alt text-accent me-2"></i> 123 Tech Street, City</p>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card gadget-card border-secondary">
          <div class="card-body">
            <h2 class="h6 mb-3">Send a message</h2>
            <p class="text-muted small">Use the form below or email us directly. We usually reply within 24 hours.</p>
          </div>
        </div>
      </div>
    </div>
  </main>

  @include('frontend.gadget-v1.partials.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
