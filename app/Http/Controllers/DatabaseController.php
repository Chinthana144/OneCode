<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DatabaseController extends Controller
{
    public function index()
    {
        return view('query.query_execute');
    }//index

    public function runQuery(Request $request)
    {
        $query = trim($request->input('query'));

        try {
            // Check if SELECT query
            if (stripos($query, 'select') === 0) {
                $result = DB::select($query);

                return response()->json([
                    'type'   => 'select',
                    'data'   => $result,
                ]);
            }

            // INSERT / UPDATE / DELETE
            $success = DB::statement($query);

            return response()->json([
                'type'    => 'statement',
                'success' => $success,
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}//class