<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Products') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="w-full">
                        <thead>
                            <tr>
                                <th class="text-left">Product Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->Name }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <x-primary-button class="mb-6 mx-6">
                    <a href="{{ route('products.create') }}">
                        {{ __('Create Product') }}
                    </a>
                </x-primary-button>
            </div>
        </div>
    </div>
</x-app-layout>
