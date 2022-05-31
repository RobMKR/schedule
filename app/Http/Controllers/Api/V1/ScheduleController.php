<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\V1\NotFoundEntityException;
use App\Models\User;
use App\Services\Response\ResponseService;
use App\Services\Schedule\ScheduleService;
use App\Services\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController {

    protected ScheduleService $scheduleService;
    protected UserService $userService;

    public function __construct(ScheduleService $scheduleService, UserService $userService) {
        $this->scheduleService = $scheduleService;
        $this->userService = $userService;
    }

    // TODO: Add pagination
    public function getByEmail(Request $request): JsonResponse {
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            /** @var User $user */
            $user = $this->userService->getByEmail($request->input('email'));
        } catch (NotFoundEntityException $e) {
            return ResponseService::errorMessage('User not found', 404);
        }

        $schedules = $this->scheduleService->getByUser($user);

        return ResponseService::data($schedules);
    }

    public function getSelf() {
        $me = Auth::user();

        $schedules = $this->scheduleService->getByUser($me);

        return ResponseService::data($schedules);
    }

}
