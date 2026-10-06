<style>
/* Custom style to hide the scrollbar */
.hide-scrollbar::-webkit-scrollbar {
    width: 0px;
    background: transparent;
}

.hide-scrollbar {
    scrollbar-width: thin;
    scrollbar-color: transparent transparent;
}

/* Additional hover effects for smoother transitions */
.sidebar-item {
    transition: background-color 0.3s ease, transform 0.3s ease, box-shadow 0.3s ease;
}

.sidebar-item:hover {
    background-color: rgba(248, 113, 113, 0.1);
    /* Light hover background */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    /* Subtle shadow */
    transform: scale(1.05);
    /* Slight scaling effect */
}

.active:hover {
    background-color: rgba(248, 113, 113, 0.2);
    /* Darker background for active items */
    box-shadow: 0 4px 12px rgba(248, 113, 113, 0.2);
    /* Active item shadow */
}
</style>
{{--<aside id="sidebar"
    class="w-64 bg-white p-4 border-r shadow-md fixed h-full left-0 transform -translate-x-full md:translate-x-0 z-50 transition-transform duration-300 ease-in-out overflow-y-scroll hide-scrollbar">

    {{-- <aside id="sidebar" class="w-64 bg-white p-4 border-r shadow-md fixed h-full left-0 transform -translate-x-full md:translate-x-0 z-50 transition-transform duration-300 ease-in-out overflow-y-auto"> --}}
<nav class="mt-4">
    <ul>
        <!-- Dashboard -->
        <li
            class="p-3 text-pink-500 font-bold flex items-center space-x-2 sidebar-item active:border-r-4 active:border-pink-100 active:bg-pink-100 rounded-r-full">
            <a href="/dashboard">
                <i class="ph ph-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>


        <li>
            <a href="/appointment"
                class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
                <i class="ph ph-calendar"></i>
                <span>Appointments</span>
            </a>
        </li>

        {{-- <li class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
                <a href="/patient_detail"> <i class="ph ph-credit-card"></i> <span>Patient Smart Cards</span><a>
            </li> --}}

        <li>
            <!-- Hidden Checkbox for Toggle -->
            <input type="checkbox" id="cards-toggle" class="hidden peer">

            <!-- Label as Toggle Button -->
            <label for="cards-toggle" class="flex items-center space-x-2 p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 
               rounded cursor-pointer sidebar-item active:border-r-4 active:border-pink-100 active:bg-pink-100 
               rounded-r-full transition-all duration-300 ease-in-out">
                <i class="ph ph-credit-card"></i>
                <span>Patient Smart Cards</span>
            </label>

            <!-- Smooth Dropdown -->
            <ul
                class="max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out peer-checked:max-h-40 peer-checked:opacity-100 pl-8 ">
                <li>
                    <a href="/patient_detail"
                        class="flex items-center space-x-2 p-2 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded">
                        <i class="ph ph-user-circle"></i>
                        <span>Patient detail </span>
                    </a>
                </li>
                <li>
                    <a href="/new_patient"
                        class="flex items-center space-x-2 p-2 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded">
                        <i class="ph ph-user-plus"></i>
                        <span>Patient List</span>
                    </a>
                </li>
            </ul>
        </li>




        <li>
            <!-- Hidden Checkbox for Toggle -->
            <input type="checkbox" id="user-toggle" class="hidden peer">

            <!-- Label as Toggle Button -->
            <label for="user-toggle" class="flex items-center space-x-2 p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 
               rounded cursor-pointer sidebar-item active:border-r-4 active:border-pink-100 active:bg-pink-100 
               rounded-r-full transition-all duration-300 ease-in-out">
                <i class="ph ph-user"></i>
                <span>Users</span>
            </label>

            <!-- Smooth Dropdown -->
            <ul
                class="max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out peer-checked:max-h-40 peer-checked:opacity-100 pl-8 ">
                <li>
                    <a href="/user-list"
                        class="flex items-center space-x-2 p-2 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded">
                        <i class="ph ph-user-circle"></i>
                        <span>User List</span>
                    </a>
                </li>
                <li>
                    <a href="/add-user"
                        class="flex items-center space-x-2 p-2 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded">
                        <i class="ph ph-user-plus"></i>
                        <span>Add User</span>
                    </a>
                </li>
            </ul>
        </li>




        <li>
            <a href="/add-on"
                class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
                <i class="ph ph-plus-circle"></i> <span>AddOn</span>
                <a>
        </li>

        <!-- Radiology (with sub-menu) -->
        <li>
            <input type="checkbox" id="radiology-toggle" class="hidden peer">
            <label for="radiology-toggle"
                class="flex items-center space-x-2 p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded cursor-pointer sidebar-item active:border-r-4 active:border-pink-100 active:bg-pink-100 rounded-r-full">
                <i class="ph ph-grid-nine"></i>
                <span>Radiology</span>
            </label>
            <ul class="hidden peer-checked:block pl-8 mt-2">
                <li class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded">
                    X-Ray</li>
                <li class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded">MRI
                </li>
                <li class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded">CT
                    Scan</li>
            </ul>
        </li>
        <li>
            <!-- Hidden Checkbox for Toggle -->
            <input type="checkbox" id="role-toggle" class="hidden peer">

            <!-- Label as Toggle Button -->
            <label for="role-toggle" class="flex items-center space-x-2 p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 
               rounded cursor-pointer sidebar-item active:border-r-4 active:border-pink-100 active:bg-pink-100 
               rounded-r-full transition-all duration-300 ease-in-out">
                <i class="ph ph-users-three"></i>
                <span>Role Management</span>
            </label>

            <!-- Smooth Dropdown -->
            <ul
                class="max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out peer-checked:max-h-40 peer-checked:opacity-100 pl-8 mt-2">
                <li>
                    <a href="/create_role"
                        class="flex items-center space-x-2 p-2 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded">
                        <i class="ph ph-user-circle"></i>
                        <span>Create Role</span>
                    </a>
                </li>
                <li>
                    <a href="/manage_role"
                        class="flex items-center space-x-2 p-2 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded">
                        <i class="ph ph-user-plus"></i>
                        <span>Create Permission</span>
                    </a>
                </li>
                <li>
                    <a href="/role_permission"
                        class="flex items-center space-x-2 p-2 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded">
                        <i class="ph ph-shield-check"></i>
                        <span>Role & Permission</span>
                    </a>
                </li>
            </ul>
        </li>


        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-money"></i>
            <span>Hospital Charges</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-file-text"></i>
            <span>Documents</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-drop"></i>
            <span>Blood Banks</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <a href="{{ route('ipdpatient') }}" class="flex items-center space-x-2 w-full">
                <i class="ph ph-bed"></i>
                <span>IPD Patient In</span>
            </a>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <a href="{{ route('opdpatient') }}" class="flex items-center space-x-2 w-full">
                <i class="ph ph-stethoscope"></i>
                <span>OPD-Patient Out</span>
            </a>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <a href="/schedule" class="hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2">
                <i class="ph ph-calendar"></i>
                <span>Schedule</span>
            </a>
        </li>


        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <a href="/doctor_schedule" class="flex items-center space-x-2 w-full">
                <i class="ph ph-user-circle"></i>
                <span>Doctors</span>
            </a>
        </li>


        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-prescription"></i>
            <span>Prescriptions</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-heartbeat"></i>
            <span>Diagnosis</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-buildings"></i>
            <span>Front Offices</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-receipt"></i>
            <span>Billings</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-bed"></i>
            <span>Bed Management</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-chat-circle"></i>
            <span>Enquiries</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-monitor"></i>
            <span>Front CMS</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-currency-dollar"></i>
            <span>Finances</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-pill"></i>
            <span>Medicines</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-users"></i>
            <span>Patients</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-package"></i>
            <span>Inventories</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-video"></i>
            <span>Live Consultations</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-flask"></i>
            <span>Pathology</span>
        </li>



        <!-- Blood Banks -->
        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item active:border-r-4 active:border-pink-100 active:bg-pink-100 rounded-r-full">
            <i class="ph ph-drop"></i>
            <span>Blood Banks</span>
        </li>
        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-chart-line"></i> <span>Reports</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-gear"></i> <span>Services</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-sliders-horizontal"></i> <span>Setting</span>
        </li>

        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-syringe"></i> <span>Vaccination</span>
        </li>
        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class="ph ph-envelope-simple"></i> <span>SMS/Mail</span>
        </li>
        <li
            class="p-3 text-gray-500 hover:font-bold hover-text-purple-color hover:bg-pink-100 rounded flex items-center space-x-2 sidebar-item">
            <i class=""></i> <span></span>
        </li>

    </ul>
    <br>
</nav>
</aside>--}}

