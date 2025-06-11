<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import DropdownMenu from '@/components/DropdownMenu.vue';

defineProps({
    title: String,
});

const formMenuItems = [
    { name: 'Employees', href: route('employees.index'), icon: 'UserGroupIcon' },
    { name: 'Manual Attendance', href: route('daily-attendances.index'), icon: 'Bars2Icon' },
    { name: 'Holidays', href: route('holidays.index'), icon: 'Bars2Icon' },
    { name: 'Employee Allowances', href: route('employee-allowances.index'), icon: 'Bars2Icon' },
    { name: 'Employee Advance', href: route('employee-advances.index'), icon: 'Bars2Icon' },
    { name: 'Employee Deduction', href: route('employee-deductions.index'), icon: 'Bars2Icon' },
    { name: 'Warnings', href: route('employee-warnings.index'), icon: 'Bars2Icon' },
    { name: 'Promotion', href: route('employee-promotions.index'), icon: 'Bars2Icon' },
    { name: 'Termination', href: route('employee-terminations.index'), icon: 'Bars2Icon' },
    { name: 'Leave Applications', href: '#', icon: 'Bars2Icon' },
];

const supportingFormMenuItems = [
    { name: 'Department', href: route('departments.index'), icon: 'DocumentPlusIcon' },
    { name: 'Designation', href: route('designations.index'), icon: 'DocumentPlusIcon' },
    { name: 'Allowance', href: route('allowances.index'), icon: 'DocumentPlusIcon' },
    { name: 'Shift', href: route('shifts.index'), icon: 'DocumentPlusIcon' },
    { name: 'Attendance machines', href: route('attendance-machines.index'), icon: 'DocumentPlusIcon' },
    { name: 'Roster types', href: route('roster-types.index'), icon: 'DocumentPlusIcon' },
    { name: 'Grades', href: route('grades.index'), icon: 'DocumentPlusIcon' },
    { name: 'Employee shift roster', href: route('employee-shift-roster.index'), icon: 'DocumentPlusIcon' },
    { name: 'Leave Types', href: route('leave-types.index'), icon: 'DocumentPlusIcon' },
];

const ReportMenuItems = [
    { name: 'Daily Attendance Report', href: '#', icon: 'DocumentTextIcon' },
    { name: 'Employee Report', href: '#', icon: 'DocumentTextIcon' },
    { name: 'Monthly Attendance Report', href: '#', icon: 'DocumentTextIcon' },
    { name: 'Department Wise Attendance Report', href: '#', icon: 'DocumentTextIcon' },
    { name: 'Daily Absent Report', href: '#', icon: 'DocumentTextIcon' },
    { name: 'Monthly Absent Report', href: '#', icon: 'DocumentTextIcon' },
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
