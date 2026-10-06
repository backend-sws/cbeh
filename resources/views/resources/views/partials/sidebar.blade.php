@php
    use Spatie\Permission\Models\Role;
    use Illuminate\Support\Facades\Request;
    $currentUser = Auth::user();
    $rolesSubmenu = [];

    foreach (Role::all() as $role) {
        if (in_array($role->name, ['Patient'])) {
            continue;
        }
        $rolesSubmenu[] = [
            'title' => ucwords($role->name),
            'link' => route('admin.users.user_list', ['name' => $role->name]),
            // 'is_active' => request()->is('user/' . $role->name),
            'is_active' => Request::is('admin/user/' . $role->name),
            'permission' => 'users.user_list',
            'icon' => '<i class="far fa-user nav-icon"></i>',
        ];
    }

    // Main sidebar menu configuration
    $sidebar_menu_links = [
        [
            'title' => 'Dashboard',
            'link' => route('dashboard'),
            'icon' => '<i class="nav-icon fas fa-tachometer-alt"></i>',
            'permission' => 'dashboard',
            'is_active' => Request::is('admin/dashboard'),
            'submenu' => [],
        ],
        [
            'title' => 'Collection Logs',
            'link' => route('admin.collection.index'),
            'icon' => '<i class="nav-icon fas fa-file-invoice"></i>',
            'permission' => 'collection.index',
            'is_active' => Request::is('admin/collection/logs'),
            'submenu' => [],
        ],
        [
            'title' => 'Billing',
            'icon' => '<i class="nav-icon fas fa-receipt"></i>',
            'link' => route('admin.billing.list_bill'),
            'is_active' => Request::is('admin/bill'),
            'submenu' => [],
            'permission' => 'billing',
        ],
        [
            'title' => 'Blood Management',
            'icon' => '<i class="nav-icon fas fa-tint"></i>',
            'is_active' => Request::is('admin/blood-*'),
            'permission' => 'blood.management',
            'submenu' => [
                [
                    'title' => 'Blood Bank',
                    'link' => route('admin.blood-bank.index'),
                    'icon' => '<i class="fas fa-hospital nav-icon"></i>',
                    'is_active' => Request::is('admin/blood-bank*'),
                    'permission' => 'blood-bank.index',
                ],
                [
                    'title' => 'Blood Donors',
                    'link' => route('admin.blood-donor.index'),
                    'icon' => '<i class="fas fa-user nav-icon"></i>',
                    'is_active' => Request::is('admin/blood-donor*'),
                    'permission' => 'blood-donor.index',
                ],
                [
                    'title' => 'Blood Donations',
                    'link' => route('admin.blood-donation.index'),
                    'icon' => '<i class="fas fa-hand-holding-medical nav-icon"></i>',
                    'is_active' => Request::is('admin/blood-donation*'),
                    'permission' => 'blood-donation.index',
                ],
                [
                    'title' => 'Blood Issues',
                    'link' => route('admin.blood-issue.index'),
                    'icon' => '<i class="fas fa-notes-medical nav-icon"></i>',
                    'is_active' => Request::is('admin/blood-issue*'),
                    'permission' => 'blood-issue.index',
                ],
            ],
        ],
        [
            'title' => 'Patient Registration',
            'icon' => '<i class="nav-icon fas fa-user-injured"></i>',
            'link' => route('admin.users.user_list', ['name' => 'Patient']),
            'is_active' =>
                Request::is('admin/user/Patient') || Request::is('patient-cases/*') || Request::is('patient-cases'),
            'permission' => 'users.user_list',
            'submenu' => [
                // [
                //     'title' => 'Patient',
                //     'link' => route('admin.users.user_list', ['name' => 'Patient']),
                //     'is_active' =>
                //        Request::is('admin/user/Patient') || Request::is('patient-cases/*') || Request::is('patient-cases'),
                //     'permission' => 'users.user_list',
                //     'icon' => '<i class="nav-icon fas fa-user-injured"></i>',
                // ],
                // [
                //     'title' => 'Patient Case',
                //     'link' => route('patient-cases.index'),
                //     'is_active' => request()->is('patient-cases/*') || request()->is('patient-cases'),
                //     'permission' => 'users.user_list',
                //     'icon' => '<i class="nav-icon fas fa-procedures"></i>',
                // ],
                // [
                //     'title' => 'Admission',
                //     'link' => route('admin.admissions.index'),
                //     'is_active' => request()->is('admin/admissions/*') || request()->is('admissions'),
                //     'permission' => 'users.user_list',
                //     'icon' => '<i class="nav-icon fas fa-hospital-user"></i>',
                // ],
            ],
        ],
        [
            'title' => 'Opd Patient',
            'icon' => '<i class="nav-icon fas fa-stethoscope"></i>',
            'link' => route('admin.opd.index'),
            'is_active' => Request::is('admin/opd-patient/*') || Request::is('admin/opd-patient'),
            'submenu' => [],
            'permission' => 'opd.index',
        ],
        [
            'title' => 'Ipd Patient',
            'icon' => '<i class="nav-icon fas fa-bed"></i>',
            'link' => route('admin.ipd.index'),
            'is_active' => Request::is('admin/ipd-patient') || (Request::is('admin/ipd-patient/*') && !Request::is('admin/ipd-patient/medication-*')),
            'submenu' => [],
            'permission' => 'ipd.index',
        ],
        [
            'title' => 'Medication Chart (MAR)',
            'icon' => '<i class="nav-icon fas fa-pills"></i>',
            'link' => route('admin.ipd.medication.station'),
            'is_active' => Request::is('admin/ipd-patient/medication-*'),
            'submenu' => [],
            'permission' => 'ipd.index',
        ],

        [
            'title' => 'Bed Management',
            'icon' => '<i class="nav-icon fas fa-bed"></i>',
            'link' => route('admin.bed.index'),
            'is_active' => request()->is('admin/bed/*') || request()->is('admin/bed'),
            'submenu' => [
                [
                    'title' => 'Bed Type',
                    'link' => route('admin.bed.type'),
                    'is_active' => Request::is('admin/bed/type'),
                    'permission' => 'bed.type',
                    'icon' => '<i class="nav-icon fas fa-bed"></i>',
                ],
                [
                    'title' => 'Bed List',
                    'link' => route('admin.bed.beds'),
                    'is_active' => Request::is('admin/bed/bed-list'),
                    'permission' => 'bed.index',
                    'icon' => '<i class="nav-icon fas fa-list"></i>',
                ],
                [
                    'title' => 'Bed Assign',
                    'link' => route('admin.bed.newassignlist'),
                    'is_active' => Request::is('admin/bed/new-assign-list'),
                    'permission' => 'bed.newassignlist',
                    'icon' => '<i class="nav-icon fas fa-tasks"></i>',
                ],
                [
                    'title' => 'Bed Status',
                    'link' => route('admin.bed.status'),
                    'is_active' => Request::is('admin/bed/status'),
                    'permission' => 'bed.status',
                    'icon' => '<i class="nav-icon fas fa-info-circle"></i>',
                ],
            ],
            'permission' => 'bed.index',
        ],
        // [
        //     'title' => 'Blood',
        //     'icon' => '<i class="nav-icon fas fa-tint"></i>',
        //     'link' => route('admin.blood.issue_index'),
        //     'is_active' => Request::is('admin/blood/issue') || Request::is('admin/blood/donation') || Request::is('admin/blood/bank'),
        //     'permission' => 'blood.issue_index',
        //     'submenu' => [
        //         [
        //             'title' => 'Blood Allotment',
        //             'link' => route('admin.blood.issue_index'),
        //             'is_active' => Request::is('admin/blood/issue'),
        //             'permission' => 'blood.issue_index',
        //             'icon' => '<i class="nav-icon fas fa-hand-holding-medical"></i>',
        //         ],
        //         [
        //             'title' => 'Blood Donation',
        //             'link' => route('admin.blood.donation_index'),
        //             'is_active' => Request::is('admin/blood/donation'),
        //             'permission' => 'blood.donation_index',
        //             'icon' => '<i class="nav-icon fas fa-heart"></i>',
        //         ],
        //         [
        //             'title' => 'Blood Group',
        //             'link' => route('admin.blood.bank_index'),
        //             'is_active' => Request::is('admin/blood/bank'),
        //             'permission' => 'blood.bank_index',
        //             'icon' => '<i class="nav-icon fas fa-vial"></i>',
        //         ],
        //         [
        //             'title' => 'Blood Donor',
        //             'link' => route('admin.blood.donor_index'),
        //             'is_active' => Request::is('admin/blood/donor'),
        //             'permission' => 'blood.donor_index',
        //             'icon' => '<i class="nav-icon fas fa-user-plus"></i>',
        //         ],
        //     ],
        // ],
        [
            'title' => 'Document',
            'icon' => '<i class="nav-icon fas fa-file-alt"></i>',
            'link' => route('admin.document.document_index'),
            'is_active' => Request::is('admin/document') || Request::is('admin/document/donation'),
            'permission' => 'document.document_index',
            'submenu' => [
                [
                    'title' => 'Document',
                    'link' => route('admin.document.document_index'),
                    'is_active' => Request::is('admin/document'),
                    'permission' => 'document.document_index',
                    'icon' => '<i class="nav-icon far fa-file"></i>',
                ],
                [
                    'title' => 'Document Type',
                    'link' => route('admin.document.document_type_index'),
                    'is_active' => Request::is('admin/document/donation'),
                    'permission' => 'document.document_type_index',
                    'icon' => '<i class="nav-icon fas fa-file-signature"></i>',
                ],
            ],
        ],
        // [
        //     'title' => 'Diagnosis',
        //     'icon' => '<i class="nav-icon fas fa-heartbeat"></i>',
        //     'link' => route('admin.diagnosis.diagnosis_category.index'),
        //     'is_active' => request()->is('admin/diagnosis/category*') ||
        //                    request()->is('admin/diagnosis/category') ||
        //                    request()->is('admin/diagnosis/test*') ||
        //                    request()->is('admin/diagnosis/test'),
        //     'permission' => 'diagnosis.diagnosis_category.index',
        //     'submenu' => [
        //         [
        //             'title' => 'Diagnosis Category',
        //             'link' => route('admin.diagnosis.diagnosis_category.index'),
        //             'is_active' => Request::is('admin/diagnosis/category'),
        //             'permission' => 'diagnosis.diagnosis_category.index',
        //             'icon' => '<i class="nav-icon fas fa-tags"></i>',
        //         ],
        //         [
        //             'title' => 'Diagnosis Test',
        //             'link' => route('admin.diagnosis.diagnosis_test.index'),
        //             'is_active' => Request::is('admin/diagnosis/test'),
        //             'permission' => 'diagnosis.diagnosis_test.index',
        //             'icon' => '<i class="nav-icon fas fa-flask"></i>',
        //         ],
        //     ],
        // ],

        [
            'title' => 'Medicine Management',
            'icon' => '<i class="nav-icon fas fa-pills"></i>',
            'link' => '#',
            'is_active' => Request::is('admin/medicine/*') || Request::is('admin/medicine'),
            'permission' => 'medicine',
            'submenu' => [
                [
                    'title' => 'Medicine Category',
                    'link' => route('admin.medicine.category.index'),
                    'is_active' => Request::is('admin/medicine/category'),
                    'permission' => 'medicine.category.index',
                    'icon' => '<i class="nav-icon fas fa-tags"></i>',
                ],
                [
                    'title' => 'Medicine Company ',
                    'link' => route('admin.medicine.brand.index'),
                    'is_active' => Request::is('admin/medicine/brand'),
                    'permission' => 'medicine.index',
                    'icon' => '<i class="nav-icon fas fa-trademark"></i>',
                ],
                [
                    'title' => 'Medicine',
                    'link' => route('admin.medicine.index'),
                    'is_active' => Request::is('admin/medicine'),
                    'permission' => 'medicine.index',
                    'icon' => '<i class="nav-icon fas fa-pills"></i>',
                ],

                [
                    'title' => 'Medicine Purchase',
                    'link' => route('admin.medicine.purchase.index'),
                    'is_active' => Request::is('admin/medicine/purchase'),
                    'permission' => 'medicine.index',
                    'icon' => '<i class="nav-icon fas fa-shopping-cart"></i>',
                ],
                [
                    'title' => 'Medicine Sale',
                    'link' => route('admin.medicine.bill.index'),
                    'is_active' => Request::is('admin/medicine/bill'),
                    'permission' => 'medicine.index',
                    'icon' => '<i class="nav-icon fas fa-file-invoice"></i>',
                ],
                [
                    'title' => 'Expired Medicine',
                    'link' => route('admin.medicine.expired'),
                    'is_active' => Request::is('admin/medicine/expired-list'),
                    'permission' => 'medicine.index',
                    'icon' => '<i class="nav-icon fas fa-exclamation-triangle"></i>',
                ],
                [
                    'title' => 'Medicine Dump & Loss',
                    'link' => route('admin.medicine.dump.report'),
                    'is_active' => Request::is('admin/medicine/dump-report'),
                    'permission' => 'medicine.index',
                    'icon' => '<i class="nav-icon fas fa-dumpster-fire"></i>',
                ],
            ],
        ],

        [
            'title' => 'Pathology',
            'icon' => '<i class="nav-icon fas fa-flask"></i>',
            'link' => '#',
            'is_active' => Request::is('admin/pathology/*') || Request::is('admin/pathology'),
            'permission' => 'pathology',
            'submenu' => [
                [
                    'title' => 'Pathology Category',
                    'link' => route('admin.pathology.category.index'),
                    'is_active' => Request::is('admin/pathology/category'),
                    'permission' => 'pathology.category.index',
                    'icon' => '<i class="nav-icon fas fa-tags"></i>',
                ],
                [
                    'title' => 'Pathology Unit',
                    'link' => route('admin.pathology.unit.index'),
                    'is_active' => Request::is('admin/pathology/unit'),
                    'permission' => 'pathology.unit.index',
                    'icon' => '<i class="nav-icon fas fa-ruler"></i>',
                ],
                [
                    'title' => 'Pathology Parameter',
                    'link' => route('admin.pathology.parameter.index'),
                    'is_active' => Request::is('admin/pathology/parameter'),
                    'permission' => 'pathology.parameter.index',
                    'icon' => '<i class="nav-icon fas fa-sliders-h"></i>',
                ],
                [
                    'title' => 'Pathology Test',
                    'link' => route('admin.pathology.test.index'),
                    'is_active' => Request::is('admin/pathology/test'),
                    'permission' => 'pathology.test.index',
                    'icon' => '<i class="nav-icon fas fa-vial"></i>',
                ],
                [
                    'title' => 'Pathology Bill/Reports',
                    'link' => route('admin.pathology.bill.index'),
                    'is_active' => Request::is('admin/pathology/bill'),
                    'permission' => 'pathology.bill.index',
                    'icon' => '<i class="nav-icon fas fa-file-invoice"></i>',
                ],
            ],
        ],

        [
            'title' => 'Birth Certificate',
            'icon' => '<i class="nav-icon fas fa-baby"></i>',
            'link' => route('admin.birth_report.index'),
            'is_active' => Request::is('admin/birth-report*'),
            'permission' => 'birth_report.index', // Make sure to add this permission in DB
            'submenu' => [],
        ],

        [
            'title' => 'Death Certificate',
            'icon' => '<i class="nav-icon fas fa-book-dead"></i>',
            'link' => route('admin.death_report.index'),
            'is_active' => Request::is('admin/death-report*'),
            'permission' => 'death_report.index',
            'submenu' => [],
        ],

        ...$currentUser->hasRole('Admin')
            ? [
                [
                    'title' => 'Users',
                    'icon' => '<i class="nav-icon fas fa-users"></i>',
                    'link' => '#',
                    'is_active' => Request::is('admin/user/*') || Request::is('user'),
                    'submenu' => $rolesSubmenu,
                    'permission' => 'users.user_list',
                ],
            ]
            : [],
        [
            'title' => 'Hospital Charge',
            'icon' => '<i class="nav-icon fas fa-money-bill-wave"></i>',
            'link' => route('admin.charge.chargeCategorylist', ['name' => 'charge']),
            'is_active' =>
                Request::is('admin/charge-type-list/*') ||
                Request::is('admin/charge/*') ||
                Request::is('admin/charge/charge-category') ||
                Request::is('admin/charge/edit-charge-type/*') ||
                Request::is('admin/doctor/charge/list'),
            'permission' => 'charge.chargeCategorylist',
            'submenu' => [
                [
                    'title' => 'Charge Type',
                    'link' => route('admin.charge.typelist'),
                    'is_active' =>
                        Request::is('admin/charge/charge-type-list/*') || Request::is('admin/charge/charge-type-list'),
                    'permission' => 'charge.chargeCategorylist',
                    'icon' => '<i class="nav-icon fas fa-tags"></i>',
                ],
                [
                    'title' => 'Charge Category',
                    'link' => route('admin.charge.chargeCategorylist', ['name' => 'charge']),
                    'is_active' =>
                        Request::is('admin/charge/charge-category') ||
                        Request::is('admin/charge/charge-category') ||
                        Request::is('admin/charge/edit-charge-type/*'),
                    'permission' => 'charge.chargeCategorylist',
                    'icon' => '<i class="nav-icon fas fa-list-alt"></i>',
                ],
                [
                    'title' => 'Charge',
                    'link' => route('admin.charge.Hospitalcharge.list'),
                    'is_active' => Request::is('admin/charge/charge-list') || Request::is('charge'),
                    'permission' => 'charge.chargeCategorylist',
                    'icon' => '<i class="nav-icon fas fa-file-invoice-dollar"></i>',
                ],
                [
                    'title' => 'OPD Doctor Charge',
                    'link' => route('admin.doctor.charge.list'),
                    'is_active' => Request::is('admin/doctor/charge/list') || Request::is('admin/doctor/charge'),
                    'permission' => 'charge.chargeCategorylist',
                    'icon' => '<i class="nav-icon fas fa-user-md"></i>',
                ],
            ],
        ],
        [
            'title' => 'Settings',
            'icon' => '<i class="nav-icon fas fa-cog"></i>',
            'link' => '#',
            'is_active' => Request::is('admin/hospital-web-settings') || Request::is('admin/hospital-print-settings'),
            'submenu' => [
                [
                    'title' => 'Hospital Web Settings',
                    'link' => route('admin.hospital-web-settings.index'),
                    'is_active' => Request::is('admin/hospital-web-settings'),
                    'permission' => 'hospital-web-settings.index',
                    'icon' => '<i class="nav-icon fas fa-globe"></i>',
                ],
                [
                    'title' => 'PDF Print Settings',
                    'link' => route('admin.hospital-print-settings.index'),
                    'is_active' => Request::is('admin/hospital-print-settings'),
                    'permission' => 'hospital-web-settings.index',
                    'icon' => '<i class="nav-icon fas fa-print"></i>',
                ],
            ],
            'permission' => 'hospital-web-settings.index',
        ],
        [
            'title' => 'Role Management',
            'icon' => '<i class="nav-icon fas fa-user-lock"></i>',
            'link' => '#',
            'is_active' => Request::is('role/*') || Request::is('roles'),
            'permission' => ['roles.index', 'roles.permission.index', 'roles.role_permission'],
            'submenu' => [
                [
                    'title' => 'Create Roles',
                    'link' => route('roles.index'),
                    'is_active' => Request::is('roles'),
                    'permission' => 'roles.index',
                    'icon' => '<i class="nav-icon fas fa-user-tag"></i>',
                ],

                [
                    'title' => 'Roles & Permission',
                    'link' => route('roles.role_permission'),
                    'is_active' => Request::is('role/role-permission'),
                    'permission' => 'roles.role_permission',
                    'icon' => '<i class="nav-icon fas fa-link"></i>',
                ],
            ],
        ],
        [
            'title' => 'Audit Logs',
            'link' => route('admin.audit_logs.index'),
            'icon' => '<i class="nav-icon fas fa-history"></i>',
            'permission' => 'audit.logs',
            'is_active' => Request::is('admin/audit-logs*'),
            'submenu' => [],
        ],
    ];
@endphp

<aside id="sidebar"
    class="w-64 bg-white border-r shadow-xl fixed top-0 left-0 h-screen z-50 transition-transform duration-300 ease-in-out flex flex-col overflow-y-auto hide-scrollbar -translate-x-full md:translate-x-0"
    style="min-height: 100vh;">
    <!-- Sidebar Logo -->
    <div class="flex items-center justify-center py-2 border-b mb-2">
        <div class="w-80 h-14  rounded-full flex items-center justify-center mr-3">
            <img src="{{ asset('img/' . setting('logo', 'img/patnalogo.jpeg')) }}" alt="Logo" class="h-10 w-auto">
        </div>
        {{-- <h2 class="text-xl font-bold text-pink-600">HMS</h2> --}}
    </div>

    <!-- Sidebar Menu -->
    <ul class="space-y-4 px-2 pb-8 flex-1 min-h-100">
        @foreach ($sidebar_menu_links as $sidebar_menu_link)
            @canany((array) $sidebar_menu_link['permission'])
                <li class="relative" x-data="{ open: {{ $sidebar_menu_link['is_active'] ? 'true' : 'false' }} }">
                    <!-- Main Menu Item -->
                    <a href="{{ $sidebar_menu_link['submenu'] ? '#' : $sidebar_menu_link['link'] }}"
                        @if (!empty($sidebar_menu_link['submenu'])) @click.prevent="open = !open" @endif
                        class="group px-3 py-2.5 hover:bg-pink-50 hover:text-pink-700 rounded-lg w-full block flex items-center space-x-3 transition-colors duration-200 border border-transparent
                              {{ $sidebar_menu_link['is_active'] ? 'bg-pink-50/80 text-pink-700 font-semibold shadow-sm border-pink-100' : 'text-gray-500' }}">
                        {!! $sidebar_menu_link['icon'] !!}
                        <span>{{ $sidebar_menu_link['title'] }}</span>
                        <span class="ml-auto" x-show="!!{{ !empty($sidebar_menu_link['submenu']) ? 'true' : 'false' }}">
                            <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                                :class="{ 'rotate-180': open }"></i>
                        </span>
                    </a>

                    <!-- Submenu -->
                    @if (!empty($sidebar_menu_link['submenu']))
                        <ul class="space-y-1 mt-1 ml-4 border-l-2 border-pink-100 pl-2" x-show="open" x-collapse>
                            @foreach ($sidebar_menu_link['submenu'] as $submenu)
                                @can($submenu['permission'])
                                    <li>
                                        <a href="{{ $submenu['link'] }}"
                                            class="relative group px-3 py-2 hover:bg-pink-50 hover:text-pink-600 rounded-lg w-full block flex items-center space-x-3 transition-colors duration-200
                                                  {{ $submenu['is_active'] ? 'bg-pink-50/50 text-pink-600 font-semibold' : 'text-gray-500' }}">
                                            {!! $submenu['icon'] ?? '<i class="far fa-circle text-[10px] w-[20px] text-center mr-[10px]"></i>' !!}
                                            <span>{{ $submenu['title'] }}</span>
                                        </a>
                                    </li>
                                @endcan
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endcanany
        @endforeach
    </ul>
</aside>

<style>
    /* Scrollbar styling */
    .hide-scrollbar::-webkit-scrollbar {
        width: 6px;
        background: transparent;
    }

    .hide-scrollbar:hover::-webkit-scrollbar-thumb {
        background: rgba(244, 114, 182, 0.5);
        border-radius: 3px;
    }

    .hide-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(244, 114, 182, 0.5) transparent;
    }

    /* Icon alignment */
    .nav-icon {
        width: 20px;
        text-align: center;
        margin-right: 10px;
    }

    /* Smooth transitions */
    .sidebar-item,
    .sidebar-subitem {
        transition: all 0.2s ease-in-out;
    }

    /* Active state enhancements */
    [x-cloak] {
        display: none !important;
    }
</style>
