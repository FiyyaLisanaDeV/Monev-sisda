<?php

namespace App\Policies;

use App\Models\PaketProgres;
use App\Models\User;

class PaketProgresPolicy
{
    public function viewAny(User $user): bool { return true; }
    public function view(User $user, PaketProgres $paketProgres): bool { return true; }
    public function create(User $user): bool { return false; }
    public function update(User $user, PaketProgres $paketProgres): bool { return false; }
    public function delete(User $user, PaketProgres $paketProgres): bool { return false; }
    public function restore(User $user, PaketProgres $paketProgres): bool { return false; }
    public function forceDelete(User $user, PaketProgres $paketProgres): bool { return false; }
}
