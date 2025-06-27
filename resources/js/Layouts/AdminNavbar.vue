<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import MegaMenu from '@/components/MegaMenu.vue';
import DropdownMenu from '@/components/DropdownMenu.vue';

const props = defineProps({
    title: String,
    hasExpenseModule: Boolean,
    hasDonationModule: Boolean,
    hasInventoryModule: Boolean,
    hasLabModule: Boolean,
    hasAccountsModule: Boolean,

});

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
        { name: 'User Cash Summary', href: route('user-cash-summaries.index'), icon: 'DocumentTextIcon' },


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
  {
    title: 'Forms',
    items: [
        { name: 'Patients', href: route('patients.index'), icon: 'BanknotesIcon' },
        { name: 'Appointments', href: route('appointments.index'), icon: 'BanknotesIcon' },
        { name: 'Follow Ups', href: route('follow-ups.index'), icon: 'BanknotesIcon' },
        { name: 'Patient feedbacks', href: route('patient-feedbacks.index'), icon: 'BanknotesIcon' },
        // { name: 'Donations', href: route('donations.index'), icon: 'BanknotesIcon' },

    ],
  },
  {
    title: 'Supporting Forms',
    items: [
        { name: 'Category', href: route('categories.index'), icon: 'ListBulletIcon' },
        { name: 'Services', href: route('services.index'), icon: 'ListBulletIcon' },
    ],
  },
  {
    title: 'Reports',
    items: [
        { name: 'Doc Detail Report', href: route('opd.reports-doctor-detail'), icon: 'BookOpenIcon' },
        { name: 'Summary by Date', href: route('opd.reports-summary-by-date'), icon: 'BookOpenIcon' },
        { name: 'Summary by Shift', href: route('opd.reports-summary-by-shift'), icon: 'BookOpenIcon' },
        { name: 'Summary Detail by Date', href: route('opd.reports-summary-detail-by-date'), icon: 'BookOpenIcon' },
        { name: 'Specialist By Date', href: route('opd.reports-specialist-by-date'), icon: 'BookOpenIcon' },
        { name: 'General By Date', href: route('opd.reports-general-by-date'), icon: 'BookOpenIcon' },
        { name: 'Specialist Detail by Doctor', href: route('opd.reports-specialist-detail-by-doctor'), icon: 'BookOpenIcon' },
        { name: 'General Detail by Doctor', href: route('opd.reports-general-detail-by-doctor'), icon: 'BookOpenIcon' },
        { name: 'Specialist Detail', href: route('opd.reports-specialist-detail'), icon: 'BookOpenIcon' },
        { name: 'General Detail', href: route('opd.reports-general-detail'), icon: 'BookOpenIcon' },
        { name: 'Consultant Tax Report', href: route('opd.reports-consultant-tax'), icon: 'BookOpenIcon' },
        { name: 'Detail By Amount Report', href: route('opd.reports-detail-by-amount'), icon: 'BookOpenIcon' },
        { name: 'Detail By Doctor Report', href: route('opd.reports-detail-by-doctor'), icon: 'BookOpenIcon' },
        { name: 'Detail By Doctor + Amount Report', href: route('opd.reports-detail-by-doctor-amount'), icon: 'BookOpenIcon' },
        { name: 'MR Summary', href: route('opd.reports-mr-summary'), icon: 'BookOpenIcon' },

    ],
  },
];

const labMenuItems = [
  {
    title: 'Forms',
    items: [


      { name: 'Packages', href: props.hasLabModule ? route('packages.index') : null },
      { name: 'Sub-Packages', href: props.hasLabModule ? route('sub-packages.index') : null },
      { name: 'Patient Tests', href: props.hasLabModule ? route('patient-tests.index') : null },
    //   { name: 'Other Receipts', href: route('patient-tests.other_receipt') },
    ],
  },
  {
    title: 'Supporting Forms',
    items: [
    { name: 'Lab Tests', href: props.hasLabModule ? route('lab-tests.index') : null },
    // { name: 'Other Receipts', href: props.hasLabModule ? route('lab-tests.other-receipt') : null },
    { name: 'Out Door Test', href: props.hasLabModule ? route('lab-tests.outdoor') : null },
    { name: 'Test groups', href: props.hasLabModule ? route('test-groups.index') : null },
      { name: 'Units', href: props.hasLabModule ? route('units.index') : null },
      { name: 'Test Categories', href: props.hasLabModule ? route('test-categories.index') : null },
      { name: 'Widal Tests', href: props.hasLabModule ? route('widal-tests.index') : null },
    ],
  },
  {
    title: 'Reports',
    items: [
    //   { name: 'Test Reports By Month', href: '' },
      { name: 'Test Count Reports', href: props.hasLabModule ? route('lab.test-count') : null, icon: 'DocumentPlusIcon' },
      { name: 'Package Count Reports', href: props.hasLabModule ? route('lab.package-count') : null, icon: 'DocumentPlusIcon' },
      { name: 'Referral By Reports', href: props.hasLabModule ? route('lab.referral-by') : null, icon: 'DocumentPlusIcon' },
      { name: 'Payment Reports', href: props.hasLabModule ? route('lab.payment-report') : null, icon: 'DocumentPlusIcon' },
      { name: 'CareOFF Reports', href: props.hasLabModule ? route('lab.careoff-report') : null, icon: 'DocumentPlusIcon' },
      { name: 'LabTest Summary Reports', href: props.hasLabModule ? route('lab.report-lab-detail-summary') : null, icon: 'DocumentPlusIcon' },
      { name: 'Critical Patients Report', href: props.hasLabModule ? route('lab.critical-report') : null, icon: 'DocumentPlusIcon' },
   //   { name: 'LabTest Detail Reports', href: route('lab.report-lab-detail'), icon: 'DocumentPlusIcon' },
    ],
  },
];
const otherMenuItems = [
  {
    title: 'Forms',
    items: [
    { name: 'Other Receipts Listing', href: props.hasLabModule ? route('patient-tests.other_receipt') : null },
    ],
  },
  {
    title: 'Supporting Forms',
    items: [
    { name: 'Other Receipts', href: props.hasLabModule ? route('lab-tests.other-receipt') : null },
    ],
  },
];

const IPDMenuItems = [
  {
    title: 'Forms',
    items: [
        { name: 'Admission', href: route('admissions.index'), icon: 'BanknotesIcon' },
        { name: 'Discharge', href: route('admissions.discharge'), icon: 'BanknotesIcon' },
        { name: 'Patient case studies', href: route('patient-case-studies.index'), icon: 'BanknotesIcon' },
    ],
  },
  {
    title: 'Supporting Forms',
    items: [
        { name: 'Room types', href: route('room-types.index'), icon: 'ListBulletIcon' },
        { name: 'Rooms', href: route('rooms.index'), icon: 'ListBulletIcon' },
        { name: 'Room beds', href: route('room-beds.index'), icon: 'ListBulletIcon' },
    ],
  },
  {
    title: 'Reports',
    items: [
      { name: 'Patient List', href: route('ipd.reports-patient-list'), icon: 'ListBulletIcon' },
      { name: 'Admit Patient List', href: route('ipd.reports-admit-patient-list'), icon: 'ListBulletIcon' },
      { name: 'Admission Advance Refered By', href: route('ipd.reports-admission-advance-refered-by'), icon: 'ListBulletIcon' },
      { name: 'Admission Advance Department By', href: route('ipd.reports-admission-advance-dept-by'), icon: 'ListBulletIcon' },
      { name: 'Admission Advance Service By', href: route('ipd.reports-admission-advance-service-by'), icon: 'ListBulletIcon' },
      { name: 'Indoor Billing Detail', href: route('ipd.reports-indoor-billing-detail'), icon: 'ListBulletIcon' },
      { name: 'Indoor Billing By Careoff', href: props.hasDonationModule ? route('ipd.reports-indoor-billing-by-careoff') : null, icon: 'ListBulletIcon' },
      { name: 'Indoor Billing By Careoff Authority', href: props.hasDonationModule ? route('ipd.reports-indoor-billing-by-careoff-authority') : null, icon: 'ListBulletIcon' },
      { name: 'Indoor Billing Detail By Doctor', href: route('ipd.reports-indoor-billing-detail-by-doctor'), icon: 'ListBulletIcon' },
      { name: 'Billing File No.', href: route('ipd.reports-billing-file-no'), icon: 'ListBulletIcon' },
      { name: 'Indoor Summary Detail', href: route('ipd.reports-indoor-summary-detail'), icon: 'ListBulletIcon' },
      { name: 'Indoor Summary Detail By File No.', href: route('ipd.reports-indoor-summary-detail-by-file-no'), icon: 'ListBulletIcon' },
    ],
  },
];



const OTMenuItems = [
  {
    title: 'Forms',
    items: [
      { name: 'Operations', href: route('operations.index'), icon: 'BanknotesIcon' },
    ],
  },

  {
    title: 'Reports',
    items: [
      { name: 'Patient List', href: route('ot.reports-patient-list'), icon: 'ListBulletIcon' },
      { name: 'Anesthesia Doctor Report', href: route('ot.report-anesthesia-doctor'), icon: 'ListBulletIcon' },
      { name: 'Procedure Wise Report', href: route('ot.report-procedure-type'), icon: 'ListBulletIcon' },

    ],
  },
];


const accountMenuItems = [
  {
    title: 'Forms',
    items: [
        { name: 'Cash Receipt Voucher', href: props.hasAccountsModule ? route('vouchers.create', {'vt': 1} ) : null, icon: 'CRIcon' },
        { name: 'Cash Payment Voucher', href: props.hasAccountsModule ? route('vouchers.create', {'vt': 2}) : null, icon: 'CPIcon' },
        { name: 'Bank Receipt Voucher', href: props.hasAccountsModule ? route('vouchers.create', {'vt': 3}) : null, icon: 'BanknotesIcon' },
        { name: 'Bank Payment Voucher', href: props.hasAccountsModule ? route('vouchers.create', {'vt': 4}) : null, icon: 'BanknotesIcon' },
        { name: 'Journal Voucher', href: props.hasAccountsModule ? route('vouchers.create', {'vt': 5}) : null, icon: 'BanknotesIcon' },
        { name: 'Vouchers Audit', href: props.hasAccountsModule ? route('vouchers-audit.index') : null, icon: 'BanknotesIcon' },
        // { name: 'Vouchers', href: route('vouchers.index'), icon: 'BanknotesIcon' },
    ],
  },
  {
    title: 'Supporting Forms',
    items: [
        { name: 'Control accounts', href: props.hasAccountsModule ? route('control-accounts.index') : null, icon: 'OneIcon' },
        { name: 'Sub control accounts', href: props.hasAccountsModule ? route('sub-control-accounts.index') : null, icon: 'TwoIcon' },
        { name: 'Sub head accounts', href: props.hasAccountsModule ? route('sub-head-accounts.index') : null, icon: 'ThreeIcon' },
        { name: 'Account codes', href: props.hasAccountsModule ? route('account-codes.index') : null, icon: 'FourIcon' },
        { name: 'Chart of accounts', href: props.hasAccountsModule ? route('chart-of-accounts.index') : null, icon: 'FiveIcon' },
        { name: 'Voucher types', href: props.hasAccountsModule ? route('voucher-types.index') : null, icon: 'FolderPlusIcon' }
    ],
  },
  {
    title: 'Reports',
    items: [
      { name: 'Trial Balance', href: props.hasAccountsModule ? route('trial-balance') : null, icon: 'ListBulletIcon' },
      { name: 'Balance Sheet', href: props.hasAccountsModule ? route('balance-sheet') : null, icon: 'ListBulletIcon' },
      { name: 'Notes to Balance Sheet', href: props.hasAccountsModule ? route('balance-sheet-notes') : null, icon: 'ListBulletIcon' },
      { name: 'Income Statement', href: props.hasAccountsModule ? route('income-statement') : null, icon: 'ListBulletIcon' },
      { name: 'Income Statement Notes', href: props.hasAccountsModule ? route('income-statement-notes') : null, icon: 'ListBulletIcon' },
      { name: 'Cash Book', href: props.hasAccountsModule ? route('cash-books') : null, icon: 'ListBulletIcon' },
      { name: 'Journal Book', href: props.hasAccountsModule ? route('journal-books') : null, icon: 'ListBulletIcon' },
      { name: 'General Ledger', href: props.hasAccountsModule ? route('party-ledger') : null, icon: 'ListBulletIcon' },
      { name: 'Vouchers', href: props.hasAccountsModule ? route('vouchers.index') : null, icon: 'ListBulletIcon' },
    ],
  },
];
const reportMenuItems = [
  { name: 'Patient report', href: route('patient-reports.index') },
  { name: 'IPD by admission date', href: route('IPD-reports-by-admission-date.index') },
  { name: 'IPD by discharge date', href: route('IPD-reports-by-discharge-date.index') },
  { name: 'IPD by file no.', href: route('IPD-reports-by-file-no.index') },
  { name: 'Indoor billing report', href: route('indoor-billing-reports.index') },
  { name: 'Doctor report', href: route('doctor-reports.index') },
  { name: 'Service report by type', href: route('service-reports-by-type.index') },
  { name: 'Service billing report', href: route('service-billing-reports.index') },

];


const expenseMenuItems = [
  { name: 'Expense', href: props.hasExpenseModule ? route('expenses.index') : null, icon: 'DocumentPlusIcon' },
  { name: 'Expense Report', href: props.hasExpenseModule ? route('expense.report') : null, icon: 'DocumentPlusIcon' },
];

const donationenuItems = [
  {
    title: 'Forms',
    items: [
        { name: 'Receivable', href: props.hasDonationModule ? route('donors.index') : null, icon: 'ListBulletIcon' },
        { name: 'Receivable Funds', href: props.hasDonationModule ? route('donor-funds.index') : null, icon: 'BanknotesIcon' },
    ],
  },
  {
    title: 'Reports',
    items: [
      { name: 'Z/F Details by Date', href: props.hasDonationModule ? route('donation.reports-zf-detail') : null, icon: 'BookOpenIcon' },
      { name: 'Care OFF Summary', href: props.hasDonationModule ? route('donation.reports-careoff-summary') : null, icon: 'BookOpenIcon' }
    ],
  },

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
    NavLink,
    MegaMenu,
    DropdownMenu
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
    <div class="hidden xl:space-x-8 sm:-my-px sm:ml-2 xl:ml-10 sm:flex">
        <!-- <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
            Dashboard
        </NavLink> -->
        <MegaMenu :menuItems="payrollMenuItems" title="Payroll" />
        <MegaMenu v-if="hasInventoryModule" :menuItems="inventoryMenuItems" title="Inventory" />
        <!-- <MegaMenu :menuItems="financeMenuItems" title="Finance" /> -->
        <DropdownMenu :menuItems="settingsMenuItems" title="Settings" />
        <MegaMenu :menuItems="OPDMenuItems" title="OPD" />
        <MegaMenu v-if="hasLabModule" :menuItems="labMenuItems" title="Lab" />
        <MegaMenu v-if="hasLabModule" :menuItems="otherMenuItems" title="Other Receipts" />
        <MegaMenu :menuItems="IPDMenuItems" title="IPD" />
        <MegaMenu :menuItems="OTMenuItems" title="OT" />
        <MegaMenu v-if="hasAccountsModule" :menuItems="accountMenuItems" title="Accounts" />
        <DropdownMenu :menuItems="reportMenuItems" title="Reports" />
        <MegaMenu v-if="hasDonationModule" :menuItems="donationenuItems" title="Donation" />
        <DropdownMenu v-if="hasExpenseModule" :menuItems="expenseMenuItems" title="Expense" />
    </div>
</template>
