<?php

namespace App\Services\Settings;

use Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupService
{
    public function lists(){
        $files = Storage::files('Laravel');
        $fileDetails = collect($files)->map(function ($file) {
            preg_match('/stsims-(\d{4}-\d{2}-\d{2}-\d{2}-\d{2}-\d{2})/', $file, $matches);
            $dateFromFilename = $matches[1] ?? null;
            return [
                'name' => basename($file),
                'path' => $file,
                'size' => $this->formatSize(Storage::size($file)),
                'date' => $dateFromFilename
                    ? Carbon::createFromFormat('Y-m-d-H-i-s', $dateFromFilename)->toDateTimeString()
                    : null,
            ];
        });
       
        $fileDetailsArray = json_decode($fileDetails, true);
        usort($fileDetailsArray, function ($a, $b) {
            $dateA = strtotime($a['date']);
            $dateB = strtotime($b['date']);
        
            return $dateB - $dateA;
        });
        
        // Convert the sorted array back to JSON
        $sortedJson = json_encode($fileDetailsArray);
        
        // Return the sorted JSON
        return $sortedJson;
    }

    public function generate()
    {
        $data = Artisan::call('backup:run');
        
        if($data == 0){
            return back()->with([
                'message' => 'Backup was successfully. Thanks',
                'data' => '',
                'type' => 'bxs-check-circle',
                'color' => 'success'
            ]); 
        }else{
            return back()->with([
                'message' => 'Backup was not successfully. Thanks',
                'data' => '',
                'type' => 'ri-close-circle-fill',
                'color' => 'danger'
            ]); 
        }
    }

    public function download($filename)
    {
        $filePath = '/app/Laravel/' . $filename;

        return $this->streamFile($filePath);
    }

    private function streamFile($filePath)
    {
        return new StreamedResponse(function () use ($filePath) {
            $stream = fopen(storage_path($filePath), 'rb');

            while (!feof($stream)) {
                echo fread($stream, 1024);
            }

            fclose($stream);
        }, 200, [
            'Content-Type' => mime_content_type(storage_path($filePath)),
            'Content-Disposition' => 'attachment; filename="' . basename($filePath) . '"',
        ]);
    }

    private function formatSize($sizeInBytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $sizeInBytes > 1024; $i++) {
            $sizeInBytes /= 1024;
        }

        return round($sizeInBytes, 2) . ' ' . $units[$i];
    }
}
