<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;



class ImportClientsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    public function handle()
    {
        $file = Storage::get($this->path);
        $rows = array_map('str_getcsv', explode("\n", trim($file)));
        $header = array_map('trim', array_shift($rows));
        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            if (empty($data['full_name'])) { //exist if client name is not exist can be used for more important fields
                continue;
            }
            try {
                $validatedDetails = $this->validateClientDetails($data);
                if ($validatedDetails) { //insert if have a valid full name with hierarchy
                    Client::create($validatedDetails);
                }
            } catch (\Exception $e) {
                Log::error('Client Import Error: ' . $e->getMessage(), $data);
            }
        }
        Storage::delete($this->path);
    }

    /* 
        funciton to validate csv inputs can be changes accroding to reqirements
        taking client pk as parant id for reference
    */
    private function validateClientDetails(array $data): ?array
    {
        $rules = [
            'full_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50|min:8',
            'dob' => 'nullable|date_format:d-m-Y',
            'balance' => 'nullable|numeric|min:0.01',
            'parent_id' => 'nullable', //exists:clients,id removed check becouse of dont have full data of client table like first record have parant id 2803
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            if ($validator->errors()->has('full_name')) {
                return null;
            }
            // Remove only key which failvalidation
            foreach ($validator->errors()->keys() as $key) {
                unset($data[$key]);
            }
        }
        return [
            'full_name' => $data['full_name'],
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'dob' => !empty($data['dob']) ? Carbon::createFromFormat('d-m-Y', $data['dob'])->toDateString() : null,
            'balance' => isset($data['balance']) ? floatval($data['balance']) : null,
            'parent_id' => $data['parent_id'] > 0 ? $data['parent_id'] : null,
        ];
    }
}
