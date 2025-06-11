<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import DropdownMenu from '@/components/DropdownMenu.vue';

const props = defineProps({
    title: String,
    hasExpenseModule: Boolean,
    hasDonationModule: Boolean,
    hasLabModule: Boolean,
    hasInventoryModule: Boolean,
});

const formMenuItems = [
  { name: 'Lab Tests', href: props.hasLabModule ? route('lab-tests.index') : null },
  { name: 'Out Door Test', href: props.hasLabModule ? route('lab-tests.outdoor') : null },
  { name: 'Patient Tests', href: props.hasLabModule ? route('patient-tests.index') : null },
  { name: 'Other Receipts', href: props.hasLabModule ? route('patient-tests.other_receipt') : null },
  { name: 'Packages', href: props.hasLabModule ? route('packages.index') : null },
  { name: 'Sub-Packages', href: props.hasLabModule ? route('sub-packages.index') : null },
];


const supportingFormMenuItems = [
  { name: 'Test groups', href: props.hasLabModule ? route('test-groups.index') : null },
  { name: 'Units', href: props.hasLabModule ? route('units.index') : null },
  { name: 'Test Categories', href: props.hasLabModule ? route('test-categories.index') : null },
  { name: 'Widal Tests', href: props.hasLabModule ? route('widal-tests.index') : null },
  { name: 'Expense', href: props.hasExpenseModule ? route('expenses.index') : null, icon: 'DocumentPlusIcon' },
];

const ReportMenuItems = [
  { name: 'Expense Report', href: props.hasExpenseModule ? route('expense.report') : null, icon: 'DocumentPlusIcon' },
  { name: 'Test Reports By Month', href: '' },
//   { name: 'Test Count Reports', href: route('lab.test-count')  ,icon: 'DocumentPlusIcon'},
  { name: 'Package Count Reports', href: props.hasLabModule ? route('lab.package-count') : null  ,icon: 'DocumentPlusIcon'},
  { name: 'Referral By Reports', href: props.hasLabModule ? route('lab.referral-by') : null ,icon: 'DocumentPlusIcon'},
  { name: 'Payment Reports', href: props.hasLabModule ? route('lab.payment-report') : null ,icon: 'DocumentPlusIcon'},
  { name: 'CareOFF Reports', href: props.hasLabModule ? route('lab.careoff-report') : null ,icon: 'DocumentPlusIcon'},
  { name: 'Critical Patients Report', href: props.hasLabModule ? route('lab.critical-report') : null ,icon: 'DocumentPlusIcon'},
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
        <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
            Dashboard
        </NavLink>
        <DropdownMenu :menuItems="formMenuItems" title="Form" />
        <DropdownMenu :menuItems="supportingFormMenuItems" title="Supporting Form" />
        <DropdownMenu :menuItems="ReportMenuItems" title="Reports" />
    </div>
</template>
