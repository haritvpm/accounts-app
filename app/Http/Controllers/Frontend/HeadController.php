<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyHeadRequest;
use App\Http\Requests\StoreHeadRequest;
use App\Http\Requests\UpdateHeadRequest;
use App\Models\Head;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HeadController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('head_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $heads = Head::all();

        return view('frontend.heads.index', compact('heads'));
    }

    public function create()
    {
        abort_if(Gate::denies('head_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.heads.create');
    }

    public function store(StoreHeadRequest $request)
    {
        $head = Head::create($request->all());

        return redirect()->route('frontend.heads.index');
    }

    public function edit(Head $head)
    {
        abort_if(Gate::denies('head_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.heads.edit', compact('head'));
    }

    public function update(UpdateHeadRequest $request, Head $head)
    {
        $head->update($request->all());

        return redirect()->route('frontend.heads.index');
    }

    public function show(Head $head)
    {
        abort_if(Gate::denies('head_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('frontend.heads.show', compact('head'));
    }

    public function destroy(Head $head)
    {
        abort_if(Gate::denies('head_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $head->delete();

        return back();
    }

    public function massDestroy(MassDestroyHeadRequest $request)
    {
        Head::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}