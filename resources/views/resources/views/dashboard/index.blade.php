@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto p-4">
    <!-- Stats Cards -->
    <!-- <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
            <div class="w-12 h-12 flex items-center justify-center bg-purple-200 text-purple-600 rounded-full">
                <i class="fa fa-users"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-xl font-bold">Bill Amount</h2>
                <p class="text-gray-600">AR$ 79.22M</p>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
            <div class="w-12 h-12 flex items-center justify-center bg-pink-200 text-pink-600 rounded-full">
                <i class="fa fa-user-check"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-xl font-bold">Advance</h2>
                <p class="text-gray-600">AR$ 5.68M</p>
            </div>
        </div>

        <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
            <div class="w-12 h-12 flex items-center justify-center bg-blue-200 text-blue-600 rounded-full">
                <i class="fa fa-user-plus"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-xl font-bold">Payment</h2>
                <p class="text-gray-600">AR$ 89.32M</p>
            </div>
        </div> -->

        <!-- <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
            <div class="w-12 h-12 flex items-center justify-center bg-green-200 text-green-600 rounded-full">
                <i class="fa fa-bell"></i>
            </div>
            <div class="ml-4">
                <h2 class="text-xl font-bold">Invoice</h2>
                <p class="text-gray-600">AR$ 99.32M</p>
            </div>
        </div>
    </div> -->
    <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-5">
                        <!-- Total Users -->
                        <!-- <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-purple-200 text-purple-600 rounded-full">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold">Total Users</h2>
                                <p class="text-gray-600">1,234 Users</p>
                            </div>
                        </div> -->

                        <!-- Active Users -->
                        <!-- <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-pink-200 text-pink-600 rounded-full">
                                <i class="fa fa-user-check"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold">Active Users</h2>
                                <p class="text-gray-600">567 Online</p>
                            </div>
                        </div> -->

                        <!-- New Users -->
                        <!-- <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-blue-200 text-blue-600 rounded-full">
                                <i class="fa fa-user-plus"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold">New Users</h2>
                                <p class="text-gray-600">120 Today</p>
                            </div>
                        </div> -->

                        <!-- Total Subscribers -->
                        <!-- <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-green-200 text-green-600 rounded-full">
                                <i class="fa fa-bell"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold"> Subscribers</h2>
                                <p class="text-gray-600">3,456</p>
                            </div>
                        </div>
                    </div> -->
 <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-5">
                        <!-- Total Users -->
                        <!-- <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-purple-200 text-purple-600 rounded-full">
                                <i class="fa fa-users"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold">Total Users</h2>
                                <p class="text-gray-600">1,234 Users</p>
                            </div>
                        </div> -->

                        <!-- Active Users -->
                        <!-- <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-pink-200 text-pink-600 rounded-full">
                                <i class="fa fa-user-check"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold">Active Users</h2>
                                <p class="text-gray-600">567 Online</p>
                            </div>
                        </div> -->

                        <!-- New Users -->
                        <!-- <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-blue-200 text-blue-600 rounded-full">
                                <i class="fa fa-user-plus"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold">New Users</h2>
                                <p class="text-gray-600">120 Today</p>
                            </div>
                        </div> -->

                        <!-- Total Subscribers -->
                        <!-- <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
                            <div
                                class="w-12 h-12 flex items-center justify-center bg-green-200 text-green-600 rounded-full">
                                <i class="fa fa-bell"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-xl font-bold"> Subscribers</h2>
                                <p class="text-gray-600">3,456</p>
                            </div>
                        </div>
                    </div> -->
    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 mt-6">
        <!-- Chart Card -->
        <!-- <div id="chartCard" class="lg:col-span-9 bg-white p-4 rounded-lg shadow">
            <div class="flex justify-between items-center border-b pb-2">
                <h3 class="text-lg font-bold text-gray-700">Earnings & Expenses</h3>
            </div>
            <div id="chartCardBody" class="pt-4">
                <canvas id="lineChart" class="w-full h-64"></canvas>
            </div>
        </div> -->

        <!-- Side Stats -->
        <!-- <div class="lg:col-span-3 flex flex-col gap-4">
            <div class="bg-white shadow-lg rounded-lg p-6 flex items-center">
                <div class="w-12 h-12 flex items-center justify-center bg-yellow-200 text-yellow-600 rounded-full">
                    <i class="fa fa-chart-line"></i>
                </div>
                <div class="ml-4">
                    <h2 class="text-xl font-bold">Total Sales</h2>
                    <p class="text-gray-600">$45,678</p>
                </div>
            </div> -->

            <!-- <div class="bg-white p-4 rounded-lg shadow-md">
                <h3 class="font-semibold">Notifications</h3>
                <ul id="notificationList" class="space-y-2 mt-2">
                    <li class="p-2 bg-gray-100 rounded">User A made a payment of ₹5,000</li>
                    <li class="p-2 bg-gray-100 rounded">User B booked an appointment</li>
                </ul>
                <button onclick="addNotification()" class="mt-2 text-blue-600 hover:underline">Add Notification</button>
            </div>
        </div>
    </div> -->
 <!-- Enquiries Section -->
                    <!-- <div class="bg-white p-6 rounded-lg shadow mt-6">
                        <h3 class="text-purple-600 font-semibold">Enquiries</h3>
                        <p class="text-2xl font-bold text-gray-700 mt-4">No Enquiries Yet</p>
                        <ul class="space-y-2 mt-4">
                            <li class="p-2 bg-gray-100 rounded">User C updated their profile</li>
                        </ul>
                    </div> -->

    <!-- Appointments -->
   <!-- <div class="mt-6 p-4 bg-white shadow-md rounded-lg">
                        <h2 class="text-xl font-bold text-purple-900 mb-4">Appointments</h2>

                        <table class="w-full border-collapse border border-gray-300">
                            <thead>
                                <tr class="bg-gray-200 ">
                                    <th class="border border-gray-300 p-2">PATIENT</th>
                                    <th class="border border-gray-300 p-2">DOCTOR</th>
                                    <th class="border border-gray-300 p-2">DOCTOR DEPARTMENT</th>
                                    <th class="border border-gray-300 p-2">DATE & TIME</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border border-gray-300 p-2">
                                        <div class="flex items-center gap-2">
                                            <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full mr-4"
                                                alt="Patient">
                                            <span>Asmita</span>
                                        </div>
                                    </td>
                                    <td class="border border-gray-300 p-2">Bool Jumpa</td>
                                    <td class="border border-gray-300 p-2">Heart Life</td>
                                    <td class="border border-gray-300 p-2 text-center">
                                        <p class="font-bold">10:00AM</p>
                                        <p class="text-sm">25th Jan, 2025</p>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="border border-gray-300 p-2">
                                        <div class="flex items-center gap-2">
                                            <img src="https://via.placeholder.com/40" class="w-10 h-10 rounded-full mr-4"
                                                alt="Patient">
                                            <span>Aarchi</span>
                                        </div>
                                    </td>
                                    <td class="border border-gray-300 p-2">Bipin Dr.</td>
                                    <td class="border border-gray-300 p-2">Body Bon</td>
                                    <td class="border border-gray-300 p-2 text-center">
                                        <p class="font-bold">05:00AM</p>
                                        <p class="text-sm">26th Jan, 2025</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table> -->
                    </div>
</div> 
@endsection


