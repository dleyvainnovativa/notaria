<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Memorial;
use App\Models\TimelineEvent;
use Illuminate\Http\Request;

class TimelineController extends Controller
{
    /**
     * GET /api/{memorial}/timeline
     */
    public function index(Request $request, Memorial $memorial)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return response()->json(
            $memorial->timelineEvents()
                ->orderBy('event_date')
                ->get()
        );
    }

    /**
     * GET /api/{memorial}/timeline/{timelineEvent}
     */
    public function show(Request $request, Memorial $memorial, TimelineEvent $timelineEvent)
    {
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->authorizeEvent($memorial, $timelineEvent);

        return response()->json($timelineEvent);
    }

    /**
     * POST /api/{memorial}/timeline
     */
    public function store(Request $request, Memorial $memorial)
    {
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'event_date'    => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $event = $memorial->timelineEvents()->create($data);

        return response()->json($event, 201);
    }

    /**
     * PUT /api/{memorial}/timeline/{timelineEvent}
     */
    public function update(
        Request $request,
        Memorial $memorial,
        TimelineEvent $timelineEvent
    ) {
        $this->authorizeEvent($memorial, $timelineEvent);
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $data = $request->validate([
            'title'   => 'required|string|max:255',
            'event_date'    => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $timelineEvent->update($data);

        return response()->json($timelineEvent);
    }

    /**
     * DELETE /api/{memorial}/timeline/{timelineEvent}
     */
    public function destroy(Request $request, Memorial $memorial, TimelineEvent $timelineEvent)
    {
        $this->authorizeEvent($memorial, $timelineEvent);
        $user = $request->user();
        if (! $memorial->canAccess($user)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        $timelineEvent->delete();

        return response()->json(null, 204);
    }

    /**
     * Ensure event belongs to memorial
     */
    protected function authorizeEvent(Memorial $memorial, TimelineEvent $event)
    {
        if ($event->memorial_id !== $memorial->id) {
            abort(404);
        }
    }
}
