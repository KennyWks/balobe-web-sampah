@extends('auth.layouts.main')
@section('content')
<form method="POST" action="/signin">
    @csrf
    <img class="mb-4" src="/assets/images/logo-balobe.jpg" alt="" width="150" height="90">
    <h1 class="h3 mb-3 fw-normal">Silahakan login</h1>

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="form-floating">
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
            placeholder="name@example.com" value="{{ old('email') }}" autocomplete="off">
        <label for="email">Email</label>
        @error('email')
        <div id="email" class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>
    <div class="form-floating">
        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
            name="password" placeholder="Password" autocomplete="off">
        <label for="password">Password</label>
        @error('password')
        <div id="password" class="invalid-feedback">
            {{ $message }}
        </div>
        @enderror
    </div>

    <div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me"> Ingat saya
        </label>
    </div>
    <button class="w-100 btn btn-lg btn-primary" type="submit">Masuk</button>
    <p class="mt-5 mb-3 text-muted">&copy; 2017–2022</p>
</form>
@endsection