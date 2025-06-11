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
  { name: 'Patients', href: route('patients.index') },
  { name: 'Appointments', href: route('appointments.index') },
  { name: 'Follow Ups', href: route('follow-ups.index') },
  { name: 'Patient feedbacks', href: route('patient-feedbacks.index') },
];

const supportingFormMenuItems = [
  { name: 'Category', href: route('categories.index') },
  { name: 'Services', href: route('services.index') },
  { name: 'Receivable', href: props.hasDonationModule ? route('donors.index') : null },
  { name: 'Receivable Funds', href: props.hasDonationModule ? route('donor-funds.index') : null },
  { name: 'Expense', href: props.hasExpenseModule ? route('expenses.index') : null, icon: 'DocumentPlusIcon' },
];

const ReportMenuItems = [
  { name: 'Doc Detail Report', href: route('opd.reports-doctor-detail') },
  { name: 'Z/F Details by Date', href: props.hasDonationModule ? route('donation.reports-zf-detail') : null },
  { name: 'Summary by Date', href: route('opd.reports-summary-by-date') },
  { name: 'Summary by Shift', href: route('opd.reports-summary-by-shift') },
  { name: 'Summary Detail by Date', href: route('opd.reports-summary-detail-by-date') },
  { name: 'Specialist By Date', href: route('opd.reports-specialist-by-date') },
  { name: 'General By Date', href: route('opd.reports-general-by-date') },
  { name: 'Specialist Detail by Doctor', href: route('opd.reports-specialist-detail-by-doctor') },
  { name: 'General Detail by Doctor', href: route('opd.reports-general-detail-by-doctor') },
  { name: 'Specialist Detail', href: route('opd.reports-specialist-detail') },
  { name: 'General Detail', href: route('opd.reports-general-detail') },
  { name: 'Consultant Tax Report', href: route('opd.reports-consultant-tax') },
  { name: 'Detail By Amount Report', href: route('opd.reports-detail-by-amount') },
  { name: 'Detail By Doctor Report', href: route('opd.reports-detail-by-doctor') },
  { name: 'Detail By Doctor + Amount Report', href: route('opd.reports-detail-by-doctor-amount') },
  { name: 'Expense Report', href: props.hasExpenseModule ? route('expense.report') : null, icon: 'DocumentPlusIcon' },
  { name: 'Care OFF Summary', href: props.hasDonationModule ? route('donation.reports-careoff-summary') : null, icon: 'BookOpenIcon' },
  { name: 'MR Summary', href: route('opd.reports-mr-summary'), icon: 'BookOpenIcon' },

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
