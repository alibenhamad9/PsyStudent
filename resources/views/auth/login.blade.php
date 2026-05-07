@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card">
            <div class="card-body p-5">
                <h3 class="card-title text-center mb-4">
                    <i class="fas fa-sign-in-alt"></i> Connexion
                </h3>

                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                               id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                               id="password" name="password" required>
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100 btn-lg">
                        <i class="fas fa-sign-in-alt"></i> Se connecter
                    </button>
                </form>

                <hr class="my-4">

                <p class="text-center">
                    Pas encore inscrit? <a href="{{ route('register') }}">Créer un compte</a>
                </p>

                <div class="alert alert-info mt-3" role="alert">
                    <small>
                        <strong>Compte de test:</strong><br>
                        Email: admin@example.com<br>
                        Mot de passe: password123
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection