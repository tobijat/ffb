<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminExtremeteamService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AdminExtremeteamController extends Controller
{
    public function __construct(
        private readonly AdminExtremeteamService $extremeTeams,
    ) {}

    public function show(Request $request): View
    {
        return $this->render($request);
    }

    public function populate(Request $request): RedirectResponse
    {
        $userId = (int) Auth::id();
        $result = $this->extremeTeams->populate($userId, $request->all());

        $redirect = redirect()->route('admin.extremeteam');

        if (! ($result['ok'] ?? false)) {
            return $redirect->with('admin_errors', $result['errors'] ?? ['Unbekannter Fehler.']);
        }

        return $redirect
            ->with('admin_message', $result['message'] ?? 'OK')
            ->with('admin_details', $result['details'] ?? []);
    }

    private function render(Request $request): View
    {
        $userId = (int) Auth::id();
        $errors = session('admin_errors');

        return view('admin.extremeteam', [
            'data' => $this->extremeTeams->pagePayload($userId),
            'errors' => is_array($errors) ? $errors : [],
            'answer' => session('admin_message'),
            'details' => session('admin_details') ?: [],
            'legacyBase' => '/',
        ]);
    }
}
