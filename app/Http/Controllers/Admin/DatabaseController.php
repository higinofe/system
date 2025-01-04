<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

use App\Mail\UsageAlert;
use App\Models\Database;
use App\Models\Domain;
use App\Models\User;


class DatabaseController extends Controller
{
    //check used space
    public function checkUsage(Database $database)
    {        
        //simulation rand space hidden
        $used = rand(0, 100);
        $database->usage = $used;
        $database->save();

        if ($used >= 80) {
            $this->sendUsageAlert($database);
        }

        return response()->json([
            'usage' => $used, 
            'max_quota' => $database->max_quota
        ]);

    }

    //Created DB
    public function create(Request $request)
    {
        $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|unique:databases,name',
            'max_quota' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            $domain = Domain::findOrFail($request->domain_id);
            $user = User::findOrFail($request->user_id);

            $dbName = 'db_' . $request->name . '_' . $domain->id;
            DB::statement("CREATE DATABASE {$dbName}");

            $database = Database::create([
                'name' => $dbName,
                'domain_id' => $domain->id,
                'user_id' => $user->id,
                'usage' => 0, 
                'max_quota' => $request->max_quota,
            ]);

            DB::commit();

            return response()->json(['message' => 'Database provisioned successfully!', 'data' => $database], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to provision database: ' . $e->getMessage()], 500);
        }
    }

    private function sendUsageAlert(Database $database)
    {
        $user = $database->user;
        $message = "Alert: Your database '{$database->name}' has exceeded 80% of its storage quota.";

        Mail::to($user->email)->send(new UsageAlert($database, $message));
    }
}
