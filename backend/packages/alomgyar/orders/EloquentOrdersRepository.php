<?php

namespace Alomgyar\Orders;

use App\Order;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Collection;

class EloquentOrdersRepository
{
    private Order $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    public function getOrdersFromStoreBetweenFromAndToDate(array $store, Carbon $fromDate, Carbon $toDate): Collection
    {
        return $this->model->query()
            ->whereIn('store', $store)->whereBetween('created_at', [$fromDate, $toDate])
            ->get();
    }

    public function getOrdersForWeekReport(array $orderTypes, array $store, Carbon $fromDate, Carbon $toDate): array
    {
        $dateFormat = 'Y-m-d';
        // Get orders from database
        $orders = $this->getOrdersFromStoreBetweenFromAndToDate($store, $fromDate, $toDate)
            ->whereIn('status', $orderTypes);

        $groupedOrders = $orders->mapToGroups(fn ($item) => [Carbon::make($item->created_at)->format($dateFormat) => $item]);

        // Create date period based on $fromDate and $toDate
        $datePeriod = new \DatePeriod($fromDate, CarbonInterval::day(), $toDate);

        // Populate resultForChart array with orders data
        $resultForChart = [];
        foreach ($datePeriod as $day) {
            $actualDay = $day->format($dateFormat);
            $resultForChart[] = [
                'date' => $actualDay,
                'alpha' => $groupedOrders->has($actualDay) ? $groupedOrders->get($actualDay)->count() : 0,
            ];
        }

        return [
            'total' => $orders->count(),
            'dataForChart' => $resultForChart,
        ];
    }
}
