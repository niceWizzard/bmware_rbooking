<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

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
    public function version(Request $request): ?string
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
        $user = Auth::user();

        if (!$user) {
            return [
                ...parent::share($request),
                'auth' => [
                    'user' => null,
                ],
                'flash' => [
                    'message' => fn () => $request->session()->get('message'),
                ],
            ];
        }

        $roleData = match ($user->role) {
            User::ROLE_ADMIN => ['admin' => $user->admin?->only('id', 'name')],
            User::ROLE_PATIENT => ['patient' => $user->patient?->only('id', 'first_name', 'last_name')],
            default => [],
        };

        return [
            ...parent::share($request),
            'auth' => [
                'user' => [
                    ...$user->only(['id', 'email', 'role', 'patient_id', 'admin_id']),
                    ...$roleData,
                ],
            ],
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
            ],
        ];

    }
}
