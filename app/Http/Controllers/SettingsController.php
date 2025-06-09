<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Parsedown;


class SettingsController extends Controller
{
    public function uploadController(Request $request)
    {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();

        if (!$filename) {
            return response()->json(['message' => 'No se ha proporcionado ningún nombre de archivo.'], 400);
        }

        $controllerFiles = File::allFiles(app_path('Http'));
        $routeFiles = File::allFiles(base_path('routes'));
        $App = File::allFiles(app_path());

        foreach ($controllerFiles as $file) {
            if ($file->getFilename() === $filename) {
                $filePath = $file->getPathname();

                if (!File::exists($filePath)) {
                    return response()->json(['message' => 'El archivo no existe.'], 404);
                }

                if ($request->input('download') === 'true') {
                    return response()->download($filePath, $filename, [
                        'Content-Type' => 'application/octet-stream',
                    ]);
                }

                $currentContent = File::get($filePath);
                return response()->json([
                    'message' => 'El archivo ya existe en la carpeta de controladores',
                    'content' => $currentContent,
                    'filename' => $filename,
                    'tipo' => 'http',
                    'path' => $filePath,
                    'prompt' => '¿Deseas sobrescribir el archivo ' . $filename . '?'
                ], 200);
            }
        }

        foreach ($routeFiles as $file) {
            if ($file->getFilename() === $filename) {
            $currentContent = File::get($file->getPathname());

            if (!File::exists($filePath)) {
                return response()->json(['message' => 'El archivo no existe.'], 404);
            }

            if ($request->input('download') === 'true') {
                return response()->download($filePath, $filename, [
                    'Content-Type' => 'application/octet-stream',
                ]);
            }

            return response()->json([
                'message' => 'El archivo ya existe en la carpeta routes',
                'content' => $currentContent,
                'filename' => $filename,
                'tipo' => 'api',
                'path' => $file->getPathname(),
                'prompt' => '¿Deseas sobrescribir el archivo ' . $filename . '?',
            ], 200);
            }
        }

        foreach ($App as $file) {
            if ($file->getFilename() === $filename) {
            $currentContent = File::get($file->getPathname());

            if (!File::exists($filePath)) {
                return response()->json(['message' => 'El archivo no existe.'], 404);
            }

            if ($request->input('download') === 'true') {
                return response()->download($filePath, $filename, [
                    'Content-Type' => 'application/octet-stream',
                ]);
            }

            return response()->json([
                'message' => 'El archivo ya existe en la carpeta app',
                'content' => $currentContent,
                'filename' => $filename,
                'tipo' => 'app',
                'path' => $file->getPathname(),
                'prompt' => '¿Deseas sobrescribir el archivo ' . $filename . '?',
            ], 200);
            }
        }

        return response()->json(['message' => 'El archivo no existe en ninguna de las carpetas especificadas.'], 404);
    }

    public function overwriteController(Request $request)
    {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'No se ha subido ningún archivo.'], 400);
        }

        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $tipo = $request->input('tipo');
        $path = $request->input('path');

        if ($tipo === 'api') {
            $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR, '', $path);
            $directoryPath = dirname($path);
            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true);
            }
            $file->move($directoryPath, $filename);
        } elseif ($tipo === 'http') {
            $relativePath = str_replace(base_path() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR, '', $path);
            $directoryPath = dirname($path);

            $file->move($directoryPath, $filename);
        } elseif ($tipo === 'app') {
            $normalizedPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
            $normalizedBase = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, base_path());

            $relativePath = ltrim(str_replace($normalizedBase, '', $normalizedPath), DIRECTORY_SEPARATOR);

            $appPrefix = 'app' . DIRECTORY_SEPARATOR;
            while (str_starts_with($relativePath, $appPrefix)) {
                $relativePath = substr($relativePath, strlen($appPrefix));
            }

            $finalPath = app_path($relativePath);
            $directoryPath = dirname($finalPath);

            if (!File::exists($directoryPath)) {
                File::makeDirectory($directoryPath, 0755, true);
            }

            $file->move($directoryPath, $filename);

        } else {
            return response()->json(['message' => 'Destino no válido.'], 400);
        }
        return response()->json(['message' => 'Archivo sobrescrito exitosamente.'], 200);
    }
}

