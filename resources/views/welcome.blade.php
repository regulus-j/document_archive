<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Tracking & Archiving System</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}">
    @vite('resources/css/app.css')
</head>

<body class="font-sans">
    <nav class="bg-white shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="#" class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.svg') }}" alt="DocArchive" class="h-12">
                <span class="text-xl font-semibold text-gray-800">DocArchive</span>
            </a>
            <div class="space-x-4">
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 transition-colors">Login</a>
                <a href="{{ route('register') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Sign Up</a>
            </div>
        </div>
    </nav>

    <section class="bg-gray-100 py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center">
                <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
                    <h1 class="text-4xl lg:text-5xl font-bold mb-6">Document Tracking & Archiving System</h1>
                    <p class="text-xl text-gray-600 mb-8">Structured solution designed to monitor, manage, and securely
                        store documents throughout their lifecycle, ensuring easy retrieval, quick and secure access to
                        documents by scanning or searching for keywords</p>
                    <div class="space-x-4">
                        <a href="#"
                            class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">Get
                            Started for free</a>
                        <a href="#"
                            class="border border-gray-300 text-gray-600 px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors">Learn
                            More</a>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <img src="{{ asset('images/docu-manage.jpg') }}" alt="Document Management"
                        class="w-full rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-10">Upload your Documents</h2>
            <div class="flex flex-wrap items-center">
                <div class="w-full md:w-1/2 mb-10 md:mb-0">
                    <img src="{{ asset('images/scan_document.jpg') }}" alt="From device & Scan"
                        class="w-full rounded-lg shadow-lg">
                </div>
                <div class="w-full md:w-1/2 md:pl-10">
                    <h3 class="text-2xl font-semibold mb-4">From device & Scan</h3>
                    <p class="text-gray-600">Select the files from your computer or device that you want to upload. Or
                        scan the your files to upload.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-10">Tracking of Documents</h2>
            <div class="flex flex-wrap items-center">
                <div class="w-full md:w-1/2 mb-10 md:mb-0 md:order-last">
                    <img src="{{ asset('images/search.jpg') }}" alt="Locations & Audit logs"
                        class="w-full rounded-lg shadow-lg">
                </div>
                <div class="w-full md:w-1/2 md:pr-10">
                    <h3 class="text-2xl font-semibold mb-4">Locations & Audit logs</h3>
                    <p class="text-gray-600">Tracks document locations, updates, approvals, rejections, recording who
                        accessed the document, when, and for how long</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-10">Archive Documents</h2>
            <div class="flex flex-wrap items-center">
                <div class="w-full md:w-1/2 mb-10 md:mb-0">
                    <img src="{{ asset('images/access.jpg') }}" alt="Secure Documents"
                        class="w-full rounded-lg shadow-lg">
                </div>
                <div class="w-full md:w-1/2 md:pl-10">
                    <h3 class="text-2xl font-semibold mb-4">Secure Documents</h3>
                    <p class="text-gray-600">Store document in storing section to safeguard sensitive information,
                        implementing structured roles and authorize access to maintain a secure and efficient document.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-10">Looking for the files</h2>
            <div class="flex flex-wrap items-center">
                <div class="w-full md:w-1/2 mb-10 md:mb-0 md:order-last">
                    <img src="{{ asset('images/keyword.jpg') }}" alt="Search keyword/tag & Scan"
                        class="w-full rounded-lg shadow-lg">
                </div>
                <div class="w-full md:w-1/2 md:pr-10">
                    <h3 class="text-2xl font-semibold mb-4">Search keyword/tag & Scan</h3>
                    <p class="text-gray-600">Enter specific keywords or tags to search documents. Or scan your physical
                        documents to search</p>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-[#1e2837] text-white py-16">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap justify-between -mx-4">
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <h5 class="text-2xl font-medium mb-4">DocArchive</h5>
                    <p class="text-gray-300 leading-relaxed">
                        A document tracking & archiving system is software that secure manage document lifecycle from
                        uploading to archival. Tracks document locations, updates, approvals, rejections, and recording
                        who accessed the document.
                    </p>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <h5 class="text-2xl font-medium mb-4">Products</h5>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Features</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Solutions</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Enterprise</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Pricing</a></li>
                    </ul>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <h5 class="text-2xl font-medium mb-4">Support</h5>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Terms & Service</a>
                        </li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Privacy</a></li>
                    </ul>
                </div>
                <div class="w-full sm:w-1/2 lg:w-1/4 px-4 mb-8">
                    <h5 class="text-2xl font-medium mb-4">Follow Us</h5>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Twitter</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">LinkedIn</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Instagram</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>