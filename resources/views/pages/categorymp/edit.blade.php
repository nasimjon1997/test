@extends('layout.app')

@section('breadcrumbs')
    <h2>Категория продуктов</h2>
    <ol class="breadcrumb bg-white">
        <li><a href="/">Главная</a></li>
        <li><a href="{{route('categorymp-index')}}">Категория продуктов</a></li>
        <li class="active"><a href="{{route('categorymp-edit', $category->category_id)}}">Редактирование</a></li>
    </ol>
@endsection

@section('content')

    <div class="x_content">
        <form id="demo-form2"  class="form-horizontal form-label-left" method="post" action="{{route('categorymp-update', $category->category_id)}}">
            @csrf
            <input name="_method" type="hidden" value="PATCH">
            <div class="form-group">
                <label>ID <span class="required">*</span></label>
                <input type="text" name="category_id" class="form-control col-md-7 col-xs-12" value="{{$category->category_id}}">
                @if($errors->has('category_id')) <p class="alert-danger">{{ $errors->first('category_id') }}</p> @endif
            </div>
            <div class="form-group">
                <label>Язык <span class="required">*</span></label>
                <select class="select2 form-control" name="language_id">
                    <option value="">-- Выберите значение --</option>
                    @foreach($lang as $l)
                        <option @if($category->language_id==$l->language_id) selected @endif value="{{$l->language_id}}">{{$l->name}}</option>
                    @endforeach
                </select>
                @if($errors->has('language_id')) <p class="alert-danger">{{ $errors->first('language_id') }}</p> @endif
            </div>
            <div class="form-group">
                <label >Название<span class="required">*</span></label>
                <input type="text" name="name" class="form-control col-md-7 col-xs-12" value="{{$category->name}}">
                @if($errors->has('name')) <p class="alert-danger">{{ $errors->first('name') }}</p> @endif
            </div>
            <div class="form-group">
                <label>Номер сортировки</label>
                <input type="text" class="form-control col-md-7 col-xs-12" name="sort" value="{{$category->sort}}">
                @if($errors->has('sort')) <p class="alert-danger">{{ $errors->first('sort') }}</p> @endif
            </div>
            <div class="form-group">
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </div>
            </div>

        </form>
    </div>

@endsection