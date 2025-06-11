<template>
  <Head title="Vouchers" />
  <AppLayout title="Vouchers">
      <div class="w-full mx-auto pt-6">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
          <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <h1 class="font-semibold text-3xl text-primary">
              Vouchers
            </h1>
            <div class="mt-2 sm:mt-0">
                        <form @submit.prevent="report.get(route('vouchers.index'), report)">
                            <div class="grid grid-cols-1 gap-x-4 gap-y-4 md:grid-cols-3">
                                <div>
                                    <label for="voucher_no" class="block text-sm md:text-base font-medium text-gray-900">
                                    Voucher #
                                    </label>
                                    <div class="mt-2">
                                    <input id="voucher_no" name="voucher_no" type="text" autocomplete="voucher_no" class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="report.voucher_no"/>
                                    </div>
                                </div>
                                <div>
                                    <label for="from_date" class="block text-sm md:text-base font-medium text-gray-900">
                                    From Date
                                    </label>
                                    <div class="mt-2">
                                    <input id="from_date" name="from_date" type="text" ref="from_date" autocomplete="from_date" class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="report.from_date"/>
                                    </div>
                                </div>
                                <div>
                                    <label for="to_date" class="block text-sm md:text-base font-medium text-gray-900">
                                    To Date
                                    </label>
                                    <div class="mt-2">
                                    <input id="to_date" name="to_date" type="text" ref="to_date" autocomplete="to_date" class="block w-full rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" v-model="report.to_date"/>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <label for="to_date" class="block text-sm md:text-base font-medium text-gray-900">
                                    &nbsp;
                                    </label>
                                    <button type="submit" class="rounded bg-primary px-5 py-2 w-24 text-sm md:text-base font-medium text-white shadow-sm border border-primary hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary">
                                    Search
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
              <!-- <inertia-link
                  :href="route('vouchers.create')"
                  class="block rounded bg-primary w-fit px-4 py-2 text-center text-sm md:text-base font-medium text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                  >Create voucher</inertia-link
                > -->
            </div>
            <div class="mt-6 flow-root">
                <div class="ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden">
                  <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                    <thead>
                      <tr class="divide-x divide-gray-200">
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          ID
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          Voucher type
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          Voucher date
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          Voucher #
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          Debit.
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          Credit.
                        </th>
                        <th
                          scope="col"
                          class="bg-primary whitespace-nowrap py-3.5 px-4 text-center text-base md:text-lg font-medium text-white"
                        >
                          Actions
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        class="divide-x divide-gray-200"
                        v-for="(
                          voucher, index
                        ) in vouchers.data"
                        :key="voucher.id"
                        :class="[
                            index % 2 === 0 ? 'bg-gray-50' : 'bg-white',
                            currentVoucherId === voucher.id ? 'highlighted-row' : ''
                        ]"
                      >
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ voucher.id }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{
                            voucher?.voucher_type?.voucher_name
                          }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ convertDate(voucher?.voucher_date) }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ voucher.voucher_no }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ withCommas(voucher.totalDr) }}
                        </td>
                        <td
                          class="whitespace-nowrap text-center relative p-4 text-gray-900 text-sm md:text-base border-b border-gray-200"
                        >
                          {{ withCommas(voucher.totalCr) }}
                        </td>
                        <td
                          class="relative whitespace-nowrap border-b border-gray-200 p-4"

                        >
                        <div class="flex items-center justify-center space-x-3" >
                          <InertiaLink  v-if="roles.includes(1) || editOptionEnable(voucher?.created_at) < 10"
                            :href="
                              route(
                                'vouchers.edit',
                                voucher.id
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
                          <button  v-if="roles.includes(1) || editOptionEnable(voucher?.created_at) < 10"
                            @click.prevent="
                              deleteVoucher(voucher.id)
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
                          <div class="mt-2">
                            <button @click="printVoucher(voucher)"> <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                          </svg></button>
                          </div>
                        </div>

                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
                <div class="pagination-container py-4 flex justify-between px-4">
      <div class="pagination-info flex">
        Page
        <span class="font-semibold px-2 text-gray-900 ">{{ vouchers.current_page }}</span>
        of
        <span class="font-semibold px-2 text-gray-900 ">{{ vouchers.last_page }}</span>
        <div>
          <p class="pagination-info">
            Showing
            <span class="font-semibold px-1  text-gray-900 ">{{ vouchers.from }}</span>
            to
            <span class="font-semibold px-1  text-gray-900 ">{{ vouchers.to }}</span>
            of
            <span class="font-semibold px-1  text-gray-900 ">{{ vouchers.total }}</span>
            entries
          </p>
        </div>
      </div>

      <div class="pagination-links">
        <template v-if="vouchers.links">
          <template v-for="(link, index) in vouchers.links" :key="index">
            <InertiaLink
              v-if="link.url"
              :href="link.url"
              v-html="link.label"
              :class="['pagination-box', { 'pagination-box-current': link.active }]"
            ></InertiaLink>
          </template>
        </template>
      </div>
    </div>
              </div>
        </div>
      </div>
  </AppLayout>
</template>

      <script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref,onMounted } from 'vue';
import { Head, Link as InertiaLink, usePage, useForm } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import Swal from 'sweetalert2';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
const from_date = ref(null);
const to_date = ref(null);
const props = defineProps({
    vouchers: Array,
    from_date: String,
    to_date: String,
    voucher_no: String,
});

const report = useForm({
    from_date: props?.from_date || new Date().toISOString().slice(0, 10),
    to_date: props?.to_date || new Date().toISOString().slice(0, 10),
    voucher_no: props?.voucher_no || null,
});

const printVoucher = (voucher) => {
    const url = route("vouchers.print", voucher);
    const printWindow = window.open(url, "_blank");
    setTimeout(function(){
        printWindow.focus();
        }, 500);
};
const convertDate = (dated) => {
    // Example date string in ISO format
  const dateString = dated;

  // Create a Date object from the date string
  const date = new Date(dateString);

  // Extract day, month, and year
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
  const year = date.getFullYear();

  // Format date as d-m-y
  const formattedDate = `${day}-${month}-${year}`;
  return formattedDate;

}
const editOptionEnable = (date) => {
  // Your date string
  const dateString = date;

  // Parse the date string into a Date object
  const givenDate = new Date(dateString);

  // Get the current date
  const currentDate = new Date();

  // Calculate the difference in milliseconds
  const differenceInMilliseconds =  currentDate - givenDate;

  // Convert the difference from milliseconds to minutes
  const differenceInMinutes = Math.floor(differenceInMilliseconds / 1000 / 60);
  return differenceInMinutes;
}

const withCommas = (value) => {
  if (value == null || value == '') {
    return '0';
  }
  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

onMounted(() => {
    const flatpickrOptions = (defaultDate) => ({
        dateFormat: "d-m-Y",
        allowInput: true,
        clickOpens: true,
        defaultDate: defaultDate || new Date(),
    });

    flatpickr(to_date.value, flatpickrOptions(report.to_date));
    flatpickr(from_date.value, flatpickrOptions(report.from_date));


});
const deleteVoucher = (id) => {
//   if (confirm("Are you sure you want to delete this voucher?")) {
//     return Inertia.delete(route("vouchers.destroy", id));
//   }
Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.delete(route("vouchers.destroy", id));
        }
    });
};

const page = usePage()
const roles = ref([]);
if(page.props.auth.user?.user_role){
    page.props.auth.user?.user_role.map(user_role => {
        roles.value.push(user_role?.role_id);
    });
    console.log('roles', roles.value);
}

</script>
      <script>
export default {
  components: {
    AppLayout,
    Head,
    InertiaLink,
  },
};
</script>
<style scoped>
.pagination-box {
    padding: 10px 16px;
  border: 1px solid #ccc;
  border-radius: 0.25rem;
  margin: 0 0.25rem;
  text-decoration: none;
}
.pagination-box-current {
  background-color: #6D4C41;
  color: #fff;
}
.pagination-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1rem;
}
.pagination-info {
  display: flex;
  align-items: center;
}
.pagination-links {
  display: flex;
}
</style>
