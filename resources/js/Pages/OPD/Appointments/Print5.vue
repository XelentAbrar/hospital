<template>
    <div class="a4-size relative mx-auto flex flex-col rounded-lg shadow-md overflow-hidden">
      <div class="relative flex-initial">
      <div class="absolute top-0 left-0">
        <svg class="w-auto h-32" viewBox="0 0 117 43" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M14.5 0V32C35.1291 20.0986 90.579 5.90201 116.365 0.141406L14.5 0Z" fill="#F5B553"/>
          <path d="M0 0V42.5C16 23.7 77.3333 6.33333 106 0H0Z" fill="#231F20"/>
          </svg>
      </div>

      <div class="flex items-center justify-end pt-8 pr-4">
      <div class="-mb-7">
          <h2 v-if="appointment.is_printed == '2'" class="bg-gray-200 text-gray-900 border border-gray-300 text-center text-md font-semibold w-24">Duplicate</h2>

        <h2 class="doctor-info text-xl font-semibold">{{ appointment?.doctor?.name }}</h2>
        <h3 class="doctor-info">

          <template v-for="(education, index) in appointment?.doctor?.education">
            {{education.degree}}<span v-if="appointment.doctor.education.length - 1 != index">,</span>
          </template>
        </h3>
      </div>
    </div>

      <div>
        <svg class="w-full h-auto" viewBox="0 0 344 53" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M115 1.50003C62.5 4 17 22.9999 0.5 32.9999C37.7 13.3998 88.5 9 115 7.00003C141.098 5.03039 185.667 12.9999 205 18C207.4 18.4 244.333 28.8334 262.5 34C276.5 40 322.333 48.1667 343.5 51.5V39C315.1 36.6 269.333 25.3334 250 20C226.8 13.6 201.333 8.66661 191.5 6.9999C160.7 0.599902 127.667 0.500034 115 1.50003Z" fill="#D1D3D4" stroke="#D1D3D4"/>
          </svg>

          <div class="flex justify-end items-center px-3 rounded py-2  ">
               Mr #:  {{ mr_number }} Slip #:  {{ appointment?.slip_no }}  &nbsp; Serial #:  {{ appointment?.doc_serial }}
        </div>
      </div>

      <div class="header-accent -mt-28 pl-16 mb-6 flex items-center space-x-8 text-center">
        <img class="h-auto w-20 mb-2" src="/build/assets/logo-20b0503a.png" alt="" />
        <div>
          <h1 class="doctor-info text-2xl font-bold">CHINIOT GENERAL HOSPITAL</h1>
          <div class="text-center text-sm">
            <p v-if="projectType === 'hms'">Nawaz Town Sargodha Road, Faisalabad</p>
            <p v-else>209 Jinnah Colony, Faisalabad</p>
            <p v-if="projectType === 'hms'">Ph: 041-8787231-8848060 </p>
            <p v-else>Ph: 041-2618764-2634890</p>

          </div>

        </div>

      </div>

    </div>

    <div class="flex-auto px_20">
      <div class="flex w-full gap-4 mb-4 border border-dashed border-gray-300 px-2 py-3">
        <div class="space-y-1">
            <div class="whitespace-nowrap "><span class="font-bold">Sr#</span>{{ appointment.id }}</div>
            <div class="whitespace-nowrap float-left"><span class="font-bold">Fees:</span> {{ withCommas(sumAppointmentFees ?? 0) }}</div>
        </div>
          <div class="space-y-2">
            <div class="whitespace-nowrap text-sm"><span class="font-bold">Name:</span> {{ appointment.patient_name }}</div>
            <div class="whitespace-nowrap"><span class="font-bold">Date:</span>{{ formattedDate }}</div>
          </div>
          <div class="space-y-2">
            <div class="whitespace-nowrap"><span class="font-bold">Less ZF:</span> {{ withCommas(appointment?.zf_fee ?? 0) }}</div>
            <div class="whitespace-nowrap"><span class="font-bold">Age/Sex:</span> {{ appointment.patient_age }} / {{ appointment.patient_gender }}</div>
          </div>
        <div class="space-y-2">
          <div class="whitespace-nowrap mr-6"><span class="font-bold">Time:</span> {{ appointment.appointment_time }}</div>
          <div class="whitespace-nowrap"><span class="font-bold">Net:</span> {{ withCommas(+sumAppointmentFees - +(+appointment?.zf_fee + +appointment?.donor_fee ?? 0)) }}</div>
          <div class="whitespace-nowrap"><span class="font-bold">Net:</span> {{ appointment?.careoff?.name }}</div>
        </div>
      </div>
      <div style="height: 37%;" class="prescription-area flex flex-grow border-t-2 border-gray-300">
          <div class="left">
            <div>
                          <ul class="flex-1 space-y-4 mt-8 font-semibold">
                              <li>BP</li>
                              <li>Temp</li>
                              <li>Spo2</li>
                              <li>RBS</li>
                          </ul>
                        </div>
          </div>

          <div class="right">
              <h1 class="text-2xl leading-tight tracking-tighter">R<sub>x</sub></h1>
          </div>
      </div>
      <div class="mb-4 flex justify-between border-b-2 border-t-2 border-gray-300 p-2">
          <span class="font-semibold text-xs">Recp: {{ appointment?.creator?.name }}</span>
          <span class="font-semibold text-md">Authority: {{appointment?.welfare}}</span>
      </div>
      <div id="scissors">
        <div></div>
      </div>
      <div style="height: 37%;" class="prescription-area flex flex-grow border-t-2 border-gray-300 mt-1">
        <div class="left p-2">
          <div class="whitespace-nowrap"><span class="font-bold">Sr#</span> {{ appointment.id }}</div>
        </div>
        <div class="right p-2">
          <div class="whitespace-nowrap text-sm"><span class="font-bold">Name:</span> {{ appointment.patient_name }}</div>
        </div>
    </div>
    <div class="flex justify-between border-b border-t-2 border-gray-300 pb-4">
        <span class=" text-xs">Recp: {{ appointment?.creator?.name }}</span>
        <span class="font-semibold text-xs">Authority: {{appointment?.welfare}}</span>
    </div>
  </div>
    <!-- <div class="flex-initial text-center pb-2">
      <p>Nawaz Town Sargodha Road, Faisalabad Ph: 041-8787231-8848060</p>
    </div> -->
    </div>
  </template>

  <script>
  export default {
    props: {
      appointment: Object,
      user: Object,
      print_date_time: String,
      mr_number: String,
    },
    data() {
    return {
      projectType: import.meta.env.VITE_PROJECT_TYPE,
      duplicateTextVisible: false,
    };
  },

    methods:{
        checkDuplicateTextVisibility() {
      const storedFlag = localStorage.getItem('duplicateTextVisible');
      console.log('Stored flag:', storedFlag);
      if (storedFlag === 'true') {
        this.duplicateTextVisible = true;
      }
    },

    setDuplicateTextVisibility() {
      setTimeout(() => {
        if (this.appointment.is_printed === 1) {
          this.duplicateTextVisible = true;
          localStorage.setItem('duplicateTextVisible', 'true');
        }
      }, 320000);
    },
      handleKeydown(event) {
      if (event.ctrlKey && event.key === 'p') {
        event.preventDefault();
        window.location.reload();
      }
    },
      withCommas(value) {
          console.log('value', value)
        if(value == null || value == ''){
          return '0';
        }
          return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      },
    },
    computed: {
        formattedDate() {
    const date = new Date(this.appointment.appointment_date);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = String(date.getFullYear()).slice(-2);

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
    mounted() {
    this.checkDuplicateTextVisibility();
    this.setDuplicateTextVisibility();
    // setTimeout(() => {
    //     window.close();
    //   }, 1000);
    setTimeout(() => {
      window.print();
      setTimeout(() => {
        window.close();
      }, 500);
    }, 500);
//   },
//   beforeDestroy() {
//     setTimeout(() => {
//     //   window.print();
//       // Close the tab after the print dialog
//       setTimeout(() => {
//         window.close();
//       }, 500); // Allow a short delay to ensure the print dialog is processed
//     }, 500); // Delay before triggering print
  },
  };
  </script>

  <style scoped>
          @import url('https://fonts.googleapis.com/css2?family=Yellowtail&display=swap');
    body {
  pointer-events: none;
}
          @media print {
            @page { size: A4; margin: 0; }
            body { margin: 0; }
        }

      .a4-size {
          width: 210mm;
          height: 297mm;
      }
      .p_20{
            padding: 20mm;
        }
        .px_20{
            padding-left: 20mm;
            padding-right: 20mm;
        }
      .prescription-area .left {
          width: 25%;
          border-right: 2px solid #d1d5db;
      }
      .prescription-area .right {
          width: 75%;
          padding-left: 16px;
      }
      .doctor-info {
          font-family: 'Helvetica', Arial, sans-serif;
      }
      .header-accent {
          border-bottom: 4px solid #f5b553;
          padding-bottom: 4px;
      }

#scissors {
    height: 25px;
    width: 100%;
    margin: auto auto;
    background-image: url('http://i.stack.imgur.com/cXciH.png');
    background-repeat: no-repeat;
    background-position: right;
    background-size: contain;
    position: relative;
    transform: rotate(180deg);
}
#scissors div {
    position: relative;
    width: 97%;
    top: 50%;
    border-top: 2px dashed black;
    margin-top: -12px;
 }
  </style>
