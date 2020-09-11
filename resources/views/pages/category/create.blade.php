@extends('layout.app')

@section('breadcrumbs')
    <h2>Категория продуктов</h2>
    <ol class="breadcrumb bg-white">
        <li><a href="/">Главная</a></li>
        <li><a href="{{route('categories.index')}}">Категория продуктов</a></li>
        <li class="active"><a href="{{route('categories.create')}}"> Добавление новой записи</a></li>
    </ol>
@endsection

@section('content')

    <div class="x_content">
        <form id="demo-form2"  class="form-horizontal form-label-left" method="post"
              action="{{route('categories.store')}}">
            @csrf
            <div class="form-group @if($errors->has("name_taj")) has-error @endif">
                <label>Таджикский <span class="required">*</span></label>
                <input type="text" name="name_taj" class="form-control col-md-7 col-xs-12" value="{{ old("name_taj") }}">
                @if($errors->has('name_taj')) <p class="help-block">{{ $errors->first('name_taj') }}</p> @endif
            </div>
            <div class="form-group @if($errors->has("name_rus")) has-error @endif">
                <label>Русский <span class="required">*</span></label>
                <input type="text" name="name_rus" class="form-control col-md-7 col-xs-12" value="{{ old("name_rus") }}">
                @if($errors->has('name_rus')) <p class="help-block">{{ $errors->first('name_rus') }}</p> @endif
            </div>
            <div class="form-group @if($errors->has("name_eng")) has-error @endif">
                <label >Английский <span class="required">*</span></label>
                <input type="text" name="name_eng" class="form-control col-md-7 col-xs-12" value="{{ old("name_eng") }}">
                @if($errors->has('name_eng')) <p class="help-block">{{ $errors->first('name_eng') }}</p> @endif
            </div>
            <div class="form-group @if($errors->has("name_uzb")) has-error @endif">
                <label>Узбекский <span class="required">*</span></label>
                <input type="text" class="form-control col-md-7 col-xs-12" name="name_uzb" value="{{ old("name_uzb") }}">
                @if($errors->has('name_uzb')) <p class="help-block">{{ $errors->first('name_uzb') }}</p> @endif
            </div>
            <div class="form-group">
                <div class="text-right">
                    <button type="submit" class="btn btn-success">Сохранить</button>
                </div>
            </div>
        </form>
    </div>

@endsection