<x-app-layout>


    <div class="flex">

        <div class="flex flex-col items-center w-1/4" aria-label="Sidebar">
            <form action="{{route('buscar_articulos')}}" method="get">
                <x-input-label for="categoria_id" :value="'Categorias'" />
                <select id="categoria_id"
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full"
                    name="categoria_id" required>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}" {{ old('categoria_id') }}>
                            {{ $categoria->nombre }}
                        </option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('categoria_id')" class="mt-2" />
                    <x-primary-button class="ms-1 mt-2">
                        {{ __('Buscar') }}
                    </x-primary-button>
            </form>
        </div>

        <div class="p-2 grid grid-cols-3 gap-4 justify-center justify-items-center">
            @foreach ($articulos as $articulo)
                <div
                    class="p-6 max-w-xs min-w-full bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">
                    <a href="#">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $articulo->denominacion }}
                        </h5>
                    </a>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                        {{ $articulo->denominacion }}
                    </p>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                        Precio: {{ dinero($articulo->precio) }}
                    </p>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                        {{ $articulo->categoria->nombre }}
                    </p>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                        stock: {{ $articulo->stocks }}
                    </p>
                    <a href="{{ route('carrito.insertar', $articulo) }}"
                        class="inline-flex items-center py-2 px-3.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Añadir al carrito
                        <svg aria-hidden="true" class="ml-3 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </a>
                </div>
            @endforeach
        </div>

        @if (!$carrito->vacio())
            <aside class="flex flex-col items-center w-2/4" aria-label="Sidebar">
                <div class="overflow-y-auto py-4 px-3 bg-gray-50 rounded dark:bg-gray-800">
                    <table class="mx-auto text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <th scope="col" class="py-3 px-6">Descripción</th>
                            <th scope="col" class="py-3 px-6">Cantidad</th>
                        </thead>
                        <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($carrito->getLineas() as $id => $linea)
                                @php
                                    $articulo = $linea->getArticulo();
                                    $cantidad = $linea->getCantidad();
                                    $precio = $articulo->precio;
                                    $importe = $cantidad * $precio;
                                    $total += $importe;

                                @endphp
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="py-4 px-6">
                                        {{ $articulo->denominacion }}
                                    </td>

                                    <td class="py-4 px-6 text-center">
                                        {{ $cantidad }}
                                    </td>

                                    <td class="py-4 px-6 text-center"> <a
                                            href="{{ route('carrito.sumar', $articulo) }}"
                                            class="inline-flex items-center py-1 px-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            +
                                        </a>

                                        <a href="{{ route('carrito.eliminar', $articulo) }}"
                                            class="inline-flex items-center py-1 px-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            -
                                        </a>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <a href="{{ route('carrito.quitar', $articulo) }}"
                                            class="inline-flex items-center py-1 px-2.5 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                            -linea
                                        </a>

                                    </td>
                                    <td class="py-4 px-8">
                                        {{ dinero($importe) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <p class="py-4 px-6 text-center">Total: {{ dinero($total) }}</p>
                <div>
                    <a href="{{ route('carrito.vaciar') }}"
                        class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">Vaciar
                        carrito</a>
                    <a href="{{ route('comprar') }}""
                        class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-4 py-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-900">Comprar</a>
                </div>
            </aside>
        @endif
    </div>
</x-app-layout>
