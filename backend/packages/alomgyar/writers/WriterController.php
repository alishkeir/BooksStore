<?php

namespace Alomgyar\Writers;

use Alomgyar\Consumption_reports\ConsumptionReport;
use Alomgyar\Consumption_reports\Reports\AuthorConsumptionReport;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductSelectResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WriterController extends Controller
{
    public function index()
    {
        $model = Writer::latest()->paginate(25);

        return view('writers::index', [
            'model' => $model,
        ]);
    }

    public function create()
    {
        return view('writers::create');
    }

    public function store()
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status'];

        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }

        $writer = Writer::create($data);

        //handle authors
        if (! empty($data['author'] ?? false)) {
            $writer->author()->sync($data['author']);
        }

        session()->flash('success', 'Író sikeresen létrehozva!');

        return response()->json(['success' => true, 'model' => $writer, 'return_url' => route('writers.index')]);
//        return redirect()->route('writers.index')->with('success', 'Író sikeresen létrehozva!');
    }

    public function edit(Writer $writer)
    {
        return view('writers::edit', [
            'model' => $writer,
        ]);
    }

    public function update(Writer $writer)
    {
        $data = request()->all();
        $this->validateRequest();
        $checks = ['status'];

        foreach ($checks as $check) {
            if (! ($data[$check] ?? false)) {
                $data[$check] = 0;
            }
        }
        $writer->update($data);

        //handle authors
        if (! empty($data['author'] ?? false)) {
            $writer->author()->sync($data['author']);
        } else {
            //delete
        }
        session()->flash('success', 'Író sikeresen frissítve!');

        return response()->json(['success' => true, 'model' => $writer, 'return_url' => route('writers.index')]);
//        return redirect()->route('writers.index', ['writer' => $writer->id]);
    }

    public function show(Writer $writer)
    {
        $consumptionReports = ConsumptionReport::get();
        $startDate = date('Y-m-d', strtotime('First day of this month')).' 00:00:00';
        $endDate = date('Y-m-d', strtotime('Last day of this month')).' 23:59:59';
        $actualConsumptionReport = AuthorConsumptionReport::getConsumptionReport($startDate, $endDate, true, $writer->id);
        $books = collect($actualConsumptionReport[$writer->id] ?? []);
        unset($books['details']);
        $reports = collect([]);

        foreach ($consumptionReports as $report) {
            if ($report->link_to_author_report) {
                foreach ($report->link_to_author_report ?? []  as $file) {
                    if (Str::startsWith($file, Str::slug($writer->title))) {
                        $reports->push([
                            'id' => $report->id,
                            'period' => $report->period,
                            'file' => $file,
                        ]);
                    }
                }
            }
        }

        return view('writers::show', [
            'model' => $writer,
            'reports' => $reports,
            'books' => $books,
        ]);
    }

    public function destroy(Writer $writer)
    {
        $writer->delete();

        return redirect()->route('writers.index')->with('success', 'Writer '.__('messages.deleted'));
    }

    protected function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
        ]);
    }

    public function search(Request $request)
    {
        $term = trim($request->q);
        $onlyBooks = $request->onlyBooks;

        $writers = Writer::select('id', 'title')
                           ->search($term)
                           ->latest();

        $writers = $writers->paginate(25);

        return response([
            'results' => ProductSelectResource::collection($writers),
            'pagination' => [
                'more' => $writers->currentPage() !== $writers->lastPage(),
            ],
        ]);
    }
}
