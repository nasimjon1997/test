@extends('layout.app')

@section('breadcrumbs')
    <h2>Категория продуктов</h2>
    <ol class="breadcrumb bg-white">
        <li><a href="/">Главная</a></li>
        <li class="active"><a href="{{route('categories.index')}}">Категория продуктов</a></li>
    </ol>
@endsection

@section('content')

    @can('category-create')
        <div class="pull-right">
        <a href="{{route('categories.create')}}"><button class="btn btn-success">
                    <i class="fa fa-plus"></i> Новая запись</button></a>
        </div>
    @endcan

    <div class="x_content">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Таджикский</th>
                <th>Русский</th>
                <th>Английский</th>
                <th>Узбекский</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @php $nn = $categories->perPage()*($categories->currentPage()-1); @endphp
            @foreach($categories as $category)
                <tr>
                    <td>
                        {{++$nn}}
                    </td>
                    <td>
                        {{$category->name_taj}}
                    </td>
                    <td>
                        {{$category->name_rus}}
                    </td>
                    <td>
                        {{$category->name_eng}}
                    </td>
                    <td>
                        {{$category->name_uzb}}
                    </td>
                    <td>
                        @can('category-update')
                            <a href="{{route('categories.edit',$category->category_id)}}">
                                <i class="fa fa-edit"></i>
                            </a> |
                        @endcan
                        @can('category-delete')
                            <a onclick="myFunction({{$category->category_id}})">
                                <i class="fa fa-close"></i>
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <p class="pull-right">Всего записей: {{ $categories->total() }}</p>

        {!! $categories->links() !!}
    </div>

    <form method="post" type="hidden" id="delete" name="delete">
        @csrf
        <input name="_method" type="hidden" value="DELETE">
        <button type="submit" hidden></button>
    </form>

@endsection

@section('custom-script')

    <script type="text/javascript">
        function myFunction(id) {
            var r = confirm("Вы действительно хотите удалить");
            if (r == true) {
                var url='{{route('categories.destroy', ':id') }}';
                url=url.replace(':id', id);
                $('#delete').attr('action', url);
                $('#delete').submit();
            }
        };
    </script>
@endsection

