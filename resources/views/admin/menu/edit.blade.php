@extends('template.master')
@section('title', 'Edit Menu Item')
@section('content')
    <h1>Edit Menu Item</h1>
    <form action="{{ route('menu.update', $menuItem->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label>Name:</label>
        <input type="text" name="name" value="{{ $menuItem->name }}" required><br>
        <label>Price:</label>
        <input type="number" name="price" step="0.01" value="{{ $menuItem->price }}" required><br>
        <label>Image:</label>
        <input type="file" name="image" accept="image/*"><br>
        <button type="submit">Update</button>
    </form>
@endsection
