<!DOCTYPE html>
<html>

<head>
    <title>Detalles de la Trabajador - {{ $trabajador->persona->cedula }}</title>
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
        <h1>Reporte de la Trabajador</h1>
        <p>Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="content bg-blue-600">
        <h2>Información Personal</h2>

        <table class="data-table">
            <tr>
                <th>Nombre Completo</th>
                <td>{{ $trabajador->persona->nombre }} {{ $trabajador->persona->segundo_nombre }} {{ $trabajador->persona->apellido }} {{ $trabajador->persona->segundo_apellido }}</td>
            </tr>
            <tr>
                <th>Cédula</th>
                <td>{{ $trabajador->persona->cedula }}</td>
            </tr>
            <tr>
                <th>Nacionalidad</th>
                <td>{{ $trabajador->persona->nacionalidad ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Género</th>
                <td>{{ $trabajador->persona->genero ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Fecha de Nacimiento</th>
                <td>{{ $trabajador->persona->nacimiento->format('d/m/Y') ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Teléfono</th>
                <td>{{ $trabajador->persona->telefono ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $trabajador->persona->email ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Dirección</th>
                <td>{{ $trabajador->persona->direccion ?? 'No registrado' }}</td>
            </tr>
            <tr>
                <th>Cargo</th>
                <td>{{ $trabajador->cargo->descripcion ?? 'Sin cargo asignado' }}</td>
            </tr>
            <tr>
                <th>Zona de trabajo</th>
                <td>{{ $trabajador->zona_trabajo ?? 'No asignada' }}</td>
            </tr>
            <tr>
                <th>Fecha de registro</th>
                <td>{{ $trabajador->created_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>

    </div>
</body>

</html>