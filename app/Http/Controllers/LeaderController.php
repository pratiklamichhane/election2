<?php

namespace App\Http\Controllers;

use App\Models\Leader;
use App\Models\Party;
use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $leaders = Leader::with(['party', 'election'])
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhereHas('party', function ($q) use ($search) {
                          $q->where('name', 'LIKE', "%{$search}%");
                      })
                      ->orWhereHas('election', function ($q) use ($search) {
                          $q->where('name', 'LIKE', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(5);
    
        $parties = Party::all();
        $elections = Election::all();
    
        return view('leaders.index', compact('leaders', 'parties', 'elections', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parties = Party::all();
        $elections = Election::all();
        return view('leaders.create',compact('parties','elections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
     $validated = $request->validate([
            'name' => 'required|string|max:255',
            'party_id' => 'required|exists:parties,id',
            'election_id' => 'required|exists:elections,id',
            'logo' => 'required|file|mimes:jpeg,png,jpg,gif,webp|max:4096',

        ]);
        $logoPath = $request->file('logo')->store('logos', 'public');
        $validated['logo'] = $logoPath;
     
        Leader::create($validated);

        return redirect()->route('leaders.index')->with('success', 'Leader added successfully.');
    }



    /**
     * Display the specified resource.
     */
    public function show(Leader $leader)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leader $leader)
    {
        $parties = Party::all();
        $elections = Election::all();
        return view('leaders.edit',compact('leader','parties','elections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leader $leader)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'party_id' => 'required|exists:parties,id',
            'election_id' => 'required|exists:elections,id',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        // Check if a new logo is uploaded
        if ($request->hasFile('logo')) {
            // Delete the old logo from storage if it exists
            if ($leader->logo) {
                Storage::disk('public')->delete($leader->logo);
            }

            // Store the new logo and get the file path
            $logoPath = $request->file('logo')->store('logos', 'public');
            $validated['logo'] = $logoPath;
        } else {
            unset($validated['logo']);
        }
    

        $leader->update($validated);

        return redirect()->route('leaders.index')->with('success', 'Leader updated successfully.');
    } 

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leader $leader)
    {
        $leader->delete();
        return redirect()->route('leaders.index')->with('success', 'Leader deleted successfully.');
    }
}
