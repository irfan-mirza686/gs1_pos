<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zoom-in and Zoom-out Effect</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS for Zoom-in and Zoom-out Effect */
        .card {
            height: 100%;
            transition: transform 0.2s;
            cursor: pointer;
        }

        .card:hover {
            transform: scale(0.9);
            z-index: 1;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .card-img {
      width: 100%;
      height: 200px; /* Set height as needed */
      object-fit: cover; /* Maintain aspect ratio and crop as necessary */
    }
        header {
            background-color: #0D117F;
            /* Dark background color */
            color: #ffffff;
            /* Font color */
            padding: 0.5rem 0;
            /* Reduced padding */
        }

        .navbar-brand img {
            max-height: 40px;
            /* Adjust the logo height as needed */
        }

        .navbar {
            min-height: 60px;
            /* Adjust the top bar height as needed */
        }
    </style>
</head>

<body>

    <!-- Top Header -->
    <header class="text-white p-2" style="background-color: #0D117F; color: white;">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col">
                    <h1>GS1 Services</h1> <sup>Powered by GS1 Standards</sup> <!-- Title on the left -->
                </div>
                <div class="col text-end">
                    <a class="navbar-brand" href="#"><img src="{{asset('assets/uploads/Picture3.png')}}" alt="Logo"></a>
                    <!-- Logo on the right -->
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 mx-auto">
                <!-- Card with Image on Left and Title/Description on Right -->
                <a href="destination.html" class="card text-decoration-none">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{asset('assets/uploads/grocery.png')}}" class="img-fluid rounded-start h-100 card-img"
                                alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Grocery Store</h5>
                                <p class="card-text">This is a short description of the card. It could contain
                                    additional information about the card content.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mx-auto">
                <!-- Card with Image on Left and Title/Description on Right -->
                <a href="destination.html" class="card text-decoration-none">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{asset('assets/uploads/medicine.png')}}" class="img-fluid rounded-start h-100 card-img"
                                alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Pharmacy POS</h5>
                                <p class="card-text">This is a short description of the card. It could contain
                                    additional information about the card content.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mx-auto">
                <!-- Card with Image on Left and Title/Description on Right -->
                <a href="destination.html" class="card text-decoration-none">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{asset('assets/uploads/retail_pos.png')}}" class="img-fluid rounded-start h-100 card-img"
                                alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Retail SME</h5>
                                <p class="card-text">This is a short description of the card. It could contain
                                    additional information about the card content.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>


        <div class="row mt-2">
            <div class="col-md-4 mx-auto">
                <!-- Card with Image on Left and Title/Description on Right -->
                <a href="destination.html" class="card text-decoration-none">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{asset('assets/uploads/cafe.png')}}" class="img-fluid rounded-start h-100 card-img"
                                alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Cafeteria/Sandwich POS</h5>
                                <p class="card-text">This is a short description of the card. It could contain
                                    additional information about the card content.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-4 mx-auto">
                <!-- Card with Image on Left and Title/Description on Right -->
                <a href="destination.html" class="card text-decoration-none">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{asset('assets/uploads/restaurant.png')}}" class="img-fluid rounded-start h-100 card-img"
                                alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Restaurant POS</h5>
                                <p class="card-text">This is a short description of the card. It could contain
                                    additional information about the card content.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4 mx-auto">
                <!-- Card with Image on Left and Title/Description on Right -->
                <a href="destination.html" class="card text-decoration-none">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="{{asset('assets/uploads/pump-pos.png')}}" class="img-fluid rounded-start h-100 card-img"
                                alt="...">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Petrol Station POS</h5>
                                <p class="card-text">This is a short description of the card. It could contain
                                    additional information about the card content.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>


    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>

</html>
