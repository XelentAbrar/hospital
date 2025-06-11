<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import DropdownMenu from '@/components/DropdownMenu.vue';

const props = defineProps({
    title: String,
    hasExpenseModule: Boolean,
    hasDonationModule: Boolean,
    hasInventoryModule: Boolean,
});

const formMenuItems = [
  { name: 'Admission', href: route('admissions.index') },
  { name: 'Discharge', href: route('admissions.discharge') },
  { name: 'Patient case studies', href: route('patient-case-studies.index') },
];


const supportingFormMenuItems = [
  { name: 'Room types', href: route('room-types.index') },
  { name: 'Rooms', href: route('rooms.index') },
  { name: 'Room beds', href: route('room-beds.index') },
  { name: 'Expense', href: props.hasExpenseModule ? route('expenses.index') : null, icon: 'DocumentPlusIcon' },
];


const ReportMenuItems = [
  { name: 'Admit Patient List', href: route('ipd.reports-admit-patient-list'), icon: 'ListBulletIcon' },
      { name: 'Admission Advance Refered By', href: route('ipd.reports-admission-advance-refered-by'), icon: 'ListBulletIcon' },
      { name: 'Admission Advance Department By', href: route('ipd.reports-admission-advance-dept-by'), icon: 'ListBulletIcon' },
      { name: 'Admission Advance Service By', href: route('ipd.reports-admission-advance-service-by'), icon: 'ListBulletIcon' },
      { name: 'Indoor Billing Detail', href: route('ipd.reports-indoor-billing-detail'), icon: 'ListBulletIcon' },
      { name: 'Indoor Billing By Careoff', href: route('ipd.reports-indoor-billing-by-careoff'), icon: 'ListBulletIcon' },
      { name: 'Indoor Billing By Careoff Authority', href: route('ipd.reports-indoor-billing-by-careoff-authority'), icon: 'ListBulletIcon' },
      { name: 'Indoor Billing Detail By Doctor', href: route('ipd.reports-indoor-billing-detail-by-doctor'), icon: 'ListBulletIcon' },
      { name: 'Billing File No.', href: route('ipd.reports-billing-file-no'), icon: 'ListBulletIcon' },
      { name: 'Indoor Summary Detail', href: route('ipd.reports-indoor-summary-detail'), icon: 'ListBulletIcon' },
      { name: 'Indoor Summary Detail By File No.', href: route('ipd.reports-indoor-summary-detail-by-file-no'), icon: 'ListBulletIcon' },
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
