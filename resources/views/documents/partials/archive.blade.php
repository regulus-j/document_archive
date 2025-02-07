<div class="bg-white shadow-xl rounded-lg overflow-hidden p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Archived Documents</h2>
        <div class="flex items-center mt-4 md:mt-0">
            <input type="text" id="archived-search" placeholder="Search archived documents..."
                class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
            <select id="archived-filter" class="ml-4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:border-blue-300">
                <option value="title">Title</option>
                <option value="uploader">Uploader</option>
                <option value="description">Description</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border-collapse table-auto bg-white">
            <thead>
                <tr class="bg-gray-50 text-left">
                    <th class="px-4 py-2 border-b">Title</th>
                    <th class="px-4 py-2 border-b">Uploader</th>
                    <th class="px-4 py-2 border-b">Archived Date</th>
                    <th class="px-4 py-2 border-b">Description</th>
                    <th class="px-4 py-2 border-b">Actions</th>
                </tr>
            </thead>
            <tbody id="archived-table-body">
                @forelse ($archivedDocuments as $document)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border-t">{{ $document->title }}</td>
                        <td class="px-4 py-2 border-t">{{ $document->uploader->name ?? 'Unknown' }}</td>
                        <td class="px-4 py-2 border-t">{{ $document->archived_at->format('Y-m-d H:i') }}</td>
                        <td class="px-4 py-2 border-t">{{ Str::limit($document->description, 50) }}</td>
                        <td class="px-4 py-2 border-t">
                            <a href="{{ route('documents.restore', $document->id) }}" class="text-blue-600 hover:underline">Restore</a>
                            <form action="{{ route('documents.delete', $document->id) }}" method="POST" class="inline-block ml-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-gray-500">No archived documents found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $archivedDocuments->links() }}
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('archived-search');
        const filterSelect = document.getElementById('archived-filter');
        const tableBody = document.getElementById('archived-table-body');

        searchInput.addEventListener('keyup', function () {
            const searchTerm = this.value.toLowerCase();
            const filterField = filterSelect.value;
            const rows = tableBody.querySelectorAll('tr');

            rows.forEach(row => {
                let cellText = '';

                switch(filterField) {
                    case 'title':
                        cellText = row.cells[0].textContent.toLowerCase();
                        break;
                    case 'uploader':
                        cellText = row.cells[1].textContent.toLowerCase();
                        break;
                    case 'description':
                        cellText = row.cells[3].textContent.toLowerCase();
                        break;
                    default:
                        cellText = row.cells[0].textContent.toLowerCase();
                }

                if (cellText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>