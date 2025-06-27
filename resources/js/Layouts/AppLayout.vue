<script setup>
import { ref ,onMounted } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import AdminNavbar from './AdminNavbar.vue';
import LabNavbar from './LabNavbar.vue';
import OPDNavbar from './OPDNavbar.vue';
import OTNavbar from './OTNavbar.vue';
import IPDNavbar from './IPDNavbar.vue';
import HRMNavbar from './HRMNavbar.vue';
import InventoryNavbar from './InventoryNavbar.vue';
import AccountsNavbar from './AccountsNavbar.vue';
import DoctorNavbar from './DoctorNavbar.vue';
import PatientNavbar from './PatientNavbar.vue';
import ReceptionistNavbar from './ReceptionistNavbar.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import MegaMenu from '@/components/MegaMenu.vue';
import DropdownMenu from '@/components/DropdownMenu.vue';
import SearchModel from "@/Pages/SearchModel.vue";
import Swal from 'sweetalert2';
import axios from 'axios';

const props = defineProps({
    title: String,
    hasExpenseModule: Boolean,
    hasDonationModule: Boolean,
    hasInventoryModule: Boolean,
    hasLabModule: Boolean,
    hasAccountsModule : Boolean,
});
const summaries = ref([]);
const users = ref([]);
const role = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/user-cash-summary');
        summaries.value = response.data.summaries;
        users.value = response.data.users;
        role.value = response.data.role;
    } catch (error) {
        console.error('Error fetching data:', error);
    }
});
const confirmLogoutWithCashHandover = () => {
    const userSummary = summaries.value.find(s => s.user_id === role?.value?.user_id);
    // console.log(userSummary.user.user_role[0].role_id);
 if ( !userSummary || userSummary.user.user_role[0].role_id !== 10) {
        logout();
    }

    Swal.fire({
        title: 'Cash Handover Required',
        html: `
            <div class="space-y-4 text-left">
                <div>
                    <h2 class="font-bold flex justify-end">Total Amount ${ userSummary.total_amount }</h2>
                    <label class="block text-sm font-medium text-gray-700">Collected Amount</label>
                    <input id="collected_amount" type="number"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                           value="${userSummary.collected_amount}"
                           min="0" max="${userSummary.total_amount}" step="0.01">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Assign To User</label>
                    <select id="updated_by" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">Select User</option>
                        ${users.value
                            .filter(u => u.id !== role.value.user_id)
                            .map(approver => `
                                <option value="${approver.id}">
                                    ${approver.name}
                                </option>
                            `).join('')}
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Confirm & Logout',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            const collectedAmount = parseFloat(document.getElementById('collected_amount').value);
            const updatedBy = document.getElementById('updated_by').value.trim();

            if (isNaN(collectedAmount)) {
                Swal.showValidationMessage('Please enter a valid amount');
                return false;
            }

            if (!updatedBy) {
                Swal.showValidationMessage('Please select a user to assign cash');
                return false;
            }

            return {
                collected_amount: collectedAmount,
                updated_by: updatedBy
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            router.put(`/user-cash-summaries/${userSummary.id}`, result.value, {
                preserveScroll: true,
                onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Cash assigned successfully',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    router.post('/logout', {}, {
                        onSuccess: () => {
                            window.location.href = '/login';
                        },
                        onError: () => {
                            Swal.fire('Error!', 'Logout failed', 'error');
                        }
                    });
                });
            }
            });
        }
    });

};

const projectType = import.meta.env.VITE_PROJECT_TYPE;

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};

const payrollMenuItems = [
  {
    title: 'Forms',
    items: [
        { name: 'Employees', href: route('employees.index'), icon: 'UserGroupIcon' },
        { name: 'Manual Attendance', href: route('daily-attendances.index'), icon: 'Bars2Icon' },
        { name: 'Holidays', href: route('holidays.index'), icon: 'Bars2Icon' },
        { name: 'Employee Allowances', href: route('employee-allowances.index'), icon: 'Bars2Icon' },
        { name: 'Employee Advance', href: route('employee-advances.index'), icon: 'Bars2Icon' },
        { name: 'Employee Deduction', href: route('employee-deductions.index'), icon: 'Bars2Icon' },
        { name: 'Warnings', href: route('employee-warnings.index'), icon: 'Bars2Icon' },
        { name: 'Promotion', href: route('employee-promotions.index'), icon: 'Bars2Icon' },
        { name: 'Termination', href: route('employee-terminations.index'), icon: 'Bars2Icon' },
        // { name: 'Leave Applications', href: '#', icon: 'Bars2Icon' },
    ],
  },
  {
    title: 'Supporting Forms',
    items: [
        { name: 'Department', href: route('departments.index'), icon: 'DocumentPlusIcon' },
        { name: 'Designation', href: route('designations.index'), icon: 'DocumentPlusIcon' },
        { name: 'Allowance', href: route('allowances.index'), icon: 'DocumentPlusIcon' },
        { name: 'Shift', href: route('shifts.index'), icon: 'DocumentPlusIcon' },
        { name: 'Attendance machines', href: route('attendance-machines.index'), icon: 'DocumentPlusIcon' },
        { name: 'Roster types', href: route('roster-types.index'), icon: 'DocumentPlusIcon' },
        { name: 'Grades', href: route('grades.index'), icon: 'DocumentPlusIcon' },
        { name: 'Employee shift roster', href: route('employee-shift-roster.index'), icon: 'DocumentPlusIcon' },
        { name: 'Leave Types', href: route('leave-types.index'), icon: 'DocumentPlusIcon' },
    ],
  },
  {
    title: 'Reports',
    items: [
        { name: 'Daily Attendance Report', href: route('daily-attendance-report.index'), icon: 'DocumentTextIcon' },
        { name: 'Employee Report', href: route('employee-attendance-report.index'), icon: 'DocumentTextIcon' },
        { name: 'Attendance Calender Report', href: route('attendance-calender-report.index'), icon: 'DocumentTextIcon' },
        { name: 'Monthly Attendance Report', href: route('monthly-attendance-report.index'), icon: 'DocumentTextIcon' },
        { name: 'Department Wise Attendance Report', href: route('department-attendance-report.index'), icon: 'DocumentTextIcon' },
        { name: 'Daily Absent Report', href: route('daily-absent-report.index'), icon: 'DocumentTextIcon' },
        { name: 'Monthly Absent Report', href: route('monthly-absent-report.index'), icon: 'DocumentTextIcon' },
        { name: 'User Activity Log', href: route('activity-log'), icon: 'DocumentTextIcon' },
        { name: 'Voucher Activity Log', href: props.hasAccountsModule ? route('voucher-activity-log') : null, icon: 'DocumentTextIcon' },

    ],
  },
];

const inventoryMenuItems = [
  {
    title: 'Forms',
    items: [
        { name: 'Demands', href: props.hasInventoryModule ? route('demands.index') : null, icon: 'BanknotesIcon' },
        { name: 'Purchase Order', href: props.hasInventoryModule ? route('purchase-orders.index') : null, icon: 'CreditCardIcon' },
        { name: 'Inward Gatepass', href: props.hasInventoryModule ? route('inward-gatepass.index') : null, icon: 'ArrowPathRoundedSquareIcon' },
        { name: 'Goods Receipt Notes', href: props.hasInventoryModule ? route('goods-receipt-notes.index') : null, icon: 'BuildingStorefrontIcon' },
        { name: 'Material Issue Notes', href: props.hasInventoryModule ? route('material-issue-notes.index') : null, icon: 'PresentationChartLineIcon' },
        { name: 'Material Return Notes', href: props.hasInventoryModule ? route('material-return-notes.index') : null, icon: 'BookOpenIcon' },
    ],
  },
  {
    title: 'Supporting Forms',
    items: [
        // { name: 'Categories', href: route('categories.index'), icon: 'ListBulletIcon' },
        // { name: 'Sub Categories', href: route('sub-categories.index'), icon: 'ListBulletIcon' },
        { name: 'UOM', href: props.hasInventoryModule ? route('uoms.index') : null, icon: 'ListBulletIcon' },
        { name: 'Taxes', href: props.hasInventoryModule ? route('taxes.index') : null, icon: 'ListBulletIcon' },
        // { name: 'Warehouse', href: route('warehouses.index'), icon: 'ListBulletIcon' },
        { name: 'Department', href: props.hasInventoryModule ? route('inventory-departments.index') : null, icon: 'ListBulletIcon' },
        { name: 'Product', href: props.hasInventoryModule ? route('products.index') : null, icon: 'ListBulletIcon' },
        { name: 'Salts', href: props.hasInventoryModule ? route('salts.index') : null, icon: 'ListBulletIcon' },
        { name: 'ProductTypes', href: props.hasInventoryModule ? route('item_types.index') : null, icon: 'ListBulletIcon' },
        { name: 'ProductCategories', href: props.hasInventoryModule ? route('item_categories.index') : null, icon: 'ListBulletIcon' },
        { name: 'Suppliers', href: props.hasInventoryModule ? route('suppliers.index') : null, icon: 'BuildingStorefrontIcon' },
        // { name: 'Customers', href: route('customers.index'), icon: 'UsersIcon' },
    ],
  },
  {
    title: 'Reports',
    items: [
        { name: 'Item Ledger', href: props.hasInventoryModule ? route('inventory.item-ledger') : null, icon: 'PresentationChartLineIcon' },
        { name: 'Purchase Report', href: props.hasInventoryModule ? route('inventory.purchase-report') : null, icon: 'BookOpenIcon' },
        { name: 'Sales Report', href: props.hasInventoryModule ? route('inventory.sale-report') : null, icon: 'BookOpenIcon' },
        { name: 'Stock Report', href: props.hasInventoryModule ? route('inventory.stock') : null, icon: 'BookOpenIcon' },
        { name: 'Low Stock Report', href: props.hasInventoryModule ? route('inventory.low-stock') : null, icon: 'BookOpenIcon' },
        { name: 'Rate List', href: props.hasInventoryModule ? route('inventory.rate-list') : null, icon: 'BookOpenIcon' },
        { name: 'Expired Products', href: props.hasInventoryModule ? route('inventory.expiry-product') : null, icon: 'BookOpenIcon' },
        { name: 'Return Report', href: props.hasInventoryModule ? route('inventory.return-report') : null, icon: 'BookOpenIcon' },
    ],
  },
];

const financeMenuItems = [
  {
    title: 'Forms',
    items: [
        { name: 'Income', href: route('employees.index'), icon: 'BanknotesIcon' },
        { name: 'Expense', href: route('states.index'), icon: 'CreditCardIcon' },
        { name: 'Transfer Balance', href: route('states.index'), icon: 'ArrowPathRoundedSquareIcon' },
    ],
  },
  {
    title: 'Supporting Forms',
    items: [
        { name: 'Account List', href: '#', icon: 'ListBulletIcon' },
        { name: 'Suppliers', href: '#', icon: 'BuildingStorefrontIcon' },
        { name: 'Customers', href: '#', icon: 'UsersIcon' },
    ],
  },
  {
    title: 'Reports',
    items: [
        { name: 'Profit & Loss', href: '#', icon: 'PresentationChartLineIcon' },
        { name: 'General Ledger', href: '#', icon: 'BookOpenIcon' },
    ],
  },
];

const settingsMenuItems = [
  { name: 'Countries', href: route('countries.index') },
  { name: 'States', href: route('states.index') },
  { name: 'Cities', href: route('cities.index') },
  { name: 'General Settings', href: route('settings') },
  { name: 'Account Settings', href: props.hasAccountsModule ? route('acc_settings.index') : null },
];

const OPDMenuItems = [
  { name: 'Services', href: route('services.index') },
  { name: 'Patients', href: route('patients.index') },
  { name: 'Appointments', href: route('appointments.index') },
  { name: 'Follow Ups', href: route('follow-ups.index') },
  { name: 'Patient feedbacks', href: route('patient-feedbacks.index') },
];

const labMenuItems = [
  { name: 'Test groups', href: props.hasLabModule ? route('test-groups.index') : null },
  { name: 'Units', href: props.hasLabModule ? route('units.index') : null },
  { name: 'Test Categories', href: props.hasLabModule ? route('test-categories.index') : null },
  { name: 'Widal Tests', href: props.hasLabModule ? route('widal-tests.index') : null },
  { name: 'Lab Tests', href: props.hasLabModule ? route('lab-tests.index') : null },
  { name: 'Other Receipts', href: props.hasLabModule ? route('lab-tests.other-receipt') : null },
  { name: 'Out Door Test', href: props.hasLabModule ? route('lab-tests.outdoor') : null },
  { name: 'Packages', href: props.hasLabModule ? route('packages.index') : null },
  { name: 'Sub-Packages', href: props.hasLabModule ? route('sub-packages.index') : null },
  { name: 'Patient Tests', href: props.hasLabModule ? route('patient-tests.index') : null },
  { name: 'Other Receipts', href: props.hasLabModule ? route('patient-tests.other_receipt') : null },

];
const otherMenuItems = [

  { name: 'Other Receipts', href: props.hasLabModule ? route('lab-tests.other-receipt') : null },
  { name: 'Other Receipts Listing', href: props.hasLabModule ? route('patient-tests.other_receipt') : null },

];

const IPDMenuItems = [
  { name: 'Room types', href: route('room-types.index') },
  { name: 'Rooms', href: route('rooms.index') },
  { name: 'Room beds', href: route('room-beds.index') },
  { name: 'Admission', href: route('admissions.index') },
  { name: 'Patient case studies', href: route('patient-case-studies.index') },
];

const accountMenuItems = [
  { name: 'Control accounts', href: props.hasAccountsModule ? route('control-accounts.index') : null },
  { name: 'Sub control accounts', href: props.hasAccountsModule ? route('sub-control-accounts.index') : null },
  { name: 'Sub head accounts', href: props.hasAccountsModule ? route('sub-head-accounts.index') : null },
  { name: 'Account codes', href: props.hasAccountsModule ? route('account-codes.index') : null },
  { name: 'Chart of accounts', href: props.hasAccountsModule ? route('chart-of-accounts.index') : null },
  { name: 'Voucher types', href: props.hasAccountsModule ? route('voucher-types.index') : null },
  { name: 'Vouchers', href: props.hasAccountsModule ? route('vouchers.index') : null }, 
];

const reportMenuItems = [
  { name: 'Patient report', href: route('patient-reports.index') },
  { name: 'Indoor patient report by admission date', href: route('IPD-reports-by-admission-date.index') },
  { name: 'Indoor patient report by discharge date', href: route('IPD-reports-by-discharge-date.index') },
  { name: 'Indoor patient report by file no.', href: route('IPD-reports-by-file-no.index') },
  { name: 'Indoor billing report', href: route('indoor-billing-reports.index') },
  { name: 'Doctor report', href: route('doctor-reports.index') },
  { name: 'Service report by type', href: route('service-reports-by-type.index') },
  { name: 'Service billing report', href: route('service-billing-reports.index') },
  { name: 'Expense Report', href: props.hasExpenseModule ? route('expense.report') : null, icon: 'DocumentPlusIcon' },

];

const page = usePage()
const roles = ref([]);
if(page.props.auth.user?.user_role){
    page.props.auth.user?.user_role.map(user_role => {
        roles.value.push(user_role?.role_id);
    });
}

</script>
<script>
export default {
  components: {
    AdminNavbar,
    NavLink,
    MegaMenu,
    DropdownMenu,
    LabNavbar,
    OPDNavbar,
    OTNavbar,
    IPDNavbar,
    HRMNavbar,
    InventoryNavbar,
    AccountsNavbar,
    DoctorNavbar,
    PatientNavbar,
    ReceptionistNavbar
  },
};
</script>
<style>
  .dropdown-menu-full-width {
    left: 0;
    right: 0;
    width: 100%;
    margin-left: 0;
    transform: none;
  }

  nav a {
  transition: color 0.3s ease;
}

/* nav a:hover {
  color: #106BC7;
} */
</style>
<template>
    <div>
        <Head :title="title" />

        <Banner />

        <SearchModel />

        <div class="min-h-screen bg-gray-50">
           <nav class="bg-primary border-b border-gray-100 shadow-md font-saira">

                <!-- Primary Navigation Menu -->
                <div class="w-full mx-auto px-4 sm:px-4 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark class="block h-16 w-auto transform  hover:rotate-45 transition duration-300" />
                                </Link>
                            </div>
                            <h1 class="shrink-0 flex items-center ml-6 text-white font-medium mt-0.5" v-if="projectType === 'jinnah'">JC HMS</h1>
                            <h1 class="shrink-0 flex items-center ml-6 text-white font-medium mt-0.5" v-if="projectType === 'hms'">SGD HMS</h1>

                        </div>
                        <div>
    
                            <template v-if="roles.includes(1)">
                                    <AdminNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                                <template v-if="roles.includes(2)">
                                    <LabNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                                <template v-if="roles.includes(3)">
                                    <OPDNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                                <template v-if="roles.includes(4)">
                                    <IPDNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                                <template v-if="roles.includes(4)">
                                    <OTNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                                <template v-if="roles.includes(5)">
                                    <HRMNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                                <template v-if="roles.includes(6)">
                                    <InventoryNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                                <template v-if="roles.includes(7)">
                                    <AdminNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                                <template v-if="roles.includes(8)">
                                    <DoctorNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                                <template v-if="roles.includes(9)">
                                    <PatientNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                                <template v-if="roles.includes(10)">
                                    <ReceptionistNavbar :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule" :hasLabModule="hasLabModule" :hasAccountsModule="hasAccountsModule" />
                                </template>
                        </div>
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <div class="ml-3 relative">
                                <!-- Teams Dropdown -->
                                <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-900 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{ $page.props.auth.user.current_team.name }}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <div class="w-60">
                                            <!-- Team Management -->
                                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    Manage Team
                                                </div>

                                                <!-- Team Settings -->
                                                <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
                                                    Team Settings
                                                </DropdownLink>

                                                <DropdownLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')">
                                                    Create New Team
                                                </DropdownLink>

                                                <div class="border-t border-gray-200" />

                                                <!-- Team Switcher -->
                                                <div class="block px-4 py-2 text-xs text-gray-400">
                                                    Switch Teams
                                                </div>

                                                <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                                    <form @submit.prevent="switchToTeam(team)">
                                                        <DropdownLink as="button">
                                                            <div class="flex items-center">
                                                                <svg v-if="team.id == $page.props.auth.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                </svg>

                                                                <div>{{ team.name }}</div>
                                                            </div>
                                                        </DropdownLink>
                                                    </form>
                                                </template>
                                            </template>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button v-if="$page.props.jetstream.managesProfilePhotos" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="`${$page.props.auth.user.name}`">
                                        </button>

                                        <span v-else class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-900 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{$page.props.auth.user.name}}

                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <!-- Account Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            Manage Account
                                        </div>

                                        <DropdownLink :href="route('profile.show')">
                                            Profile
                                        </DropdownLink>

                                        <DropdownLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')">
                                            API Tokens
                                        </DropdownLink>

                                        <div class="border-t border-gray-200" />

                                        <!-- Authentication -->
                                        <!-- <form @submit.prevent="logout">
                                            <DropdownLink as="button">
                                                Log Out
                                            </DropdownLink>
                                        </form> -->
                                        <form @click.prevent="confirmLogoutWithCashHandover" class="w-full text-left">
                                            <DropdownLink as="button">
                                                Log Out
                                            </DropdownLink>
                                        </form>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-900 transition duration-150 ease-in-out" @click="showingNavigationDropdown = ! showingNavigationDropdown">
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}" class="sm:hidden">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="flex items-center px-4">
                            <div v-if="$page.props.jetstream.managesProfilePhotos" class="shrink-0 mr-3">
                                <img class="h-10 w-10 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="`${$page.props.auth.user.name}`">
                            </div>

                            <div>
                                <div class="font-medium text-base text-gray-800">
                                    {{ $page.props.auth.user.name}}
                                </div>
                                <div class="font-medium text-sm text-gray-900">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
                                Profile
                            </ResponsiveNavLink>

                            <ResponsiveNavLink v-if="$page.props.jetstream.hasApiFeatures" :href="route('api-tokens.index')" :active="route().current('api-tokens.index')">
                                API Tokens
                            </ResponsiveNavLink>

                            <!-- Authentication -->
                            <form method="POST" @submit.prevent="logout">
                                <ResponsiveNavLink as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </form>

                            <!-- Team Management -->
                            <template v-if="$page.props.jetstream.hasTeamFeatures">
                                <div class="border-t border-gray-200" />

                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Manage Team
                                </div>

                                <!-- Team Settings -->
                                <ResponsiveNavLink :href="route('teams.show', $page.props.auth.user.current_team)" :active="route().current('teams.show')">
                                    Team Settings
                                </ResponsiveNavLink>

                                <ResponsiveNavLink v-if="$page.props.jetstream.canCreateTeams" :href="route('teams.create')" :active="route().current('teams.create')">
                                    Create New Team
                                </ResponsiveNavLink>

                                <div class="border-t border-gray-200" />

                                <!-- Team Switcher -->
                                <div class="block px-4 py-2 text-xs text-gray-400">
                                    Switch Teams
                                </div>

                                <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                    <form @submit.prevent="switchToTeam(team)">
                                        <ResponsiveNavLink as="button">
                                            <div class="flex items-center">
                                                <svg v-if="team.id == $page.props.auth.user.current_team_id" class="mr-2 h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>{{ team.name }}</div>
                                            </div>
                                        </ResponsiveNavLink>
                                    </form>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header" class="">
                <div class="w-ful mx-auto pt-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="py-1 px-2">
                <slot />
            </main>
        </div>
    </div>
</template>
