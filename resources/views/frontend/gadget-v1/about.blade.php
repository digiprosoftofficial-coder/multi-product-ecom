<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>About – {{ config('app.name') }}</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
  <link rel="stylesheet" href="{{ asset('gadget-v1/style.css') }}">
</head>
<body class="gadget-v1">
  @include('frontend.gadget-v1.partials.header')

  <main class="container py-4">
    <h1 class="h4 mb-4">About us</h1>
    <div class="row">
      <div class="col-lg-8">
        <p class="text-muted">We are a modern gadget store focused on accessories and tech for everyday life. Our selection is curated for quality and usability.</p>
        <p class="text-muted">From cables and chargers to smart accessories, we aim to offer products that fit your workflow and lifestyle.</p>
        <h2 class="h6 mt-4 mb-2">Our values</h2>
        <ul class="text-muted">
          <li>Quality over quantity</li>
          <li>Clear product information</li>
          <li>Fast, reliable shipping</li>
        </ul>
      </div>
    </div>
  </main>

  @include('frontend.gadget-v1.partials.footer')

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
