<template>
<Head title="Balance Sheet" />
<AppLayout title="Balance Sheet">
    <div class="bg-white shadow sm:rounded-lg">
    <div class="px-4 py-6 sm:px-6">
        <h3 class="font-semibold text-4xl text-primary">Balance Sheet</h3>
    </div>
    <div class="border-t border-gray-100 py-2 px-4">
        <form @submit.prevent="filter.get(route('balance-sheet'), filter); isLoading = true">
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
                <div class="flex items-center space-x-4 mt-8">
                    <input type="checkbox" autocomplete="current_balance" v-model="filter.current_balance" id="current_balance"/>
                    <label for="current_balance" class="text-sm font-semibold text-gray-900">
                    Show Current Balance
                    </label>
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
                            <p style="font-size: 14px;line-height:1.2;font-weight: 400;text-align: center;margin: 0" v-if="projectType === 'hms'">Nawaz Town Sarghoda Road, Faisalabad</p>
                            <p style="font-size: 14px;line-height:1.2;font-weight: 400;text-align: center;margin: 0" v-else>209 Jinnah Colony , Faisalabad</p>
                            <div>
                                <p style="font-size: 22px;line-height:1.2;font-weight: 500;text-align: center;margin: 0;margin-bottom: 10px;">Balance Sheet</p>
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
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10" >{{ filter.current_balance ? 'Current Debit' : 'Closing Debit' }}.</th>
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10" >{{ filter.current_balance ? 'Current Credit' : 'Closing Credit' }}.</th>
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-400 sticky top-0 z-10" >Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(report, index) in reports" :key="index">
                                            <!-- Control Account Level -->
                                            <tr v-if="index === 0 || report.acc_code_level1 !== reports[index - 1].acc_code_level1">
                                                <td class="border-b-1 border border-gray-400 bg-gray-300 font-bold text-left p-2">
                                                {{ report.acc_desc_level1 }}
                                                </td>
                                                <td class="border-b-1 border border-gray-400 bg-gray-300 font-bold text-right p-2">
                                                    <span v-if="filter.current_balance">
                                                {{
                                                    withCommas(totalAccountCodeLevel(report.acc_code_level1, 'current_debit', 'acc_code_level1'))
                                                }}
                                                    </span>
                                                    <span v-else>
                                                {{
                                                    withCommas(totalAccountCodeLevel(report.acc_code_level1, 'closing_debit', 'acc_code_level1'))
                                                }}
                                                    </span>
                                                </td>
                                                <td class="border-b-1 border border-gray-400 bg-gray-300 font-bold text-right p-2">
                                                    <span v-if="filter.current_balance">
                                                {{
                                                    withCommas(totalAccountCodeLevel(report.acc_code_level1, 'current_credit', 'acc_code_level1'))
                                                }}
                                                </span>
                                                    <span v-else>
                                                {{
                                                    withCommas(totalAccountCodeLevel(report.acc_code_level1, 'closing_credit', 'acc_code_level1'))
                                                }}
                                                    </span>
                                                </td>
                                                <td class="border-b-1 border border-gray-400 bg-gray-300 font-bold text-right p-2">
                                                    <span v-if="filter.current_balance">
                                                {{

                                                    totalAccountCodeLevel(report.acc_code_level1, 'current_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'current_credit', 'acc_code_level1') < 0 ?

                                                    '('+withCommas(+(+totalAccountCodeLevel(report.acc_code_level1, 'current_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'current_credit', 'acc_code_level1')) * -1)+')'
                                                    :
                                                    withCommas(+totalAccountCodeLevel(report.acc_code_level1, 'current_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'current_credit', 'acc_code_level1'))
                                                }}
                                                </span>
                                                    <span v-else>
                                                {{

                                                    totalAccountCodeLevel(report.acc_code_level1, 'closing_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'closing_credit', 'acc_code_level1') < 0 ?

                                                    '('+withCommas(+(+totalAccountCodeLevel(report.acc_code_level1, 'closing_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'closing_credit', 'acc_code_level1')) * -1)+')'
                                                    :
                                                    withCommas(+totalAccountCodeLevel(report.acc_code_level1, 'closing_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'closing_credit', 'acc_code_level1'))
                                                }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <!-- Sub Control Account Level -->
                                            <tr v-if="(index === 0 || report.acc_code_level2 !== reports[index - 1].acc_code_level2)  &&
                                                ((totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0))">
                                                <td colspan="7" class="border-b-1 border border-gray-400 font-semibold text-left pl-4">
                                                {{ report.acc_desc_level2 }}
                                                </td>
                                            </tr>
                                            <!-- Sub Head Account Level -->
                                            <tr v-if="(index === 0 || report.acc_code_level3 !== reports[index - 1].acc_code_level3)  &&
                                                ((totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0))">
                                                <td class="border-b-1 border border-gray-400 pl-16">
                                                {{ report.acc_code_level3 }} -  {{ report.acc_desc_level3 }}
                                                </td>
                                                <td class="border-b-1 border border-gray-400 text-right">
                                                    <span v-if="filter.current_balance">
                                                    {{
                                                        withCommas(totalAccountCodeLevel(report.acc_code_level3, 'current_debit', 'acc_code_level3'))
                                                    }}
                                                    </span>
                                                    <span v-else>
                                                    {{
                                                        withCommas(totalAccountCodeLevel(report.acc_code_level3, 'closing_debit', 'acc_code_level3'))
                                                    }}
                                                    </span>
                                                </td>
                                                <td class="border-b-1 border border-gray-400 text-right">
                                                    <span v-if="filter.current_balance">
                                                    {{
                                                        withCommas(totalAccountCodeLevel(report.acc_code_level3, 'current_credit', 'acc_code_level3'))
                                                    }}
                                                    </span>
                                                    <span v-else>
                                                    {{
                                                        withCommas(totalAccountCodeLevel(report.acc_code_level3, 'closing_credit', 'acc_code_level3'))
                                                    }}
                                                    </span>
                                                </td>
                                                <td class="border-b-1 border border-gray-400 text-right">
                                                    <span v-if="filter.current_balance">
                                                    {{
                                                        totalAccountCodeLevel(report.acc_code_level3, 'current_debit', 'acc_code_level3') - +totalAccountCodeLevel(report.acc_code_level3, 'current_credit', 'acc_code_level3') < 0 ?

                                                        '('+withCommas(+(+totalAccountCodeLevel(report.acc_code_level3, 'current_debit', 'acc_code_level3') - +totalAccountCodeLevel(report.acc_code_level3, 'current_credit', 'acc_code_level3')) * -1)+')' :

                                                        withCommas(+totalAccountCodeLevel(report.acc_code_level3, 'current_debit', 'acc_code_level3') - +totalAccountCodeLevel(report.acc_code_level3, 'current_credit', 'acc_code_level3'))
                                                    }}
                                                    </span>
                                                    <span v-else>
                                                    {{
                                                        totalAccountCodeLevel(report.acc_code_level3, 'closing_debit', 'acc_code_level3') - +totalAccountCodeLevel(report.acc_code_level3, 'closing_credit', 'acc_code_level3') < 0 ?

                                                        '('+withCommas(+(+totalAccountCodeLevel(report.acc_code_level3, 'closing_debit', 'acc_code_level3') - +totalAccountCodeLevel(report.acc_code_level3, 'closing_credit', 'acc_code_level3')) * -1)+')' :

                                                        withCommas(+totalAccountCodeLevel(report.acc_code_level3, 'closing_debit', 'acc_code_level3') - +totalAccountCodeLevel(report.acc_code_level3, 'closing_credit', 'acc_code_level3'))
                                                    }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex px-4 mb-1">
                                <div class="w-full">
                                    <div class="rounded-lg overflow-hidden bg-gray-50 shadow-sm ring-1 ring-gray-300">
                                        <table class="min-w-full divide-y divide-gray-300">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="whitespace-nowrap text-left text-lg font-semibold text-gray-900">Summary</th>
                                                    <th scope="col" class="whitespace-nowrap text-left text-lg font-semibold text-gray-900 bg-yellow-200">
                                                        {{ filter.current_balance ? 'Current Debit' : 'Closing Debit' }}
                                                    </th>
                                                    <th scope="col" class="whitespace-nowrap text-left text-lg font-semibold text-gray-900 bg-yellow-200">
                                                        {{ filter.current_balance ? 'Current Credit' : 'Closing Credit' }}
                                                    </th>
                                                    <th scope="col" class="whitespace-nowrap text-left text-lg font-semibold text-gray-900 bg-yellow-200">Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-300">
                                                <template v-for="(report, index) in reports" :key="index">
                                                    <tr v-if="index === 0 || report.acc_code_level1 !== reports[index - 1].acc_code_level1" class="hover:bg-gray-50">
                                                        <td class="text-lg font-medium text-gray-900">Total {{ report.acc_desc_level1 }}</td>
                                                        <td class="text-lg text-gray-900 bg-yellow-200">
                                                            <span v-if="filter.current_balance">
                                                                {{
                                                                    withCommas(totalAccountCodeLevel(report.acc_code_level1, 'current_debit', 'acc_code_level1'))
                                                                }}
                                                            </span>
                                                            <span v-else>
                                                                {{
                                                                    withCommas(totalAccountCodeLevel(report.acc_code_level1, 'closing_debit', 'acc_code_level1'))
                                                                }}
                                                            </span>
                                                        </td>
                                                        <td class="text-lg text-gray-900 bg-yellow-200">
                                                            <span v-if="filter.current_balance">
                                                            {{
                                                                withCommas(totalAccountCodeLevel(report.acc_code_level1, 'current_credit', 'acc_code_level1'))
                                                            }}
                                                            </span>
                                                            <span v-else>
                                                            {{
                                                                withCommas(totalAccountCodeLevel(report.acc_code_level1, 'closing_credit', 'acc_code_level1'))
                                                            }}
                                                            </span>
                                                        </td>
                                                        <td class="text-lg text-gray-900 bg-yellow-200">
                                                            <span v-if="filter.current_balance">
                                                            {{
                                                                +totalAccountCodeLevel(report.acc_code_level1, 'current_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'current_credit', 'acc_code_level1') < 0 ?

                                                                '('+withCommas(+(+totalAccountCodeLevel(report.acc_code_level1, 'current_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'current_credit', 'acc_code_level1')) * -1)+')'

                                                                :
                                                                withCommas(+totalAccountCodeLevel(report.acc_code_level1, 'current_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'current_credit', 'acc_code_level1'))
                                                            }}
                                                            </span>
                                                            <span v-else>
                                                            {{
                                                                +totalAccountCodeLevel(report.acc_code_level1, 'closing_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'closing_credit', 'acc_code_level1') < 0 ?

                                                                '('+withCommas(+(+totalAccountCodeLevel(report.acc_code_level1, 'closing_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'closing_credit', 'acc_code_level1')) * -1)+')'

                                                                :
                                                                withCommas(+totalAccountCodeLevel(report.acc_code_level1, 'closing_debit', 'acc_code_level1') - +totalAccountCodeLevel(report.acc_code_level1, 'closing_credit', 'acc_code_level1'))
                                                            }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </template>
                                                <tr class="hover:bg-gray-50">
                                                        <td class="text-lg font-semibold text-gray-900">Grand Total</td>
                                                        <td class="text-lg text_white bg-gray-800 font-semibold">
                                                            <span v-if="filter.current_balance">
                                                            {{
                                                                withCommas(GrandTotal('current_debit', 'acc_code_level1'))
                                                            }}
                                                            </span>
                                                            <span v-else>
                                                            {{
                                                                withCommas(GrandTotal('closing_debit', 'acc_code_level1'))
                                                            }}
                                                            </span>
                                                        </td>
                                                        <td class="text-lg text_white bg-gray-800 font-semibold">
                                                            <span v-if="filter.current_balance">
                                                            {{
                                                                withCommas(GrandTotal('current_credit', 'acc_code_level1'))
                                                            }}
                                                            </span>
                                                            <span v-else>
                                                            {{
                                                                withCommas(GrandTotal('closing_credit', 'acc_code_level1'))
                                                            }}
                                                            </span>
                                                        </td>
                                                        <td class="text-lg text_white bg-gray-800 font-semibold">
                                                            <span v-if="filter.current_balance">
                                                            {{
                                                                +GrandTotal('current_debit', 'acc_code_level1') - +GrandTotal('current_credit', 'acc_code_level1') < 0 ?

                                                                '('+withCommas(+(+GrandTotal('current_debit', 'acc_code_level1') - +GrandTotal('current_credit', 'acc_code_level1')) * -1)+')'
                                                                :
                                                                withCommas(+GrandTotal('current_debit', 'acc_code_level1') - +GrandTotal('current_credit', 'acc_code_level1'))
                                                            }}
                                                            </span>
                                                            <span v-else>
                                                            {{
                                                                +GrandTotal('closing_debit', 'acc_code_level1') - +GrandTotal('closing_credit', 'acc_code_level1') < 0 ?

                                                                '('+withCommas(+(+GrandTotal('closing_debit', 'acc_code_level1') - +GrandTotal('closing_credit', 'acc_code_level1')) * -1)+')'
                                                                :
                                                                withCommas(+GrandTotal('closing_debit', 'acc_code_level1') - +GrandTotal('closing_credit', 'acc_code_level1'))
                                                            }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                            </tbody>
                                        </table>
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
import { computed, ref , onMounted} from "vue";
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
  current_balance: false,
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

const GrandTotal = (type, levelType) => {
    let reports = props.reports
    let sum = 0;
    reports.map((res, index) => {
        if(index === 0 || res.acc_code_level1 !== reports[index - 1].acc_code_level1){
            sum = +sum + +totalAccountCodeLevel(res.acc_code_level1 ,type, levelType)
        }
    })
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
