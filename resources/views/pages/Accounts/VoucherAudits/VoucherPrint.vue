<template>
    <div class='printDiv'>
        <div style="font-family: Arial, Helvetica, sans-serif; padding: 10px; border: 1px solid #e0e0e0; border-radius: 10px; width: 100%; background-color: #f9f9f9;">
  <div style="text-align: center; margin-bottom: 5px;">
    <h1 style="font-size: 20px; font-weight: bold; color: #2c3e50;">Chiniot General Hospital</h1>
    <p style="font-size: 12px; margin: 0; color: #7f8c8d;" v-if="projectType === 'hms'">Nawaz Town Sarghoda Road, Faisalabad</p>
    <p style="font-size: 12px; margin: 0; color: #7f8c8d;" v-else >209 Jinnah Colony , Faisalabad</p>
    <!-- <p style="font-size: 12px; margin: 0; color: #7f8c8d;">Ph: 041-8848060 | 8787231</p> -->
    <!-- <p class="text-xs" v-if="projectType === 'hms'" style="font-size: 12px; margin: 0; color: #7f8c8d;">Ph: 041-8848060-8787231</p> -->
    <!-- <p class="text-xs" style="font-size: 12px; margin: 0; color: #7f8c8d;" v-else>Ph: 041-2618764-2634890</p> -->
    <p style="font-size: 12px; font-style:inherit; margin: 0; color: #7f8c8d;"  v-if="projectType === 'hms'">(Under supervision of <strong>MOFAD-E-AMMA</strong> Chiniot Sheikh Association)</p>
    <div style="display: flex;align-items: center;justify-content: space-between;flex: initial;width: 100%;margin: 0 auto">
                <div style="width: 50%; padding-left: 3rem;" v-if="projectType === 'hms'">
                  <p style="margin: 0;text-align: center;font-weight: 200;border:1px solid #000;width: fit-content;padding: 4px;font-size: 12px;line-height: 1;">{{ voucher?.created_by?.name }}</p>

                </div>
            </div>
        </div>


        <h2 style="font-size: 18px; font-weight: bold; text-align: center; margin: 0; color: #2980b9;">{{ voucher?.voucher_type?.voucher_name }}</h2>
        <h2 v-if="voucher.is_printed == '2'" class="bg-gray-200 text-gray-900 text-center border border-gray-300  text-md font-semibold w-24">Duplicate</h2>
  <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
    <div>
      <p style="font-weight: bold; color: #34495e;">Date: {{ new Date(voucher.voucher_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}</p>
      <p style="color: #2c3e50;"></p>
    </div>
    <div style="text-align: right;">
      <p style="font-weight: bold; color: #34495e;">Voucher No: {{ voucher.voucher_no }}</p>
      <p style="color: #2c3e50;"> </p>
    </div>
  </div>
  <div style="background-color: #ffffff; padding: 6px 12px; border-radius: 8px; border: 1px solid #ddd; margin-bottom: 5px; ">
    <h3 style="font-size: 14px; font-weight: bold; color: #16a085;line-height:1;">Summary</h3>
    <div style="display: flex; gap: 24px ;">
      <span style="color: #34495e; font-weight: bolder;font-size: medium;">Narration :</span>
      <span style="color: #2c3e50;font-size: medium;">{{ voucher.narration }}</span>
    </div>

  </div>

  <h4 style="font-weight: bold;font-size: 14px; margin-bottom: 3px; color: #2980b9;">Voucher Details:</h4>
  <table style="width: 100%; border-collapse: collapse; margin-bottom: 5px;">
    <thead>
      <tr style="background-color: #eaeaea;">
        <th style="border: 1px solid #000; padding: 6px; text-align: center;font-weight: 600; color: #34495e;font-size: 14px;">Code</th>
        <th style="border: 1px solid #000; padding: 6px; text-align: center;font-weight: 600; color: #34495e;font-size: 14px;">Head</th>
        <th style="border: 1px solid #000; padding: 6px; text-align: center;font-weight: 600; color: #34495e;font-size: 14px;">Transaction Narration</th>
        <th style="border: 1px solid #000; padding: 6px; text-align: center;font-weight: 600; color: #34495e;font-size: 14px;">Cheque</th>
        <th style="border: 1px solid #000; padding: 6px; text-align: center;font-weight: 600; color: #34495e;font-size: 14px;">Debit</th>
        <th style="border: 1px solid #000; padding: 6px; text-align: center;font-weight: 600; color: #34495e;font-size: 14px;">Credit</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="detail in voucher.voucher_details" :key="detail.id">
          <td style="border: 1px solid #000; padding: 4px; color: #2c3e50;font-size:12px;">{{ detail?.chart_of_account?.acc_code }}</td>
        <td style="border: 1px solid #000; padding: 4px; color: #2c3e50;font-size:12px;">{{ detail?.chart_of_account?.acc_desc }}</td>
        <td style="border: 1px solid #000; padding: 4px; color: #2c3e50;font-size:12px;">{{ detail.transaction_type }} <span v-if="detail?.remarks"> {{detail?.remarks}}</span></td>
        <td style="border: 1px solid #000; padding: 4px; color: #2c3e50;font-size:12px;">{{ detail.transaction_no }}</td>
        <td style="border: 1px solid #000; padding: 4px; color: #2c3e50;font-size:12px; text-align: right;">{{ formatNumber(detail.dr) }}</td>
        <td style="border: 1px solid #000; padding: 4px; color: #2c3e50;font-size:12px; text-align: right;">{{ formatNumber(detail.cr) }}</td>
      </tr>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="4" style="text-align: right; font-weight: bolder;padding: 4px; color: #2c3e50;font-size:14px;">Total :</td>
            <td style="border: 1px solid #000; padding: 4px; color: #2c3e50;font-size:14px;font-weight: 600; text-align: right;">{{ formatNumber(voucher.voucher_details.reduce((total, detail) => total + parseFloat(detail.dr || 0), 0)) }}            </td>
            <td style="border: 1px solid #000; padding: 4px; color: #2c3e50;font-size:14px;font-weight: 600; text-align: right;">{{ formatNumber(voucher.voucher_details.reduce((total, detail) => total + parseFloat(detail.cr || 0), 0)) }}
            </td>
        </tr>
    </tfoot>
  </table>

  <div style="display: flex; justify-content: space-between; margin-top: 40px;">
    <div style="text-align: center; width: 45%;">
        <hr style="border: 1px solid #2c3e50; width: 70%; margin: 0 auto;">
      <p style="font-weight: bold; color: #34495e;">Prepared By:</p>
    </div>
    <div style="text-align: center; width: 45%;">
        <hr style="border: 1px solid #2c3e50; width: 70%; margin: 0 auto;">
      <p style="font-weight: bold; color: #34495e;">Checked By:</p>
    </div>
    <div style="text-align: center; width: 45%;">
        <hr style="border: 1px solid #2c3e50; width: 70%; margin: 0 auto;">
      <p style="font-weight: bold; color: #34495e;">Approved By:</p>
    </div> <div style="text-align: center; width: 45%;">
        <hr style="border: 1px solid #2c3e50; width: 70%; margin: 0 auto;">
      <p style="font-weight: bold; color: #34495e;">Received By:</p>
    </div>
  </div>
</div>
          <!-- <hr class="border-b-1 border border-dotted border-gray-700 my-2"> -->




        </div>
</template>
<script>
export default {
  props: {
    voucher: Object,
    user: Object,
    print_date_time: String,
  },
  data() {
    return {
      projectType: import.meta.env.VITE_PROJECT_TYPE,
    };
  },
  mounted() {
    setTimeout(() => {
      window.print();
      setTimeout(() => {
        window.close();
      }, 1600);
    }, 1200);
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
<style scoped>
@page {
    margin: 1in;
}
.printDiv{
  padding: 20px;
}
@media print {
    @page {
        size: A4;
        margin: 0;
    }
    .printDiv{
  padding: 10px;
}
  }
</style>
