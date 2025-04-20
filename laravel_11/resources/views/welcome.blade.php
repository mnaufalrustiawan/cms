<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar Example</title>
    <script src="https://cdn.tailwindcss.com"></script> <!-- Add Tailwind CDN -->
</head>
<body class="bg-gray-100">

    @php
        $navbar = \App\Models\Navbar::first();
    @endphp

    <!-- Navbar -->
    <nav class="bg-gray-800 text-white py-4 px-6 flex items-center justify-between">
        <div class="flex items-center">
            <img src="{{ $navbar->logo ? asset('storage/' . $navbar->logo) : '' }}" alt="Logo" class="h-10 w-auto mr-4">
            <div>
                <h1 class="text-2xl font-bold">{{ $navbar->organization_name }}</h1>
                <h2 class="text-lg">{{ $navbar->cabinet_name }}</h2>
            </div>
        </div>
        <ul class="flex space-x-6 items-center">
            @php
                $navLinks = \App\Models\NavLink::where('is_active', true)->orderBy('order')->get();
            @endphp
            @foreach ($navLinks as $link)
                <li>
                    <a href="{{ $link->url }}" class="text-white hover:text-gray-400 transition-colors duration-300">{{ $link->name }}</a>
                </li>
            @endforeach
        </ul>
        <div>
            <!-- You can add links or buttons here if needed -->
            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-300">Login</button>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        <h3 class="text-3xl font-semibold text-center mb-8">Welcome to the Website</h3>
        <p class="text-lg text-gray-700">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor.
        </p>
    </div>

</body>
</html>
