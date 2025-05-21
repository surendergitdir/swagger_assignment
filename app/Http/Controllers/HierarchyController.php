<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Http\Requests\Client\AddClientRequest;
use App\Jobs\ImportClientsJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;



class HierarchyController extends Controller
{
    
    public function index()
    {
        $client = Client::select('id', 'parent_id', 'full_name')
            ->whereNull('parent_id')
            ->with(['children:id,parent_id,full_name'])
            ->paginate(100);
        return view('hierarchy.index', compact('client'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('hierarchy.create', compact('clients'));
    }

    public function store(AddClientRequest $request)
    {
        Client::create([
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'dob' => $request->dob,
            'balance' => $request->balance ?? 0,
            'parent_id' => $request->parent_id,
        ]);
        return redirect('/')->with('success', 'Client added successfully!');
    }


    //fn to save csv file and disptch job to extract data
    public function import(Request $request)
    {
        try {
            $filename = Str::uuid() . '.' . $request->file('csv_file')->getClientOriginalExtension();
            if (!Storage::disk('local')->exists('client')) {
                Storage::disk('local')->makeDirectory('client');
            }
            if (!is_writable(storage_path('app/client'))) {
                throw new \Exception('The client folder is not writable. Please check storage permissions.');
            }
            // Attempt to store the file
            $path = $request->file('csv_file')->storeAs('client', $filename, 'local');
            if (!$path) {
                throw new \Exception('File upload failed. Could not store file.');
            }
            // Dispatch job
            ImportClientsJob::dispatch($path);
            return redirect('/')->with('success', 'CSV file uploaded! Importing in background...');
        } catch (\Exception $e) {
            Log::error('CSV upload/import error: ' . $e->getMessage());
    
            return redirect()->back()->withErrors(['csv_file' => 'Upload failed: Something went wrong!']);
        }
    }

    public function importForm()
    {
        return view('hierarchy.import');
    }
}
