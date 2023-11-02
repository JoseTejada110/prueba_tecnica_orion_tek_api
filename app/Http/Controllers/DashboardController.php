<?php

namespace App\Http\Controllers;

use App\Models\ClientAddressModel;
use App\Models\ClientModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function activeClientsAndAddresses(Request $request)
    {
        $totalClients = ClientModel::count();
        $totalAddresses = ClientAddressModel::count();

        return response([
            'total_clients' => $totalClients,
            'total_addresses' => $totalAddresses,
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    public function clientsByMonth(Request $request)
    {
        DB::statement("SET lc_time_names = 'es_ES'");
        $lastSixMonthsTable = DB::raw("(SELECT DATE_SUB(NOW(), INTERVAL n MONTH) AS date
        FROM (
            SELECT 0 n UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
        ) numbers) months");
        
        $result = DB::table($lastSixMonthsTable)
        ->leftJoin('clients', function($join) {
            $join->on(DB::raw("DATE_FORMAT(clients.created_at, '%Y-%m')"), '=', DB::raw("DATE_FORMAT(months.date, '%Y-%m')"))
                ->whereNull('clients.deleted_at');
        })
        ->where('months.date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
        ->select(DB::raw('IFNULL(COUNT(clients.id), 0) as y'), DB::raw('months.date as x'))
        ->groupBy('months.date')
        ->orderBy('months.date', 'ASC')
        ->get();

        return $this->showAll($result);
    }

    public function addressesByType(Request $request)
    {
        // DB::statement("SET lc_time_names = 'es_ES'");
        
        $result = DB::table('client_address')
            ->select(DB::raw('IFNULL(COUNT(client_address.id), 0) as y'), DB::raw('address_type.type as x'))
            ->join('address_type', 'client_address.type_id', '=', 'address_type.id')
            ->whereNull('client_address.deleted_at')
            ->groupBy('client_address.type_id', 'address_type.type')
            ->get();
        
        // $result = DB::table($lastSixMonthsTable)
        // ->leftJoin('clients', function($join) {
        //     $join->on(DB::raw("DATE_FORMAT(clients.created_at, '%Y-%m')"), '=', DB::raw("DATE_FORMAT(months.date, '%Y-%m')"))
        //         ->whereNull('clients.deleted_at');
        // })
        // ->where('months.date', '>=', Carbon::now()->subMonths(5)->startOfMonth())
        // ->select(DB::raw('IFNULL(COUNT(clients.id), 0) as y'), DB::raw('months.date as x'))
        // ->groupBy('months.date')
        // ->orderBy('months.date', 'ASC')
        // ->get();

        return $this->showAll($result);
    }
}
