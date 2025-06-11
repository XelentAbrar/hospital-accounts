<template>
<Head title="General Ledger" />
<AppLayout title="General Ledger">
    <div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-6 sm:px-6">
        <h3 class="font-semibold text-4xl text-primary">General Ledger</h3>
    </div>
    <div class="border-t border-gray-100 py-2 px-4">
        <form @submit.prevent="report.get(route('general-ledger'), report); isLoading = true">
            <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-4">
                <div>
                    <label for="from_date" class="block text-sm font-semibold text-gray-900">
                    From Date
                    </label>
                    <div class="mt-2">
                        <input id="from_date" name="from_date" type="date"  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="report.from_date"/>
                    </div>
                </div>
                <div>
                    <label for="to_date" class="block text-sm font-semibold text-gray-900">
                    To Date
                    </label>
                    <div class="mt-2">
                        <input id="to_date" name="to_date" type="date"  class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="report.to_date"/>
                    </div>
                </div>
                <div class="mt-0 sm:mt-4">
                    <button type="submit" class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary" :disabled="isLoading" :class="{ 'opacity-25': isLoading }">
                    Search
                    </button>
                    <button
                    class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2" @click="print()" v-if="reports.length > 0 && !isLoading">
                    Print
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>



    <div class="w-full mx-auto">
        <div class="bg-white shadow-sm rounded-lg">
            <div>
                <div>
                    <!-- <p style="font-size: 24px;font-weight: 600;text-align: center;margin: 0;margin-bottom: 20px;margin-top: 20px;">{{selectedAccount && selectedAccount.acc_desc ? selectedAccount.acc_desc : ''}}</p> -->
                </div>
                <div class="mt-2 flow-root" id="printData">
                    <div style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
                        <div class="print-only">
                            <div class="print-page-number">
                                <span class="page-number"></span>
                                </div>
                            <div>
                                <p style="font-size: 24px;line-height: 1.2;font-weight: 600;text-align: center;margin: 0;">Chiniot General Hospital</p>
                                <p style="font-size: 14px;line-height: 1.2;font-weight: 400;text-align: center;margin: 0;" v-if="projectType === 'hms'">Nawaz Town Sarghoda Road, Faisalabad</p>
                                <p style="font-size: 14px;line-height: 1.2;font-weight: 400;text-align: center;margin: 0;" v-else>209 Jinnah Colony , Faisalabad</p>
                            </div>
                            <div>
                                <p style="font-size: 20px;line-height: 1.2;font-weight: 500;text-align: center;margin: 0;">General Ledger</p>
                            </div>
                            <!-- <div>
                                <p style="font-size: 18px;line-height: 1.2;font-weight: 500;text-align: center;margin: 0;">{{selectedAccount && selectedAccount.acc_desc ? selectedAccount.acc_desc : ''}}</p>
                            </div> -->
                            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin: 6px auto;">
                                <div style="flex: 1; display: flex; align-items: center;">
                                    <h4 style="margin: 0; font-family: sans-serif; width: 100px;">From</h4>
                                    <h4 style="margin: 0 6px; font-weight: bold; width: 6px; text-align: center;">:</h4>
                                    <p style="margin: 0; margin-left: 2px; text-align: left; font-weight: 500; white-space: nowrap;">
                                        {{ new Date(from_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                    </p>
                                </div>
                                <div style="flex: 1; display: flex; align-items: center; justify-content: center;">
                                    <h4 style="margin: 0; font-family: sans-serif; width: 100px;">To</h4>
                                    <h4 style="margin: 0 6px; font-weight: bold; width: 6px; text-align: center;">:</h4>
                                    <p style="margin: 0; margin-left: 2px; text-align: left; white-space: nowrap;">
                                        {{ new Date(to_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                    </p>
                                </div>
                                <div style="flex: 1; display: flex; align-items: center; justify-content: flex-end;">
                                    <h4 style="margin: 0; font-family: sans-serif; width: 100px;">Printed On</h4>
                                    <h4 style="margin: 0 6px; font-weight: bold; width: 6px; text-align: center;">:</h4>
                                    <p style="margin: 0; margin-left: 2px; text-align: left; font-weight: 500; white-space: nowrap;">
                                        {{ new Date().toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="ring-1 ring-gray-300">
                            <div class="border-b-2 border-dashed border-gray-400 border-spacing-4 mb-8">
                                <!-- Ledger Section -->
                                <table class="min-w-full h-auto border-collapse border-spacing-0 divide-y divide-gray-300 overflow-auto mb-8">
                                    <thead>
                                        <tr class="divide-x divide-gray-300">
                                        <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10">Date</th>
                                        <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10">Vou#</th>
                                        <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10">Description</th>
                                        <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10">Debit. (DR)</th>
                                        <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10">Credit. (CR)</th>
                                        <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(report, index) in reports" :key="index">
                                            <tr class="divide-x divide-gray-300">
                                            <td class="border-b-2 border-dashed text-left"> {{ new Date(report?.date).toLocaleDateString('en-GB').replace(/\//g, '-') }}</td>
                                            <td class="border-b-2 border-dashed text-left">{{ report.voucher_no }}</td>
                                            <td class="border-b-2 border-dashed text-left">{{ report.narration }} {{ report.description }}</td>
                                            <td class="border-b-2 border-dashed text-right">{{ withCommas(parseFloat(report.debit).toFixed(2)) }}</td>
                                            <td class="border-b-2 border-dashed text-right">{{ withCommas(parseFloat(report.credit).toFixed(2)) }}</td>
                                            <td class="border-b-2 border-dashed text-right">{{report.debit > report.credit ? 'dr' : 'cr'}} {{ withCommas(parseFloat(balances[index]).toFixed(2)) }}</td>
                                            </tr>
                                        </template>

                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-end px-4 mb-2">
                                <div class="lg:col-start-3 lg:row-end-1 w-3/5">
                                    <div class="rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-300">
                                        <dl class="flex flex-wrap">
                                        <div class="flex-auto pl-4 pt-2">
                                            <dt class="text-base font-semibold leading-6 text-gray-900">Summary</dt>
                                        </div>
                                        <div class="mt-1 flex w-full justify-between gap-x-4 px-4">
                                            <span class="text-sm font-medium text-gray-900">Total Debit</span>
                                            <span class="text-sm font-medium text-gray-900">{{ withCommas(totalDebit.toFixed(2)) }}</span>
                                        </div>
                                        <div class="mt-1 flex w-full justify-between gap-x-4 px-4">
                                            <span class="text-sm font-medium text-gray-900">Total Credit</span>
                                            <span class="text-sm font-medium text-gray-900">{{ withCommas(totalCredit.toFixed(2)) }}</span>
                                        </div>
                                        </dl>
                                        <div class="mt-2 flex justify-between border-t border-gray-400 px-4 py-2">
                                        <span href="#" class="text-base font-semibold text-gray-900">Closing Balance</span>
                                        <span href="#" class="text-base font-semibold text-gray-900">{{ withCommas(((+totalDebit) - totalCredit).toFixed(2)) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
import { ref, computed, onMounted, watch } from "vue";
import dayjs from 'dayjs';
import Multiselect from "vue-multiselect";
import axios from 'axios';

const props = defineProps({
    reports: Array,
    from_date: String,
    to_date: String,
    cr_sum: String,
    dr_sum: String,
});

const formatDate = (date) => {
  const d = new Date(date);
  const day = String(d.getDate()).padStart(2, '0');
  const month = String(d.getMonth() + 1).padStart(2, '0');
  const year = d.getFullYear();

  return `${day}-${month}-${year}`;
};

const report = useForm({
  from_date: props?.from_date ? formatDate(props?.from_date) : formatDate(new Date()),
  to_date: props?.to_date ? formatDate(props?.to_date) : formatDate(new Date()),
});
const projectType = import.meta.env.VITE_PROJECT_TYPE;


const accounts = ref([]);
const isLoading = ref(false);
// const selectedAccount = ref(null);
const openingBalance = ref(0);
const balances = ref([]);


const withCommas = (value) => {
  if (value == null || value == '') {
    return '0';
  }
  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};
const totalDebit = computed(() => {
  return props.reports.reduce((sum, report) => sum + parseFloat(report.debit || 0), 0);
});

const totalCredit = computed(() => {
  return props.reports.reduce((sum, report) => sum + parseFloat(report.credit || 0), 0);
});


const computeBalances = () => {
  let balance = 0;
  balances.value = props.reports.map(report => {
    balance += parseFloat(report.debit || 0) - parseFloat(report.credit || 0);
    return balance;
  });
};

watch(() => props.reports, computeBalances, { immediate: true });

// Function to fetch accounts on component mount
const fetchAccounts = async () => {
    try {
        // const response = await axios.get(route('get-accounts'));
        // accounts.value = response.data;
        // if (!selectedAccount.value) {
        //     selectedAccount.value = accounts.value.find(account => account.id === report.account_id);
        // }
    } catch (error) {
        console.error('Error fetching accounts:', error);
    }
};

// Function to fetch ledger data on form submission
const fetchLedger = async () => {
    try {
        const queryParams = new URLSearchParams({
            from_date: report.from_date,
            to_date: report.to_date,
            account_id: report.account_id,
        }).toString();
        const url = `${route('general-ledger')}?${queryParams}`;
        await axios.get(url);
    } catch (error) {
        console.error('Error fetching ledger data:', error);
    }
};

// Watch for changes in the form data and update the selected account
// watch(() => report.account_id, (newAccountId) => {
//     selectedAccount.value = accounts.value.find(account => account.id === newAccountId);
// });

const print = () => {
  var header_str = '<html><head><title>' + document.title + '</title></head><body>';
  var footer_str = '</body></html>';
  var new_str = document.getElementById('printData').innerHTML;
  var old_str = document.body.innerHTML;
  document.body.innerHTML = header_str + new_str + footer_str;
  window.print();

  return false;
};

onMounted(() => {
  fetchAccounts();
});

</script>
<script>
import Multiselect from "vue-multiselect";
export default {
  components: {
    Multiselect,
    AppLayout,
    Head,
    InertiaLink,
  },
};
</script>
    <style src="vue-multiselect/dist/vue-multiselect.css"></style>
    <style media="print">
    .print-only {
        display: none;
    }
    table {
          width: 100%;
          height: auto;
          border-collapse: collapse;
      }

      table th, table tfoot td{
          padding: 12px 14px;
          font-size:16px;
        }
        table td{
          padding: 10px 12px;
          font-size: 14px;
        }
        @media only screen and (min-width: 1600px) {
          table th, table tfoot td{
            padding: 14px 16px;
            font-size:18px;
          }
        }
        .text_white{
          color: #fff;
        }
    @media print {
        .print-only {
            display: block;
        }
        @page { size: A4; margin: 55px 0px 40px 0px;
            font-family: "Fira Mono";
            font-size: 12px;
            sheet-size: A4;

            @top-right {
                content: 'Page ' counter(page) ' of ' counter(pages);
                margin-right: 20px !important;
                margin-top: 50px !important;
                padding-top: 20px !important;
                padding-bottom: 10px !important;
            }

    }
      /* body { margin: 0; } */
    .a4-size {
        width: 210mm;
        height: 70;
    }
        table th{
        padding: 8px 4px;
        margin: 20px 4px !important;
        font-size: 12px !important;
        line-height: 10px !important;
      }
      table tfoot td{
        padding: 6px;
        font-size: 12px !important;
        line-height: 14px !important;
      }
      table td{
        padding: 4px 4px 3px;
        font-size: 9px !important;
        line-height: 11px !important;
      }
      .text-lg{
        font-size: 14px !important;
        line-height: 14px !important;
      }
      .pt-4{
        padding-top: 10px !important;
    }
    .mt-4{
        margin-top: 10px !important;
    }
    .py-4{
          padding-top: 10px !important;
          padding-bottom: 10px !important;
      }
      .pt-6{
        padding-top: 12px !important;
      }
      .mt-6{
        margin-top: 12px !important;
      }
          .text_white{
            color: #000 !important;
          }
    }
</style>
