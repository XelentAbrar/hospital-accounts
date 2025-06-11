<template>
<Head title="Income Statement" />
<AppLayout title="Income Statement">
    <div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-6 sm:px-6">
        <h3 class="font-semibold text-4xl text-primary">Income Statement</h3>
    </div>
    <div class="border-t border-gray-100 py-2 px-4">
        <form @submit.prevent="filter.get(route('income-statement'), filter); isLoading = true">
            <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-4">
                <div>
                    <label for="from_date" class="block text-sm font-semibold text-gray-900">
                    From Date
                    </label>
                    <div class="mt-2">
                    <input id="from_date" name="from_date" type="text" ref="from_date" autocomplete="from_date" class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="filter.from_date"/>
                    </div>
                </div>
                <div>
                    <label for="to_date" class="block text-sm font-semibold text-gray-900">
                    To Date
                    </label>
                    <div class="mt-2">
                        <input id="to_date" name="to_date" type="text" ref="to_date" autocomplete="to_date" class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="filter.to_date"/>
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



    <div class="w-full mx-auto">
        <div class="bg-white shadow-sm rounded-lg">
            <div>
                <div class="mt-2 flow-root" id="printData">
                    <div style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
                        <div class="print-only">
                            <!-- <div style="display: flex;flex-direction: column; align-items: center;justify-content: center;"> -->
                                <!-- <img style="width:100px;object-fit: contain; margin-bottom: 16px;" alt=""> -->
                                <!-- <p style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Nawaz Town Sarghoda Road, Faisalabad</p>
                                <p style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Ph: 041-8848060 | 8787231</p> -->
                            <!-- </div> -->
                            <div style="flex: 1; display: flex; align-items: center; justify-content: flex-end;">
                                <!-- <h4 style="margin: 0; font-size: xx-small; font-family: sans-serif; width: 100px;">Printed On</h4>
                                <h4 style="margin: 0 6px; font-weight: bold; width: 6px; text-align: center;">:</h4> -->
                                <p style="margin: 0; font-size: xx-small; margin-left: 2px; text-align: left; font-weight: 500; white-space: nowrap;">
                                    {{ new Date().toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                </p>
                            </div>
                            <p style="font-size: 24px;line-height:1.2;font-weight: 600;text-align: center;margin: 0">Chiniot General Hospital</p>
                            <p style="font-size: 24px;line-height:1.2;font-weight: 600;text-align: center;margin: 0" v-if="projectType === 'hms'">Nawaz Town Sarghoda Road, Faisalabad</p>
                            <p style="font-size: 24px;line-height:1.2;font-weight: 600;text-align: center;margin: 0" v-else>209 Jinnah Colony , Faisalabad</p>
                            <div>
                                <p style="font-size: 22px;line-height:1.2;font-weight: 500;text-align: center;margin: 0;margin-bottom: 10px;">Income Statement</p>
                            </div>
                            <div style="display: flex; align-items: center; justify-content: space-between; width: 100%; margin: 6px 48px auto;">

                            <div style="
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                width: 100%;
                                margin: 6px  auto;
                            ">
                                <div style="
                                    padding: 6px 12px;
                                    border: 1px solid black;
                                    border-radius: 6px;
                                    text-align: center;
                                    font-weight: 500;
                                    white-space: nowrap;
                                    font-size: 14px;
                                ">
                                From : {{ filter?.from_date }} - To : {{ filter?.to_date }}
                                </div>
                            </div>

                            </div>

                        </div>
                        <!-- start report data -->
                        <div class="ring-1 ring-gray-300">


                            <div class="border-b-1 border border-gray-400 border-spacing-4 mb-8">
                                <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-300 overflow-auto">
                                    <thead>
                                        <tr class="divide-x divide-gray-300">
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10">A/C Description</th>
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10" >Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(report, index) in reports.filter(r => r.acc_code_level1.startsWith('13') || r.acc_code_level1.startsWith('14'))" :key="index">
                                            <!-- Control Account Level -->
                                            <tr v-if="index === 0 || report.acc_code_level1 !== reports.filter(r => r.acc_code_level1.startsWith('13') || r.acc_code_level1.startsWith('14'))[index - 1].acc_code_level1">
                                                <td class="border-b-1 border border-gray-400 bg-gray-300 font-bold text-left p-2" colspan="3">
                                                {{ report.acc_desc_level1 }}
                                                </td>
                                            </tr>
                                            <!-- Sub Control Account Level -->
                                            <tr v-if="(index === 0 || report.acc_code_level2 !== reports.filter(r => r.acc_code_level1.startsWith('13') || r.acc_code_level1.startsWith('14'))[index - 1].acc_code_level2)  &&
                                                ((totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0))">
                                                <td colspan="7" class="border-b-1 border border-gray-400 font-semibold text-left pl-4">
                                                {{ report.acc_desc_level2 }}
                                                </td>
                                            </tr>
                                            <!-- Sub Head Account Level -->
                                            <tr v-if="(index === 0 || report.acc_code_level3 !== reports.filter(r => r.acc_code_level1.startsWith('13') || r.acc_code_level1.startsWith('14'))[index - 1].acc_code_level3)  &&
                                                ((totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0))">
                                                <td class="border-b-1 border border-gray-400 pl-16">
                                                {{ report.acc_code_level3 }} -  {{ report.acc_desc_level3 }}
                                                </td>
                                                <td class="border-b-1 border border-gray-400 text-right">
                                                    <span v-if="totalAccountCodeLevel(report.acc_code_level3, 'closing_debit', 'acc_code_level3') - totalAccountCodeLevel(report.acc_code_level3, 'closing_credit', 'acc_code_level3') > 0">
                                                        {{withCommas(totalAccountCodeLevel(report.acc_code_level3, 'closing_debit', 'acc_code_level3') - totalAccountCodeLevel(report.acc_code_level3, 'closing_credit', 'acc_code_level3'))}}
                                                    </span>
                                                    <span v-else>
                                                        {{withCommas(-1 * (totalAccountCodeLevel(report.acc_code_level3, 'closing_debit', 'acc_code_level3') - totalAccountCodeLevel(report.acc_code_level3, 'closing_credit', 'acc_code_level3')))}}
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-secondary text-black">
                                        <td class="text-left font-bold px-3 border-y border-gray-400">Total Income</td>
                                        <td class="text-right font-bold pr-4 px-3 border-y border-gray-400">
                                                            <span v-if="GrandTotalIncome('closing_debit', 'acc_code_level1') - GrandTotalIncome('closing_credit', 'acc_code_level1') > 0">
                                                        {{withCommas(GrandTotalIncome('closing_debit', 'acc_code_level1') - GrandTotalIncome('closing_credit', 'acc_code_level1'))}}
                                                    </span>
                                                    <span v-else>
                                                        {{withCommas(-1 * (GrandTotalIncome('closing_debit', 'acc_code_level1') - GrandTotalIncome('closing_credit', 'acc_code_level1')))}}
                                                    </span>
                                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>


                            <div class="border-b-1 border border-gray-400 border-spacing-4 mb-8">
                                <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-300 overflow-auto">
                                    <thead>
                                        <tr class="divide-x divide-gray-300">
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10">A/C Description</th>
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10" >Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(report, index) in reports.filter(r => r.acc_code_level1.startsWith('12') || r.acc_code_level1.startsWith('12'))" :key="index">
                                            <!-- Control Account Level -->
                                            <tr v-if="index === 0 || report.acc_code_level1 !== reports.filter(r => r.acc_code_level1.startsWith('12') || r.acc_code_level1.startsWith('12'))[index - 1].acc_code_level1">
                                                <td class="border-b-1 border border-gray-400 bg-gray-300 font-bold text-left p-2" colspan="3">
                                                {{ report.acc_desc_level1 }}
                                                </td>
                                            </tr>
                                            <!-- Sub Control Account Level -->
                                            <tr v-if="(index === 0 || report.acc_code_level2 !== reports.filter(r => r.acc_code_level1.startsWith('12') || r.acc_code_level1.startsWith('12'))[index - 1].acc_code_level2)  &&
                                                ((totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0))">
                                                <td colspan="7" class="border-b-1 border border-gray-400 font-semibold text-left pl-4">
                                                {{ report.acc_desc_level2 }}
                                                </td>
                                            </tr>
                                            <!-- Sub Head Account Level -->
                                            <tr v-if="(index === 0 || report.acc_code_level3 !== reports.filter(r => r.acc_code_level1.startsWith('12') || r.acc_code_level1.startsWith('12'))[index - 1].acc_code_level3)  &&
                                                ((totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0))">
                                                <td class="border-b-1 border border-gray-400 pl-16">
                                                {{ report.acc_code_level3 }} -  {{ report.acc_desc_level3 }}
                                                </td>
                                                <td class="border-b-1 border border-gray-400 text-right">
                                                    <span v-if="totalAccountCodeLevel(report.acc_code_level3, 'closing_debit', 'acc_code_level3') - totalAccountCodeLevel(report.acc_code_level3, 'closing_credit', 'acc_code_level3') > 0">
                                                        {{withCommas(totalAccountCodeLevel(report.acc_code_level3, 'closing_debit', 'acc_code_level3') - totalAccountCodeLevel(report.acc_code_level3, 'closing_credit', 'acc_code_level3'))}}
                                                    </span>
                                                    <span v-else>
                                                        ({{withCommas(-1 * (totalAccountCodeLevel(report.acc_code_level3, 'closing_debit', 'acc_code_level3') - totalAccountCodeLevel(report.acc_code_level3, 'closing_credit', 'acc_code_level3')))}})
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-secondary text-black">
                                        <td class="text-left font-bold px-3 border-y border-gray-400">Total Expense</td>
                                        <td class="text-right font-bold pr-4 px-3 border-y border-gray-400">
                                                    <span v-if="+GrandTotalExpenditure('closing_debit', 'acc_code_level1') - +GrandTotalExpenditure('closing_credit', 'acc_code_level1') > 0">
                                                        {{withCommas(+GrandTotalExpenditure('closing_debit', 'acc_code_level1') - +GrandTotalExpenditure('closing_credit', 'acc_code_level1'))}}
                                                    </span>
                                                    <span v-else>
                                                        ({{withCommas(-1 * (+GrandTotalExpenditure('closing_debit', 'acc_code_level1') - +GrandTotalExpenditure('closing_credit', 'acc_code_level1')))}})
                                                    </span>
                                        </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                            <div class="flex justify-end px-4 mb-1">
                                <div class="lg:col-start-3 lg:row-end-1 w-3/5">
                                <div class="rounded-lg bg-gray-50 shadow-sm ring-1 ring-gray-300">
                                    <dl class="flex flex-wrap">
                                        <div class="flex-auto pl-4 pt-2">
                                            <dt class="text-base font-semibold leading-6 text-gray-900">Summary</dt>
                                        </div>
                                        <div class="mt-2 flex w-full justify-between gap-x-4 border-t border-gray-400 px-4 pt-2">
                                            <span class="text-sm font-medium leading-6 text-gray-900">Total Income</span>
                                            <span class="text-sm font-medium leading-6 text-gray-900">
                                                <span v-if="GrandTotalIncome('closing_debit', 'acc_code_level1') - GrandTotalIncome('closing_credit', 'acc_code_level1') > 0">
                                                        {{withCommas(GrandTotalIncome('closing_debit', 'acc_code_level1') - GrandTotalIncome('closing_credit', 'acc_code_level1'))}}
                                                    </span>
                                                    <span v-else>
                                                        {{withCommas(-1 * (GrandTotalIncome('closing_debit', 'acc_code_level1') - GrandTotalIncome('closing_credit', 'acc_code_level1')))}}
                                                    </span>
                                            </span>
                                        </div>
                                        <div class="mt-1 flex w-full justify-between gap-x-4 px-4"><span class="text-sm font-medium leading-6 text-gray-900">Total Expense</span>
                                        <span class="text-sm font-medium leading-6 text-gray-900">
                                                    <span v-if="GrandTotalExpenditure('closing_debit', 'acc_code_level1') - GrandTotalExpenditure('closing_credit', 'acc_code_level1') > 0">
                                                        {{withCommas(GrandTotalExpenditure('closing_debit', 'acc_code_level1') - GrandTotalExpenditure('closing_credit', 'acc_code_level1'))}}
                                                    </span>
                                                    <span v-else>
                                                        ({{withCommas(-1 * (GrandTotalExpenditure('closing_debit', 'acc_code_level1') - GrandTotalExpenditure('closing_credit', 'acc_code_level1')))}})
                                                    </span>
                                                            </span></div>
                                    </dl>
                                    <div class="mt-2 flex justify-between border-t border-gray-400 px-4 py-2">
                                        <span href="#" class="text-base font-semibold leading-6 text-gray-900">Profit &amp; Loss</span>
                                        <span href="#" class="text-base font-semibold leading-6" :class="calculateTotal() < 0 ? 'text-red-600' : 'text-green-600'">
                                            {{calculateTotal() < 0 ? (-1 * calculateTotal()) +' Deficit' : sum + ' Surplus'}}
                                        </span></div>
                                </div>
                                </div>
                            </div>

                        </div>

                        <!-- end report date -->
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
import { computed, ref,onMounted } from "vue";
import dayjs from 'dayjs';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
const props = defineProps({
    reports: Array,
    from_date: String,
    to_date: String,
    cr_sum: String,
    dr_sum: String,
    accounts: Array,
    selected_accounts: String,
});
const projectType = import.meta.env.VITE_PROJECT_TYPE;

const defaultFromDate = dayjs().month() < 6 ? dayjs().year(dayjs().year() - 1).month(6).date(1).format('DD-MM-YYYY') : dayjs().year(dayjs().year()).month(6).date(1).format('DD-MM-YYYY');
// const defaultToDate = dayjs().month() < 6 ? dayjs().year(dayjs().year()).month(5).date(30).format('YYYY-MM-DD') : dayjs().year(dayjs().year() + 1).month(5).date(30).format('YYYY-MM-DD');

const filter = useForm({
  from_date: props?.from_date || defaultFromDate,
  to_date: props?.to_date || new Date().toLocaleDateString("en-GB").replace(/\//g, "-"),
  selected_accounts: props?.selected_accounts || null,
  column: '6',
});
onMounted(() => {
    const flatpickrOptions = (defaultDate) => ({
        dateFormat: "d-m-Y", // Format: DD-MM-YYYY
        allowInput: true, // Allows manual typing
        clickOpens: true, // Ensures the picker still works
        defaultDate: defaultDate || new Date(), // Use provided default date or current date
    });

    flatpickr(to_date, flatpickrOptions(filter.to_date));
    flatpickr(from_date, flatpickrOptions(filter.from_date));
});

const totalIncome = computed(() => {
    let closing_debit = 0;
    let closing_credit = 0;
    let sum = 0;
    props.reports
    .filter(r => r.acc_code_level1.startsWith('12') || r.acc_code_level1.startsWith('12')).map((res, index) => {

        closing_debit = +((res.closing_debit - res.closing_credit) + +res.ca_opening > 0 ? (res.closing_debit - res.closing_credit) + +res.ca_opening : 0);

        closing_credit = +((res.closing_debit - res.closing_credit) + +res.ca_opening <= 0 ? (res.closing_debit - res.closing_credit) + +res.ca_opening : 0);

        sum = sum + (+closing_debit - +closing_credit);
    });
    return sum;
});


const selectedAccount = ref(null);
const isLoading = ref(false);
const onAccountSelect = (selectedAccount) => {
    filter.selected_accounts = selectedAccount?.acc_code ?? null;
};
if (props?.selected_accounts) {
  const code = props.accounts.find(
    (c) => c.acc_code === props?.selected_accounts
  );
  if (code) {
    selectedAccount.value = code;
  }
}


const withCommas = (value) => {
  if (value == null || value == '') {
    return '0';
  }
  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

const formatWithCommas = (value) => {
  if (value == null || value === '') {
    return '0';
  }
  const number = value;
  const formattedNumber = number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  return number < 0 ? `(${formattedNumber.replace('-', '')})` : formattedNumber;
};

const totalAssets = computed(() => {
  return props.reports
    .filter(r => r.acc_code_level1.startsWith('10'))
    .reduce((sum, report) => sum + parseFloat(report.balance || 0), 0);
});

const totalLiabilities = computed(() => {
  return props.reports
    .filter(r => r.acc_code_level1.startsWith('11'))
    .reduce((sum, report) => sum + parseFloat(report.balance || 0), 0);
});

const calculateTotal = () => {

    let totalExpenditure = 0;
    if(GrandTotalExpenditure('closing_debit', 'acc_code_level1') - GrandTotalExpenditure('closing_credit', 'acc_code_level1') > 0){
        totalExpenditure = GrandTotalExpenditure('closing_debit', 'acc_code_level1') - GrandTotalExpenditure('closing_credit', 'acc_code_level1')
    }
    else{
        totalExpenditure = -1 * (GrandTotalExpenditure('closing_debit', 'acc_code_level1') - GrandTotalExpenditure('closing_credit', 'acc_code_level1'))
    }

    let totalIncome = 0;
    if(GrandTotalIncome('closing_debit', 'acc_code_level1') - GrandTotalIncome('closing_credit', 'acc_code_level1') > 0){
        totalIncome = GrandTotalIncome('closing_debit', 'acc_code_level1') - GrandTotalIncome('closing_credit', 'acc_code_level1')
    }
    else{
        totalIncome = -1 * (GrandTotalIncome('closing_debit', 'acc_code_level1') - GrandTotalIncome('closing_credit', 'acc_code_level1'))
    }

    let sum = +totalIncome - +totalExpenditure;
    return  sum;


};
const GrandTotalExpenditure = (type, levelType) => {
    let reports = props.reports.filter(r => r.acc_code_level1.startsWith('12'))
    let sum = 0;
    reports.map((res, index) => {
        if(index === 0 || res.acc_code_level1 !== reports[index - 1].acc_code_level1){
            sum = +sum + +totalAccountCodeLevel(res.acc_code_level1 ,type, levelType)
        }
    })
    return sum;
}

const GrandTotalIncome = (type, levelType) => {
    let reports = props.reports.filter(r => r.acc_code_level1.startsWith('13') || r.acc_code_level1.startsWith('14'))
    let sum = 0;
    reports.map((res, index) => {
        if(index == 0 || res.acc_code_level1 != reports[index - 1].acc_code_level1){
            sum = +sum + +totalAccountCodeLevel(res.acc_code_level1 ,type, levelType)
        }
    })
    // console.table(reports.filter(r => r.acc_code_level1.startsWith('13') || r.acc_code_level1.startsWith('14')));
    return sum;
}
const totalAccountCodeLevel = (level4Code, type, levelType) => {
  let reports = props.reports
    .filter(r => r[levelType] === level4Code);
    let sum = 0;
    reports.map((res, index) => {
        if(type == 'opening_debit'){
            sum = +sum + +((res.opening_debit - res.opening_credit) + +res.ca_opening > 0 ? (res.opening_debit - res.opening_credit) + +res.ca_opening : 0);
        }
        else if(type == 'opening_credit'){
            sum = +sum + +((res.opening_debit - res.opening_credit) + +res.ca_opening <= 0 ? (res.opening_debit - res.opening_credit) + +res.ca_opening : 0);
        }
        else if(type == 'current_debit'){
            sum = +sum + +(res.current_debit - res.current_credit > 0 ? res.current_debit - res.current_credit : 0);
        }
        else if(type == 'current_credit'){
            sum = +sum + +(res.current_debit - res.current_credit <= 0 ? res.current_debit - res.current_credit : 0);
        }
        else if(type == 'closing_debit'){
            sum = +sum + +((res.closing_debit - res.closing_credit) + +res.ca_opening > 0 ? (res.closing_debit - res.closing_credit) + +res.ca_opening : 0);
        }
        else if(type == 'closing_credit'){
            sum = +sum + +((res.closing_debit - res.closing_credit) + +res.ca_opening <= 0 ? (res.closing_debit - res.closing_credit) + +res.ca_opening : 0);
        }
    });
    if(sum < 0){
        sum = -1 * sum;
    }
    return parseFloat(sum);
    // .reduce((sum, report) => sum + parseFloat(report[type] || 0), 0);
};

const print = () => {
  var header_str = '<html><head><title>' + document.title + '</title></head><body>';
  var footer_str = '</body></html>';
  var new_str = document.getElementById('printData').innerHTML;
  var old_str = document.body.innerHTML;
  document.body.innerHTML = header_str + new_str + footer_str;
  window.print();

  return false;
};

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
            @page { size: A4; margin: 0; }
          body { margin: 0; }
        .a4-size {
            width: 210mm;
            height: auto;
        }
            table th{
            padding: 8px 4px;
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
          .text_white{
            color: #000 !important;
          }
        }
    </style>
