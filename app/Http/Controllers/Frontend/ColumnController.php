<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\StoreColumnRequest;
use App\Http\Requests\UpdateColumnRequest;
use App\Models\Column;
use App\Models\Head;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ColumnController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        abort_if(Gate::denies('column_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $columns = Column::with(['head'])->get();

        return view('frontend.columns.index', compact('columns'));
    }

    public function create()
    {
        abort_if(Gate::denies('column_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $heads = Head::pluck('object_head', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.columns.create', compact('heads'));
    }

    public function store(StoreColumnRequest $request)
    {
        $column = Column::create($request->all());

        return redirect()->route('frontend.columns.index');
    }

    public function edit(Column $column)
    {
        abort_if(Gate::denies('column_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $heads = Head::pluck('object_head', 'id')->prepend(trans('global.pleaseSelect'), '');

        $column->load('head');

        return view('frontend.columns.edit', compact('column', 'heads'));
    }

    public function update(UpdateColumnRequest $request, Column $column)
    {
        $column->update($request->all());

        return redirect()->route('frontend.columns.index');
    }
}
