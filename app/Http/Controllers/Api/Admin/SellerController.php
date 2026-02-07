<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SellerController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'mobile' => 'required|string|max:20',
                'country' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'password' => 'required|string|min:6',
                'skills' => 'required|array|min:1',
                'skills.*' => 'required|string|max:100',
            ]);

            // Create seller
            $seller = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country' => $request->country,
                'state' => $request->state,
                'password' => Hash::make($request->password),
                'role' => 'seller',
            ]);

            // Convert skill names to IDs
            $skillIds = [];
            foreach ($request->skills as $skillName) {
                $skill = Skill::firstOrCreate([
                    'name' => $skillName,
                ]);
                $skillIds[] = $skill->id;
            }

            // Attach skills to seller
            $seller->skills()->sync($skillIds);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Seller created successfully',
                'data' => [
                    'id' => $seller->id,
                    'name' => $seller->name,
                    'email' => $seller->email,
                    'skills' => $request->skills,
                ]
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Failed to create seller'
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $sellers = User::where('role', 'seller')
            ->with('skills:id,name')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'data' => $sellers
        ], 200);
    }
}
