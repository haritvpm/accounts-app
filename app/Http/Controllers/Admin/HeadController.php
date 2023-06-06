<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyHeadRequest;
use App\Http\Requests\StoreHeadRequest;
use App\Http\Requests\UpdateHeadRequest;
use App\Models\Head;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HeadController extends Controller
{
    use CsvImportTrait;

    public function index()
    {
        //abort_if(Gate::denies('head_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $heads = Head::with(['user'])->get();

        return view('admin.heads.index', compact('heads'));
    }

    public function create()
    {
        //abort_if(Gate::denies('head_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('ddo', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.heads.create', compact('users'));
    }

    public function store(StoreHeadRequest $request)
    {
        $head = Head::create($request->all() + [
            'user_id' => auth()->id(), //for ddo
        ] );

        return redirect()->route('admin.heads.index');
    }

    public function edit(Head $head)
    {
        //abort_if(Gate::denies('head_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('ddo', 'id')->prepend(trans('global.pleaseSelect'), '');

        $head->load('user');

        return view('admin.heads.edit', compact('head', 'users'));
    }

    public function update(UpdateHeadRequest $request, Head $head)
    {
        $head->update($request->all());

        return redirect()->route('admin.heads.index');
    }

    public function show(Head $head)
    {
       // abort_if(Gate::denies('head_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $head->load('user');

        return view('admin.heads.show', compact('head'));
    }

    public function destroy(Head $head)
    {
        //abort_if(Gate::denies('head_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $head->delete();

        return back();
    }

    public function massDestroy(MassDestroyHeadRequest $request)
    {
        $heads = Head::find(request('ids'));

        foreach ($heads as $head) {
            $head->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
