<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DocArchive - Document Archiving System</title>
    <link rel="icon" href="{{ asset('images/logo.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .nav-link,
        .navbar-brand {
            color: #333;
            font-weight: 500;
        }

        .btn-get-started {
            background-color: #0065fc;
            color: white;
            border-radius: 4px;
            padding: 8px 16px;
        }

        .hero-section {
            padding: 80px 0;
            background-color: #f8f9fa;
        }

        .feature-section {
            padding: 60px 0;
        }

        .feature-image {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }

        footer {
            background-color: #333;
            color: white;
            padding: 40px 0;
        }

        footer a {
            color: #fff;
            text-decoration: none;
        }

        footer a:hover {
            color: #0d6efd;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="{{ asset('images/logo.svg') }}" alt="DocArchive" height="50">
                <span>DocArchive</span>
            </a>
            </ul>
            <div class="d-flex">
                <a href="{{ route('login') }}" class="btn me-2">Login</a>
                <a href="#" class="btn btn-get-started">Get Started</a>
            </div>
        </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 mb-4">Documents Archiving System with OCR</h1>
                    <p class="lead mb-4">Secure and organized. Capture all documents from the starting point by
                        retrieval. Categorizing and storing files to keep them accessible and protected from loss or
                        unauthorized access.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-primary">Get Started for free</a>
                        <a href="#" class="btn btn-outline-secondary">Learn More</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <img src="{{ asset('images/docu-manage.jpg') }}" alt="Document Management" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section class="feature-section">
        <div class="container">
            @foreach([['title' => 'Upload your files', 'image' => 'scan_document.jpg', 'heading' => 'From device & Scan', 'description' => 'Select the files from your computer or device that you want to upload. Or scan live your files to upload.'], ['title' => 'Looking for the files', 'image' => 'search.jpg', 'heading' => 'Search keyword/tag & Scan', 'description' => 'Enter specific keywords or tags to search documents. Or scan your physical documents to search'], ['title' => 'Secure Documents', 'image' => 'access.jpg', 'heading' => 'User Access & Permission', 'description' => 'Set specific access level thresholds, implementing structured roles and permissions access to maintain a secure and efficient document management environment.']] as $feature)
                <h2 class="text-center mb-5">{{ $feature['title'] }}</h2>
                <div class="row align-items-center mb-5">
                    <div class="col-md-6 @if($loop->index % 2 != 0) order-md-last @endif">
                        <img src="{{ asset('images/' . $feature['image']) }}" alt="{{ $feature['heading'] }}"
                            class="feature-image">
                    </div>
                    <div class="col-md-6">
                        <h3>{{ $feature['heading'] }}</h3>
                        <p>{{ $feature['description'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h5>DocArchive</h5>
                    <p>Document archiving system to safeguard files, memories, and manage documents you may access.</p>
                </div>
                @foreach(['Products', 'Support', 'Follow Us'] as $category)
                    <div class="col-md-3">
                        <h5>{{ $category }}</h5>
                        <ul class="list-unstyled">
                            @foreach(['Item 1', 'Item 2', 'Item 3', 'Item 4'] as $item)
                                <li><a href="#">{{ $item }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>