@extends('Admin.templates.layout')
@section('content')
    <div class="row p-3">
        <button class="btn btn-primary"><a style="color: red"
                href=" {{ route('route_BE_Admin_Add_Phuong_Thuc_Thanh_Toan') }}">Thêm</a></button>
    </div>
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible" role="alert">
            <strong>{{ Session::get('error') }}</strong>
        </div>
    @endif


    {{-- hiển thị message đc gắn ở session::flash('success') --}}

    @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible" role="alert">
            <strong>{{ Session::get('success') }}</strong>
        </div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">STT</th>
                <th scope="col">Hình thức thanh toán </th>


                <th scope="col">Sửa</th>
                <th scope="col">Xóa </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $key => $item)
                <tr>
                    <th scope="row"> {{ $loop->iteration }}</th>
                    <td> {{ $item->ten }}</td>
                    <td> <button class="btn btn-warning"><a
                                href="{{ route('route_BE_Admin_Edit_Phuong_Thuc_Thanh_Toan', ['id' => $item->id]) }}"> Sửa
                            </a></button></td>
                    <td> <button class="btn btn-danger"><a
                                href="{{ route('route_BE_Admin_Xoa_Phuong_Thuc_Thanh_Toan', ['id' => $item->id]) }}">
                                Xóa</a></button></td>

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
