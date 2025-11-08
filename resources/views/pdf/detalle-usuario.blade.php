<!DOCTYPE html>
<html>

<head>
    <title>Detalles del Usuario - {{ $persona->cedula }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* CSS INCRUSTADO PARA EL PDF */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            padding: 15px;
            background-color: #f3f4f6;
            /* gray-100 */
            border-bottom: 2px solid #3b82f6;
            /* blue-500 */
        }

        .content {
            padding: 20px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        .data-table th {
            background-color: #e5e7eb;
            /* gray-200 */
            color: #1f2937;
            /* gray-800 */
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
            color: #6b7280;
            /* gray-500 */
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Reporte de Usuario</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="content">
        <h2>Información Personal</h2>

        <table class="data-table">
            <tr>
                <th>Nombre Completo</th>
                <td>{{ $persona->nombre }} {{ $persona->apellido }}</td>
            </tr>
            <tr>
                <th>Cédula</th>
                <td>{{ $persona->cedula }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $persona->email ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td>{{ $persona->telefono ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Genero</th>
                <td>{{ $persona->genero ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Direccion</th>
                <td>{{ $persona->direccion ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Fecha Nacimiento</th>
                <td>{{ $persona->nacimiento->format('d/m/Y') ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Creacion cuenta</th>
                <td>{{ $persona->created_at->format('d/m/Y H:i') ?? 'No registrado' }}</td>
            </tr>
        </table>

    </div>
</body>

</html>