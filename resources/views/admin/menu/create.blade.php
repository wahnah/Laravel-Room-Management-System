@extends('template.master')
@section('title', 'Create Menu Item')
@section('content')
    <h1>Create Menu Item</h1>
    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Price:</label>
        <input type="number" name="price" step="0.01" required><br>
        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required><br>
        <button type="submit">Create</button>
    </form>
@endsection
