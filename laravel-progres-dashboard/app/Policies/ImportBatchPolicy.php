<?php

namespace App\Policies;

use App\Models\ImportBatch;
use App\Models\User;

class ImportBatchPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, ImportBatch $importBatch): bool { return true; }
    public function create(User $user): bool { return $user->hasRole('Admin'); }
    public function update(User $user, ImportBatch $importBatch): bool { return false; }
    public function delete(User $user, ImportBatch $importBatch): bool { return false; }
    public function restore(User $user, ImportBatch $importBatch): bool { return false; }
    public function forceDelete(User $user, ImportBatch $importBatch): bool { return false; }
}
