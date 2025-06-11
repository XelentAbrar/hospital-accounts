<template>
    <Head title="CashBook" />
    <AppLayout title="CashBook">
      <div class="p-6 bg-gray-50 ">
        <div class="">
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Cash Book Report</h2>
          </div>

          <!-- Filter Section -->
          <div class=" p-4 mb-6">
            <form @submit.prevent="filters.get(route('cash-books'), filters)" >
                <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-6">
             <div class="mt-2">
                <label for="from_date" class="block text-sm font-semibold text-gray-900">
                    Search
                    </label>
                    <input
                    v-model="filters.search"
                    placeholder="Search by Voucher ID, No, Description, etc."
                    class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base"
                  />
             </div>
              <div class="mb-2">
                    <label for="from_date" class="block text-sm font-semibold text-gray-900">
                    From Date
                    </label>
                    <div class="mt-2">
                    <input id="from_date" name="from_date" type="text" ref="from_date" autocomplete="from_date" class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="filters.from_date"/>
                    </div>
                </div>
                <div class="mb-2">
                    <label for="to_date" class="block text-sm font-semibold text-gray-900">
                    To Date
                    </label>
                    <div class="mt-2">
                    <input id="to_date" name="to_date" type="text" ref="to_date" autocomplete="to_date" class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="filters.to_date"/>
                    </div>
                </div>
                <div class="mt-6">
                    <button type="submit" class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary" :disabled="isLoading" :class="{ 'opacity-25': isLoading }">
                    Search
                    </button>
                    <button class="rounded bg-blue-700 px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-blue-700 hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-700 ml-2" @click="print()" v-if="reports.length > 0 && !isLoading">
                    Print
                    </button>
                </div>
            </div>
            </form>
          </div>
          <div class="mt-2 flow-root" id="printData">
                    <div style="font-family: Arial, Helvetica, sans-serif; padding: 20px;display: flex;flex-direction: column;">
                        <div class="print-only">
                            <!-- <div style="display: flex;flex-direction: column; align-items: center;justify-content: center;"> -->
                                <!-- <img style="width:100px;object-fit: contain; margin-bottom: 16px;" alt=""> -->
                                <!-- <p style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Nawaz Town Sarghoda Road, Faisalabad</p>
                                <p style="font-size: 18px;font-weight: 500;text-align: center;margin: 0;margin-bottom: 2px;">Ph: 041-8848060 | 8787231</p> -->
                            <!-- </div> -->
                            <div style="flex: 1; display: flex; align-items: center; justify-content: flex-start;">
                                <!-- <h4 style="margin: 0; font-size: xx-small; font-family: sans-serif; width: 100px;">Printed On</h4>
                                <h4 style="margin: 0 6px; font-weight: bold; width: 6px; text-align: center;">:</h4> -->
                                <p style="margin: 0; font-size: xx-small; margin-left: 2px; text-align: left;margin-top: -2.3rem; font-weight: 500; white-space: nowrap;">
                                    {{ new Date().toLocaleDateString('en-GB').replace(/\//g, '-') }}
                                </p>
                            </div>
                            <p style="font-size: 24px;line-height:1.2;font-weight: 700;text-align: center;margin: 0">Chiniot General Hospital</p>
                            <p style="font-size: 14px;line-height:1.2;font-weight: 400;text-align: center;margin: 0" v-if="projectType === 'hms'">Nawaz Town Sarghoda Road, Faisalabad</p>
                            <p style="font-size: 14px;line-height:1.2;font-weight: 400;text-align: center;margin: 0" v-else>209 Jinnah Colony , Faisalabad</p>
                            <div>
                                <p style="font-size: 22px;line-height:1.2;font-weight: 600;text-align: center;margin: 0;">Cash Book</p>
                            </div>
                            <div    style="
                                    display: flex;
                                    align-items: center;
                                    justify-content: space-between;
                                    width: 100%;
                                    margin: 0px auto 20px auto;
                                ">

                            <div style="
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                width: 100%;
                                margin: 6px  auto;
                            ">
                                <div style="
                                    padding: 0 12px;
                                    border: 1px solid black;
                                    border-radius: 0;
                                    text-align: center;
                                    font-weight: 500;
                                    white-space: nowrap;
                                    font-size: 14px;
                                ">
                                   From : {{ filters?.from_date }} - To : {{ filters?.to_date }}
                                </div>
                            </div>

                        </div>

                        </div>
          <div class="bg-white rounded_lg overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full h-auto border-collapse border-spacing-0 divide-y divide-gray-300 overflow-auto mb-8">
                <thead>
                  <tr class="divide-x divide-gray-300">
                    <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border border-gray-300 sticky top-0 z-10 pl-6">V. NO.</th>
                    <!-- <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border border-gray-300 sticky top-0 z-10 w-28">Type</th> -->
                    <!-- <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border border-gray-300 sticky top-0 z-10">Voucher No</th> -->
                    <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border border-gray-300 sticky top-0 z-10">Date</th>
                    <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border border-gray-300 sticky top-0 z-10 pl-6">Description</th>
                    <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border border-gray-300 sticky top-0 z-10 ">Debit</th>
                    <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border border-gray-300 sticky top-0 z-10 ">Credit</th>
                    <th scope="col" class="bg-primary whitespace-nowrap text-center font-bold text_white border border-gray-300 sticky top-0 z-10 ">Balance</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 border-t border-gray-300">
                  <template v-for="voucher in reports" :key="voucher.id">
                    <tr v-for="detail in voucher.voucher_details" :key="detail.id" class="hover:bg-gray-50 divide-x divide-gray-300">
                      <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ voucher.voucher_type.voucher_abrv }}</td> -->
                      <td class="border-b border-gray-300 text-left whitespace-nowrap text-gray-900 text-center align-top"><span class="inline-flex w-7">{{ voucher.voucher_type.voucher_abrv }}</span> {{ voucher.voucher_no }}</td>
                      <!-- <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ voucher.voucher_no }} </td> -->
                      <td class="border-b border-gray-300 text-left whitespace-nowrap text-gray-900 text-center align-top">{{ new Date(voucher?.voucher_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}</td>
                      <td class="border-b border-gray-300 text-left text-gray-900 align-top">
                        <div class="font-bold underline text_base">{{ detail.chart_of_account?.acc_desc }}- <span class="font-normal">{{ detail.chart_of_account?.acc_code }}</span> </div>
                       <div> {{ detail.remarks }}</div>
                      </td>
                      <td class="whitespace-nowrap border-b border-gray-300 text-gray-900 text-right align-top">
                        {{ withCommas(parseFloat(detail.dr ? (detail.dr) : '').toFixed(0)) }}
                      </td>
                      <td class="whitespace-nowrap border-b border-gray-300  text-gray-900 text-right align-top">
                        {{  withCommas(parseFloat(detail.cr ? (detail.cr) : '').toFixed(0)) }}
                      </td>
                      <td class="border-b border-gray-300 whitespace-nowrap text-gray-900 text-right align-top">

                      </td>
                    </tr>
                  </template>
                </tbody>

              </table>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="reports.length === 0" class="text-center py-12">
            <div class="text-gray-500">No records found</div>
          </div>
          </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref ,onMounted ,onUnmounted} from 'vue';
  import AppLayout from "@/Layouts/AppLayout.vue";
  import { Head, useForm, Link as InertiaLink } from "@inertiajs/vue3";
  import flatpickr from "flatpickr";
  import "flatpickr/dist/flatpickr.min.css";
  const props = defineProps({
    reports: Array,
    from_date: String,
    to_date: String,
    search: Object,
  });
  const projectType = import.meta.env.VITE_PROJECT_TYPE;

  const filters = useForm({
    search: props.search || '',
    from_date: props.from_date || new Date().toLocaleDateString("en-GB").replace(/\//g, "-"),
    to_date: props.to_date || new Date().toLocaleDateString("en-GB").replace(/\//g, "-"),
  });
  const handleKeydown = (event) => {
  if (event.ctrlKey && event.key === 'p') {
    event.preventDefault();
    window.location.reload();
  }
};
  onMounted(() => {
    const flatpickrOptions = (defaultDate) => ({
        dateFormat: "d-m-Y",
        allowInput: true,
        clickOpens: true,
        defaultDate: defaultDate || new Date(),
    });

    flatpickr(to_date, flatpickrOptions(filters.to_date));
    flatpickr(from_date, flatpickrOptions(filters.from_date));
    window.addEventListener("keydown", handleKeydown);

});
onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
});
  const formatDate = (dateString) => {
    if (!dateString) return '';
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
  };

  const withCommas = (value) => {
  if (value == null || value == '') {
    return '0';
  }
  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};
  const calculateTotal = (details, type) => {
    return details
      .filter(detail => type === 'dr' ? detail.dr : detail.cr)
      .reduce((sum, detail) => sum + parseFloat(type === 'dr' ? detail.dr : detail.cr), 0);
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
     .text_base{
      font-size: 16px;
     }
     .rounded_lg{
      border-radius: 8px;
      box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
     }
 @media print {
     .print-only {
         display: block;
     }
     .rounded_lg{
      border-radius: 0;
      border: 1px solid #d1d5db;
      box-shadow: none;
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
   .text_base{
      font-size: 10px;
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
