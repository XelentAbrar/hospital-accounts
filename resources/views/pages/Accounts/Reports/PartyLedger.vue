<template>
<Head title="General Ledger" />
<AppLayout title="General Ledger">
    <div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-6 sm:px-6">
        <h3 class="font-semibold text-4xl text-primary">General Ledger</h3>
    </div>
    <div class="border-t border-gray-100 py-2 px-4">
        <form @submit.prevent="report.get(route('party-ledger'), report); isLoading = true">
            <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-4">
                <div>
                    <label for="account_id" class="block text-sm font-semibold  text-gray-900">
                    Account
                    </label>
                    <div class="mt-2">
                        <multiselect
                            v-model="selectedAccount"
                            :options="accounts"
                            :searchable="true"
                            :multiple="false"
                            :close-on-select="true"
                            :show-labels="false"
                            placeholder="Select Account"
                            :custom-label="accountLabel"
                            track-by="id"
                            @select="onAccountSelect"
                        />
                    </div>
                </div>
                <div>
                    <label for="from_date" class="block text-sm font-semibold text-gray-900">
                    From Date
                    </label>
                    <div class="mt-2">
                        <input id="from_date" name="from_date" type="text" ref="from_date" autocomplete="from_date" class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="report.from_date"/>
                    </div>
                </div>
                <div>
                    <label for="to_date" class="block text-sm font-semibold text-gray-900">
                    To Date
                    </label>
                    <div class="mt-2">
                        <input id="to_date" name="to_date" type="text" ref="to_date" autocomplete="to_date" class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="report.to_date"/>
                    </div>
                </div>
                <div class="mt-0 sm:mt-7">
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



    <div class="w-full mx-auto pb-2 mb-2 ">
        <div class="bg-white shadow-sm rounded-lg">
            <div style="margin-top: 40px;">
                <div style="margin-top: 40px;">
                    <p
                    v-if="selectedAccount && (selectedAccount.acc_desc || selectedAccount.acc_code)"
                    style="font-size: 24px; line-height: 1.2; font-weight: bold; text-align: center; margin: 0;"
                    >
                    Title:
                    {{ selectedAccount.acc_desc || '' }}
                    <span v-if="selectedAccount.acc_code">({{ selectedAccount.acc_code }})</span>
                    </p>
                </div>
                <div class="mt-2 flow-root" id="printData">
                    <div style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
                        <div class="print-only">

                            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                            <div style="display: flex; align-items: center;">
                                <!-- <h4 style="margin: 0; font-family: sans-serif; width: 100px; font-size: small;">Printed On</h4> -->
                                <!-- <h4 style="margin: 0 6px; font-weight: bold; width: 6px; text-align: center;">:</h4> -->
                                <p style="margin: 0; margin-left: 2px; text-align: left; font-display: 6px; white-space: nowrap; font-size: 7px;">
                                    {{ new Date().toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                </p>
                            </div>
                            <div style="position: absolute; left: 50%; transform: translateX(-50%); text-align: center; margin-top: 3px;">
                                <p style="font-size: 24px; line-height: 1.2; font-weight: 600; margin: 0;">Chiniot General Hospital</p>
                                <p style="font-size: 16px; line-height: 1.2; font-weight: 400; margin: 0;" v-if="projectType === 'hms'">
                                    Nawaz Town Sarghoda Road, Faisalabad
                                </p>
                                <p style="font-size: 16px; line-height: 1.2; font-weight: 400; margin-bottom: 1px;" v-else>
                                    209 Jinnah Colony, Faisalabad
                                </p>
                            </div>
                        </div>
                            <div>
                                <p style="font-size: 16px;line-height: 3.2;font-weight: 500;text-align: center;margin-bottom: 1px; margin: 0;">General Ledger</p>
                            </div>
                            <div>
                                <p style="font-size: 12px;line-height: 1.2;font-weight: 600;text-align: center;margin: 0;">Title : {{selectedAccount && selectedAccount.acc_desc ? selectedAccount.acc_desc : ''}} ({{selectedAccount && selectedAccount.acc_code ? selectedAccount.acc_code : ''}})</p>
                            </div>
                            <div style="border: 1px solid #d1d5db; padding: 6px; border-radius: 4px; box-shadow: 2px 2px 6px rgba(0, 0, 0, 0.1); width: 100%; max-width: 230px; margin: auto; margin-top: 6px;">
                                <div style="display: flex; justify-content: center; align-items: center;">
                                    <!-- From Date -->
                                    <div style="display: flex; align-items: center;">
                                        <h4 style="margin: 0; font-family: sans-serif; width: 100px; font-size: 12px; font-weight: 400;">From :  {{ report?.from_date }}
                                            <!-- {{ new Date(from_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}  -->

                                        </h4>
                                        <h4 style="margin: 0;padding-left: 6px ;font-family: sans-serif; width: 100px; font-size: 12px; font-weight: 400;">To :  {{ report.to_date }}
                                            <!-- {{ new Date(to_date).toLocaleDateString('en-GB').replace(/\//g, '-') }} -->
                                        </h4>

                                    </div>

                                    <!-- To Date -->
                                    <div style="display: flex; align-items: center;">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="ring-0 ring-gray-300">
                            <div class="border-b-0 border border-gray-400  mb-8">
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
                                        <tr>
                                            <td colspan="5" class="border-b  border-gray-400 text-left font-semibold">Opening</td>
                                            <td class="border-b  border-gray-400 text-right font-semibold">
                                            {{ withCommas(Math.round(openingBalance)) }}
                                            <span v-if="openingBalance <= 0">CR</span>
                                            <span v-else>Dr</span>
                                            </td>
                                        </tr>
                                        <template v-for="(report, index) in reports" :key="index">
                                            <tr class="divide-x divide-gray-300">
                                            <td class="border-b-2  border-gray-400 text-left whitespace-nowrap"> {{ report?.date.includes("to") ? report?.date : new Date(report?.date).toLocaleDateString('en-GB').replace(/\//g, '-') }}</td>
                                            <td v-if="projectType === 'jinnah' && role && role.role_id == 7" class="border-b-2  border-gray-400 text-left whitespace-nowrap" style="padding: 0% !important;">
                                                <a :href="route('vouchers.print',report)" class="cursor-pointer" target="_blank">
                                                    {{ report.voucher_type }} {{ report.voucher_no }}
                                                </a>
                                            </td>
                                            <td v-else class="border-b-2  border-gray-400 text-left whitespace-nowrap" style="padding: 0% !important;">
                                                <a :href="route('vouchers.edit',report.id)" class="cursor-pointer" target="_blank">
                                                    {{ report.voucher_type }} {{ report.voucher_no }}
                                                </a>
                                            </td>
                                            <td class="border-b-2  border-gray-400 text-left" v-if="projectType === 'jinnah'" > {{ report.description && report.description.length > 100 ? report.description.substring(0, 100) + '...' : report.description }}  {{ report?.transaction_no }}</td>
                                            <td class="border-b-2  border-gray-400 text-left" v-else>{{ report.narration }} {{ report.description && report.description.length > 100 ? report.description.substring(0, 100) + '...' : report.description }}  {{ report?.transaction_no }}</td>
                                            <td class="border-b-2  border-gray-400 text-right">{{ withCommas(Math.round(report.debit)) }}</td>
                                            <td class="border-b-2  border-gray-400 text-right">{{ withCommas(Math.round(report.credit)) }}</td>
                                            <!-- <td class="border-b-2  border-gray-400 text-right">{{ withCommas(parseFloat(balances[index]).toFixed(2)) }}</td> -->
                                            <!-- <td class="border-b-2  text-right whitespace-nowrap"> {{ withCommas(parseFloat(balances[index]).toFixed(2)) }} <span class="font-bold whitespace-nowrap"> {{balances[index] > 0 ? 'DR' : 'CR'}}</span></td> -->
                                            <td class="border-b-2  text-right whitespace-nowrap">
                                                {{ withCommas(Math.round(balances[index])) }}
                                                <span class="font-bold ml-2 whitespace-nowrap">
                                                    {{ balances[index] > 0 ? 'DR' : 'CR' }}
                                                </span>
                                                </td>

                                            </tr>

                                        </template>

                                        <tr class="border-2  border-black" style="border: 3px solid black !important;">
                                            <th colspan="3"></th>
                                            <th class="text-right">{{ withCommas(Math.round(totalDebit)) }}</th>
                                            <th class="text-right">{{ withCommas(Math.round(totalCredit)) }}</th>
                                            <!-- <th class="text-center">{{  withCommas( Math.round(+openingBalance + +totalDebit - +totalCredit)) }}</th> -->
                                            <th class="text-center whitespace-nowrap">
                                                {{ withCommas(Math.round(+openingBalance + +totalDebit - +totalCredit)) }}
                                                <span v-if="(+openingBalance + +totalDebit - +totalCredit) <= 0">CR</span>
                                                <span v-else>Dr</span>
                                                </th>

                                        </tr>
                                    </tbody>
                                    <!-- <tfoot style="border: 3px dashed black !important;">

                                    </tfoot> -->
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="flex justify-end px-4 mb-2">
                                <div class="lg:col-start-3 lg:row-end-1 w-3/5">
                                    <div class="rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-300">
                                        <dl class="flex flex-wrap">
                                        <div class="flex-auto pl-4 pt-2">
                                            <dt class="text-base font-semibold leading-6 text-gray-900">Summary</dt>
                                        </div>
                                        <div class="mt-2 flex w-full justify-between gap-x-4 border-t border-gray-400 px-4 pt-2">
                                            <span class="text-sm font-medium text-gray-900">Opening Balance</span>
                                            <span class="text-sm font-medium text-gray-900">{{ withCommas(parseFloat(openingBalance).toFixed(2)) }}</span>
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
                                        <span href="#" class="text-base font-semibold text-gray-900">{{ withCommas(((+openingBalance + totalDebit) - totalCredit).toFixed(2)) }}</span>
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
import { Head, useForm, Link as InertiaLink , router} from "@inertiajs/vue3";
import { ref, computed, onMounted,onUnmounted ,watch } from "vue";
import dayjs from 'dayjs';
import Multiselect from "vue-multiselect";
import axios from 'axios';
// import moment from 'moment';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";


const from_date = ref(null);
const to_date = ref(null);

const props = defineProps({
    reports: Array,
    from_date: String,
    to_date: String,
    account_id: String,
    role: String,
    cr_sum: String,
    dr_sum: String,
    opening_balance: Number,
    accounts: Array
});

// const defaultFromDate = dayjs().month() < 6 ? dayjs().year(dayjs().year() - 1).month(6).date(1).format("DD-MM-YYYY") : dayjs().year(dayjs().year()).month(6).date(1).format('YYYY-MM-DD');
// const defaultToDate = dayjs().month() < 6 ? dayjs().year(dayjs().year()).month(5).date(30).format('YYYY-MM-DD') : dayjs().year(dayjs().year() + 1).month(5).date(30).format('YYYY-MM-DD');
const defaultFromDate = dayjs().format("DD-MM-YYYY");
const projectType = import.meta.env.VITE_PROJECT_TYPE;
const report = useForm({
    from_date: props?.from_date || defaultFromDate,
    to_date: props?.to_date || new Date().toLocaleDateString("en-GB").replace(/\//g, "-"),
    account_id: props?.account_id || null,
});

const accounts = ref([]);
const isLoading = ref(false);
const selectedAccount = ref(null);
const openingBalance = ref(props.opening_balance);
const balances = ref([]);

const onAccountSelect = (selectedAccount) => {
    report.account_id = selectedAccount.id;
};

if (report?.account_id) {
  const doctor = props.accounts.find(
    (c) => c.id === parseInt(report?.account_id)
  );
  if (doctor) {
    selectedAccount.value = doctor;
  }
}

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
const handleKeydown = (event) => {
  if (event.ctrlKey && event.key === 'p') {
    event.preventDefault();
    window.location.reload();
  }
};

const computeBalances = () => {
  let balance = parseFloat(openingBalance.value);
  balances.value = props.reports.map(report => {
    balance += parseFloat(report.debit || 0) - parseFloat(report.credit || 0);
    return balance;
  });
};

watch(() => props.reports, computeBalances, { immediate: true });

// Function to fetch accounts on component mount
const fetchAccounts = async () => {
    try {
        const response = await axios.get(route('get-accounts'));
        accounts.value = response.data;
        if (!selectedAccount.value && report.account_id) {
            selectedAccount.value = accounts.value.find(account => account.id === report.account_id);
        }
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
        const url = `${route('party-ledger')}?${queryParams}`;
        await axios.get(url);
    } catch (error) {
        console.error('Error fetching ledger data:', error);
    }
};
const accountLabel = (account) => {
    return `${account.acc_desc} (${account.acc_code})`;
};
// Watch for changes in the form data and update the selected account
watch(() => report.account_id, (newAccountId) => {
    selectedAccount.value = accounts.value.find(account => account.id === newAccountId);
});

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
    const flatpickrOptions = (defaultDate) => ({
        dateFormat: "d-m-Y", // Format: DD-MM-YYYY
        allowInput: true, // Allows manual typing
        clickOpens: true, // Ensures the picker still works
        defaultDate: defaultDate || new Date(), // Use provided default date or current date
    });

    flatpickr(to_date.value, flatpickrOptions(report.to_date));
    flatpickr(from_date.value, flatpickrOptions(report.from_date));
  fetchAccounts();
  window.addEventListener('keydown', handleKeydown);

});


onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
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
    <style>
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
          padding: 8px 10px;
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
      /* body { margin: 10px 0px 4px 0px ; } */
    .a4-size {
        width: 210mm;
        height: auto;
    }
        table th{
        padding: 8px 4px;
        font-size: 13px !important;
        line-height: 10px !important;
      }
      table tfoot td{
        padding: 6px;
        font-size: 13px !important;
        line-height: 14px !important;
      }
      table td{
        padding: 6px 6px 5px;
        font-size: 11px !important;
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
