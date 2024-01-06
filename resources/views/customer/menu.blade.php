@extends('template.master')
@section('title', 'Menu')
@section('content')

<h1>Menu</h1>
    <form action="{{ route('order.store', ['room' => $room->id, 'customer' => $customer->id]) }}" method="POST">
        @csrf
        @foreach ($menuItems as $menuItem)
            <div>
                <label>
                    <input type="checkbox" name="items[]" value="{{ $menuItem->id }}">
                    {{ $menuItem->name }} - ${{ $menuItem->price }}
                </label>
                <input type="number" name="quantities[{{ $menuItem->id }}]" min="1" value="1">
            </div>
        @endforeach
        <button type="submit">Place Order</button>
    </form>


@endsection
