@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.head.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.heads.update", [$head->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="object_head">{{ trans('cruds.head.fields.object_head') }}</label>
                <input class="form-control {{ $errors->has('object_head') ? 'is-invalid' : '' }}" type="text" name="object_head" id="object_head" value="{{ old('object_head', $head->object_head) }}" required>
                @if($errors->has('object_head'))
                    <span class="text-danger">{{ $errors->first('object_head') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.head.fields.object_head_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="object_head_name">{{ trans('cruds.head.fields.object_head_name') }}</label>
                <input class="form-control {{ $errors->has('object_head_name') ? 'is-invalid' : '' }}" type="text" name="object_head_name" id="object_head_name" value="{{ old('object_head_name', $head->object_head_name) }}" required>
                @if($errors->has('object_head_name'))
                    <span class="text-danger">{{ $errors->first('object_head_name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.head.fields.object_head_name_helper') }}</span>
            </div>
          <!--   <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.head.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $head->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.head.fields.user_helper') }}</span>
            </div> -->
            <div class="form-group">
                <label class="required" for="detail_head">{{ trans('cruds.head.fields.detail_head') }}</label>
                <input class="form-control {{ $errors->has('detail_head') ? 'is-invalid' : '' }}" type="text" name="detail_head" id="detail_head" value="{{ old('detail_head', $head->detail_head) }}" required>
                @if($errors->has('detail_head'))
                    <span class="text-danger">{{ $errors->first('detail_head') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.head.fields.detail_head_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection