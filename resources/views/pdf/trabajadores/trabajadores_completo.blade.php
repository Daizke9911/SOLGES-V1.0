<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Reporte Completo de Trabajadores</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; word-wrap: break-word; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Reporte Completo de Trabajadores</h1>
    <p>Generado el: {{ now()->format('d/m/Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Cédula</th>
                <th>Nombre Completo</th>
                <th>Nacionalidad</th>
                <th>Género</th>
                <th>Fecha Nacimiento</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Cargo</th>
                <th>Zona de trabajo</th>
                <th>Fecha de registro</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trabajadores as $trabajador)
                <tr>
                    <td>{{ $trabajador->persona->cedula }}</td>
                    <td>{{ $trabajador->persona->nombre }} {{ $trabajador->persona->segundo_nombre }} {{ $trabajador->persona->apellido }} {{ $trabajador->persona->segundo_apellido }}</td>
                    <td>{{ $trabajador->persona->nacionalidad ?? 'No registrado' }}</td>
                    <td>{{ $trabajador->persona->genero ?? 'No registrado' }}</td>
                    <td>{{ optional($trabajador->persona->nacimiento)->format('d/m/Y') ?? 'No registrado' }}</td>
                    <td>{{ $trabajador->persona->telefono ?? 'No registrado' }}</td>
                    <td>{{ $trabajador->persona->email ?? 'No registrado' }}</td>
                    <td>{{ $trabajador->persona->direccion ?? 'No registrado' }}</td>
                    <td>{{ $trabajador->cargo->descripcion ?? 'Sin cargo' }}</td>
                    <td>{{ $trabajador->zona_trabajo ?? 'No asignada' }}</td>
                    <td>{{ optional($trabajador->created_at)->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
