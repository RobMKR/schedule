<?php

namespace App\Services\User;

use App\Exceptions\V1\NotFoundEntityException;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class UserService {

    public function me(): User {
        return Auth::user();
    }

    public function getAll(): Collection {
        return User::all([
            'id', 'name', 'email', 'role'
        ]);
    }

    /**
     * @throws NotFoundEntityException
     */
    public function find($id): User {
        /** @var User|null $user */
        $user = User::query()->find($id);

        if (!$user) {
            throw new NotFoundEntityException('User not found');
        }

        return $user;
    }

    /**
     * @throws NotFoundEntityException
     */
    public function getByEmail(string $email): User {
        $user = User::query()->where('email', $email)
            ->where('role', User::ROLE_STAFF)
            ->first();

        if (!$user) {
            throw new NotFoundEntityException('User not found');
        }

        return $user;
    }

    public function update(User $user, array $fields): User {
        $user->update($fields);

        return $user;
    }

    public function delete(User $user): void {
        // TODO: Ideally should be soft delete
        // Not remove records from database
        $user->delete();
    }

}
