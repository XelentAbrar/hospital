<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import DropdownMenu from '@/components/DropdownMenu.vue';

defineProps({
    title: String,
    hasAccountsModule: Boolean,
});

const formMenuItems = [
  { name: 'Chart of accounts', href: props.hasAccountsModule ? route('chart-of-accounts.index') : null, icon: 'BanknotesIcon' },
  { name: 'Cash Receipt Voucher', href: props.hasAccountsModule ? route('vouchers.create', {'vt': 1} ) : null, icon: 'BanknotesIcon' },
  { name: 'Cash Payment Voucher', href: props.hasAccountsModule ? route('vouchers.create', {'vt': 2}) : null, icon: 'BanknotesIcon' },
  { name: 'Bank Receipt Voucher', href: props.hasAccountsModule ? route('vouchers.create', {'vt': 3}) : null, icon: 'BanknotesIcon' },
  { name: 'Bank Payment Voucher', href: props.hasAccountsModule ? route('vouchers.create', {'vt': 4}) : null, icon: 'BanknotesIcon' },
  { name: 'Journal Voucher', href: props.hasAccountsModule ? route('vouchers.create', {'vt': 5}) : null, icon: 'BanknotesIcon' },
  { name: 'Vouchers Audit', href: props.hasAccountsModule ? route('vouchers-audit.index') : null, icon: 'BanknotesIcon' },
//   { name: 'Vouchers', href: route('vouchers.index'), icon: 'BanknotesIcon' }
];

const supportingFormMenuItems = [
  { name: 'Control accounts', href: props.hasAccountsModule ? route('control-accounts.index') : null, icon: 'BanknotesIcon' },
  { name: 'Sub control accounts', href: props.hasAccountsModule ? route('sub-control-accounts.index') : null, icon: 'BanknotesIcon' },
  { name: 'Sub head accounts', href: props.hasAccountsModule ? route('sub-head-accounts.index') : null, icon: 'BanknotesIcon' },
  { name: 'Account codes', href: props.hasAccountsModule ? route('account-codes.index') : null, icon: 'BanknotesIcon' },
  { name: 'Voucher types', href: props.hasAccountsModule ? route('voucher-types.index') : null, icon: 'BanknotesIcon' },
];

const ReportMenuItems = [
  { name: 'Trial Balance', href: props.hasAccountsModule ? route('trial-balance') : null, icon: 'ListBulletIcon' },
  { name: 'Balance Sheet', href: props.hasAccountsModule ? route('balance-sheet') : null, icon: 'ListBulletIcon' },
  { name: 'Notes to Balance Sheet', href: props.hasAccountsModule ? route('balance-sheet-notes') : null, icon: 'ListBulletIcon' },
  { name: 'Income Statement', href: props.hasAccountsModule ? route('income-statement') : null, icon: 'ListBulletIcon' },
  { name: 'Income Statement Notes', href: props.hasAccountsModule ? route('income-statement-notes') : null, icon: 'ListBulletIcon' },
  { name: 'Cash Book', href: props.hasAccountsModule ? route('cash-books') : null, icon: 'ListBulletIcon' },
  { name: 'Journal Book', href: props.hasAccountsModule ? route('journal-books') : null, icon: 'ListBulletIcon' },
  { name: 'General Ledger', href: props.hasAccountsModule ? route('party-ledger') : null, icon: 'ListBulletIcon' },
  { name: 'Vouchers', href: props.hasAccountsModule ? route('vouchers.index') : null, icon: 'ListBulletIcon' },
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
