<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Tracking & Archiving System</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-800 transition-colors">Sign in</a>
                <a href="{{ route('register') }}"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors">Sign Up</a>
            </div>
        </div>
    </nav>

    <section class="bg-gray-100 py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center">
                <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
                    <h1 class="text-6xl lg:text-7xl font-bold mb-8" style="font-size: 3rem;">Document Tracking &
                        Archiving System</h1>
                    <p class="text-2xl text-gray-600 mb-10">Structured solution designed to monitor, manage, and
                        securely
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
                    <video controls class="w-full h-64 rounded-lg shadow-lg">
                        <source src="{{ asset('videos/intro.mp4') }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
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

    <section class="text-gray-600 body-font overflow-hidden" x-data="{ plan: 'yearly' }">
    <div class="container px-5 py-24 mx-auto">
        <div class="flex flex-col text-center w-full mb-20">
            <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-gray-900">Plan & Pricing</h1>
            <p class="lg:w-2/3 mx-auto leading-relaxed text-base text-gray-500">Choose the plan that fits your needs.</p>

            <!-- Toggle Buttons -->
            <div class="flex mx-auto border-2 border-blue-500 rounded overflow-hidden mt-6">
                <button 
                    class="py-1 px-4 focus:outline-none" 
                    :class="{ 'bg-blue-500 text-white': plan === 'monthly', 'text-gray-700': plan !== 'monthly' }" 
                    @click="plan = 'monthly'">
                    Monthly
                </button>
                <button 
                    class="py-1 px-4 focus:outline-none" 
                    :class="{ 'bg-blue-500 text-white': plan === 'yearly', 'text-gray-700': plan !== 'yearly' }" 
                    @click="plan = 'yearly'">
                    Yearly
                </button>
            </div>
        </div>

        <!-- Pricing Cards in a Row -->
        <div class="flex justify-center gap-6">
            <!-- Basic Plan -->
            <div class="p-4 w-full md:w-1/3">
                <div class="h-full p-6 rounded-lg border-2 border-blue-500 flex flex-col relative overflow-hidden">
                    <span class="bg-blue-500 text-white px-3 py-1 tracking-widest text-xs absolute right-0 top-0 rounded-bl">Popular</span>
                    <h2 class="text-sm tracking-widest title-font mb-1 font-medium">BASIC</h2>
                    <h1 class="text-5xl text-gray-900 leading-none flex items-center pb-4 mb-4 border-b border-gray-200">
                        <span x-text="plan === 'monthly' ? 'P 2,200' : 'P 24,000'"></span>
                        <span class="text-lg ml-1 font-normal text-gray-500" x-text="plan === 'monthly' ? '/month' : '/year'"></span>
                    </h1>
                    <p class="flex items-center text-gray-600 mb-2">
                        <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>Up to 5 users
                    </p>
                    <p class="flex items-center text-gray-600 mb-2">
                        <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>10 GB of storage
                    </p>
                    <p class="flex items-center text-gray-600 mb-2">
                        <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </span>Basic tracking and archiving
                    </p>

                    <button class="flex items-center mt-auto text-white bg-blue-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-blue-600 rounded">
                        Start Now
                        <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                            <path d="M5 12h14M12 5l7 7-7 7"></path>
                        </svg>
                    </button>
                    <p class="text-xs text-gray-500 mt-3">Best for professionals.</p>
                </div>
            </div>

              <!-- PREMIUM PLAN -->
              <div class="p-4 w-full md:w-1/3">
                    <div class="h-full p-6 rounded-lg border-2 border-gray-300 flex flex-col relative overflow-hidden">
                        <h2 class="text-sm tracking-widest title-font mb-1 font-medium">PREMIUM</h2>
                        <h1 class="text-5xl text-gray-900 leading-none flex items-center pb-4 mb-4 border-b border-gray-200">
                            <span x-text="plan === 'monthly' ? 'P 3,500' : 'P 38,000'"></span>
                            <span class="text-lg ml-1 font-normal text-gray-500" x-text="plan === 'monthly' ? '/month' : '/year'"></span>
                        </h1>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>Up to 20 users
                        </p>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>50 GB of storage
                        </p>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>Advanced tracking and archiving features
                        </p>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>Full search capabilities with filters
                        </p>
                        <p class="flex items-center text-gray-600 mb-6">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>Customizable user roles and permissions
                        </p>
                        <button class="flex items-center mt-auto text-white bg-blue-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-blue-600 rounded">
                            Start Now
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <p class="text-xs text-gray-500 mt-3">Perfect for special projects.</p>
                    </div>
                </div>

                <!-- Business Plan -->
                <div class="p-4 w-full md:w-1/3">
                    <div class="h-full p-6 rounded-lg border-2 border-gray-300 flex flex-col relative overflow-hidden">
                        <h2 class="text-sm tracking-widest title-font mb-1 font-medium">BUSINESS</h2>
                        <h1 class="text-5xl text-gray-900 leading-none flex items-center pb-4 mb-4 border-b border-gray-200">
                            <span x-text="plan === 'monthly' ? 'P 8,500' : 'P 90,000'"></span>
                            <span class="text-lg ml-1 font-normal text-gray-500" x-text="plan === 'monthly' ? '/month' : '/year'"></span>
                        </h1>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>Unlimited users
                        </p>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>Unlimited storage
                        </p>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>Full document tracking
                        </p>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>Real-time notifications and alerts
                        </p>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span> Office management
                        </p>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span> Customizable user roles & permissions
                        </p>
                        <p class="flex items-center text-gray-600 mb-2">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span> Generating of Reports
                        </p>
                        <p class="flex items-center text-gray-600 mb-6">
                            <span class="w-4 h-4 mr-2 inline-flex items-center justify-center bg-gray-400 text-white rounded-full flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" class="h-10 w-9 flex-none text-blue-600">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </span>Advanced security features
                        </p>
                        <button class="flex items-center mt-auto text-white bg-blue-500 border-0 py-2 px-4 w-full focus:outline-none hover:bg-blue-600 rounded">
                            Start Now
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-auto" viewBox="0 0 24 24">
                                <path d="M5 12h14M12 5l7 7-7 7"></path>
                            </svg>
                        </button>
                        <p class="text-xs text-gray-500 mt-3">Idea for Businesses</p>
                    </div>
                </div>

            <!-- Add other plans here with similar adjustments -->
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