<nav class="d-lg-none py-2 border-secondary border-bottom">
  <div class="container">
    <ul class="nav flex-column">
      <li class="nav-item"><a class="nav-link link-accent" href="{{ route('home') }}">Home</a></li>
      <li class="nav-item"><a class="nav-link link-accent" href="{{ route('products.index') }}">Shop</a></li>
      <li class="nav-item"><a class="nav-link link-accent" href="{{ route('cart.index') }}">Cart</a></li>
      <li class="nav-item"><a class="nav-link link-accent" href="{{ route('about') }}">About</a></li>
      <li class="nav-item"><a class="nav-link link-accent" href="{{ route('contact') }}">Contact</a></li>
      @auth
        <li class="nav-item"><a class="nav-link link-accent" href="{{ route('dashboard') }}">Account</a></li>
        <li class="nav-item">
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link link-accent border-0 bg-transparent">Logout</button>
          </form>
        </li>
      @else
        <li class="nav-item"><a class="nav-link link-accent" href="{{ route('login') }}">Login</a></li>
      @endauth
    </ul>
  </div>
</nav>
