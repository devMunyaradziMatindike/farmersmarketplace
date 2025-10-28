<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): Response
    {
        $query = User::withCount('products');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $users = $query->paginate(20)->through(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
                'auth_method' => $user->auth_method,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'products_count' => $user->products_count, // Use the eager loaded count
            ];
        });

        $stats = [
            'total' => User::count(),
            'buyers' => User::where('role', 'buyer')->count(),
            'sellers' => User::where('role', 'seller')->count(),
            'admins' => User::where('role', 'admin')->count(),
        ];

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'stats' => $stats,
            'filters' => [
                'search' => $request->search,
                'role' => $request->role,
                'sort' => $sortBy,
                'order' => $sortOrder,
            ],
        ]);
    }

    /**
     * Display the specified user.
     */
    public function show(Request $request, User $user): Response
    {
        $user->load('products.category', 'products.photos');

        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'role' => $user->role,
            'auth_method' => $user->auth_method,
            'google_id' => $user->google_id,
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
        ];

        $products = $user->products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => number_format($product->price, 2),
                'status' => $product->status,
                'category' => $product->category->name,
                'photos' => $product->photos->map(function ($photo) {
                    return [
                        'photo_url' => $photo->photo_url,
                    ];
                }),
                'created_at' => $product->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $stats = [
            'total_products' => $user->products()->count(),
            'available_products' => $user->products()->where('status', 'available')->count(),
            'sold_products' => $user->products()->where('status', 'sold_out')->count(),
        ];

        return Inertia::render('Admin/Users/Show', [
            'user' => $userData,
            'products' => $products,
            'stats' => $stats,
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(Request $request, User $user): Response
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'role' => $user->role,
            ],
        ]);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['buyer', 'seller', 'admin'])],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $data = $request->only(['name', 'email', 'phone_number', 'role']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.show', $user)->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(Request $request, User $user): RedirectResponse
    {
        // Prevent deleting yourself
        if ($user->id === $request->user()->id) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        // Delete user's products and photos
        foreach ($user->products as $product) {
            foreach ($product->photos as $photo) {
                \Storage::disk('public')->delete($photo->photo_path);
            }
            $product->delete();
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Suspend the specified user.
     */
    public function suspend(Request $request, User $user): RedirectResponse
    {
        // This would require a 'suspended' field in the users table
        // For now, we'll just return a message
        return redirect()->back()->with('info', 'User suspension feature coming soon.');
    }

    /**
     * Activate the specified user.
     */
    public function activate(Request $request, User $user): RedirectResponse
    {
        // This would require a 'suspended' field in the users table
        // For now, we'll just return a message
        return redirect()->back()->with('info', 'User activation feature coming soon.');
    }
}





