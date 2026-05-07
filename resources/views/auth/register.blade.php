@extends('layouts.app')

@section('title', 'Créer un compte')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h2 class="mb-4">Créer un compte</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">prenom</label>
                    <input type="text" name="prenom" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-control" required minlength="6">
                    <small class="text-muted">Au minimum 8 caractères</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control" required minlength="6">
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Créer le compte
                </button>

            </form>

        </div>
    </div>
</div>
@endsection