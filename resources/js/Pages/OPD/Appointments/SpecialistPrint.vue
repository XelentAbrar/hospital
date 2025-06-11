<template>
  <div class="appointment-type-2">
    <div class="header">
      <div class="header-center">
        <h1>CHINIOT GENERAL HOSPITAL</h1>
        <p v-if="projectType === 'hms'">Nawaz Town Sargodha Road, Faisalabad</p>
        <p v-else>209 Jinnah Colony, Faisalabad</p>
        <p v-if="projectType === 'hms'">Ph: 041-8846060-8787231</p>
        <p v-else>Ph: 041-8846060-8787231</p>
      </div>
    </div>

    <div class="details">
      <div class="row flex items-center justify-between border-b border-black pb-2">
        <div>
          <p>DR. {{ appointment.doctor.name }}</p>
          <p><span class="font-semibold">Sr: </span>{{ appointment.id }}</p>
        </div>
        <p class="px-5 py-0.5 border-black border" style="border-radius: 100%">17</p>
        <div>
          <p>
            <span class="font-semibold">Care Off: </span
            >{{ appointment.patient_relation_name }}
          </p>
          <p><span class="font-semibold">Authority: </span>Azam sb</p>
        </div>
      </div>
      <div class="flex items-start justify-between border-b border-black py-2">
        <div class="w-1/3">
            <p class="flex items-center space-x-4 mb-2"><span class="font-semibold w-36">Name: </span><span class="text-center w-40">{{ appointment.patient_name }}</span></p>
            <p class="flex items-center space-x-4 mb-2"><span class="font-semibold w-36">Address: </span><span class="text-center w-40">{{ appointment.patient_address }}</span></p>
            <p class="flex items-center space-x-4 mb-2"><span class="font-semibold w-36">Consultation fee: </span><span class="text-center w-32">{{ sumAppointmentFees }}</span></p>
            <p class="flex items-center space-x-4 mb-2 mt-5"><span class="font-bold w-36">Total: </span><span class="py-1 border-y-[2px] border-black text-center w-32">{{ sumAppointmentFees }}</span></p>
        </div>
        <div class="w-1/3">
            <p class="flex items-center space-x-4 mb-2"><span class="font-semibold w-28">Age: </span>{{ patientAge }}</p>
            <p class="flex items-center space-x-4 mb-2"><span class="font-semibold w-28">Gender: </span>{{ appointment.patient_gender }}</p>
            <p>&nbsp;</p>
            <p class="mt-6">{{ formattedDate }} {{ formattedTime }}</p>
        </div>
      </div>
      <div class="row justify-end">
      </div>
    </div>

  </div>
</template>

<script>
export default {
  props: {
    appointment: Object,
  },
  data() {
    return {
      projectType: import.meta.env.VITE_PROJECT_TYPE,
    };
  },
  mounted() {
    // Automatically trigger the print dialog when the page/component is loaded
    window.print();
  },
  computed: {
    formattedDate() {
    const date = new Date(this.appointment.appointment_date);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();

    return `${day}-${month}-${year}`;
},
    formattedTime() {
      const date = new Date(
        this.appointment.appointment_date +
          " " +
          this.appointment.appointment_time
      );
      return date.toLocaleTimeString("en-GB", {
        hour: "2-digit",
        minute: "2-digit",
      });
    },
    patientAge() {
      const today = new Date();
      const dob = new Date(this.appointment.patient_dob);
      let age = today.getFullYear() - dob.getFullYear();
      const monthDifference = today.getMonth() - dob.getMonth();
      if (
        monthDifference < 0 ||
        (monthDifference === 0 && today.getDate() < dob.getDate())
      ) {
        age--;
      }
      return age;
    },
    sumAppointmentFees() {
      let sum = 0;
      if (
        this.appointment.appointment_details &&
        this.appointment.appointment_details.length > 0
      ) {
        sum = this.appointment.appointment_details.reduce(
          (acc, detail) => acc + parseFloat(detail.fee),
          0
        );
      }
      return sum;
    },
  },
};
</script>

<style scoped>
.appointment-type-2 {
  font-family: Arial, sans-serif;
  width: 100%;
  padding: 20px;
}

.header-center {
  text-align: center;
}

.header-center h1 {
  margin: 0;
  font-size: 1.5em;
}

.details,
.fees,
.footer {
  margin-top: 10px;
}

.row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
}
</style>
