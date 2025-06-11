<template>
    <div class="px-4 pt-4">
      <div class="bg-white overflow-hidden shadow-xl rounded-lg">
        <div class="flex justify-between items-center border-b border-gray-200 px-6 py-4">
          <h1 class="text-3xl font-bold text-gray-800">{{ employee?.name }}</h1>
          <select
            v-model="selectedFilter"
            @change="fetchAppointments"
            class="p-2 border w-64 border-gray-300 rounded-md focus:ring focus:ring-indigo-300 focus:outline-none"
          >
            <option value="today">Today</option>
            <option value="3days">Prev 3 Days</option>
            <option value="week">This Week</option>
            <option value="month">This Month</option>
          </select>
        </div>
        <div class="mt-6 px-6 py-4">
          <h2 class="text-2xl font-bold text-gray-800 mb-4">Appointments</h2>
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow">
              <thead>
                <tr class="bg-gray-100 text-gray-700">
                  <th class="px-4 py-2 text-center text-md font-bold">Checked <span class="px-2">
                    <input
                      type="checkbox"
                      v-model="selectAll"
                      @change="toggleSelectAll"
                      class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                    />
                  </span></th>
                  <th class="px-4 py-2 text-left text-md font-bold">Appointment Id</th>
                  <th class="px-4 py-2 text-left text-md font-bold">Appointment Date</th>
                  <th class="px-4 py-2 text-left text-md font-bold">Patient Name</th>
                  <th class="px-4 py-2 text-left text-md font-bold">Slip No</th>
                  <th class="px-4 py-2 text-left text-md font-bold">Doc Serial</th>
                  <!-- <th class="px-4 py-2 text-left text-md font-bold">Checked</th> -->
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="appointment in doctorAppointments"
                  :key="appointment.id"
                  class="border-b hover:bg-gray-50 transition"
                >
                  <td class="px-4 py-2 text-md text-gray-800 text-center">
                    <input
                      type="checkbox"
                      v-model="selectedAppointments"
                      :value="appointment.id"
                      @change="updateDoctorCheck(appointment.id, $event.target.checked)"
                      class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                    />
                  </td>
                  <td class="px-4 py-2 text-md text-gray-800">{{ appointment.id }}</td>
                  <td class="px-4 py-2 text-md text-gray-800">{{ appointment.appointment_date }}</td>
                  <td class="px-4 py-2 text-md text-gray-600">{{ appointment.patient_name }}</td>
                  <td class="px-4 py-2 text-md text-gray-500">{{ appointment.slip_no }}</td>
                  <td class="px-4 py-2 text-md text-gray-500">{{ appointment.doc_serial }}</td>
                  <!-- <td class="px-4 py-2 text-center">
                    <input
                      type="checkbox"
                      :checked="appointment.is_doctor_checked === 1"
                      @change="updateDoctorCheck(appointment.id, $event.target.checked)"
                      class="h-5 w-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                    />
                  </td> -->
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { ref, onMounted, watch } from 'vue';
  import axios from 'axios';
  import {router } from '@inertiajs/vue3';


  const selectedFilter = ref('today');
  const role = ref(null);
  const employee = ref(null);
  const selectAll = ref(false);
  const selectedAppointments = ref([]);
  const doctorAppointments = ref([]);

  const fetchAppointments = async () => {
    try {
      const response = await axios.get('/doctor-dashboard', {
        params: { filterDate: selectedFilter.value }
      });
      role.value = response.data.role;
      employee.value = response.data.employee;
      doctorAppointments.value = response.data.doctorAppointments;
    } catch (error) {
      console.error('Error fetching dashboard data:', error);
    }
  };

  const updateDoctorCheck = async (appointmentId, isChecked) => {
    try {
      const response = await axios.post(`/appointments/${appointmentId}/update`, {
        is_doctor_checked: isChecked ? 1 : 0
      });

      const appointment = doctorAppointments.value.find(app => app.id === appointmentId);
      if (appointment) {
        appointment.is_doctor_checked = response.data.is_doctor_checked;
      }
      router.visit('/dashboard');
    } catch (error) {
      console.error('Error updating doctor check:', error);
    }
  };

  const toggleSelectAll = () => {
    if (selectAll.value) {
      selectedAppointments.value = doctorAppointments.value.map(app => app.id);
      doctorAppointments.value.forEach(app => updateDoctorCheck(app.id, true));
    } else {
      selectedAppointments.value = [];
      doctorAppointments.value.forEach(app => updateDoctorCheck(app.id, false));
    }
  };

  onMounted(() => {
    fetchAppointments();
  });

  watch(selectedFilter, () => {
    fetchAppointments();
  });
  </script>

  <style scoped>
  .hover\:bg-gray-100:hover {
    background-color: #f7fafc;
  }
  </style>
