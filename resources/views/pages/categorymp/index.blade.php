@extends('layout.app')

@section('breadcrumbs')
    <h2>Категория продуктов</h2>
    <ol class="breadcrumb bg-white">
        <li><a href="/">Главная</a></li>
        <li class="active"><a href="{{route('categorymp-index')}}">Категория продуктов</a></li>
    </ol>
@endsection

@section('content')

    @can('categorymp-create')
        <div class="pull-right">
        <a href="{{route('categorymp-create')}}"><button class="btn btn-success">
                    <i class="fa fa-plus"></i> Новая запись</button></a>
        </div>
    @endcan

    <div class="x_content">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Язык</th>
                <th>Название</th>
                <th>Номер сортировки</th>
                <th>Добавил</th>
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
                        {{$category->category_id}}
                    </td>
                    <td>
                        {{$category->lang->name}}
                    </td>
                    <td>
                        {{$category->name}}
                    </td>
                    <td>
                        {{$category->sort}}
                    </td>
                    <td>
                        {{$category->user->fio}}
                    </td>
                    <td>
                        @can('categorymp-update')
                            <a href="{{route('categorymp-edit',$category->category_id)}}">
                                <i class="fa fa-edit"></i>
                            </a> |
                        @endcan
                        @can('categorymp-delete')
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
                var url='{{route('categorymp-destroy', ':id') }}';
                url=url.replace(':id', id);
                $('#delete').attr('action', url);
                $('#delete').submit();
            }
        };
    </script>
@endsection

