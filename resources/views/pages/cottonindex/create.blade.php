@extends('layout.app')

@section('custom-css')
    <!-- Bootstrap-datepicker -->
    <link href="{{asset('css/bootstrap-datepicker.css')}}" rel="stylesheet">
@endsection

@section('breadcrumbs')
    <h2>Мировой хлопок</h2>
    <ol class="breadcrumb bg-white">
        <li><a href="/">Главная</a></li>
        <li><a href="{{route('cottonindex-index')}}">Мировой хлопок</a></li>
        <li class="active"><a href="{{route('cottonindex-create')}}"> Добавление новой записи</a></li>
    </ol>
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-4">
            <form id="demo-form2"  class="form-horizontal form-label-left" method="post" action="{{route('cottonindex-store')}}" autocomplete="off">
                @csrf
                <div class="form-group @if($errors->has('sana')) has-error @endif">
                    <label for="sana">Дата <span class="red">*</span></label>
                    <input type="text" id="sana" name="sana" class="form-control" value="{{old('sana')}}">
                    @if($errors->has('sana')) <p class="alert-danger">{{ $errors->first('sana') }}</p> @endif
                </div>
                <div class="form-group @if($errors->has('cottonindex')) has-error @endif">
                    <label for="cottonindex">Цена <span class="red">*</span></label>
                    <input type="text" id="cottonindex" name="cottonindex" class="form-control" value="{{old('cottonindex')}}">
                    @if($errors->has('cottonindex')) <p class="help-block">{{ $errors->first('cottonindex') }}</p> @endif
                </div>
                <div class="form-group">
                    <div class="text-right">
                        <button type="submit" class="btn btn-success">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('custom-script')
    <!-- Bootstrap-Datepicker -->
    <script src="{{asset('js/bootstrap-datepicker.js')}}"></script>

    <script>
        $('#sana').datepicker({
            format: "dd.mm.yyyy",
            weekStart: 1,
            todayBtn: "linked",
            language: "ru",
            autoclose: true,
            todayHighlight: true
        });
    </script>
@endsection