<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\V1\NotFoundEntityException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Response\ResponseService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminUserController extends Controller {

    protected UserService $userService;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }

    // TODO: Add pagination/validation/etc
    public function getAll(): JsonResponse {
        return ResponseService::data($this->userService->getAll());
    }

    public function update($id, Request $request): JsonResponse {
        try {
            $user = $this->userService->find($id);
        } catch (NotFoundEntityException $e) {
            return ResponseService::errorMessage('User not found', 404);
        }

        $request->validate([
            'email' => 'required|email|unique:users|max:255',
            'name' => 'required|max:255',
            'role' => 'required|in:' . User::ROLE_ADMIN . ',' . User::ROLE_STAFF,
        ], $request->input());

        $this->userService->update($user, $request->only('email', 'name', 'role'));

        return ResponseService::successMessage('User updated successfully');
    }

    /**
     * @param $id
     * @return JsonResponse|Response
     */
    public function delete($id) {
        try {
            $user = $this->userService->find($id);
        } catch (NotFoundEntityException $e) {
            return ResponseService::errorMessage('User not found', 404);
        }

        $this->userService->delete($user);

        return ResponseService::successNoContent();
    }

}
