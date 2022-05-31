<?php

namespace App\Services\Schedule;

use App\Exceptions\V1\NotFoundEntityException;
use App\Exceptions\V1\ScheduleNotUniqueException;
use App\Models\WorkSchedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ScheduleService {

    const SORT_ASC = 'ASC';
    const SORT_DESC = 'DESC';

    const PERIOD_YEAR = 'y';
    const PERIOD_QUARTER = 'q';
    const PERIOD_MONTH = 'm';
    const PERIOD_WEEK = 'w';

    const AVAILABLE_PERIOD_FILTERS = [
        self::PERIOD_YEAR,
        self::PERIOD_QUARTER,
        self::PERIOD_MONTH,
        self::PERIOD_WEEK,
    ];

    protected function getDateFromPeriod($period): Carbon {
        $date = Carbon::now();

        switch ($period) {
            case self::PERIOD_YEAR: $date->subYear(); break;
            case self::PERIOD_QUARTER: $date->subMonths(3); break;
            case self::PERIOD_MONTH: $date->subMonth(); break;
            case self::PERIOD_WEEK: $date->subWeek(); break;
        }

        return $date;
    }

    public function accumulated(string $period, string $sort): Collection {
        $query = WorkSchedule::query();

        if (in_array($period, self::AVAILABLE_PERIOD_FILTERS)) {
            $date = $this->getDateFromPeriod($period);
            $query->whereDate('date', '>', $date);
        }

        if (!in_array($sort, [self::SORT_ASC, self::SORT_DESC])) {
            $sort = self::SORT_DESC;
        }

        $query->join('users', 'users.id', '=', 'work_schedules.user_id');

        $query->groupBy('user_id');

        $query->select([DB::raw('SUM(work_schedules.hours) as total'), 'users.email']);
        $query->orderBy('total', $sort);

        return $query->get();
    }

    /**
     * @throws NotFoundEntityException
     */
    public function find(string $userId, string $date): WorkSchedule {
        /** @var WorkSchedule|null $schedule */
        $schedule = WorkSchedule::query()->where('user_id', $userId)
            ->where('date', $date)
            ->first();

        if (!$schedule) {
            throw new NotFoundEntityException('Schedule not found');
        }

        return $schedule;
    }

    /**
     * @throws ScheduleNotUniqueException
     */
    public function create(User $user, string $date, string $hours) {

        try {
            return WorkSchedule::query()->create([
                'user_id' => $user->getAttribute('id'),
                'date' => $date,
                'hours' => $hours
            ]);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                throw new ScheduleNotUniqueException('Schedule must be unique to date and user');
            }

            throw $e;
        }
    }

    /**
     * @throws ScheduleNotUniqueException
     */
    public function update(WorkSchedule $schedule, array $fields): WorkSchedule {
        try {
            $schedule->update($fields);

            return $schedule;
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                throw new ScheduleNotUniqueException('Schedule must be unique to date and user');
            }

            throw $e;
        }
    }

    public function delete(WorkSchedule $schedule): void {
        // TODO: Ideally should be soft delete
        // Not remove records from database
        $schedule->delete();
    }

    public function getByUser(User $user): Collection {
        return WorkSchedule::query()
            ->where('user_id', $user->getAttribute('id'))
            ->select(['date', 'hours'])
            ->orderBy('date', 'DESC')
            ->get();
    }

    public function deleteAllForUserId($userId) {
        WorkSchedule::query()->where('user_id', $userId)->delete();
    }

}
