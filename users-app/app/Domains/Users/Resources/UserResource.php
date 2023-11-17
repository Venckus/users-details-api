<?php

namespace App\Domains\Users\Resources;

use App\Domains\Users\DB\Models\UserDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->resource->loadMissing('details');

        return [
            User::UUID => $this->resource->uuid,
            User::FIRST_NAME => $this->resource->first_name,
            User::LAST_NAME=> $this->resource->last_name,
            User::EMAIL => $this->resource->email,
            UserDetails::ADDRESS => $this->whenLoaded('details', $this->details?->address),
        ];
    }
}
