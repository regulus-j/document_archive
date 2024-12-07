@extends('layouts.app')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h1 class="text-2xl font-semibold text-gray-900">Pending Documents</h1>
                    <a href="{{ route('documents.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-800 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        New Document
                    </a>
                </div>
            </div>

            <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
                <div class="col-span-1 md:col-span-2">
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3 px-6">No</th>
                                    <th scope="col" class="py-3 px-6">Title</th>
                                    <th scope="col" class="py-3 px-6">From Office</th>
                                    <th scope="col" class="py-3 px-6">To Office</th>
                                    <th scope="col" class="py-3 px-6">Status</th>
                                    <th scope="col" class="py-3 px-6">Date Created</th>
                                    <th scope="col" class="py-3 px-6">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $key => $document)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="py-4 px-6">{{ ++$key }}</td>
                                    <td class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">{{ $document->title }}</td>
                                    <td class="py-4 px-6">{{ $document->transaction->fromOffice->name }}</td>
                                    <td class="py-4 px-6">{{ $document->transaction->toOffice->name }}</td>
                                    <td class="py-4 px-6">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if($document->status->status == 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($document->status->status == 'received') bg-green-100 text-green-800
                                            @elseif($document->status->status == 'released') bg-blue-100 text-blue-800
                                            @endif">
                                            {{ $document->status->status }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">{{ $document->created_at->format('M d, Y') }}</td>
                                    <td class="py-4 px-6">
                                        @switch($document->status->status)
                                            @case('pending')
                                                <a href="{{ route('documents.changeStatus', ['document' => $document->id, 'status' => 'received']) }}" class="text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                                                    Receive
                                                </a>
                                                @break
                                            @case('received')
                                                <a href="{{ route('documents.confirmrelease', $document->id) }}" class="text-white bg-green-600 hover:bg-green-700 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                                                    Release
                                                </a>
                                                @break
                                            @case('released')
                                                <a href="{{ route('documents.show', $document->id) }}" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                                                    View
                                                </a>
                                                <a href="{{ route('documents.changeStatus', ['document' => $document->id, 'status' => 'retracted']) }}" class="text-white bg-purple-600 hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                                                    Retract
                                                </a>
                                                @break
                                            @case('completed')
                                                <a href="{{ route('documents.show', $document->id) }}" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2">
                                                    View
                                                </a>
                                                @break
                                            @case('retracted')
                                                <a href="{{ route('documents.confirmrelease', $document->id) }}">
                                                    Release
                                                </a>
                                                @break
                                        @endswitch
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $documents->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection