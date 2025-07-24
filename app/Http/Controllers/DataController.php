<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\Order;
use App\Models\Sale;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;
use App\Jobs\ImportExternalData;

class DataController extends Controller
{

    public function showTable(Request $request)
    {
        $type = $request->query('type');
        $perPage = $request->query('perPage', 10);

        switch ($type) {
            case 'sales':
                $data = Sale::paginate($perPage);
                break;
            case 'orders':
                $data = Order::paginate($perPage);
                break;
            case 'stocks':
                $data = Stock::paginate($perPage);
                break;
            case 'incomes':
                $data = Income::paginate($perPage);
                break;
            default:
                return response("Unknown data type", 400);
        }

        return view('table', ['data' => $data, 'type' => $type]);
    }


    protected array $config = [
        'sales' => ['model' => Sale::class, 'unique' => 'sale_id'],
        'orders' => ['model' => Order::class, 'unique' => 'g_number'],
        'incomes' => ['model' => Income::class, 'unique' => 'income_id'],
        'stocks' => ['model' => Stock::class, 'unique' => 'barcode'],
    ];

    public function fetch(Request $request, string $type): JsonResponse
    {
        if (!isset($this->config[$type])) {
            return response()->json(['message' => 'Unknown data type'], 400);
        }

        $dateFrom = $request->query('dateFrom') ?? '1980-01-01';
        $dateTo = $request->query('dateTo') ?? now()->toDateString();
        $uniqueKey = $this->config[$type]['unique'];
        if ($type === 'stocks') {
            $dateFrom = now()->toDateString();
        }

        ImportExternalData::dispatch($type, $dateFrom, $dateTo, $uniqueKey);


        return response()->json([
            'message' => "Import job for '{$type}' dispatched successfully",
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
        ]);
    }


    public function show(string $type): JsonResponse
    {
        if (!isset($this->config[$type])) {
            return response()->json(['message' => 'Unknown data type'], 400);
        }

        $model = $this->config[$type]['model'];
        return response()->json($model::all());
    }

    public function delete(string $type): \Illuminate\Http\RedirectResponse
    {
        set_time_limit(0);
        if (!isset($this->config[$type])) {
            return redirect()->back()->with('error', 'Неизвестный тип данных');
        }

        $model = $this->config[$type]['model'];
        $model::truncate();

        return redirect()->back()->with('success', ucfirst($type) . ' успешно удалены');
    }


}
