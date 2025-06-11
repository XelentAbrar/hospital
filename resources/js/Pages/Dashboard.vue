<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import AdminDashboard from "./AdminDashboard.vue";
import OPDDashboard from "./OPDDashboard.vue";
import LabDashboard from "./LabDashboard.vue";
import IPDDashboard from "./IPDDashboard.vue";
import OTDashboard from "./OTDashboard.vue";
import { usePage } from '@inertiajs/vue3';
import DoctorDashboard from "./DoctorDashboard.vue";
import { ref,onMounted } from 'vue';
import InventoryDashboard from "./InventoryDashboard.vue";



const props = defineProps({
  doctor_appointments: Array,
  operation_theater: Array,
  follow_ups: Array,
  doctors: { type: Array, required: true },
  careoffs: { type: Array, required: true },
  zf_fees: { type: String, required: true },
  mr_number: { type: String, required: true },
  lab_no: { type: String, required: true },
  patients: { type: Object, required: true },
  services: { type: Array, required: true, default: () => [] },
  servicesIpd: { type: Array, required: true, default: () => [] },
  doctor_appointment_no: { type: Array, required: true, default: () => [] },
  countries: { type: Array, required: true, default: () => [] },
  test_categories: { type: Array, required: true, default: () => [] },
  sub_packages: { type: Array, required: true, default: () => [] },
  lab_tests: { type: Array, required: true, default: () => [] },
  other_tests: { type: Array, required: true, default: () => [] },
  lab_test_details: { type: Array, required: true, default: () => [] },
  widal_tests: { type: Array, required: true, default: () => [] },
  departments: { type: Array, required: true, default: () => [] },
  rooms: { type: Array, required: true, default: () => [] },
  beds: { type: Array, required: true, default: () => [] },
  room_types: { type: Array, required: true, default: () => [] },
  file_no: { type: String, required: true, default: () => [] },
  packages: { type: String, required: true, default: () => [] },

  admin_dashboard: { type: Array, required: true, default: () => [] },
  inventory_dashboard: { type: Array, required: true, default: () => [] },
  hasExpenseModule: String,
  hasDonationModule: String,
  hasInventoryModule: String,
  hasLabModule: String,


});

const page = usePage()
const roles = ref([]);
if(page.props.auth.user?.user_role){
    page.props.auth.user?.user_role.map(user_role => {
        roles.value.push(user_role?.role_id);
    });
}

const menuTab = ref('1');
onMounted(() => {
  const urlParams = new URLSearchParams(window.location.search);
  const tabFromUrl = urlParams.get('menuTab');
  if (tabFromUrl) {
    menuTab.value = tabFromUrl;
  }
});
const setMenuTab = (tab) => {
  menuTab.value = tab;
  const url = new URL(window.location.href);
  url.searchParams.set('menuTab', tab);
  window.history.replaceState({}, '', url);
};

</script>
<script>
export default {
  components: {
    AdminDashboard,
    InventoryDashboard,
    OPDDashboard,
    LabDashboard,
    IPDDashboard,
    OTDashboard,
    DoctorDashboard,
  },
};
</script>

<template>
  <AppLayout title="Dashboard" :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule"  :hasLabModule="hasLabModule" :hasInventoryModule="hasInventoryModule">
    <AdminDashboard v-if="roles.includes(1)" :admin_dashboard="admin_dashboard"  :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule"  :hasLabModule="hasLabModule" :hasInventoryModule="hasInventoryModule" />
    <LabDashboard v-if="roles.includes(2) && hasLabModule" :zf_fees="zf_fees" :careoffs="careoffs" :mr_number="mr_number" :lab_no="lab_no" :test_categories="test_categories" :lab_tests="lab_tests"  :other_tests="other_tests" :lab_test_details="lab_test_details"  :widal_tests="widal_tests" :packages="packages" :sub_packages="sub_packages"  :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule"  :hasLabModule="hasLabModule" :hasInventoryModule="hasInventoryModule" />
    <OPDDashboard v-if="roles.includes(3)" :doctor_appointments="doctor_appointments" :mr_number="mr_number" :follow_ups="follow_ups" :doctors="doctors" :patients="patients" :services="services" :zf_fees="zf_fees" :careoffs="careoffs"  :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule"  :hasLabModule="hasLabModule" :hasInventoryModule="hasInventoryModule" />
    <IPDDashboard v-if="roles.includes(4)" :services="servicesIpd" :departments="departments" :mr_number="mr_number" :doctors="doctors" :rooms="rooms" :beds="beds" :room_types="room_types" :zf_fees="zf_fees" :careoffs="careoffs" :file_no="file_no"  :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule"  :hasLabModule="hasLabModule" :hasInventoryModule="hasInventoryModule"/>
    <AdminDashboard v-if="roles.includes(5)" :admin_dashboard="admin_dashboard"  :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule"  :hasLabModule="hasLabModule" :hasInventoryModule="hasInventoryModule"/>
    <!-- <AdminDashboard v-if="roles.includes(6)" :admin_dashboard="admin_dashboard"/> -->
    <!-- <AdminDashboard v-if="roles.includes(8)" :admin_dashboard="admin_dashboard"/> -->
    <InventoryDashboard v-if="roles.includes(6)" :inventory_dashboard="inventory_dashboard"  :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule"  :hasLabModule="hasLabModule" :hasInventoryModule="hasInventoryModule"/>
    <AdminDashboard v-if="roles.includes(7)" :admin_dashboard="admin_dashboard"  :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule"  :hasLabModule="hasLabModule" :hasInventoryModule="hasInventoryModule"/>
    <AdminDashboard v-if="roles.includes(9)" :admin_dashboard="admin_dashboard"  :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule"  :hasLabModule="hasLabModule" :hasInventoryModule="hasInventoryModule"/>
    <DoctorDashboard v-if="roles.includes(8)" :doctor_appointment_no="doctor_appointment_no"  :hasExpenseModule="hasExpenseModule" :hasDonationModule="hasDonationModule"  :hasLabModule="hasLabModule" :hasInventoryModule="hasInventoryModule"/>
    <template v-if="roles.includes(10)">
      <div class="flex items-center gap-4 w-full mx-auto sm:px-6 lg:px-8 mb-5">
        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md hover:text-gray-900 focus:text-white focus:outline-none focus:bg-blue-900 active:bg-blue-900 transition ease-in-out duration-150" :class="menuTab == '1' ? 'text-white bg-blue-900' : 'text-gray-900 bg-white' "   @click="setMenuTab('1')">OPD Dashboard</button>

        <!-- <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md hover:text-gray-900 focus:text-white focus:outline-none focus:bg-blue-900 active:bg-blue-900 transition ease-in-out duration-150" :class="menuTab == '2' ? 'text-white bg-blue-900' : 'text-gray-900 bg-white' " @click="menuTab = '2'">IPD Dashboard </button> -->
        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md hover:text-gray-900 focus:text-white focus:outline-none focus:bg-blue-900 active:bg-blue-900 transition ease-in-out duration-150" :class="menuTab == '2' ? 'text-white bg-blue-900' : 'text-gray-900 bg-white' " @click="setMenuTab('2')">IPD Dashboard </button>

        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md hover:text-gray-900 focus:text-white focus:outline-none focus:bg-blue-900 active:bg-blue-900 transition ease-in-out duration-150" :class="menuTab == '3' ? 'text-white bg-blue-900' : 'text-gray-900 bg-white' " v-if="hasLabModule" @click="setMenuTab('3')">LAB Dashboard </button>
        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md hover:text-gray-900 focus:text-white focus:outline-none focus:bg-blue-900 active:bg-blue-900 transition ease-in-out duration-150" :class="menuTab == '4' ? 'text-white bg-blue-900' : 'text-gray-900 bg-white' " @click="setMenuTab('4')">OT Dashboard </button>
      </div>
      <OPDDashboard v-if="menuTab == '1'" :doctor_appointments="doctor_appointments" :mr_number="mr_number" :follow_ups="follow_ups" :doctors="doctors" :patients="patients" :services="services" :zf_fees="zf_fees" :careoffs="careoffs" :hasDonationModule="hasDonationModule" />
      <IPDDashboard v-if="menuTab == '2'" :services="servicesIpd" :departments="departments" :mr_number="mr_number" :doctors="doctors" :rooms="rooms" :beds="beds" :room_types="room_types" :zf_fees="zf_fees" :careoffs="careoffs" :file_no="file_no" :hasDonationModule="hasDonationModule"/>
      <LabDashboard v-if="menuTab == '3'" :zf_fees="zf_fees" :careoffs="careoffs" :mr_number="mr_number" :lab_no="lab_no" :test_categories="test_categories" :lab_tests="lab_tests" :other_tests="other_tests" :lab_test_details="lab_test_details" :widal_tests="widal_tests" :packages="packages" :sub_packages="sub_packages" :hasDonationModule="hasDonationModule" />
      <OTDashboard v-if="menuTab == '4'" :zf_fees="zf_fees" :careoffs="careoffs" :mr_number="mr_number" :operation_theater="operation_theater" :hasDonationModule="hasDonationModule"  />
    </template >
  </AppLayout>
</template>

<style scoped>
.progress-ring__circle {
  transition: stroke-dashoffset 0.35s;
  transform: rotate(-90deg);
  transform-origin: 50% 50%;
}
</style>
