<template>
  <Head title="Create Voucher" />
  <AppLayout>
    <div class="w-full pt-6">
      <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-md rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <h1 class="font-semibold text-3xl text-primary">
            {{ form?.id ? "Update" : "Create" }} Voucher
          </h1>
          <div class="grid lg:grid-cols-5 gap-2 max-w-7xl mx-auto pt-6 pb-2">
            <input v-model="VoucherNo" placeholder="Enter Voucher No" class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"/>
            <button @click="onSearchClick" class="bg-primary w-32 px-2 mt-1 rounded-md text-white">Search</button>
          </div>
            <form
            @submit.prevent
            >
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 py-6">
                  <div>
                    <label
                      for="voucher_no"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Voucher number
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="name"
                        name="voucher_no"
                        type="text"
                        autocomplete="voucher_no"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        disabled="true"
                        :value="form.voucher_no"
                        tabindex="-1"
                      />
                    <InputError
                      v-if="form?.errors?.voucher_no"
                      :message="form?.errors?.voucher_no"
                    />
                  </div>
                  <div>
                    <label
                      for="voucher_type_id"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Voucher type
                      <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                      <multiselect
                        v-model="selectedVoucherTypeId"
                        :options="voucher_types"
                        @select="onVoucherTypeSelect"
                        label="voucher_name"
                        track-by="id"
                        placeholder="Select voucher type"
                      ></multiselect>
                    </div>
                    <InputError
                      v-if="form?.errors?.voucher_type_id"
                      :message="form?.errors?.voucher_type_id"
                    />
                  </div>
                  <div>
                    <label
                      for="voucher_date"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Voucher date
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="voucher_date"
                        name="voucher_date"
                        type="text" ref="voucher_date"
                        autocomplete="voucher_date"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.voucher_date"
                      />
                    <InputError
                      v-if="form?.errors?.voucher_date"
                      :message="form?.errors?.voucher_date"
                    />
                  </div>
                  <div>
                    <label
                      for="narration"
                      class="block text-sm md:text-base font-medium text-gray-900"
                      >Narration
                      <span class="text-red-500">*</span>
                    </label>
                      <input
                        id="name"
                        name="narration"
                        type="text"
                        autocomplete="narration"
                        class="mt-1 block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base h-11"
                        v-model="form.narration"
                      />
                    <InputError
                      v-if="form?.errors?.narration"
                      :message="form?.errors?.narration"
                    />
                  </div>
                </div>
                <!--Voucher Detail-->
                <div class="divide-y divide-gray-200 pt-4 pb-4">
                <div class="border shadow-md rounded-lg border-gray-200 px-4 py-6 mb-4 bg-green-50">
                  <h3 class="text-2xl font-semibold text-gray-900 pb-3">Voucher detail</h3>
                  <InputError
                    v-if="form.errors?.[`voucher_details`]"
                    :message="form.errors?.[`voucher_details`]"
                  />
                  <div class="border rounded mb-2">
                    <div class="flex divide-x border-b" >
                      <div class="min-w-0 w-96 p-1 flex items-center justify-center">
                        <label for="account" class="text-center font-medium">Account</label>
                      </div>
                      <!-- <div class="min-w-0 w-96 p-1 flex items-center justify-center">
                        <label for="service-doctor" class="text-center font-medium">Service/Doctor</label>
                      </div> -->
                      <div class="min-w-0 p-1 flex items-center justify-center w-32">
                        <label for="dr" class="text-center font-medium">DR</label>
                      </div>
                      <div class="min-w-0 p-1 flex items-center justify-center w-32">
                        <label for="cr" class="text-center font-medium">CR</label>
                      </div>
                      <div class="min-w-0 p-1 w-60 flex justify-center">
                        <label for="transaction_type" class="text-center font-medium">Transaction type</label>
                      </div>
                      <div class="min-w-0 w-44 p-1 flex items-center justify-center">
                        <label for="transaction_no" class="text-center font-medium">Transaction #</label>
                      </div>
                      <div class="min-w-0  w-2/5 p-1 flex items-center justify-center">
                        <label for="remarks" class="text-center font-medium">Remarks</label>
                      </div>
                      <div class="flex items-center p-1 w-[49px]"></div>
                    </div>
                  <div
                    v-for="(voucher_detail, index) in form.voucher_details"
                    :key="index"
                  >
                  <div class="flex divide-x border-b">
                        <div class="min-w-0 w-96 p-1 flex items-center justify-center">
                          <div class="w-full">
                        <label for="card-expiration-date" class="sr-only"
                          >Account</label
                        >
                        <multiselect
                        @keydown.enter
                          v-model="voucher_detail['chart_of_account']"
                          :options="chart_of_accounts"
                          :custom-label="accountLabel"
                          track-by="id"
                          placeholder="Select account"
                        ></multiselect>
                        <InputError
                          v-if="
                            form.errors?.[
                              `voucher_details.${index}.chart_of_account.id`
                            ]
                          "
                          :message="
                            form.errors?.[
                              `voucher_details.${index}.chart_of_account.id`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <!-- <div class="min-w-0 w-64 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="service-employee" class="sr-only">Service/Doctor Name</label>
                        <input
                                :value="voucher_detail['transaction_type'] ? voucher_detail['transaction_type']['name'] : null"
                                type="text"
                                readonly
                                class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                                placeholder="Service/Doctor Name"
                            />
                        </div>
                    </div> -->
                      <div class="min-w-0 w-32 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">DR</label>
                        <input
                          v-model="voucher_detail['dr']"
                          type="text"    @keydown="handleF3Key($event, index,'dr')"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="DR"
                          @input="updateCOD()"
                          :disabled="
                            voucher_detail['cr'] != null &&
                            voucher_detail['cr'] != '' &&
                            voucher_detail['cr'] != 0
                          "
                          :tabindex="
                            voucher_detail['cr'] != null &&
                            voucher_detail['cr'] != '' &&
                            voucher_detail['cr'] != 0
                              ? -1
                              : ''
                          "
                        />
                        <InputError
                          v-if="form.errors?.[`voucher_details.${index}.dr`]"
                          :message="
                            form.errors?.[`voucher_details.${index}.dr`]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-32 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">CR</label>
                        <input
                          v-model="voucher_detail['cr']"
                          type="text"    @keydown="handleF3Key($event, index,'cr')"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          @input="updateCOD()"
                          placeholder="CR"
                          :disabled="
                            voucher_detail['dr'] != null &&
                            voucher_detail['dr'] != '' &&
                            voucher_detail['dr'] != 0
                          "
                          :tabindex="
                            voucher_detail['dr'] != null &&
                            voucher_detail['dr'] != '' &&
                            voucher_detail['dr'] != 0
                              ? -1
                              : ''
                          "
                        />
                        <InputError
                          v-if="form.errors?.[`voucher_details.${index}.cr`]"
                          :message="
                            form.errors?.[`voucher_details.${index}.cr`]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 p-1 w-60 flex justify-center">
                        <div class="w-44">
                        <label for="card-expiration-date" class="sr-only"
                          >Transaction type</label
                        >
                        <multiselect
                          v-model="voucher_detail['transaction_type']"
                          :options="transaction_types"
                          label="label"
                          track-by="label"
                          placeholder="Select transaction type"
                          @keydown="handleF3Key($event, index,'transaction_type')"
                          class="w-44"
                        ></multiselect>
                        <InputError
                          v-if="
                            form.errors?.[
                              `voucher_details.${index}.transaction_type`
                            ]
                          "
                          :message="
                            form.errors?.[
                              `voucher_details.${index}.transaction_type`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-44 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only"
                          >Transaction #</label
                        >
                        <input
                          v-model="voucher_detail['transaction_no']"
                          type="text"    @keydown="handleF3Key($event, index,'transaction_no')"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Transaction #"
                        />
                        <InputError
                          v-if="
                            form.errors?.[
                              `voucher_details.${index}.transaction_no`
                            ]
                          "
                          :message="
                            form.errors?.[
                              `voucher_details.${index}.transaction_no`
                            ]
                          "
                        />
                      </div>
                      </div>
                      <div class="min-w-0 w-2/5 p-1 flex items-center justify-center">
                        <div class="w-full">
                        <label for="card-cvc" class="sr-only">Remarks</label>
                        <input
                          v-model="voucher_detail['remarks']"
                          type="text"    @keydown="handleF3Key($event, index,'remarks')"
                          class="relative block w-full rounded-md border-0 bg-transparent py-1.5 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:z-10 focus:ring-2 focus:ring-inset focus:ring-primary sm:text-sm sm:leading-6"
                          placeholder="Remarks"
                        />
                        <InputError
                          v-if="
                            form.errors?.[`voucher_details.${index}.remarks`]
                          "
                          :message="
                            form.errors?.[`voucher_details.${index}.remarks`]
                          "
                        />
                      </div>
                      </div>
                      <div class="flex items-center p-1">
                        <button
                          type="button"
                          @click="removeVoucherDetail(index)"
                          class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                          tabindex="-1"
                        >
                          <svg
                            class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"
                            />
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                  </div>
                  <button
                    type="button"
                    @click="addVoucherDetail"
                    class="inline-flex items-center mt-1 px-3 py-2 text-sm font-medium text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    <svg
                      class="w-4 h-4 mr-2"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                      />
                    </svg>
                    Add More
                  </button>
                </div>
              </div>

            </form>
            <div class="flex items-center justify-end mb-4">
                <span class="p-4 border rounded w-1/3">
                  DR => {{form.voucher_details.reduce((sum, row) => +sum + +row['dr'], 0)}}
                  CR => {{form.voucher_details.reduce((sum, row) => +sum + +row['cr'], 0)}}
                </span>
              </div>
              <div class="flex items-center justify-end gap-4 py-4">
                <InertiaLink
                  class="inline-flex justify-center rounded bg-white px-5 py-2 w-24 text-sm md:text-base font-medium text-primary shadow-sm border border-primary hover:bg-primary hover:text-white"
                  :href="route('vouchers.index')"
                >
                  Cancel
                </InertiaLink>
                <button
                   @click.prevent="submitForm()"  @keydown.enter.prevent="submitForm()"
                  class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary"
                >
                  Save
                </button>
              </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
    <script setup>
  import { Inertia } from "@inertiajs/inertia";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head, useForm, Link as InertiaLink, router } from "@inertiajs/vue3";
import Multiselect from "vue-multiselect";
import { onMounted, ref, defineComponent, computed } from "vue";
import InputError from "../../Components/InputError.vue";
import Swal from 'sweetalert2';

import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

const voucher_date = ref(null);
const projectType = import.meta.env.VITE_PROJECT_TYPE;
const selectedVoucherTypeId = ref(null);

const selectedVoucherNo = computed(() => {
  const voucherTypeId = selectedVoucherTypeId.value?.id;

  if (voucherTypeId === 5) {
    return props.voucher_number[5] || '';
  }
  else if (voucherTypeId === 4) {
    return props.voucher_number[4] || '';
  }
   else if (voucherTypeId === 3) {
    return props.voucher_number[3] || '';
  }
  else if (voucherTypeId === 2) {
    return props.voucher_number[2] || '';
  }
   else if (voucherTypeId === 1) {
    return props.voucher_number[1] || '';
  }

  return props.voucher_number[1] || '';
});
const props = defineProps({
  voucher: Object,
  voucher_types: Array,
  chart_of_accounts: Array,
  voucher_number: Object,
});
console.log('voucher_number',props?.voucher_number);
const form = useForm({
    id: props?.voucher?.id || null,
  voucher_type_id: props?.voucher?.voucher_type_id || null,
  voucher_date: props?.voucher?.voucher_date || new Date().toLocaleDateString("en-GB").replace(/\//g, "-"),
  //   voucher_date: (props?.voucher?.voucher_date ? new Date(props?.voucher?.voucher_date).getFullYear()+'-'+(new Date(props?.voucher?.voucher_date).getMonth() + 1).toString().padStart(2, '0')+'-'+new Date(props?.voucher?.voucher_date).getDate().toString().padStart(2, '0') : null),
 voucher_no :selectedVoucherNo || props?.voucher?.voucher_no || null,
  narration: props?.voucher?.narration || null,
  voucher_details: props?.voucher?.voucher_details || [
    {
      id: null,
      chart_of_account: null,
      dr: null,
      cr: null,
      transaction_type: null,
      transaction_no: null,
      remarks: null,
    },
    {
      id: null,
      chart_of_account: null,
      dr: null,
      cr: null,
      transaction_type: null,
      transaction_no: null,
      remarks: null,
    },
  ],
});

const updateCOD = () => {
  if(projectType == 'jinnah'){
    if((form.voucher_type_id == '2') && !form.id){
    // let sum_cr = 0;
    form.voucher_details = [...form.voucher_details.filter(item => item.chart_of_account !== null)];

    let sum_dr = 0;
    form.voucher_details.map(res => {
      sum_dr = +sum_dr + +res.dr;
    });


    let coa = form.voucher_details.filter(res => {
      return res.chart_of_account?.id == 189;
    });

    if(coa && coa[0]){
      form.voucher_details.map(res => {
        if(res.chart_of_account?.id == 189){
          res.cr = sum_dr;
        }
      });
    }
    else{
      let chart_of_account = props?.chart_of_accounts.filter(res => {
        return res.id == 189;
      })

      form.voucher_details.push({
        id: null,
        chart_of_account: {...chart_of_account[0]},
        dr: null,
        cr: sum_dr,
        transaction_type: null,
        transaction_no: null,
        remarks: null,
      });
    }
  }
  else if(form.voucher_type_id == '1' && !form.id){
    form.voucher_details = [...form.voucher_details.filter(item => item.chart_of_account !== null)];

    let sum_cr = 0;
    form.voucher_details.map(res => {
        sum_cr = +sum_cr + +res.cr;
        console.log(sum_cr);
    });


    let coa = form.voucher_details.filter(res => {
    return res.chart_of_account?.id == 189;
    });

    if(coa && coa[0]){
    form.voucher_details.map(res => {
        if(res.chart_of_account?.id == 189){
        res.dr = sum_cr;
        }
    });
    }
    else{
    let chart_of_account = props?.chart_of_accounts.filter(res => {
        return res.id == 189;
    })

    form.voucher_details.push({
        id: null,
        chart_of_account: {...chart_of_account[0]},
        cr: null,
        dr: sum_cr,
        transaction_type: null,
        transaction_no: null,
        remarks: null,
    });
    }
  }
}else{
    if((form.voucher_type_id == '2') && !form.id){
    // let sum_cr = 0;
    form.voucher_details = [...form.voucher_details.filter(item => item.chart_of_account !== null)];

    let sum_dr = 0;
    form.voucher_details.map(res => {
      sum_dr = +sum_dr + +res.dr;
    });


    let coa = form.voucher_details.filter(res => {
      return res.chart_of_account?.id == 59;
    });

    if(coa && coa[0]){
      form.voucher_details.map(res => {
        if(res.chart_of_account?.id == 59){
          res.cr = sum_dr;
        }
      });
    }
    else{
      let chart_of_account = props?.chart_of_accounts.filter(res => {
        return res.id == 59;
      })

      form.voucher_details.push({
        id: null,
        chart_of_account: {...chart_of_account[0]},
        dr: null,
        cr: sum_dr,
        transaction_type: null,
        transaction_no: null,
        remarks: null,
      });
    }
  }
  else if(form.voucher_type_id == '1' && !form.id){
    form.voucher_details = [...form.voucher_details.filter(item => item.chart_of_account !== null)];

    let sum_cr = 0;
    form.voucher_details.map(res => {
        sum_cr = +sum_cr + +res.cr;
        console.log(sum_cr);
    });


    let coa = form.voucher_details.filter(res => {
    return res.chart_of_account?.id == 59;
    });

    if(coa && coa[0]){
    form.voucher_details.map(res => {
        if(res.chart_of_account?.id == 59){
        res.dr = sum_cr;
        }
    });
    }
    else{
    let chart_of_account = props?.chart_of_accounts.filter(res => {
        return res.id == 59;
    })

    form.voucher_details.push({
        id: null,
        chart_of_account: {...chart_of_account[0]},
        cr: null,
        dr: sum_cr,
        transaction_type: null,
        transaction_no: null,
        remarks: null,
    });
    }
  }
}
};


const addVoucherDetail = () => {
  form.voucher_details.push({
    id: null,
    chart_of_account: null,
    dr: null,
    cr: null,
    transaction_type: null,
    transaction_no: null,
    remarks: null,
  });
};
const handleF3Key = (event, index, field) => {
  if (event.key === 'F3') {
    event.preventDefault();
    const voucherDetails = form.voucher_details;

    if (!voucherDetails[index][field]) {
      if (index > 0) {
        voucherDetails[index][field] = voucherDetails[index - 1][field];
      }
    }
  }
};

const removeVoucherDetail = (index) => {
  if (form.voucher_details.length > 2) {
    form.voucher_details.splice(index, 1);
  }
};
const VoucherNo = ref(props?.voucher?.voucher_no || '');

const searchVoucher = (voucherNo) => {
  if (voucherNo) {
    router.visit(route('voucher-edit', voucherNo));
  }
};
const accountLabel = (chart_of_account) => {
    // console.log(chart_of_account);
    return `${chart_of_account.acc_desc} (${chart_of_account.acc_code})`;
};
const onSearchClick = () => {
    searchVoucher(VoucherNo.value);
};

const transaction_types = computed(() => {
  return [{ label: "Cheque" }, { label: "Bill no." }, { label: "ipd" }, { label: "lab" }, { label: "opd" }, { label: "expense" }, { label: "donor_fund" }, { label: "donation" }];
});

// const selectedVoucherTypeId = ref(null);
if (props?.voucher) {
  const group = props.voucher_types.find(
    (c) => c.id == props.voucher.voucher_type_id
  );
  if (group) {
    selectedVoucherTypeId.value = group;
  }
}
else if(new URLSearchParams(window.location.search).get("vt") && new URLSearchParams(window.location.search).get("vt") != ''){
  const group = props.voucher_types.find(
    (c) => c.id == new URLSearchParams(window.location.search).get("vt")
  );
  if (group) {
    selectedVoucherTypeId.value = group;
    form.voucher_type_id = new URLSearchParams(window.location.search).get("vt");
  }
}
else{
  router.visit(route('vouchers.create'));
}

const onVoucherTypeSelect = async (selectedVoucherTypeId) => {
  form.voucher_type_id = selectedVoucherTypeId.id;
};
console.log('voucher_type_id',selectedVoucherTypeId.value);

const getVoucherId = () => {
    const urlParams = new URLSearchParams(window.location.search);
    return props?.voucher?.id || urlParams.get("vId");
};

const submitForm = () => {
    Swal.fire({
        title: 'Are you sure?',
        text: "Do you want to save this voucher?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, save it!'
    }).then((result) => {
        // Only proceed if the user clicked "Yes"
        if (result.isConfirmed) {
            // Ensure that form submission only happens after SweetAlert's confirmation
            const voucher_id = getVoucherId();

            if (form?.id) {
                // Update voucher
                form.put(route('vouchers.update', { id: form.id }), {
                    preserveScroll: true,
                    onSuccess: (res) => {
                        printVoucher(voucher_id);
                        Inertia.reload();
                    },
                    onError: (err) => {
                        console.error('Form update failed:', err);
                    }
                });
            } else {
                // Create new voucher
                form.post(route('vouchers.store'), {
                    preserveScroll: true,
                    onSuccess: (res) => {
                        const url_string = window.location.href;
                        const url = new URL(url_string);
                        const voucher_id = url.searchParams.get("vId") || getVoucherId();
                        printVoucher(voucher_id);
                        Inertia.reload();
                    },
                    onError: (err) => {
                        console.error('Form creation failed:', err);
                    }
                });
            }
        }
    }).catch((err) => {
        // Catch any errors in case SweetAlert fails to handle correctly
        console.error('SweetAlert error:', err);
    });
};
const printVoucher = (voucher_id) => {
    if (!voucher_id) {
        console.error("No voucher ID provided for printing.");
        return;
    }
    const url = route("vouchers.print", { vouchers: voucher_id });
    const printWindow = window.open(url, "_blank");
    setTimeout(() => {
        printWindow.focus();
        printWindow.print();
    }, 500);
};
onMounted(() => {
    const flatpickrOptions = (defaultDate) => ({
        dateFormat: "d-m-Y",
        allowInput: true,
        clickOpens: true,
        defaultDate: defaultDate || new Date(),
    });

    flatpickr(voucher_date.value, flatpickrOptions(form.voucher_date));
    // const today = new Date().toISOString().slice(0, 10);
    // form.details.forEach(detail => {
    //     detail.date = today;
    // });
});
defineComponent({
  components: {
    Multiselect,
  },
});
</script>
    <style src="vue-multiselect/dist/vue-multiselect.css"></style>
