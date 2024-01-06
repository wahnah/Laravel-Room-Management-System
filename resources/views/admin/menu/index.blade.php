@extends('template.master')
@section('title', 'Menu Items')
@section('content')
<h1>Menu Items</h1>
<div class="col-lg-12">
    <div class="card shadow-sm border">
        <div class="table-responsive">
            <table class="table table-sm table-hover">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($menuItems as $menuItem)
                <tr>
                    <td><img src="{{ $menuItem->image }}"></td>
                    <td>{{ $menuItem->name }}</td>
                    <td>ZK {{ $menuItem->price }}</td>
                    <td>
                        <a href="{{ route('menu.edit', $menuItem->id) }}">Edit</a>
                    <form action="{{ route('menu.destroy', $menuItem->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
</table>
</div>
    </div>
</div>
<a href="{{ route('menu.create') }}">Add New Item</a>
@endsection
