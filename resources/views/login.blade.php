<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Full Screen Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="min-h-screen bg-gray-100">
  <!-- Full screen container -->
  <div class="flex flex-col items-center justify-center h-full p-6">
    <!-- Main content container -->
    <div class="flex flex-col md:flex-row items-center justify-center w-full max-w-6xl space-y-10 md:space-y-0 md:space-x-16">
      
      <!-- Login Form -->
      <div class="bg-white p-8 rounded-lg shadow-lg w-full md:w-96">
        <h2 class="text-3xl font-bold text-center mb-3">Login</h2>
        <p class="text-gray-500 text-center mb-6">Let's build something great</p>
        <form method="POST" action="{{ route('login') }}">
        @csrf
          <div class="mb-4">
            <label class="block text-gray-600 mb-1" for="user">E-mail or Phone Number</label>
            <input type="text" id="user" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300" placeholder="Enter here..." />
          </div>
          <div class="mb-4 relative">
            <label class="block text-gray-600 mb-1" for="password">Password</label>
            <input type="password" id="password" class="w-full p-3 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-300" placeholder="Enter your password" />
            <span class="absolute right-3 top-10 text-gray-500 cursor-pointer select-none" onclick="togglePassword()">👁</span>
          </div>
          <button type="submit" class="w-full bg-indigo-600 text-white p-3 rounded hover:bg-indigo-700 transition duration-200">Login</button>
        </form>
        <div class="text-right mt-2 text-sm">
          <a href="#" class="text-indigo-600 hover:underline">Forgot Password?</a>
        </div>
        <div class="mt-6 flex space-x-2">
          <button class="w-1/2 flex items-center justify-center border border-gray-300 p-2 rounded hover:bg-gray-200 transition text-xs">
            <img src="https://img.icons8.com/color/16/google-logo.png" alt="Google Logo" class="mr-2" /> Sign up with Google
          </button>
          <button class="w-1/2 flex items-center justify-center border border-gray-300 p-1 rounded hover:bg-gray-200 transition text-xs">
            <img src="https://img.icons8.com/ios-filled/16/facebook-new.png" alt="Facebook Logo" class="mr-2" /> Sign up with Facebook
          </button>
        </div>
        <p class="text-center text-gray-500 mt-4 text-sm">
          Don't have an account? 
          <a href="#" class="text-indigo-600 hover:underline">Register now</a>
        </p>
      </div>

      <!-- Role Badges -->
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 w-full md:w-auto">
        <span class="px-4 py-2 bg-green-900 text-white rounded text-center hover:bg-green-700 transition">SUPER ADMIN</span>
        <span class="px-4 py-2 bg-blue-900 text-white rounded text-center hover:bg-blue-700 transition">DOCTOR</span>
        <span class="px-4 py-2 bg-gray-900 text-white rounded text-center hover:bg-gray-700 transition">ACCOUNTANT</span>
        <span class="px-4 py-2 bg-red-700 text-white rounded text-center hover:bg-red-500 transition">CASH HANDLER</span>
        <span class="px-4 py-2 bg-purple-700 text-white rounded text-center hover:bg-purple-500 transition">RECEPTIONIST</span>
        <span class="px-4 py-2 bg-gray-700 text-white rounded text-center hover:bg-gray-500 transition">PHARMACIST</span>
        <span class="px-4 py-2 bg-green-700 text-white rounded text-center hover:bg-green-500 transition">LAB TECHNICIAN</span>
        <span class="px-4 py-2 bg-red-900 text-white rounded text-center hover:bg-red-700 transition">NURSE</span>
        <span class="px-4 py-2 bg-blue-800 text-white rounded text-center hover:bg-blue-600 transition">PATIENT</span>
        <span class="px-4 py-2 bg-teal-900 text-white rounded text-center hover:bg-teal-700 transition">FRONT SITE</span>
      </div>
    </div>
  </div>

  <script>
    function togglePassword() {
      const passwordField = document.getElementById("password");
      passwordField.type = passwordField.type === "password" ? "text" : "password";
    }
  </script>
</body>
</html>
