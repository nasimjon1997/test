@extends('layout.app')

@section('breadcrumbs')
    <h2>Категория продуктов</h2>
    <ol class="breadcrumb bg-white">
        <li><a href="/">Главная</a></li>
        <li><a href="{{route('categorymp-index')}}">Категория продуктов</a></li>
        <li class="active"><a href="{{route('categorymp-create')}}"> Добавление новой записи</a></li>
    </ol>
@endsection

@section('content')

    <div class="x_content">
        <form id="demo-form2"  class="form-horizontal form-label-left" method="post"
              action="{{route('categorymp-store')}}">
            @csrf
            <div class="form-group @if($errors->has("category_id")) has-error @endif">
                <label>ID <span class="required">*</span></label>
                <input type="text" name="category_id" class="form-control col-md-7 col-xs-12" value="{{ old("category_id") }}">
                @if($errors->has('category_id')) <p class="help-block">{{ $errors->first('category_id') }}</p> @endif
            </div>
            <div class="form-group @if($errors->has("language_id")) has-error @endif">
                <label>Язык <span class="required">*</span></label>
                <select class="select2 form-control" name="language_id">
                    <option value="">-- Выберите значение --</option>
                    @foreach($lang as $l)
                        <option @if( old('language_id')==$l->language_id) selected @endif value="{{$l->language_id}}">{{$l->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('language_id')) <p class="help-block">{{ $errors->first('language_id') }}</p> @endif
            </div>
            <div class="form-group @if($errors->has("name")) has-error @endif">
                <label >Название <span class="required">*</span></label>
                <input type="text" name="name" class="form-control col-md-7 col-xs-12" value="{{ old("name") }}">
                @if($errors->has('name')) <p class="help-block">{{ $errors->first('name') }}</p> @endif
            </div>
            <div class="form-group @if($errors->has("sort")) has-error @endif">
                <label>Номер сортировки </label>
                <input type="text" class="form-control col-md-7 col-xs-12" name="sort" value="{{ old("sort") }}">
                @if($errors->has('sort')) <p class="help-block">{{ $errors->first('sort') }}</p> @endif
            </div>
            <input type="hidden" name="added_by" value="{{Auth::user()->id}}">
            <div class="form-group">
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </div>
            </div>
        </form>
    </div>

@endsection