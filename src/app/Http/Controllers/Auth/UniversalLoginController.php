<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class UniversalLoginController extends Controller
{
    /**
     * @var array<string, string>
     */
    private const ROLE_PATHS = [
        'super_admin' => '/admin',
        'admin_bkts' => '/bkts',
        'konselor' => '/konselor',
        'mahasiswa' => '/mahasiswa',
    ];

    public function show(Request $request): View|RedirectResponse
    {
        if ($request->user()) {
            return $this->redirectToDashboard($request->user());
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectToDashboard($request->user());
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public static function dashboardPathFor(mixed $user): ?string
    {
        foreach (self::ROLE_PATHS as $role => $path) {
            if ($user->hasRole($role)) {
                return $path;
            }
        }

        return null;
    }

    private function redirectToDashboard(mixed $user): RedirectResponse
    {
        $path = self::dashboardPathFor($user);

        if ($path !== null) {
            return redirect($path);
        }

        Auth::guard('web')->logout();

        throw ValidationException::withMessages([
            'email' => 'Akun belum memiliki role yang valid.',
        ]);
    }
}
