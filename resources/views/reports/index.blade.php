<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ... (keep the header and filters as they are) -->

            <!-- Reports Table -->
            <div class="overflow-hidden rounded-lg border border-gray-200 bg-white shadow">
                <table class="min-w-full divide-y divide-gray-200">
                    <!-- ... (keep the table header as it is) -->
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @foreach($reports as $report)
                            <tr>
                                <!-- ... (keep other table cells as they are) -->
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-3">
                                        <a href="{{ route('reports.show', $report) }}"
                                            class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open" class="text-green-600 hover:text-green-900">
                                                Download
                                            </button>
                                            <div x-show="open" @click.away="open = false"
                                                class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
                                                <div class="py-1" role="menu" aria-orientation="vertical"
                                                    aria-labelledby="options-menu">
                                                    <a href="{{ route('reports.download', ['report' => $report, 'format' => 'pdf']) }}"
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem">Download PDF</a>
                                                    <a href="{{ route('reports.download', ['report' => $report, 'format' => 'word']) }}"
                                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                                        role="menuitem">Download Word</a>
                                                </div>
                                            </div>
                                        </div>
                                        @can('delete', $report)
                                            <form action="{{ route('reports.destroy', $report) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- ... (keep the empty state and pagination as they are) -->
            </div>
        </div>
    </div>
</x-app-layout>