<?php
namespace App\Rules;

use Carbon\Carbon;
use App\Models\UserRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Rule;

class DateRequest implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
{
    $user = Auth::user();

    $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();

    if ($role) {
        return true;
    } else {
        $inputDate = Carbon::parse($value);
        $thirtyDaysFromToday = Carbon::today()->subDays(30);

        return $inputDate->greaterThanOrEqualTo($thirtyDaysFromToday);
    }
}
    // public function passes($attribute, $value)
    // {
    //     if(/Auth::role.user_id === '1'){

    //     }else{
    //     $inputDate = Carbon::parse($value);
    //     $thirtyDaysFromToday = Carbon::today()->subDays(30);
    // }
    //     // dd($inputDate->greaterThanOrEqualTo($thirtyDaysFromToday));
    //     return $inputDate->greaterThanOrEqualTo($thirtyDaysFromToday);
    // }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute must be at least 30 days from today.';
    }
}
