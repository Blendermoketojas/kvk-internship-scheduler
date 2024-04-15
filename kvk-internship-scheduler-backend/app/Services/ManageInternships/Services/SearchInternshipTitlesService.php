<?php

namespace App\Services\ManageInternships\Services;

use App\Contracts\Roles\RolePermissions;
use App\Models\Internship;
use App\Services\BaseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class SearchInternshipTitlesService extends BaseService
{
    public function rules(): array
    {
        return [
            'searchString' => 'required|string|max:255'
        ];
    }

    public function data(): array
    {
        return [
            'searchString' => $this->request['searchString']
        ];
    }

    public function permissions(): array
    {
        return [];
    }

    /**
     * @throws ValidationException
     */
    function execute(): JsonResponse
    {
        // input validation
        if (!$this->validateRules()) return response()->json("Action not allowed", 401);

        // logic execution

        $titles = Internship::select('title')
            ->where('title', 'like', '%' . $this->data()['searchString'] . '%')
            ->orderBy('created_at', 'desc')
            ->distinct()
            ->get()
            ->pluck('title');

        // response
        return response()->json($titles);
    }
}
