<?php

namespace App\Policies;

use App\Models\DataQualityReport;
use App\Models\User;

class DataQualityReportPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, DataQualityReport $model): bool { return true; }
    public function create(User $user): bool { return false; }
    public function update(User $user, DataQualityReport $model): bool { return false; }
    public function delete(User $user, DataQualityReport $model): bool { return false; }
    public function restore(User $user, DataQualityReport $model): bool { return false; }
    public function forceDelete(User $user, DataQualityReport $model): bool { return false; }
}
