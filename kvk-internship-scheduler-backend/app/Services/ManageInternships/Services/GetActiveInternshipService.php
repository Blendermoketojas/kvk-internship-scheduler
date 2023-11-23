<?php

namespace App\Services\ManageInternships\Services;

use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

class GetActiveInternshipService extends BaseService
{
    public function rules(): array
    {
        return [];
    }

    public function data(): array
    {
        return [];
    }

    public function permissions(): array
    {
        return [];
    }

    function execute() : JsonResponse
    {
        $active_internship = Internship::where('user_id', $this->user->id)
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->with('company')
            ->first();

        if ($active_internship == null) {
            response()->json('User does not currently have an active internship', 404);
        }

        return response()->json($active_internship);
    }
}
