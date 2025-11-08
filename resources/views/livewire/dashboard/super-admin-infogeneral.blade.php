<div>
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            
            <div class="bg-white rounded-xl shadow-2xl overflow-hidden" id="main-card">
                
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-6 md:p-8 flex items-center justify-between 
                            shadow-inner shadow-blue-900/50"> 
                    
                    <h2 class="text-3xl font-extrabold flex items-center tracking-wide" id="titulo-perfil">
                        <i class='bx bx-user-circle text-4xl mr-3'></i>
                        Detalles Completos del Usuario
                    </h2>
                    
                    <a href="#" 
                       class="bg-yellow-300 hover:bg-yellow-400 text-blue-900 px-4 py-2 rounded-lg font-bold text-sm shadow-lg transition-all duration-300 transform hover:scale-105 flex items-center" 
                       id="btn-modificar">
                        <i class='bx bx-edit mr-2'></i>
                        Modificar
                    </a>
                </div>

                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                        
                        <div class="md:col-span-2" id="info-nombre">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nombre Completo</label>
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 font-medium text-lg">
                                {{ auth()->user()->persona->nombre }} {{ auth()->user()->persona->apellido }}
                            </div>
                        </div>
                        
                        <div id="info-cedula">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Cédula</label>
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800">
                                {{ auth()->user()->persona_cedula }}
                            </div>
                        </div>

                        <div id="info-email">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 break-words">
                                {{ auth()->user()->persona->email ?? 'No registrado' }}
                            </div>
                        </div>
                        
                        <div id="info-telefono">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Teléfono</label>
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800">
                                {{ auth()->user()->persona->telefono ?? 'No registrado' }}
                            </div>
                        </div>
                        
                        <div id="info-sexo">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Sexo</label>
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800">
                                {{ auth()->user()->persona->sexo ?? 'No registrado' }}
                            </div>
                        </div>
                        
                        <div id="info-rol">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Rol de Usuario</label>
                            <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg text-blue-800 font-bold flex items-center">
                                <i class='bx bx-shield-alt-2 mr-2'></i>
                                {{ auth()->user()->getRoleName() }}
                            </div>
                        </div>

                        <div id="info-miembro-desde">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Miembro Desde</label>
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800">
                                {{ auth()->user()->created_at->format('d/m/Y') }}
                            </div>
                        </div>
                        
                        <div class="md:col-span-2" id="info-direccion">
                            <label class="block text-sm font-semibold text-gray-700 mb-1 flex items-center">
                                <i class='bx bx-map text-lg mr-1'></i> Dirección Completa
                            </label>
                            <div class="p-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-800 h-24 overflow-y-auto">
                                {{ auth()->user()->persona->direccion ?? 'No registrado' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div x-data="{ show: true }" class="z-50">
        <button @click="iniciarTour()" 
            x-show="show"
            class="fixed bottom-6 right-6 w-12 h-12 bg-green-500 hover:bg-green-600 text-white text-3xl font-bold rounded-full shadow-xl flex items-center justify-center cursor-pointer z-50 transition-transform transform hover:scale-110"
            title="Iniciar Tour de Ayuda">
            ?
        </button>
    </div>

    <script>
        
        function iniciarTour() {
            if (typeof window.driver === 'undefined' && typeof window.driver.js.driver === 'undefined') {
                 console.error("El pepe");
                 return;
            }
            
            const driver = window.driver.js.driver;

            const driverObj = driver({
                animate: true,
                showButtons: ['next', 'previous', 'close'],
                steps: [
                    { element: '#main-card', popover: { title: '¡Bienvenido a tu Perfil!', description: 'Panel central con toda tu información registrada.', side: 'left' } },
                    { element: '#btn-modificar', popover: { title: 'Editar Información', description: 'Usa este botón para actualizar tus datos personales.', side: 'left' } },
                    { element: '#info-nombre', popover: { title: 'Datos Personales', description: 'Tu nombre completo.', side: 'top' } },
                    { element: '#info-rol', popover: { title: 'Tu Rol', description: 'Nivel de acceso que tienes en la plataforma.', side: 'bottom' } },
                    { element: '#info-direccion', popover: { title: 'Domicilio Registrado', description: 'La dirección principal de tu cuenta.', side: 'top' } },
                    { element: '#main-card', popover: { title: '¡Tour Finalizado!', description: 'Explora y mantén tus datos actualizados.', side: 'right' } }
                ]
            });

            driverObj.drive(); 
        }
    </script>
</div>