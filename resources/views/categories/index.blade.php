@extends('layouts.admin')

@section('title', 'Categories List')
@section('content-header', 'Categories List')
@section('content-actions')
    <a href="{{ route('categories.create') }}" class="btn btn-primary">Create Category</a>

@endsection
@section('css')
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2/sweetalert2.min.css') }}">
    <style>
        .pagination{
            justify-content: center;
        }
    </style>
@endsection
@section('content')
    <div class="card product-list">
        <div class="card-body">
            <form action="{{ route('categories.index') }}" method="get">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <select name="search" class="form-control select2">
                                <option selected="selected">-- search --</option>
                                @foreach ($search as $product)
                                    <option value="{{ $product->name }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Search</butaton>
                    </div>
                </div>
            </form>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Image</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $i = 1; ?>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>
                            <form action="{{ route('categories.index') }}" method="get">
                                <input type="hidden" name="search" value="{{ $category->name }}">
                                <button type="submit" style="background: none;border: none;">{{ $category->name }}</button>
                            </form>
                            </td>
                            <td><img class="product-img" src="{{ Storage::url($category->image) }}" alt=""></td>
                        <td>{{ $category->created_at }}</td>
                        <td>{{ $category->updated_at }}</td>
                        <td>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-primary btn-sm"><i
                                    class="fas fa-edit"></i></a>
                            <button class="btn btn-danger btn-delete btn-sm"
                                data-url="{{ route('categories.destroy', $category) }}"><i
                                    class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $categories->render() }}
    </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btn-delete', function() {
                $this = $(this);
                const swalWithBootstrapButtons = Swal.mixin({
                    customClass: {
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger'
                    },
                    buttonsStyling: false
                })

                swalWithBootstrapButtons.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to delete this product?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No',
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        $.post($this.data('url'), {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        }, function(res) {
                            $this.closest('tr').fadeOut(500, function() {
                                $(this).remove();
                            })
                        })
                    }
                })
            })
        })
    </script>
@endsection
