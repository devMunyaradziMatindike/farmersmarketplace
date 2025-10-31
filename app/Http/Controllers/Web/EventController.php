<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EventController extends Controller
{
    public function index(Request $request): Response
    {
        $query = Event::query();

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by date
        if ($request->filled('upcoming')) {
            $query->where('start_date', '>=', now());
        }

        // Sort
        $sort = $request->get('sort', 'start_date');
        match ($sort) {
            'featured' => $query->orderBy('is_featured', 'desc')->orderBy('start_date', 'asc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('start_date', 'asc'),
        };

        $events = $query->withCount('registrations')->paginate(12);

        return Inertia::render('Events/Index', [
            'events' => $events,
            'filters' => $request->only(['type', 'upcoming', 'sort']),
        ]);
    }

    public function show(Event $event): Response
    {
        $event->loadCount('registrations');

        $isRegistered = false;
        if (auth()->check()) {
            $isRegistered = $event->registrations()
                ->where('user_id', auth()->id())
                ->where('status', 'confirmed')
                ->exists();
        }

        return Inertia::render('Events/Show', [
            'event' => $event,
            'isRegistered' => $isRegistered,
        ]);
    }

    public function register(Request $request, Event $event)
    {
        $validated = $request->validate([
            'registration_type' => ['required', 'in:individual,cooperative,company'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'additional_info' => ['nullable', 'string'],
        ]);

        // Check if event is open for registration
        if (! $event->is_registration_open) {
            return back()->with('error', 'Registration for this event is currently closed.');
        }

        // Check capacity
        if ($event->capacity && $event->registration_count >= $event->capacity) {
            return back()->with('error', 'This event is full. No more registrations are being accepted.');
        }

        // Check if user already registered (by email or user_id)
        $existingRegistration = null;
        if (auth()->check()) {
            $existingRegistration = $event->registrations()
                ->where('user_id', auth()->id())
                ->where('status', 'confirmed')
                ->first();
        } else {
            // Check by email for guests
            $existingRegistration = $event->registrations()
                ->where('email', $validated['email'])
                ->where('status', 'confirmed')
                ->first();
        }

        if ($existingRegistration) {
            return back()->with('error', 'This email is already registered for this event.');
        }

        // Create registration
        EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'registration_type' => $validated['registration_type'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'organization_name' => $validated['organization_name'] ?? null,
            'additional_info' => $validated['additional_info'] ?? null,
            'status' => 'confirmed',
        ]);

        return back()->with('success', 'Successfully registered for '.$event->title.'!');
    }
}
