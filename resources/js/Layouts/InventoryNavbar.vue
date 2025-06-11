<script setup>
import { ref } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import DropdownMenu from '@/components/DropdownMenu.vue';

defineProps({
    title: String,
});


const formMenuItems = [
    { name: 'Demands', href: props.hasInventoryModule ? route('demands.index') : null, icon: 'BanknotesIcon' },
    { name: 'Purchase Order', href: props.hasInventoryModule ? route('purchase-orders.index') : null, icon: 'CreditCardIcon' },
    { name: 'Inward Gatepass', href: props.hasInventoryModule ? route('inward-gatepass.index') : null, icon: 'ArrowPathRoundedSquareIcon' },
    { name: 'Goods Receipt Notes', href: props.hasInventoryModule ? route('goods-receipt-notes.index') : null, icon: 'BuildingStorefrontIcon' },
    { name: 'Material Issue Notes', href: props.hasInventoryModule ? route('material-issue-notes.index') : null, icon: 'PresentationChartLineIcon' },
    { name: 'Material Return Notes', href: props.hasInventoryModule ? route('material-return-notes.index') : null, icon: 'BookOpenIcon' },
];

const supportingFormMenuItems = [
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
];

const ReportMenuItems = [
    { name: 'Item Ledger', href: props.hasInventoryModule ? route('inventory.item-ledger') : null, icon: 'PresentationChartLineIcon' },
    { name: 'Purchase Report', href: props.hasInventoryModule ? route('inventory.purchase-report') : null, icon: 'BookOpenIcon' },
    { name: 'Sales Report', href: props.hasInventoryModule ? route('inventory.sale-report') : null, icon: 'BookOpenIcon' },
    { name: 'Stock Report', href: props.hasInventoryModule ? route('inventory.stock') : null, icon: 'BookOpenIcon' },
    { name: 'Low Stock Report', href: props.hasInventoryModule ? route('inventory.low-stock') : null, icon: 'BookOpenIcon' },
    { name: 'Rate List', href: props.hasInventoryModule ? route('inventory.rate-list') : null, icon: 'BookOpenIcon' },
    { name: 'Expired Products', href: props.hasInventoryModule ? route('inventory.expiry-product') : null, icon: 'BookOpenIcon' },
    { name: 'Return Report', href: props.hasInventoryModule ? route('inventory.return-report') : null, icon: 'BookOpenIcon' },
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
            Inventory Dashboard
        </NavLink>
        <DropdownMenu :menuItems="formMenuItems" title="Form" />
        <DropdownMenu :menuItems="supportingFormMenuItems" title="Supporting Form" />
        <DropdownMenu :menuItems="ReportMenuItems" title="Reports" />
    </div>
</template>

