<?php

namespace App\Policies;

use App\Models\RawProgresRow;
use App\Models\User;

class RawProgresRowPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, RawProgresRow $model): bool { return true; }
    public function create(User $user): bool { return false; }
    public function update(User $user, RawProgresRow $model): bool { return false; }
    public function delete(User $user, RawProgresRow $model): bool { return false; }
    public function restore(User $user, RawProgresRow $model): bool { return false; }
    public function forceDelete(User $user, RawProgresRow $model): bool { return false; }
}
