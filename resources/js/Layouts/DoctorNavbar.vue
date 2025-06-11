<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import DropdownMenu from '@/components/DropdownMenu.vue';

defineProps({
    title: String,
});

const formMenuItems = [
  { name: 'Countries', href: route('countries.index') },
  { name: 'States', href: route('states.index') },
  { name: 'Cities', href: route('cities.index') },
  { name: 'General Settings', href: route('settings') },
];


const supportingFormMenuItems = [
  { name: 'Countries', href: route('countries.index') },
  { name: 'States', href: route('states.index') },
  { name: 'Cities', href: route('cities.index') },
  { name: 'General Settings', href: route('settings') },
];

const ReportMenuItems = [
  { name: 'Countries', href: route('countries.index') },
  { name: 'States', href: route('states.index') },
  { name: 'Cities', href: route('cities.index') },
  { name: 'General Settings', href: route('settings') },
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
