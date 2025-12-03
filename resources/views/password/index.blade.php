<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generador de Contraseñas</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">
</head>
<body class="bg-light">

<div class="container py-5">
    <h1 class="text-center mb-1">Genere una contraseña aleatoria y segura al instante.</h1>
    <p class="text-center mb-4 fs-5">Utiliza este generador de contraseñas online para crear al instante una contraseña segura y aleatoria.</p>

    <div class="row justify-content-center">
        <div class="col-md-6">

            {{-- Mensajes de éxito --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Errores de validación --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title">Tu nueva contraseña</h5>
                        <div class="input-group">
                            <input type="text" id="generatedPassword" class="form-control" value="{{ $password }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copyPassword()">
                                Copiar
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">Guárdala en un lugar seguro.</small>
                    </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.generate') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="length" class="form-label">Longitud de la contraseña</label>
                            <input type="number" name="length" id="length" class="form-control"
                                   value="{{ old('length', 12) }}" min="4" max="64">
                        </div>

                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="include_lower" id="include_lower"
                                   {{ old('include_lower', 'on') ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_lower">
                                Incluir letras minúsculas (a-z)
                            </label>
                        </div>

                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="include_upper" id="include_upper"
                                   {{ old('include_upper', 'on') ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_upper">
                                Incluir letras mayúsculas (A-Z)
                            </label>
                        </div>

                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="include_numbers" id="include_numbers"
                                   {{ old('include_numbers', 'on') ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_numbers">
                                Incluir números (0-9)
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="include_symbols" id="include_symbols"
                                   {{ old('include_symbols') ? 'checked' : '' }}>
                            <label class="form-check-label" for="include_symbols">
                                Incluir símbolos (!@#$%^&*)
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Generar contraseña
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/password.js') }}"></script>

</body>
</html>
