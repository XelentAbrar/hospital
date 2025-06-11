<template>
    <div class='printDiv'>
        <div style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
                <!-- Logo and Creator Name -->
                <div style="display: flex; width: 100%; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div style="padding-left: 3rem;">
                    <p style="margin: 0; text-align: left; font-weight: 200; border:1px solid #000; width: fit-content; padding: 2px 6px 2px 6px; font-size: x-small;">
                        {{ donationData?.creator?.name }}
                    </p>
                    </div>
                    <img style="width:100px; object-fit: contain;" alt="">
                </div>

                <!-- Hospital Info (centered) -->
                <p style="font-size: 20px; font-weight: 600; text-align: center; margin: 0;">Chiniot General Hospital</p>
                <p style="font-size: 14px; font-weight: 500; text-align: center; margin: 0;" v-if="projectType === 'hms'">Nawaz Town Sarghoda Road, Faisalabad</p>
                <p style="font-size: 14px; font-weight: 500; text-align: center; margin: 0;" v-else>209 Jinnah Colony, Faisalabad</p>
                <p style="font-size: 14px; font-weight: 600; text-align: center; margin: 0;" v-if="projectType === 'hms'">Ph: 041-8848060 | 8787231</p>
                <p style="font-size: 14px; font-weight: 600; text-align: center; margin: 0;" v-else>Ph: 041-2618764-2634890</p>
                <p style="font-size: 14px; font-weight: 500; text-align: center; margin: 0;">
                    (Under supervision of <span style="font-weight: 600;">MOFAD-E-AMMA</span> <br> Chiniot Sheikh Association.Reg:fsd)
                </p>

                <!-- Duplicate Indicator -->
                <h2 v-if="donationData.is_printed == '2'" class="bg-gray-200 text-gray-900 text-center border border-gray-300 text-md font-semibold w-24">Duplicate</h2>
                </div>
            <div style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 10px auto">

                <div style="width: 50%; padding-left: 3rem;">
                  <p style="margin: 0;text-align: left;font-weight: 200;border:1px solid #000;width: fit-content;padding: 2px;padding-right: 20px;padding-left: 8px;font-weight: 200; font-size: x-small;">Slip No {{ donationData?.id }}</p>
                </div>
                <div style="width: 50%;display: flex;justify-content: end;">
                    <div style="display: flex;align-items: center;">
                        <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight: 600;">Date :</h4>
                        <!-- <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4> -->
                        <p style="margin: 0;margin-left: 2px;text-align: left;white-space: nowrap;">{{ new Date(donationData.created_at).toLocaleDateString('en-GB').replace(/\//g, '-') }} {{ donationData.donation_time }}</p>
                    </div>
                </div>

            </div>
            <div>
              <p style="font-size: 22px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 10px;margin-top: 6px;">Donation Receipt</p>
            </div>
            <div>
              <div class="rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-400">
                <div class="pl-4 py-3">
                  <dt class="text-base font-semibold leading-6 text-gray-900">Summary</dt>

                </div>
                <dl class="pb-6 space-y-3">
                  <div class="flex w-full items-center gap-x-4 border-t border-gray-400 px-4 pt-3">
                    <span class="text-sm font-normal leading-6 text-gray-900 w-48">Received From</span>
                    <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                    <span class="text-sm font-normal leading-6 text-gray-900 w-full border-b border-gray-400">{{ donationData?.donor }}</span>
                  </div>
                  <div class="flex w-full items-center gap-x-4 border-t border-gray-400 px-4 pt-3">
                    <span class="text-sm font-normal leading-6 text-gray-900 w-48">Narration</span>
                    <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                    <span class="text-sm font-normal leading-6 text-gray-900 w-full border-b border-gray-400">{{ donationData?.narration }}</span>
                  </div>
                  <div class="flex w-full items-center gap-x-4 px-4">
                    <span class="text-sm font-normal leading-6 text-gray-900 w-48">Amount</span>
                    <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                    <span class="text-sm font-normal leading-6 text-gray-900 w-full border-b border-gray-400">{{ formatNumber(donationData?.charges) }}</span>
                  </div>
                  <div class=" flex w-full items-center gap-x-4 px-4">
                    <span class="text-sm font-normal leading-6 text-gray-900 w-48 whitespace-nowrap">Amount in Words</span>
                    <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                    <span class="text-sm font-normal leading-6 text-gray-900 w-full border-b border-gray-400">{{ numberToWords(donationData?.charges)}}  Rupees Only</span>
                  </div>
                </dl>

              </div>
            </div>
          </div>
          <hr class="border-b-2 border border-dotted border-gray-700">
          <div style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
                <div style="display: flex; width: 100%; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                    <div style="padding-left: 3rem;">
                    <p style="margin: 0; text-align: left; font-weight: 200; border:1px solid #000; width: fit-content; padding: 2px 6px 2px 6px; font-size: x-small;">
                        {{ donationData?.creator?.name }}
                    </p>
                    </div>
                    <img style="width:100px; object-fit: contain;" alt="">
                </div>

                <p style="font-size: 20px; font-weight: 600; text-align: center; margin: 0;">Chiniot General Hospital</p>
                <p style="font-size: 14px; font-weight: 500; text-align: center; margin: 0;" v-if="projectType === 'hms'">Nawaz Town Sarghoda Road, Faisalabad</p>
                <p style="font-size: 14px; font-weight: 500; text-align: center; margin: 0;" v-else>209 Jinnah Colony, Faisalabad</p>
                <p style="font-size: 14px; font-weight: 600; text-align: center; margin: 0;" v-if="projectType === 'hms'">Ph: 041-8848060 | 8787231</p>
                <p style="font-size: 14px; font-weight: 600; text-align: center; margin: 0;" v-else>Ph: 041-2618764-2634890</p>
                <p style="font-size: 14px; font-weight: 500; text-align: center; margin: 0;">
                    (Under supervision of <span style="font-weight: 600;">MOFAD-E-AMMA</span> <br> Chiniot Sheikh Association.Reg:fsd)
                </p>

                <!-- Duplicate Indicator -->
                <h2 v-if="donationData.is_printed == '2'" class="bg-gray-200 text-gray-900 text-center border border-gray-300 text-md font-semibold w-24">Duplicate</h2>
                </div>
            <div style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 10px auto">
                <div style="width: 30%; padding-left: 3rem;">
                    <p style="margin: 0;text-align: left;font-weight: 200;border:1px solid #000;width: fit-content;padding: 2px;padding-right: 20px;padding-left: 8px;font-weight: 200; font-size: x-small;">Slip No {{ donationData?.id }}</p>
                  <!-- <p style="margin: 0;text-align: left;font-weight: 200;border:1px solid #000;width: fit-content;padding: 2px;padding-right: 20px;padding-left: 6px;font-weight: 200; font-size: x-small;">{{ donationData?.creator?.name }}</p> -->
                </div>
                <div style="width: 30%; padding-left: 3rem;">
                </div>
                <div style="width: 40%;display: flex;justify-content: end;">
                    <div style="display: flex;align-items: center;">
                        <h4 style="margin: 0;font-family: sans-serif; width: 100px;font-weight: 600;">Date :</h4>
                        <!-- <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4> -->
                        <p style="margin: 0;margin-left: 2px;text-align: left;white-space: nowrap;">{{ new Date(donationData.created_at).toLocaleDateString('en-GB').replace(/\//g, '-') }} {{ donationData.donation_time }}</p>
                    </div>
                </div>
            </div>
            <div>
              <p style="font-size: 22px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 10px;margin-top: 6px;">Donation Receipt</p>
            </div>
            <div>
              <div class="rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-400">
                <div class="pl-4 py-3">
                  <dt class="text-base font-semibold leading-6 text-gray-900">Summary</dt>
                </div>
                <dl class="pb-6 space-y-3">
                  <div class="flex w-full items-center gap-x-4 border-t border-gray-400 px-4 pt-3">
                    <span class="text-sm font-normal leading-6 text-gray-900 w-48">Received From</span>
                    <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                    <span class="text-sm font-normal leading-6 text-gray-900 w-full border-b border-gray-400">{{ donationData?.donor }}</span>
                  </div>
                  <div class="flex w-full items-center gap-x-4 border-t border-gray-400 px-4 pt-3">
                    <span class="text-sm font-normal leading-6 text-gray-900 w-48">Narration</span>
                    <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                    <span class="text-sm font-normal leading-6 text-gray-900 w-full border-b border-gray-400">{{ donationData?.narration }}</span>
                  </div>

                  <div class="flex w-full items-center gap-x-4 px-4">
                    <span class="text-sm font-normal leading-6 text-gray-900 w-48">Amount</span>
                    <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                    <span class="text-sm font-normal leading-6 text-gray-900 w-full border-b border-gray-400">{{formatNumber(donationData?.charges) }}</span>
                  </div>
                  <div class="flex w-full items-center gap-x-4 px-4">
                    <span class="text-sm font-normal leading-6 text-gray-900 w-48 whitespace-nowrap">Amount in Words</span>
                    <h4 style="margin:0 10px;font-weight:bold; width: 10px;text-align: center;">:</h4>
                    <span class="text-sm font-normal leading-6 text-gray-900 w-full border-b border-gray-400">{{ numberToWords(donationData?.charges)  }} Rupees Only</span>
                  </div>
                </dl>

              </div>
            </div>
          </div>
        </div>
</template>
<script>
export default {
  props: {
    donationData: Object,
    user: Object,
    print_date_time: String,
  },
  data() {
    return {
      projectType: import.meta.env.VITE_PROJECT_TYPE,
    };
  },
  methods: {
    formatNumber(number) {
        return new Intl.NumberFormat().format(number);
    },
    numberToWords(num) {
        if (num === 0) return 'zero';

        const belowTwenty = [
            'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
            'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        ];

        const tens = [
            '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
        ];

        const scales = [
            '', 'thousand', 'million', 'billion', 'trillion', 'quadrillion', 'quintillion'
        ];

        const chunks = this.chunkNumber(num);
        const words = [];

        for (let i = 0; i < chunks.length; i++) {
            if (chunks[i] > 0) {
                words.push(this.convertChunk(chunks[i]) + (scales[i] ? ' ' + scales[i] : ''));
            }
        }

        return words.reverse().join(' ').trim();
    },
    chunkNumber(num) {
        const chunks = [];
        while (num > 0) {
            chunks.push(num % 1000);
            num = Math.floor(num / 1000);
        }
        return chunks;
    },
    convertChunk(chunk) {
        const belowTwenty = [
            'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
            'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        ];
        const tens = [
            '', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'
        ];

        let result = '';

        if (chunk >= 100) {
            result += belowTwenty[Math.floor(chunk / 100)] + ' hundred ';
            chunk %= 100;
        }

        if (chunk >= 20) {
            result += tens[Math.floor(chunk / 10)] + ' ';
            chunk %= 10;
        }

        if (chunk > 0) {
            result += belowTwenty[chunk] + ' ';
        }

        return result.trim();
    }
}



};
</script>
