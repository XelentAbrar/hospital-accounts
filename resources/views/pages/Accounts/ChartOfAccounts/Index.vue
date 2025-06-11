<template>
<Head title="Chart of Accounts" />
<AppLayout title="Chart of Accounts">
    <div class="w-full mx-auto pt-6">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex flex-col md:flex-row items-center justify-between min-w-full">
                    <h1 class="font-semibold text-4xl text-primary">Chart of Accounts</h1>
                    <inertia-link
                  :href="route('chart-of-accounts.create')"
                  class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                  >Create chart of account</inertia-link
                >
                    <div class="mt-2 sm:mt-0">
                        <form @submit.prevent="filter.get(route('chart-of-accounts.index'), filter)">
                            <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
                                <div>
                                    <label for="account_id" class="block text-sm md:text-base font-medium text-gray-900">
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
                                            label="acc_desc"
                                            track-by="acc_code"
                                            @update:modelValue="onAccountSelect"
                                        />
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label for="to_date" class="block text-sm md:text-base font-medium text-gray-900">
                                    &nbsp;
                                    </label>
                                    <button type="submit" class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                                    Search
                                    </button>
                                    <button
                                    class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2" @click="print()" v-if="reports.length > 0">
                                    Print
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="mt-6 flow-root" id="printData">
                    <div style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
                        <div class="ring-1 ring-gray-300">
                            <div class="border-b-2 border-dashed border-gray-300 border-spacing-4 mb-8">
                                <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-300 overflow-auto">
                                    <thead>
                                        <tr class="divide-x divide-gray-300">
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10">A/C Description</th>
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10 hide-print" >Action</th>
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10" >Closing Debit.</th>
                                            <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border-b border-gray-300 sticky top-0 z-10" >Closing Credit.</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template v-for="(report, index) in reports" :key="index">
                                            <!-- Control Account Level -->
                                            <tr v-if="index === 0 || report.acc_code_level1 !== reports[index - 1].acc_code_level1">
                                                <td class="border-b-2 border-dashed-2 border-gray-600 bg-yellow-200 font-bold text-left p-2" colspan="4">
                                                {{ report.acc_desc_level1 }}
                                                </td>
                                            </tr>
                                            <!-- Sub Control Account Level -->
                                            <!-- <tr v-if="(index === 0 || report.acc_code_level2 !== reports[index - 1].acc_code_level2)  &&

                                                ((filter.column == '6' && (totalAccountCodeLevel(report.acc_code_level4, 'opening_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'opening_credit', 'acc_code_level4') != 0 || totalAccountCodeLevel(report.acc_code_level4, 'current_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'current_credit', 'acc_code_level4') != 0  || totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0))

                                                ||

                                                (filter.column == '2' && (totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0)))">
                                                <td colspan="7" class="border-b-2 border-dashed border-gray-300 font-semibold text-left pl-4">
                                                {{ report.acc_desc_level2 }}
                                                </td>
                                            </tr> -->
                                            <!-- Sub Head Account Level -->
                                            <!-- <tr v-if="(index === 0 || report.acc_code_level3 !== reports[index - 1].acc_code_level3)  &&

                                                ((filter.column == '6' && (totalAccountCodeLevel(report.acc_code_level4, 'opening_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'opening_credit', 'acc_code_level4') != 0 || totalAccountCodeLevel(report.acc_code_level4, 'current_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'current_credit', 'acc_code_level4') != 0  || totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0))

                                                ||

                                                (filter.column == '2' && (totalAccountCodeLevel(report.acc_code_level4, 'closing_debit', 'acc_code_level4') - totalAccountCodeLevel(report.acc_code_level4, 'closing_credit', 'acc_code_level4') != 0)))">
                                                <td colspan="7" class="border-b-2 border-dashed border-gray-300 font-semibold text-left pl-8">
                                                {{ report.acc_desc_level3 }}
                                                </td>
                                            </tr> -->
                                            <!-- Account Code Level -->
                                            <tr v-if="(index === 0 || report.acc_code_level4 !== reports[index - 1].acc_code_level4)">
                                                <td class="border-b-2 border-dashed  border-gray-300 bg-blue-200 font-bold text-lg text-left  pl-12" colspan="4">
                                                {{ report.acc_desc_level4 }}
                                                </td>
                                            </tr>
                                            <!-- Chart of Accounts Level -->
                                            <tr v-if="report.acc_code_level5">
                                                <td class="border-b-2 border-dashed border-gray-300 pl-16">
                                                  <a :href="'/party-ledger?from_date='+filter.from_date+'&to_date='+filter.to_date+'&account_id='+report.acc_id"
                                                  target="_blank"
                                                  >
                                                  {{ report.acc_code_level5 }} -  {{ report.acc_desc_level5 }}
                                                  </a>
                                                </td>
                                                <td class="border-b-2 border-dashed border-gray-300 text-right pr-4 hide-print" >
                                                  <div class="flex items-center justify-center space-x-3" v-if="roles.includes(1)">
                                                  <InertiaLink
                                                    :href="
                                                      route(
                                                        'chart-of-accounts.edit',
                                                        report.acc_id
                                                      )
                                                    "
                                                    >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                        class="w-5 lg:w-6 h-5 lg:h-6 text-primary hover:text-accent"
                                                      >
                                                        <path
                                                          stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"
                                                        />
                                                      </svg>
                                                  </InertiaLink>
                                                  <button
                                                    @click.prevent="
                                                      deleteChartOfAccount(report.acc_id)
                                                    "
                                                    type="button"

                                                  >
                                                  <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                        class="w-5 lg:w-6 h-5 lg:h-6 text-red-600 hover:text-red-900"
                                                      >
                                                        <path
                                                          stroke-linecap="round"
                                                          stroke-linejoin="round"
                                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                                        />
                                                      </svg>
                                                  </button>
                                                  </div>

                                                </td>
                                                <td class="border-b-2 border-dashed border-gray-300 text-right pr-4">
                                                    {{ withCommas(parseFloat((report.closing_debit - report.closing_credit) + +report.ca_opening > 0 ? (report.closing_debit - report.closing_credit) + +report.ca_opening : 0)) }}
                                                </td>
                                                <td class="border-b-2 border-dashed border-gray-300 text-right pr-4">
                                                    {{ withCommas(parseFloat((report.closing_debit - report.closing_credit) + +report.ca_opening <= 0 ? -1 * ((report.closing_debit - report.closing_credit) + +report.ca_opening) : 0)) }}
                                                </td>
                                            </tr>
                                        </template>
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <!-- end report date -->
                    </div>
                </div>
            </div>
        </div>

        <ConfirmationModal :show="displayDeleteModal != null" @close="displayDeleteModal = null">
            <template #title>
                Merge Account
            </template>

            <template #content>
                <div>
                  <label
                    for="coa4_id"
                    class="block text-sm md:text-base font-medium text-gray-900"
                    >Merge With Account
                    <span class="text-red-500">*</span>
                  </label>
                  <div class="mt-1">
                    <multiselect
                      v-model="selectedCoaId"
                      :options="all_chart_of_accounts"
                      @update:modelValue="onAccountCodeSelect"
                      label="acc_desc"
                      class="z-50"
                      track-by="id"
                      placeholder="Select account code"
                    ></multiselect>
                  </div>
                  <InputError
                    v-if="error"
                    :message="error"
                  />
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="displayDeleteModal = null">
                    Cancel
                </SecondaryButton>

                <button class="block rounded bg-red-700 w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-red-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer ml-2" @click="updateAccount('delete')">Merge & Delete</button>

                <button class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer ml-2" @click="updateAccount('merge')">Only Merge Account</button>

            </template>
        </ConfirmationModal>

    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, usePage,  Link as InertiaLink } from "@inertiajs/vue3";
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import { computed, ref } from "vue";
import { Inertia } from "@inertiajs/inertia";
import dayjs from 'dayjs';

const props = defineProps({
    reports: Array,
    accounts: Array,
    all_chart_of_accounts: Array,
    selected_accounts: String,
});
const projectType = import.meta.env.VITE_PROJECT_TYPE;

const defaultFromDate = dayjs().month() < 6 ? dayjs().year(dayjs().year() - 1).month(6).date(1).format('YYYY-MM-DD') : dayjs().year(dayjs().year()).month(6).date(1).format('YYYY-MM-DD');
const defaultToDate = dayjs().month() < 6 ? dayjs().year(dayjs().year()).month(5).date(30).format('YYYY-MM-DD') : dayjs().year(dayjs().year() + 1).month(5).date(30).format('YYYY-MM-DD');

const filter = useForm({
  selected_accounts: props?.selected_accounts || null,
  from_date: defaultFromDate,
  to_date: defaultToDate,
});


const selectedAccount = ref(null);
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



const page = usePage()
const roles = ref([]);
if(page.props.auth.user?.user_role){
    page.props.auth.user?.user_role.map(user_role => {
        roles.value.push(user_role?.role_id);
    });
}

const displayDeleteModal = ref(null);
const coa_id = ref(null);
const delete_id = ref(null);
const selectedCoaId = ref(null);
const error = ref(null);

const deleteChartOfAccount = (id) => {
  error.value = null;
  delete_id.value = id;
  displayDeleteModal.value = 1;
};


const onAccountCodeSelect = async (selectedCoaId) => {
  coa_id.value = selectedCoaId.id;
};

const updateAccount = async (type) => {
  if(coa_id.value == '' || coa_id.value == null){
    error.value = 'Merge Account is required!';
    return;
  }
  return Inertia.post(route("chart-of-accounts-delete", {id:delete_id.value, type:type, coa_id:coa_id.value})).then(res => {
    displayDeleteModal.value = null;
  });
};
const searchItem = async (val) => {
  try {
    router.visit(
        route( "chart-of-accounts.index" , {
            search: val,
        }),
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
  } catch (error) {
    console.error("Error fetching cities:", error);
  }
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
            .hide-print {
            display: none;
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
