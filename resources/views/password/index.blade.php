<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <title>Generador de Contraseñas Seguras</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('css/sweetalert2.min.css') }}">

    <style>

        :root {
            --primary: #2563eb;
            --danger: #e63946;
            --warning: #ffb703;
            --safe: #38b000;
        }

        body {
            background: var(--bg);
            font-family: system-ui, sans-serif;
            transition: .3s;
        }

        [data-theme="light"] {
            --bg: #f9fafb;
            --text: #111827;
            --sub: #4b5563;
            --card: #ffffff;
        }

        [data-theme="dark"] {
            --bg: #18181b;
            --text: #fafafa;
            --sub: #a1a1aa;
            --card: #262626;
        }

        .hero-title {
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            font-weight: 700;
            text-align: center;
            color: var(--text);
        }

        .hero-text {
            font-size: clamp(1rem, 2vw, 1.15rem);
            text-align: center;
            color: var(--sub);
            max-width: 600px;
            margin: 0 auto 2.5rem auto;
        }

        .card {
            border: none;
            border-radius: 1rem;
            background: var(--card);
            transition: .3s;
        }

        .card-body {
            padding: 1.8rem;
        }

        #passwordStrength {
            height: 8px;
            border-radius: 4px;
            background: #d1d5db;
            margin-top: .6rem;
        }

        #passwordStrength.fill {
            transition: .3s;
        }

        .btn-generate {
            background: var(--primary);
            border: none;
        }
        .btn-generate:hover {
            opacity: .9;
        }

        .theme-toggle {
            cursor: pointer;
            font-size: 1.8rem;
            color: var(--text);
            position: absolute;
            top: 1rem;
            right: 1rem;
        }

    </style>
</head>
<body>

<div class="theme-toggle" onclick="toggleTheme()">🌙</div>

<div class="container py-5">

    <h1 class="hero-title mb-3">
        Crea contraseñas robustas en segundos
    </h1>

    <p class="hero-text">
        Personaliza la longitud y los caracteres para obtener contraseñas seguras a tu medida.
    </p>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8 col-sm-10">

            {{-- Tarjeta contraseña --}}
            <div id="resultCard" class="card shadow-sm mb-4 d-none">
                <div class="card-body">

                    <label class="form-label fw-bold">Contraseña generada</label>

                    <div class="input-group mb-2">
                        <input type="text" id="generatedPassword" class="form-control" readonly>
                        <button class="btn btn-outline-secondary" onclick="regenerate()">🔄</button>
                    </div>

                    <div id="passwordStrength" class="fill"></div>

                    <small class="text-muted d-block mt-2">Copiada automáticamente al portapapeles.</small>
                </div>
            </div>

            {{-- FORMULARIO --}}
            <div class="card shadow-sm">
                <div class="card-body">

                    <form id="passwordForm">

                        {{-- Slider --}}
                        <label class="form-label fw-bold">Longitud: <span id="rangeValue">12</span></label>
                        <input type="range" name="length" id="length" min="4" max="64" value="12"
                               class="form-range mb-3" oninput="updateRange(this.value)">

                        {{-- Opciones --}}
                        @foreach([
                            'include_lower' => 'Letras minúsculas (a-z)',
                            'include_upper' => 'Letras mayúsculas (A-Z)',
                            'include_numbers' => 'Números (0-9)',
                            'include_symbols' => 'Símbolos (!@#$%^&*)',
                        ] as $name => $label)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="{{ $name }}"
                                       id="{{ $name }}" checked>
                                <label class="form-check-label" for="{{ $name }}">{{ $label }}</label>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-generate text-white w-100 mt-3 py-2 fw-semibold">
                            Generar contraseña
                        </button>
                    </form>

                </div>
            </div>

            <p class="text-center mt-4 text-muted small">
                Herramienta gratuita y segura. No almacenamos tus datos.
            </p>

        </div>
    </div>
</div>

<script src="{{ asset('js/sweetalert2.min.js') }}"></script>
<script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>

<script>

    // === DARK MODE AUTO ===

    const theme = localStorage.getItem("theme") || "light";
    document.documentElement.setAttribute("data-theme", theme);

    function toggleTheme() {
        const current = document.documentElement.getAttribute("data-theme");
        const next = current === "light" ? "dark" : "light";
        document.documentElement.setAttribute("data-theme", next);
        localStorage.setItem("theme", next);
    }


    // === RANGE DISPLAY ===

    function updateRange(value) {
        document.getElementById('rangeValue').innerHTML = value;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // === AJAX GENERATION ===

    $("#passwordForm").submit(function (e) {
        e.preventDefault();
        generate();
    });

    function regenerate() {
        generate();
    }

    function generate() {
        $.ajax({
            url: "{{ route('password.generate') }}",
            method: "POST",
            data: $("#passwordForm").serialize(),
            success: function (res) {

                let pwd = res.password;

                $("#generatedPassword").val(pwd);
                copy(pwd);

                updateStrength(pwd);

                $("#resultCard").removeClass("d-none").hide().fadeIn(200);
            }
        });
    }


    // === COPY AUTO ===

    function copy(text) {
        navigator.clipboard.writeText(text);

        Swal.fire({
            icon: 'success',
            title: 'Contraseña copiada',
            text: text,
            timer: 1000,
            showConfirmButton: false
        });
    }


    // === STRENGTH BAR ===

    function updateStrength(pwd) {

        const score = calculateStrength(pwd);
        const bar = document.getElementById("passwordStrength");

        bar.style.width = score + "%";

        if (score < 40) bar.style.background = "var(--danger)";
        else if (score < 70) bar.style.background = "var(--warning)";
        else bar.style.background = "var(--safe)";
    }

    function calculateStrength(pwd) {
        let score = 0;

        if (pwd.length >= 8) score += 30;
        if (/[A-Z]/.test(pwd)) score += 20;
        if (/[0-9]/.test(pwd)) score += 20;
        if (/[^A-Za-z0-9]/.test(pwd)) score += 20;
        if (pwd.length >= 14) score += 10;

        return Math.min(score, 100);
    }

</script>

</body>
</html>
