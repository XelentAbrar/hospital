<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'ziggy' => fn () => array_merge((new Ziggy)->toArray(), [
                'location' => $request->url(),
            ]),
            'hasAccountsModule' => file_exists(base_path('config/accounts.php')),
            'hasDonationModule' => file_exists(base_path('config/donation.php')),
            'hasExpenseModule' => file_exists(base_path('config/expense.php')),
            'hasLabModule' => file_exists(base_path('config/lab.php')),
            'hasInventoryModule' => file_exists(base_path('config/inventory.php')),
        ]);
    }
}
