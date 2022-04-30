<?php

namespace App\Http\Repositories;

use App\Models\Staff;

class StaffRepository
{
    public function getName(int $staffId): Staff
    {
        return Staff::select('first_name')->where('id', $staffId)->first();
    }
}
