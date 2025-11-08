<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="flex items-center justify-center">

            <div class="flex flex-col items-center {{ $paso >= 1 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $paso >= 1 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-file-blank text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Solicitud </span>
            </div>

            <div class="w-16 sm:w-24 h-1 mx-2 {{ $paso >= 2 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>


            <div class="flex flex-col items-center {{ $paso >= 2 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $paso >= 2 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-calendar-check text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Detalles Solicitud </span>
            </div>

            <div class="w-16 sm:w-24 h-1 mx-2 {{ $paso >= 3 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>

            <div class="flex flex-col items-center {{ $paso >= 3 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $paso >= 3 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-user text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Encargados</span>
            </div>

            <div class="w-16 sm:w-24 h-1 mx-2 {{ $paso > 3 ? 'bg-blue-600' : 'bg-gray-300' }}"></div>

            <div class="flex flex-col items-center {{ $paso > 3 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-12 h-12 rounded-full flex items-center justify-center border-2 
                            {{ $paso > 3 ? 'border-blue-600 bg-blue-50' : 'border-gray-300 bg-gray-50' }}">
                    <i class='bx bx-timer text-2xl'></i>
                </div>
                <span class="mt-2 text-sm font-medium hidden sm:block">Fecha</span>
            </div>
        </div>
    </div>

    @if ($paso == 1)
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <div class="max-w-2xl mx-auto">

            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-check-shield text-blue-600 text-3xl'></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Paso 1: Selecciona una Solicitud Aprobada</h2>
                <p class="text-gray-600">Haz clic en el ticket que deseas gestionar para ver sus detalles en el
                    siguiente paso.</p>
            </div>

            <div class="mb-6 relative">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por título o id......"
                    class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class='bx bx-search text-gray-400'></i>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @forelse ($solicitudAproved as $solicitud)
                <button wire:click="selectSolicitud('{{ $solicitud->solicitud_id }}')" type="button"
                    title="Seleccionar Solicitud: {{ $solicitud->titulo }}"
                    class="p-4 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 text-left 
                            border-2 
                            {{ ($selectedSolicitudId ?? null) == $solicitud->solicitud_id ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-blue-500 bg-white hover:bg-gray-50' }}">

                    <p class="text-xs font-mono text-gray-500 uppercase">Ticket No.</p>
                    <p class="text-lg font-bold text-blue-600 mb-2">{{ $solicitud->solicitud_id }}</p>
                    <p class="text-sm font-semibold text-gray-600 line-clamp-2">{{ $solicitud->titulo }}</p>
                </button>
                @empty
                <div class="col-span-full text-center py-10 bg-gray-50 border border-gray-200 rounded-lg">
                    <i class='bx bx-info-circle text-4xl text-yellow-600 mb-2'></i>
                    <p class="text-gray-600 font-medium">No se encontraron solicitudes.</p>
                </div>
                @endforelse
            </div>

            @if($solicitudAproved->hasPages())
            <div class="mt-8 pt-4 border-t border-gray-200">
                {{ $solicitudAproved->links() }}
            </div>
            @endif
        </div>
    </div>
    @endif

    @if ($paso == 2 && $selectedSolicitud)
    <div class="bg-white rounded-xl shadow-2xl border border-gray-200 p-8">
        <div class="max-w-2xl mx-auto">

            <div class="text-center mb-10">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-file-find text-blue-600 text-3xl'></i>
                </div>
                <h2 class="text-3xl font-extrabold text-gray-900 mb-2">Paso 2: Detalles de la Solicitud Seleccionada
                </h2>
                <p class="text-gray-600">Revisa cuidadosamente la información antes de continuar con la asignación de la
                    visita técnica.</p>
            </div>

            <div class="space-y-8">

                <div class="p-6 border border-blue-100 rounded-xl bg-blue-50 shadow-inner">
                    <h3 class="text-xl font-bold text-blue-800 mb-4 pb-2 border-b border-blue-200 flex items-center">
                        <i class='bx bx-receipt text-2xl text-blue-600 mr-2'></i>
                        Datos Generales del Ticket
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Número de Ticket</p>
                            <p class="text-2xl font-extrabold text-blue-700">{{ $selectedSolicitud->solicitud_id }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Tipo de Solicitud</p>
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-indigo-200 text-indigo-900">
                                {{ ucfirst($selectedSolicitud->tipo_solicitud) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Fecha de Creación</p>
                            <p class="text-base text-gray-700 font-mono">{{ $selectedSolicitud->fecha_creacion }}</p>
                        </div>
                    </div>

                    <hr class="my-6 border-blue-200">

                    <div class="space-y-4">
                        <p class="text-sm font-semibold text-gray-600 uppercase">Título de la Solicitud</p>
                        <p class="text-xl font-extrabold text-gray-800">{{ $selectedSolicitud->titulo }}</p>

                        <p class="text-sm font-semibold text-gray-600 uppercase pt-2">Descripción Detallada</p>
                        <div
                            class="text-base text-gray-700 whitespace-pre-line bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                            {{ $selectedSolicitud->descripcion }}
                        </div>
                    </div>
                </div>

                @php
                $personaModel = App\Models\Personas::find($selectedSolicitud->persona_cedula);
                @endphp

                @if ($personaModel)
                <div class="p-6 border border-gray-200 rounded-xl shadow-md">
                    <h3 class="text-xl font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <i class='bx bx-user-circle text-2xl text-blue-600 mr-2'></i>
                        Datos del Solicitante
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Nombre Completo</p>
                            <p class="text-lg text-gray-800 font-bold">
                                {{ ucwords(strtolower($personaModel->nombre)) }}
                                {{ ucwords(strtolower($personaModel->segundo_nombre)) }}
                                {{ ucwords(strtolower($personaModel->apellido)) }}
                                {{ ucwords(strtolower($personaModel->segundo_apellido))}}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Cédula de Identidad</p>
                            <p class="text-lg text-gray-800 font-mono font-bold"> {{$personaModel->nacionalidad === 1 ? 'V' : 'E'}}-{{ $selectedSolicitud->persona_cedula
                                }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Teléfono de Contacto</p>
                            <p class="text-base text-gray-700">{{ $personaModel->telefono }}</p>
                        </div>
                    </div>
                </div>
                @endif

                <div class="p-6 border border-gray-200 rounded-xl shadow-md">
                    <h3 class="text-xl font-bold text-gray-700 mb-4 pb-2 border-b border-gray-200 flex items-center">
                        <i class='bx bx-map-pin text-2xl text-blue-600 mr-2'></i>
                        Ubicación de la Solicitud
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Comunidad</p>
                            <p class="text-base text-gray-700 font-medium">{{ $selectedSolicitud->comunidad }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Municipio</p>
                            <p class="text-base text-gray-700 font-medium">{{ $selectedSolicitud->municipio }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-gray-600 uppercase">Estado / Región</p>
                            <p class="text-base text-gray-700 font-medium">{{ $selectedSolicitud->estado_region }}</p>
                        </div>
                    </div>

                    <p class="text-sm font-semibold text-gray-600 uppercase">Dirección Detallada</p>
                    <div class="text-base text-gray-700 bg-white p-4 rounded-lg border border-gray-200 shadow-sm mt-2">
                        {{ $selectedSolicitud->direccion_detallada}}
                    </div>
                </div>

            </div>

            <div class="mt-10 flex justify-between border-t pt-6">
                <button wire:click="previgesStep" type="button"
                    class="py-3 px-6 border border-gray-300 rounded-lg shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 transition-colors group">
                    <i class='bx bx-arrow-back mr-2 transition-transform group-hover:-translate-x-0.5'></i> Volver a
                    Selección
                </button>

                <button type="button" wire:click="$set('paso', 3)"
                    class="py-3 px-6 border border-transparent rounded-lg shadow-lg text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 transition-all group">
                    Continuar y Asignar Visita
                     <i class='bx bx-check ml-2 transition-transform group-hover:translate-x-0.5'></i>
                </button>
            </div>
        </div>
    </div>
    @endif


        @if ($paso == 1 )
        <div class="bg-white rounded-xl shadow-2xl border border-gray-200 p-8">
            <div class="flex flex-center text-xl text-red-600">Seleccione a un encargado para la seleccion de la visita </div>
            <label 
    class="relative p-4 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 text-left cursor-pointer block 
        border-2 "
    title="Seleccionar encargado">
    @foreach($visitors as $admins)


  <label
    class="relative flex flex-col items-start p-4 rounded-lg shadow-md transition-all duration-300 cursor-pointer h-full
           border-2 mb-4
           {{ in_array($admins->persona_cedula, $personasSelected ?? [])
               ? 'border-blue-600 bg-blue-50 transform scale-102 shadow-xl'
               : 'border-gray-200 bg-white hover:border-blue-400 hover:bg-gray-50' }}"
    title="Seleccionar visitante: {{ $admins->persona_cedula }}"
>
    <input
        type="checkbox"
        wire:model.live="personasSelected"
        value="{{ $admins->persona_cedula }}"
        class="absolute top-4 right-4 h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 z-10 cursor-pointer"
    />

    <div class="flex-grow pr-8 text-left">
        <p class="text-xs font-mono text-gray-500 uppercase mb-1">Encargado.</p>

        <div class="mb-2">
            <p class="text-lg font-bold text-blue-600 mb-0 leading-tight">{{ $admins->persona_cedula }}</p>
            <div class="flex items-center text-sm font-semibold text-gray-700">
                <i class="bx bx-user mr-1 text-gray-500"></i>
                <p class="line-clamp-2">{{ $admins->persona->nombre }} {{ $admins->persona->apellido}}</p>
            </div>
        </div>

        <div class="flex items-center text-sm mt-2
                    {{ in_array($admins->persona_cedula, $personasSelected ?? [])
                        ? 'text-blue-700'
                        : 'text-gray-600' }}">
            {{ $admins->role === 1 ? 'Administrador' : 'Asistente' }}
            <i class="bx {{ $admins->role === 1 ? 'bx-hard-hat' : 'bxs-group' }} ml-1"></i>
        </div>
    </div>
</label>

    @endforeach
        @endif
</div>