<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>{{ setting('hospital_name', 'Hospital Name') }} - Staff Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    .role-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .hospital-bg {
      background: linear-gradient(135deg, #f0f7ff 0%, #e6f0ff 100%);
    }
  </style>
</head>
<body class="min-h-screen hospital-bg">
  <!-- Full screen container -->
  <div class="flex flex-col items-center justify-center min-h-screen p-6">
    <!-- Hospital Branding -->
    <div class="mb-8 text-center">
      <div class="flex items-center justify-center mb-4">
        <div class=" text-white p-3  mr-4">
          <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="MediCare Logo" class="w-32 h-16" />
        </div>
        <div> 
                      
          <h1 class="text-3xl font-bold text-blue-800"> {{ setting('hospital_name', 'Hospital') }}</h1>
          <p class="text-blue-600">Advanced Healthcare Solutions</p>
        </div>
      </div>
    </div>

    <!-- Main content container -->
    <div class="flex flex-col md:flex-row items-center justify-center w-full max-w-6xl space-y-10 md:space-y-0 md:space-x-16">
      
      <!-- Login Form -->
      <div class="bg-white p-8 rounded-xl shadow-xl w-full md:w-96 border border-gray-100">
        <div class="flex items-center mb-6">
          <div class="bg-blue-100 p-2 rounded-lg mr-3">
            <i class="fas fa-user-md text-blue-600 text-xl"></i>
          </div>
          <h2 class="text-2xl font-bold text-gray-800">Staff Login Portal</h2>
        </div>
        
        <form method="POST" action="{{ route('login') }}" class="mb-4">
          @csrf
          <div class="mb-5">
            <label class="block text-gray-600 mb-2 text-sm font-medium" for="email">
              <i class="fas fa-envelope mr-2 text-blue-500"></i>Email or Staff ID
            </label>
            <input type="text" id="email" name="email" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent" placeholder="Enter your email or staff ID" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-sm" />
          </div>
          
          <div class="mb-5 relative">
            <label class="block text-gray-600 mb-2 text-sm font-medium" for="password">
              <i class="fas fa-lock mr-2 text-blue-500"></i>Password
            </label>
            <input type="password" name="password" id="password" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent" placeholder="Enter your password" />
            <button type="button" class="absolute right-3 top-10 text-gray-500 hover:text-blue-600" onclick="togglePassword()">
              <i class="far fa-eye"></i>
            </button>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-sm" />
          </div>
          
          <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
              <input id="remember_me" type="checkbox" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
              <label for="remember_me" class="ml-2 block text-sm text-gray-700">Remember me</label>
            </div>
            <a href="#" class="text-sm text-blue-600 hover:underline">Forgot password?</a>
          </div>
          
          <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg font-medium transition duration-200 shadow-md hover:shadow-lg">
            <i class="fas fa-sign-in-alt mr-2"></i> Login
          </button>
        </form>
        
        <div class="text-center text-sm text-gray-500 border-t border-gray-200 pt-4">
          Need help? Contact <a href="#" class="text-blue-600 hover:underline">IT Support</a>
        </div>
      </div>

      <!-- Role Selection -->
      <div class="w-full md:w-auto">
        <h3 class="text-xl font-semibold text-gray-700 mb-4 text-center md:text-left">
          <i class="fas fa-user-shield mr-2 text-blue-500"></i>Quick Role Access
        </h3>
        
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
          <!-- Admin Roles -->
          <button onclick="setCredentials('superadmin@medicare.com', 'password')" class="role-btn bg-purple-600 hover:bg-purple-700 text-white p-3 rounded-lg text-sm font-medium transition duration-200">
            <i class="fas fa-crown mr-1"></i> Super Admin
          </button>
          
          <button onclick="setCredentials('admin@medicare.com', 'password')" class="role-btn bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-lg text-sm font-medium transition duration-200">
            <i class="fas fa-user-cog mr-1"></i> Admin
          </button>
          
          <!-- Medical Staff -->
          <button onclick="setCredentials('doctor@medicare.com', 'password')" class="role-btn bg-blue-600 hover:bg-blue-700 text-white p-3 rounded-lg text-sm font-medium transition duration-200">
            <i class="fas fa-user-md mr-1"></i> Doctor
          </button>
          
          <button onclick="setCredentials('nurse@medicare.com', 'password')" class="role-btn bg-red-600 hover:bg-red-700 text-white p-3 rounded-lg text-sm font-medium transition duration-200">
            <i class="fas fa-user-nurse mr-1"></i> Nurse
          </button>
          
          <button onclick="setCredentials('surgeon@medicare.com', 'password')" class="role-btn bg-green-600 hover:bg-green-700 text-white p-3 rounded-lg text-sm font-medium transition duration-200">
            <i class="fas fa-syringe mr-1"></i> Surgeon
          </button>
          
          <!-- Support Staff -->
          <button onclick="setCredentials('pharmacist@medicare.com', 'password')" class="role-btn bg-amber-600 hover:bg-amber-700 text-white p-3 rounded-lg text-sm font-medium transition duration-200">
            <i class="fas fa-pills mr-1"></i> Pharmacist
          </button>
          
          <button onclick="setCredentials('lab@medicare.com', 'password')" class="role-btn bg-teal-600 hover:bg-teal-700 text-white p-3 rounded-lg text-sm font-medium transition duration-200">
            <i class="fas fa-microscope mr-1"></i> Lab Technician
          </button>
          
          <button onclick="setCredentials('reception@medicare.com', 'password')" class="role-btn bg-cyan-600 hover:bg-cyan-700 text-white p-3 rounded-lg text-sm font-medium transition duration-200">
            <i class="fas fa-headset mr-1"></i> Reception
          </button>
          
          <button onclick="setCredentials('account@medicare.com', 'password')" class="role-btn bg-gray-600 hover:bg-gray-700 text-white p-3 rounded-lg text-sm font-medium transition duration-200">
            <i class="fas fa-calculator mr-1"></i> Accounts
          </button>
        </div>
        
        <!-- <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-100">
          <h4 class="text-blue-800 font-medium mb-2 flex items-center">
            <i class="fas fa-info-circle mr-2"></i> Login Instructions
          </h4>
           <p class="text-sm text-blue-700">
            Select your role to auto-fill demo credentials. For security, please change your password regularly.
          </p> -->
        </div> 
      </div>
    </div>
    
    <!-- Footer -->
    <!-- <div class="mt-12 text-center text-sm text-gray-500">
      <p>© 2023 MediCare Hospital. All rights reserved.</p>
      <p class="mt-1">v2.5.1 | <a href="#" class="hover:underline">Privacy Policy</a> | <a href="#" class="hover:underline">Terms of Use</a></p>
    </div>
  </div> -->

  <script>
    function togglePassword() {
      const passwordField = document.getElementById("password");
      const icon = document.querySelector("#password + button i");
      if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.replace("fa-eye", "fa-eye-slash");
      } else {
        passwordField.type = "password";
        icon.classList.replace("fa-eye-slash", "fa-eye");
      }
    }

    // function setCredentials(email, password) {
    //   document.getElementById("email").value = email;
    //   document.getElementById("password").value = password;
    //   // Optional: Show visual feedback
    //   const roleBtns = document.querySelectorAll('.role-btn');
    //   roleBtns.forEach(btn => btn.classList.remove('ring-2', 'ring-blue-500'));
    //   event.currentTarget.classList.add('ring-2', 'ring-blue-500');
      
    //   // Small notification
    //   const notification = document.createElement('div');
    //   notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg animate-bounce';
    //   notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i> Credentials set for ${email}`;
    //   document.body.appendChild(notification);
    //   setTimeout(() => notification.remove(), 3000);
    // }
  </script>
</body>
</html>