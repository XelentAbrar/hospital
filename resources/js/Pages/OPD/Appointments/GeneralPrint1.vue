<template>
  <div
    class="prescription-layout container mx-auto"
    style="min-height: 100vh; display: flex; flex-direction: column"
  >
    <header class="max-w-7xl">
      <div class="flex items-center justify-between align-top">
        <div class="text-lg">
          <span class="font-bold">Sr:</span> <span>{{ appointment.id }}</span>
        </div>
       <div class="flex flex-col text-center          ">
          <div class="font-extrabold text-4xl">CHINIOT GENERAL HOSPITAL</div>
          <div class="font-extrabold text-4xl">{{ appointment?.doctor?.name }}</div>
          <div class="font-extrabold text-2xl">
            <template v-for="(education, index) in appointment?.doctor?.education">
              {{education.degree}}<span v-if="appointment.doctor.education.length - 1 != index">,</span>
            </template>
          </div>

        </div>
        <div>
        &nbsp;
        </div>


        </div>
    </header>
    <div class="max-w-7xl">
      <div class="flex justify-between items-start mx-auto">
        <div>
          <p class="flex justify-between w-full">
            <span class="font-semibold">Fees:</span>
            <span class="text-right w-20">{{ sumAppointmentFees }}</span>
          </p>
          <p class="flex justify-between w-full" v-if="appointment?.zf_fee">
            <span class="font-semibold">Less ZF:</span>
            <span class="text-right w-20">{{ appointment?.zf_fee ?? '' }}</span>
          </p>
          <p class="flex justify-between w-full" v-if="appointment?.zf_fee">
            <span class="font-semibold">Net:</span>
            <span class="text-right w-20">{{ sumAppointmentFees - (appointment?.zf_fee ?? 0) }}</span>
          </p>
        </div>
        <!-- <div>
          <p><span class="font-semibold">Care Off: </span> {{ appointment?.careoff?.name }}</p>
          <p><span class="font-semibold">Authority: </span>Azam sb</p>
        </div> -->
      </div>

      <div class="prescription">
        <p><span class="font-semibold">Name: </span> {{ appointment.patient_name.length > 15 ? appointment.patient_name.slice(0, 15) : appointment.patient_name }}</p>
        <p><span class="font-semibold">Age: </span>{{ patientAge }}</p>
        <p><span class="font-semibold">Sex: </span>{{ appointment?.patient?.gender }}</p>
        <p><span class="font-semibold">Date: </span>{{ formattedDate }}</p>
        <p><span class="font-semibold">Time: </span>{{ formattedTime }}</p>
      </div>

      <div class="border-b border-b-black pt-1 grid grid-cols-4" :class="appointment?.zf_fee ? 'h-[62vh]' : 'h-[70vh]'">
        <div class="border-r border-r-black"></div>
        <div class="col-span-3 pl-2 space-y-64">

        </div>
      </div>

      <div class="border-b border-b-black grid grid-cols-4">
        <div class="border-r border-r-black flex justify-between">
          <p class="w-full text-center"><span class="font-semibold">Recp: </span>{{user}}</p>
        </div>
        <div class="col-span-3 pl-2 flex justify-between">
          <p class="w-full text-center" v-if="appointment?.careoff_id != '' && appointment?.careoff_id != null"><span class="font-semibold">C/O: </span>{{appointment?.careoff_id != '' && appointment?.careoff_id != null ? appointment?.careoff?.name : ''}}</p>
          <p class="w-full text-center"><span class="font-semibold">Authority: </span>{{appointment?.welfare}}</p>
        </div>
      </div>
    </div>
    <div class="flex items-center justify-between text-center max-w-7xl">
      <div>
        <p v-if="projectType === 'hms'">Nawaz Town Sargodha Road, Faisalabad</p>
        <p v-else>209 Jinnah Colony, Faisalabad</p>
        <p v-if="projectType === 'hms'">Ph: 041-8787231-8848060</p>
                        <p v-else>Ph: 041-2618764-2634890</p>
      </div>
      <div>
        <p>{{print_date_time}}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    appointment: Object,
    user: Object,
    print_date_time: String,
  },
  data() {
    return {
      projectType: import.meta.env.VITE_PROJECT_TYPE,
    };
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
    patientAge() {
      let dateString = this.appointment.patient_dob;
      if (dateString) {
        // Parse the input date string to create a Date object
        const [day, month, year] = dateString.split("-");
        const birthDate = new Date(year, month - 1, day);

        // Get today's date
        const today = new Date();

        // Calculate the age
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        const dayDiff = today.getDate() - birthDate.getDate();

        // Adjust age if the current date is before the birth date in the current year
        if (monthDiff < 0 || (monthDiff === 0 && dayDiff < 0)) {
          age--;
        }

        return age;
      }
      return "0";
    },
  },
};
</script>

<style scoped>
.prescription-layout {
  width: 100%;
  padding: 20px;
  font-family: Arial, sans-serif;
}

.header,
.prescription {
  display: flex;
  justify-content: space-around;
}

.header-center,
.header-left,
.header-right {
  text-align: center;
}

.header-left,
.header-right {
  width: 20%;
}

.header-center {
  width: 60%;
}

.header-center h1 {
  margin: 0;
  font-size: 1.5em;
}

.prescription {
  border-top: 1px solid #000;
  border-bottom: 1px solid #000;
}
</style>
