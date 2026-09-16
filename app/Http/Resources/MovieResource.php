<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageUrl = $this->image;
        if ($imageUrl && str_starts_with($imageUrl, '/')) {
            $imageUrl = url($imageUrl);
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'release_year' => (int) $this->release_year,
            'rating' => (float) $this->rating,
            'image' => $imageUrl,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
