@extends('layouts.app')

@section('navbar')
<nav class="flex justify-between items-center bg-gray-800 text-white px-4 py-2">
    <div class="flex space-x-4">
        <a href="{{ route('agent.dashboard') }}" class="hover:text-indigo-400">Dashboard</a>
        <a href="#" class="hover:text-indigo-400">Agent Info</a>
    </div>
    <div class="flex items-center space-x-4">
        @if ($agent)
            <form action="{{ route('agent.logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 px-3 py-1 rounded">Logout</button>
            </form>
            <span class="text-gray-300">{{ $agent->name }}</span>
        @endif
    </div>
</nav>
@endsection

@section('sidebar')
<ul> 
    
    <li><a href="#" class="block text-white hover:bg-indigo-600 p-2 rounded">Option 1</a></li>
    <li><a href="#" class="block text-white hover:bg-indigo-600 p-2 rounded">Option 2</a></li>
    <li><a href="#" class="block text-white hover:bg-indigo-600 p-2 rounded">Option 3</a></li>
</ul>
@endsection

@section('content')
<div class="text-center">
    @if ($agent)
        <h1 class="text-2xl font-bold mb-4">Hello, {{ $agent->name }}!</h1>
    @endif
    <div>
        @include('mikrotik.dashboard')
    </div>
</div>
@endsection
