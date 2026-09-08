<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminNewsService;
use App\Services\FfbAuth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminNewsController extends Controller
{
    public function __construct(
        private readonly FfbAuth $auth,
        private readonly AdminNewsService $news,
    ) {
    }

    public function show(Request $request): View
    {
        $userId = $this->auth->userId($request);
        $errors = session('admin_errors');

        return $this->render(
            $userId,
            null,
            'create',
            is_array($errors) ? $errors : [],
        );
    }

    public function edit(Request $request, int $news): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $form = $this->news->formForEdit($news);
        if ($form === null) {
            return redirect()
                ->route('admin.news')
                ->with('admin_errors', ['Newseintrag nicht gefunden.']);
        }

        return $this->render($userId, $form, 'update');
    }

    public function store(Request $request): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->news->create($request->all());

        if ($result['ok']) {
            return redirect()
                ->route('admin.news')
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $result['form'] ?? null,
            'create',
            $result['errors'] ?? [],
        );
    }

    public function update(Request $request, int $news): View|RedirectResponse
    {
        $userId = $this->auth->userId($request);
        $result = $this->news->update($news, $request->all());

        if ($result['ok']) {
            return redirect()
                ->route('admin.news')
                ->with('admin_message', $result['message']);
        }

        return $this->render(
            $userId,
            $result['form'] ?? null,
            'update',
            $result['errors'] ?? [],
        );
    }

    public function destroy(Request $request, int $news): RedirectResponse
    {
        $result = $this->news->delete($news);

        if ($result['ok']) {
            return redirect()
                ->route('admin.news')
                ->with('admin_message', $result['message']);
        }

        return redirect()
            ->route('admin.news')
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
        return view('admin.news', [
            'data' => $this->news->pagePayload($userId, $form, $mode),
            'errors' => $errors,
            'answer' => session('admin_message'),
            'legacyBase' => '/',
        ]);
    }
}
