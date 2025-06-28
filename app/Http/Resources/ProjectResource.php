<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => $this->status,
            'files' => $this->processFiles(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    protected function processFiles(): array
    {
        if (empty($this->files)) {
            return [];
        }

        return array_map(function ($file) {
            return [
                'path' => $file['path'],
                'url' => Storage::url($file['path']),
                'type' => $file['type'] ?? $this->determineFileType($file['path']),
            ];
        }, $this->files);
    }

    protected function determineFileType(string $path): string
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        $videoExtensions = ['mp4', 'mov', 'avi', 'wmv'];

        return in_array(strtolower($extension), $videoExtensions) ? 'video' : 'image';
    }
}
