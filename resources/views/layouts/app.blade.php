
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'AgendeJá')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f3f4f6;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #ffffff;
            position: fixed;
            top: 0;
            left: 0;
            padding: 25px 18px;
            box-shadow: 3px 0 15px rgba(0,0,0,.04);
            z-index: 1000;
        }

        .brand {
            font-size: 23px;
            font-weight: bold;
            color: #dc2626;
            margin-bottom: 35px;
            padding: 10px;
        }

        .sidebar a {
            display: block;
            color: #4b5563;
            text-decoration: none;
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: .2s;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #dc2626;
            color: white;
        }

        .sidebar a i {
            margin-right: 8px;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 18px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 3px 15px rgba(0,0,0,.04);
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 3px 15px rgba(0,0,0,.04);
            height: 100%;
        }

        .stat-icon {
            font-size: 27px;
            color: #dc2626;
        }

        .btn-primary {
            background: #dc2626;
            border-color: #dc2626;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: #b91c1c;
            border-color: #b91c1c;
        }

        .table thead th {
            background: #f9fafb;
            color: #374151;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }

            .brand {
                margin-bottom: 15px;
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .topbar {
                flex-wrap: wrap;
                gap: 15px;
            }
        }
    </style>
</head>

<body>

    <aside class="sidebar">

        <div class="brand">
            <i class="bi bi-calendar-check"></i> AgendeJá
        </div>

        <a href="{{ route('dashboard') }}"
           class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-grid"></i> Dashboard
        </a>

        <a href="{{ route('clientes.index') }}"
           class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Clientes
        </a>

        <a href="{{ route('profissionais.index') }}"
           class="{{ request()->routeIs('profissionais.*') ? 'active' : '' }}">
            <i class="bi bi-person-badge"></i> Profissionais
        </a>

        <a href="{{ route('servicos.index') }}"
           class="{{ request()->routeIs('servicos.*') ? 'active' : '' }}">
            <i class="bi bi-briefcase"></i> Serviços
        </a>

        <a href="{{ route('agendamentos.index') }}"
           class="{{ request()->routeIs('agendamentos.*') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i> Agendamentos
        </a>

        <a href="{{ route('recebimentos.index') }}"
           class="{{ request()->routeIs('recebimentos.*') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i> Financeiro
        </a>

    </aside>

    <main class="main-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
