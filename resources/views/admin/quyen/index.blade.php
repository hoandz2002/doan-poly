@extends('Admin.templates.layout')
@section('content')
    <div class="row p-3">
        <button class="btn btn-primary"><a style="color: red"
                href=" {{ route('route_BE_Admin_Add_Quyen') }}">Thêm</a></button>
    </div>
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>{{ Session::get('error') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
        </div>
    @endif


    {{-- hiển thị message đc gắn ở session::flash('success') --}}

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <strong>{{ Session::get('success') }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">STT</th>
                <th scope="col">Tên Quyền</th>
                <th scope="col">Nhóm Quyền</th>

                <th scope="col">Sửa</th>
                <th scope="col">Xóa </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <th scope="row"> {{ $loop->iteration }}</th>
                    <td> {{ $item->ten }}</td>
                    <td> {{ $item->trang_thai }}</td>
                    <td>
                        <a href="{{ route('route_BE_Admin_Edit_Quyen', ['id' => $item->id]) }}">
                            <button class="btn btn-success"> Sửa</button></a>
                    </td>
                    <td>
                        <a href="{{ route('route_BE_Admin_Xoa_Quyen', ['id' => $item->id]) }}">
                            <button onclick="return confirm('Bạn có chắc muốn xóa ?')"
                            class="btn btn-danger"> Xóa</button></a>
                    </td>

                </tr>
            @endforeach

        </tbody>
    </table>
    <div class="">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            {{ $list->appends('extParams')->links() }}
        </div>
    </div>
@endsection
