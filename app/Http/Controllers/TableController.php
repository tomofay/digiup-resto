<?php
namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
public function __construct()
{
    $this->middleware('auth');
    $this->middleware('admin')->except(['index']);
}


    public function index()
    {
        $tables = Table::paginate(10);
        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_number' => 'required|unique:tables|integer',
            'capacity' => 'required|integer|min:1|max:20',
            'location' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        Table::create($validated);

        return redirect()->route('tables.index')
            ->with('success', 'Meja berhasil ditambahkan!');
    }

    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $validated = $request->validate([
            'table_number' => 'required|unique:tables,table_number,' . $table->id . '|integer',
            'capacity' => 'required|integer|min:1|max:20',
            'location' => 'nullable|string',
            'status' => 'required|in:available,unavailable,maintenance',
            'description' => 'nullable|string',
        ]);

        $table->update($validated);

        return redirect()->route('tables.index')
            ->with('success', 'Data meja berhasil diperbarui!');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return back()->with('success', 'Meja berhasil dihapus!');
    }
}
