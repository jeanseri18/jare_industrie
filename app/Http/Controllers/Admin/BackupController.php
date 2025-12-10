<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DatabaseBackup;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BackupController extends Controller
{
    public function index()
    {
        $recentBackups = DatabaseBackup::with('user')
            ->recent()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $totalBackups = DatabaseBackup::completed()->count();
        $totalSize = DatabaseBackup::completed()->sum('size');
        $lastBackup = DatabaseBackup::completed()->latest()->first();

        return view('admin.backup.index', compact('recentBackups', 'totalBackups', 'totalSize', 'lastBackup'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'backup_type' => 'required|in:full,structure,data',
            'tables' => 'nullable|string',
            'compress' => 'boolean',
            'filename' => 'nullable|string|max:255',
        ]);

        $filename = $request->input('filename', 'backup_' . date('Y-m-d_H-i-s'));
        $backupPath = storage_path('app/backups');
        
        // Créer le répertoire si nécessaire
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $backup = DatabaseBackup::create([
            'user_id' => auth()->id(),
            'filename' => $filename,
            'type' => $request->backup_type,
            'path' => $backupPath . '/' . $filename . '.sql',
            'tables' => $request->tables ? explode(',', $request->tables) : null,
            'compressed' => $request->boolean('compress'),
            'status' => 'running',
            'started_at' => now(),
        ]);

        // Exécuter la sauvegarde en arrière-plan
        try {
            $this->performBackup($backup);
            
            // Enregistrer l'activité
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'backup',
                'description' => "Sauvegarde {$backup->type_label} créée: {$backup->filename}",
                'model_type' => DatabaseBackup::class,
                'model_id' => $backup->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.backup.index')
                ->with('success', 'Sauvegarde créée avec succès');

        } catch (\Exception $e) {
            $backup->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'completed_at' => now(),
            ]);

            return redirect()->route('admin.backup.index')
                ->with('error', 'Erreur lors de la création de la sauvegarde: ' . $e->getMessage());
        }
    }

    private function performBackup(DatabaseBackup $backup)
    {
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');
        $host = config('database.connections.mysql.host');
        $port = config('database.connections.mysql.port');

        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s %s > %s',
            escapeshellarg($username),
            escapeshellarg($password),
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($database),
            $backup->tables ? implode(' ', array_map('escapeshellarg', $backup->tables)) : '',
            escapeshellarg($backup->path)
        );

        if ($backup->type === 'structure') {
            $command .= ' --no-data';
        } elseif ($backup->type === 'data') {
            $command .= ' --no-create-info';
        }

        $process = Process::fromShellCommandline($command);
        $process->setTimeout(300); // 5 minutes
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $size = filesize($backup->path);

        if ($backup->compressed) {
            $compressedPath = $backup->path . '.gz';
            $gz = gzopen($compressedPath, 'wb9');
            gzwrite($gz, file_get_contents($backup->path));
            gzclose($gz);
            
            unlink($backup->path);
            $backup->path = $compressedPath;
            $size = filesize($compressedPath);
        }

        $backup->update([
            'size' => $size,
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    public function download(DatabaseBackup $backup)
    {
        if ($backup->status !== 'completed') {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Cette sauvegarde n\'est pas disponible');
        }

        if (!File::exists($backup->path)) {
            return redirect()->route('admin.backup.index')
                ->with('error', 'Fichier de sauvegarde introuvable');
        }

        return response()->download($backup->path, $backup->filename . 
            ($backup->compressed ? '.gz' : '.sql'));
    }

    public function destroy(DatabaseBackup $backup)
    {
        if (File::exists($backup->path)) {
            File::delete($backup->path);
        }

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'description' => "Suppression de la sauvegarde: {$backup->filename}",
            'model_type' => DatabaseBackup::class,
            'model_id' => $backup->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $backup->delete();

        return redirect()->route('admin.backup.index')
            ->with('success', 'Sauvegarde supprimée avec succès');
    }
}