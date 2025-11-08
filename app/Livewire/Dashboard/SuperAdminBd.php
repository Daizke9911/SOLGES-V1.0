<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Log;

class SuperAdminBd extends Component
{
    use WithFileUploads;

    public $importFile;
    public $message = '';
    public $messageType = '';

    protected $listeners = ['resetMessages'];
    
    private $dumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe';
    private $mysqlPath = 'C:\\xampp\\mysql\\bin\\mysql.exe';

    /**
     * Exporta la base de datos completa a un archivo .sql.
     */
    public function exportSql()
    {
        $this->resetMessages();
        
        try {
            // ** VERIFICACIÓN CRÍTICA DE RUTA **
            if (!file_exists($this->dumpPath)) {
                 // Esta excepción mostrará la ruta exacta que no se encontró en el mensaje de error.
                 throw new \Exception('Ruta de mysqldump.exe no válida. Archivo no encontrado en: ' . $this->dumpPath);
            }
            
            $timestamp = now()->format('Y-m-d_His');
            $dbName = "proyecto";
            $dbUser = "root";
            $dbPassword = env('DB_PASSWORD');
            $dbHost = env('DB_HOST', '127.0.0.1'); 
            $dbPort = env('DB_PORT', '3306');

            if (empty($dbName)) {
                 throw new \Exception('El nombre de la base de datos (DB_DATABASE) no puede estar vacío en el archivo .env.');
            }

            // Crear directorio si no existe
            $backupDir = storage_path('app/backups');
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }
            
            $filename = "backup_{$dbName}_{$timestamp}.sql";
            $filepath = "{$backupDir}/{$filename}";

            // Argumentos de conexión
            $args = [
                '--host=' . escapeshellarg($dbHost),
                '--port=' . escapeshellarg($dbPort),
                '--user=' . escapeshellarg($dbUser),
            ];
            
            // SOLO añadir la contraseña si no está vacía o nula (común para 'root' en XAMPP).
            if (!empty($dbPassword)) {
                $args[] = '--password=' . escapeshellarg($dbPassword);
            }
            
            // Comando COMPLETO: usa redirección de salida (>)
            $command = sprintf(
                '%s %s %s > %s',
                escapeshellarg($this->dumpPath), 
                implode(' ', $args), 
                escapeshellarg($dbName),
                escapeshellarg($filepath)
            );

            $process = Process::fromShellCommandline($command);
            $process->setTimeout(300); // 5 minutos de timeout
            $process->run();
            
            if (!$process->isSuccessful()) {
                $errorOutput = $process->getErrorOutput();
                // Limpiar el archivo incompleto si existe
                if (file_exists($filepath)) { @unlink($filepath); }
                
                // Sanitizar la contraseña para evitar que se muestre en el mensaje de error
                $sanitizedCommand = preg_replace('/--password=\S*/', '--password=*', $command);
                
                throw new \Exception("Fallo en mysqldump. Error: " . $errorOutput . " Comando: " . $sanitizedCommand); 
            }

            $this->dispatch('show-toast', [
                'message' => 'Exportación de la base de datos en SQL completada.' ,
                'type' => 'success'
            ]);

            return response()->download($filepath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            $this->message = 'Error al exportar: ' . $e->getMessage();
            $this->messageType = 'error';
            Log::error('SQL Export Error: ' . $e->getMessage());
        }
    }

    /**
     * Importa la base de datos desde un archivo SQL subido.
     */
    public function importDatabase()
    {
        $this->resetMessages();
        // Nota: Solo permitimos .sql por ahora, se ignoró el botón de JSON
        $this->validate([
            'importFile' => 'required|file|mimes:sql|max:102400',
        ], [
            'importFile.mimes' => 'Solo se permiten archivos con extensión .sql.',
        ]);
        
        $path = null;
        $userId = auth()->id();

        try {
            $path = $this->importFile->store('temp-imports');
            $fullPath = storage_path("app/{$path}");

            $this->executeSqlImport($fullPath);

            if ($userId) {
                auth()->loginUsingId($userId);
            }

            Storage::delete($path);
            
            $this->dispatch('show-toast', [
                'message' => 'Importación de la base de datos SQL completada.' ,
                'type' => 'success'
            ]);

            $this->message = 'Importación completada exitosamente';
            $this->messageType = 'success';
            $this->reset(['importFile']); 

        } catch (\Exception $e) {
            if (isset($path)) {
                Storage::delete($path);
            }
            
            $this->message = 'Error al importar: ' . $e->getMessage();
            $this->messageType = 'error';
            Log::error('SQL Import Error: ' . $e->getMessage());
        }
    }

    /**
     * Lógica de bajo nivel para ejecutar el comando de importación SQL.
     */
    private function executeSqlImport($filepath)
    {
        $dbName = env('DB_DATABASE', 'proyecto');
        $dbUser = env('DB_USERNAME', 'root');
        $dbPassword = env('DB_PASSWORD');
        $dbHost = env('DB_HOST', '127.0.0.1');
        $dbPort = env('DB_PORT', '3306');

        // ** VERIFICACIÓN CRÍTICA DE RUTA **
        if (!file_exists($this->mysqlPath)) {
             // Esta excepción mostrará la ruta exacta que no se encontró en el mensaje de error.
             throw new \Exception('Ruta de mysql.exe no válida. Archivo no encontrado en: ' . $this->mysqlPath);
        }
        
        if (empty($dbName)) {
             throw new \Exception('El nombre de la base de datos (DB_DATABASE) no puede estar vacío en el archivo .env.');
        }

        // Argumentos de conexión
        $args = [
            '--host=' . escapeshellarg($dbHost),
            '--port=' . escapeshellarg($dbPort),
            '--user=' . escapeshellarg($dbUser),
        ];
        
        if (!empty($dbPassword)) {
            $args[] = '--password=' . escapeshellarg($dbPassword);
        }
        
        // Comando COMPLETO: usa redirección de entrada (<)
        $command = sprintf(
            '%s %s %s < %s',
            escapeshellarg($this->mysqlPath), 
            implode(' ', $args), 
            escapeshellarg($dbName),
            escapeshellarg($filepath)
        );

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(600);
        $process->run();

        if (!$process->isSuccessful()) {
            $errorOutput = $process->getErrorOutput() . $process->getOutput();
            // Sanitizar la contraseña para evitar que se muestre en el mensaje de error
            $sanitizedError = preg_replace('/--password=\S*/', '--password=*', $errorOutput);
            $sanitizedCommand = preg_replace('/--password=\S*/', '--password=*', $command);
            
            throw new \Exception("Fallo en mysql import. Error: " . $sanitizedError . " Comando: " . $sanitizedCommand);
        }
    }

    public function resetMessages()
    {
        $this->message = '';
        $this->messageType = '';
    }

    public function render()
    {
        return view('livewire.dashboard.super-admin-bd')->layout('components.layouts.rbac');
    }
}