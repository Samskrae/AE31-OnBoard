@extends('layouts.app')

@section('content')
<body class="bg-gray-100 text-gray-900">
    <div class="p-15" id="bordeSuperior"></div>
    <div class="mt-10">
        <div class="bg-gray-200 p-10 justify-left align-left rounded-2xl shadow-lg mx-6">
            <h1 class="text-4xl border-b-4 border-gray-400 w-full pb-2 text-left font-bold">¡Regístrate!</h1>

            {{-- Mostrar mensaje de éxito --}}
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mt-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="flex flex-row items-center mt-6">
                <form action="{{ route('guardar.csv') }}" method="POST" class="w-full max-w-lg mx-auto mt-6 flex flex-col gap-4">
                    @csrf

                    <label for="name" class="block text-lg font-medium text-gray-700">Nombre Completo:</label>
                    <input type="text" id="name" name="name" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">

                    <label for="email" class="block text-lg font-medium text-gray-700 mt-4">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">

                    <label for="password" class="block text-lg font-medium text-gray-700 mt-4">Contraseña:</label>
                    <input type="password" id="password" name="password" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">

                    <label for="date" class="block text-lg font-medium text-gray-700 mt-4">Fecha de Nacimiento:</label>
                    <input type="date" id="date" name="date" required class="mt-1 p-2 w-full border border-gray-300 rounded-md">

                    <label for="bio" class="block text-lg font-medium text-gray-700 mt-4">Biografía:</label>
                    <textarea id="bio" name="bio" rows="4" class="mt-1 p-2 w-full border border-gray-300 rounded-md"></textarea>

                    <button type="submit" class="mt-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                        Registrarse
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
@endsection
