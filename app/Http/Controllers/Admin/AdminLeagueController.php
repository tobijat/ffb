<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminLeagueService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class AdminLeagueController extends Controller
{
    public function __construct(
        private readonly AdminLeagueService $leagues,
    ) {}

    public function show(Request $request): View
    {
        $userId = (int) Auth::id();
        $errors = session('admin_errors');

        return $this->render(
            $userId,
            null,
            'create',
            is_array($errors) ? $errors : [],
        );
    }

    public function edit(Request $request, int $league): View|RedirectResponse
    {
        $userId = (int) Auth::id();
        $form = $this->leagues->formForEdit($league);
        if ($form === null) {
            return redirect()
                ->route('admin.leagues')
                ->with('admin_errors', ['Liga nicht gefunden.']);
        }

        return $this->render($userId, $form, 'update');
    }

    public function store(Request $request): View|RedirectResponse
    {
        $userId = (int) Auth::id();
        $result = $this->leagues->create(
            $request->all(),
            $request->file('league_symbol_file'),
        );

        if ($result['ok']) {
            return redirect()
                ->route('admin.leagues')
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $result['form'] ?? null,
            'create',
            $result['errors'] ?? [],
        );
    }

    public function update(Request $request, int $league): View|RedirectResponse
    {
        $userId = (int) Auth::id();
        $result = $this->leagues->update(
            $league,
            $request->all(),
            $request->file('league_symbol_file'),
        );

        if ($result['ok']) {
            return redirect()
                ->route('admin.leagues')
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $result['form'] ?? null,
            'update',
            $result['errors'] ?? [],
        );
    }

    public function destroy(Request $request, int $league): RedirectResponse
    {
        $result = $this->leagues->delete($league);

        if ($result['ok']) {
            return redirect()
                ->route('admin.leagues')
                ->with('admin_message', $result['message']);
        }

        return redirect()
            ->route('admin.leagues')
            ->with('admin_errors', $result['errors'] ?? ['Löschen fehlgeschlagen.']);
    }

    /**
     * @param  array<string, mixed>|null  $form
     * @param  list<string>  $errors
     */
    private function render(
        int $userId,
        ?array $form = null,
        string $mode = 'create',
        array $errors = [],
    ): View {
        return view('admin.leagues', [
            'data' => $this->leagues->pagePayload($userId, $form, $mode),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
