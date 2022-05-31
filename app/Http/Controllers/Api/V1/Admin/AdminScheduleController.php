<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\V1\NotFoundEntityException;
use App\Exceptions\V1\ScheduleNotUniqueException;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Response\ResponseService;
use App\Services\Schedule\ScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AdminScheduleController extends Controller {

    protected ScheduleService $scheduleService;

    public function __construct(ScheduleService $scheduleService) {
        $this->scheduleService = $scheduleService;
    }

    public function create(Request $request) {
        $request->validate([
            'userId' => 'required|numeric',
            'date' => 'required|date',
            'hours' => 'required|numeric',
        ], $request->input());

        /** @var User $user */
        $user = User::query()->find($request->input('userId'));

        if (!$user) {
            return ResponseService::errorMessage('User not found', 404);
        }

        try {
            $schedule = $this->scheduleService->create($user, $request->input('date'), $request->input('hours'));
        } catch (ScheduleNotUniqueException $e) {
            return ResponseService::errorMessage($e->getMessage(), 422);
        }

        return ResponseService::data($schedule);
    }

    public function update(Request $request): JsonResponse {
        $request->validate([
            'userId' => 'required|numeric',
            'date' => 'required|date',
            'hours' => 'required|numeric',
        ], $request->input());

        try {
            $schedule = $this->scheduleService->find($request->input('userId'), $request->input('date'));
            $this->scheduleService->update($schedule, $request->only('date', 'hours'));
        } catch (NotFoundEntityException $e) {
            return ResponseService::errorMessage('Schedule not found', 404);
        } catch (ScheduleNotUniqueException $e) {
            return ResponseService::errorMessage($e->getMessage(), 422);
        }

        return ResponseService::successMessage('Schedule updated successfully');
    }

    /**
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function delete(Request $request) {
        $request->validate([
            'userId' => 'required',
            'date' => 'required'
        ], $request->input());

        try {
            $schedule = $this->scheduleService->find($request->input('userId'), $request->input('date'));
        } catch (NotFoundEntityException $e) {
            return ResponseService::errorMessage('Schedule not found', 404);
        }

        $this->scheduleService->delete($schedule);

        return ResponseService::successNoContent();
    }

    public function accumulated(Request $request): JsonResponse {
        $data = $this->scheduleService->accumulated(
            $request->get('period', ScheduleService::PERIOD_YEAR),
            $request->get('sort', ScheduleService::SORT_ASC)
        );

        return ResponseService::data($data);
    }

}
