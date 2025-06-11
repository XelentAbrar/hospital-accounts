<template>
  <Head title="Vouchers" />
  <AppLayout title="Vouchers">
    <div class="py-1">
      <div class="w-full mx-auto px-8">
        <div class="bg-white overflow-auto shadow-sm sm:rounded-lg">
          <div class="px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
            <div class="sm:flex sm:items-center">
              <div class="flex justify-between sm:flex-auto">
                <h1 class="text-2xl font-semibold leading-6 text-gray-900">
                    Audit of Vouchers
                </h1>



              </div>
              <!-- <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <inertia-link
                  :href="route('vouchers.create')"
                  class="block rounded-md bg-primary px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary cursor-pointer"
                  >Create voucher</inertia-link
                >
              </div> -->
            </div>

            <div class="flex items-center justify-between py-5">
              <div class="flex items-center ring-1 ring-gray-200 sm:rounded-lg overflow-x-auto overflow-y-hidden">
                <table class="min-w-full border-separate border-spacing-0 divide-y divide-gray-200 overflow-auto">
                  <tbody>
                    <tr class="divide-x divide-gray-200">
                      <td class="whitespace-nowrap relative p_4 text-center font-bold text-sm md:text-base bg-primary text-white border-b"></td>
                      <td class="whitespace-nowrap relative p_4 text-center font-bold text-sm md:text-base bg-primary text-white border-b">Debit</td>
                      <td class="whitespace-nowrap relative p_4 text-center font-bold text-sm md:text-base bg-primary text-white border-b">Credit</td>
                      <td class="whitespace-nowrap relative p_4 text-center font-bold text-sm md:text-base bg-primary text-white border-b">Balance</td>
                    </tr>
                    <tr class="divide-x divide-gray-200 cursor-pointer" v-if="roles.includes(1)">
                      <td class="whitespace-nowrap relative border-b border-gray-200 font-bold text-gray-900 text-md px-2">Vouchers</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(account_vouchers,null,'dr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(account_vouchers,null,'cr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(+sumTotal(account_vouchers,null,'dr') - +sumTotal(account_vouchers,null,'cr'))}}</td>
                    </tr>
                    <tr class="divide-x divide-gray-200 cursor-pointer" :class="actions == 'opd' ? 'bg-yellow-300' : 'bg-gray-300'" @click="showRows('opd')">
                      <td class="whitespace-nowrap relative border-b border-gray-200 font-bold text-gray-900 text-md px-2">MO Eye OPD Receipt & Other ({{vouchers.filter(res => {return res.type == 'opd'}).length}})</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(vouchers, 'opd','dr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(vouchers, 'opd','cr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(+sumTotal(vouchers, 'opd','dr') - +sumTotal(vouchers, 'opd','cr'))}}</td>
                    </tr>
                    <tr class="divide-x divide-gray-200 cursor-pointer" :class="actions == 'donor_fund' ? 'bg-yellow-300' : 'bg-gray-300'" @click="showRows('donor_fund')">
                      <td class="whitespace-nowrap relative border-b border-gray-200 font-bold text-gray-900 text-md px-2">C/O </td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">--</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">--</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">--</td>
                    </tr>
                    <tr class="divide-x divide-gray-200 cursor-pointer"  :class="actions == 'ipd' ? 'bg-yellow-300' : 'bg-white'" @click="showRows('ipd')">
                      <td class="whitespace-nowrap relative border-b border-gray-200 font-bold text-gray-900 text-md px-2">Billing ({{vouchers.filter(res => {return res.type == 'ipd'}).length}})</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(vouchers, 'ipd','dr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(vouchers, 'ipd','cr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(+sumTotal(vouchers, 'ipd','dr') - +sumTotal(vouchers, 'ipd','cr'))}}</td>
                    </tr>
                    <tr class="divide-x divide-gray-200 cursor-pointer"  :class="actions == 'lab' ? 'bg-yellow-300' : 'bg-gray-300'" @click="showRows('lab')">
                      <td class="whitespace-nowrap relative border-b border-gray-200 font-bold text-gray-900 text-md px-2">LAB & Other Receipts ({{vouchers.filter(res => {return res.type == 'lab'}).length}})</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(vouchers, 'lab','dr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(vouchers, 'lab','cr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(+sumTotal(vouchers, 'lab','dr') - +sumTotal(vouchers, 'lab','cr'))}}</td>
                    </tr>
                    <tr class="divide-x divide-gray-200 cursor-pointer"  :class="actions == 'expense' ? 'bg-yellow-300' : 'bg-white'" @click="showRows('expense')">
                      <td class="whitespace-nowrap relative border-b border-gray-200 font-bold text-gray-900 text-md px-2">Expense ({{vouchers.filter(res => {return res.type == 'expense'}).length}})</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(vouchers, 'expense','dr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(vouchers, 'expense','cr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(+sumTotal(vouchers, 'expense','dr') - +sumTotal(vouchers, 'expense','cr'))}}</td>
                    </tr>
                    <tr class="divide-x divide-gray-200 cursor-pointer bg-blue-200">
                      <td class="whitespace-nowrap relative border-b border-gray-200 font-bold text-gray-900 text-md px-2">Total</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(vouchers, 'expense','dr') + +sumTotal(vouchers, 'ipd','dr') + +sumTotal(vouchers, 'opd','dr') + +sumTotal(vouchers, 'lab','dr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas(sumTotal(vouchers, 'expense','cr') + +sumTotal(vouchers, 'ipd','cr') + +sumTotal(vouchers, 'opd','cr') + +sumTotal(vouchers, 'lab','cr'))}}</td>
                      <td class="whitespace-nowrap relative border-b border-gray-200 text-right font-bold text-gray-900 text-md px-2">{{withCommas((+sumTotal(vouchers, 'expense','dr') - +sumTotal(vouchers, 'expense','cr')) + +(+sumTotal(vouchers, 'opd','dr') - +sumTotal(vouchers, 'opd','cr')) + +(+sumTotal(vouchers, 'ipd','dr') - +sumTotal(vouchers, 'ipd','cr')) + +(+sumTotal(vouchers, 'lab','dr') - +sumTotal(vouchers, 'lab','cr')))}}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- v-if="roles.includes(1)" -->
<div class="px-4 sm:px-6 lg:px-8" >
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto flex justify-between">
      <h1 class="text-lg font-semibold text-green-600">Current @ Accounts</h1>
      <div>
      <input type="checkbox" class="px-1 py-1 rounded" v-model="voucherAudit"> Show Vouchers</div>
    </div>
  </div>
  <div class="mt-2">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
        <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-green-300">
              <tr>
                <th scope="col" class="py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Date</th>
                <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900">Voucher Amount</th>
                <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900">Cash in Hand</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <tr v-for="(current_date_voucher, index) in current_date_vouchers" :class="(index % 2 === 0 ? 'bg-blue-50' : 'bg-white')">
                <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{current_date_voucher.date}}</td>
                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-right">{{withCommas(current_date_voucher.cr)}}</td>
                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-right">{{withCommas(current_date_voucher.cash_in_hand)}}</td>

              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="px-4 sm:px-6 lg:px-8">
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
      <h1 class="text-lg font-semibold text-red-600">Pending @ Accounts</h1>
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <form @submit.prevent="approvedPost()">
            <button class="block rounded-md bg-blue-600 px-3 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600" v-if="roles.includes(1)"> Authorize All Posted </button>
        </form>
    </div>
  </div>
  <div class="mt-2">
    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
        <div class="overflow-hidden shadow ring-1 ring-black/5 sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-300">
            <thead class="bg-red-300">
              <tr>
                <th scope="col" class="py-2 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Date</th>
                <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900">Voucher Amount</th>
                <th scope="col" class="px-3 py-2 text-left text-sm font-semibold text-gray-900">Cash in Hand</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
              <tr v-for="(date_voucher, index) in date_vouchers" :class="(index % 2 === 0 ? 'bg-blue-50' : 'bg-white')">
                <td class="whitespace-nowrap py-2 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{date_voucher.date}}</td>
                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-right">{{withCommas(date_voucher.cr)}}</td>
                <td class="whitespace-nowrap px-3 py-2 text-sm text-gray-500 text-right">{{withCommas(date_voucher.cash_in_hand)}}</td>

              </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td class="whitespace-nowrap px-3 py-2 text-md text-red-500">
                        IPD Pending Admissions: {{ipd_users}}
                    </td>
                </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>




            </div>

            <div class="mt-8 flow-root">
              <div class="-mx-4 -my-2 sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle">
                    <div class="flex px-4">

        </div>

        <div class="px-4 sm:px-6 lg:px-8 mb-2">
  <div class="sm:flex sm:items-center">
    <div class="sm:flex-auto">
        <input class="block w-64 rounded border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary text-sm sm:text-base" :value="filters.search" @keyup.enter="searchItem($event.target.value)" placeholder="Search" />
    </div>
    <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
        <button class="ml-5 rounded-md bg-accent px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-accent focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent" v-if="!roles.includes(1)" @click="postUpdate(vouchers, $event.target.checked)"> Post Selected for Approval </button>
    </div>
  </div>
</div>



                  <table class="min-w-full border-separate border-spacing-0">
                    <thead class="bg-primary/50">
                      <tr>
                        <th
                          scope="col"
                          class="py-1.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                        >
                          Type
                        </th>
                        <th
                          scope="col"
                          class="px-3 py-1.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Voucher type
                        </th>
                        <th
                          scope="col"
                          class="px-3 py-1.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Date
                        </th>
                        <th
                          scope="col"
                          class="px-3 py-1.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Debit.
                        </th>
                        <th
                          scope="col"
                          class="px-3 py-1.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Credit.
                        </th>
                        <th
                          scope="col"
                          class="px-3 py-1.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Voucher Detail
                        </th>
                        <th
                          scope="col"
                          class="px-3 py-1.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Description
                        </th>
                        <th
                          scope="col"
                          class="px-3 py-1.5 text-left text-sm font-semibold text-gray-900 flex items-center"
                        >
                          <input class="mr-2 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600" type="checkbox" @click="setAllIsPosted(vouchers, $event.target.checked, account_vouchers);" />
                          Post
                        </th>
                        <th
                          scope="col"
                          class="px-3 py-1.5 text-left text-sm font-semibold text-gray-900"
                        >
                          Action
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                        <!-- {{ is_posted_voucher }} -->
                     <tr v-if="voucherAudit"  v-for="(voucher_audit,index) in is_posted_voucher" :key="index" class="bg-orange-100">
                         <td class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900"> {{ voucher_audit?.type }}</td>
                         <td class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900 text-center">{{ voucher_audit?.voucher_type?.voucher_name }}</td>
                         <td class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900"> {{ new Date(voucher_audit?.voucher_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}</td>
                         <td
                         class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900 text-right"
                         >
                          {{ withCommas(sum(voucher_audit.voucher_audit_details, 'dr')) }}
                        </td>
                        <td
                          class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900 text-right"
                        >
                          {{ withCommas(sum(voucher_audit.voucher_audit_details, 'cr')) }}
                        </td>
                        <td class="whitespace-nowrap px-2 py-0.5 text-sm text-gray-900">
                            <div class="py-0.5">
                                <dd class="text-sm text-gray-900">
                                    <ul role="list" class="divide-y divide-gray-300 rounded-md border border-gray-300">
                                        <template v-for="voucher_details in voucher_audit.voucher_audit_details">
                                            <li class="flex items-center justify-between px-1.5 py-1 text-sm/6">
                                        <div class="flex flex-1 items-center">
                                            <div class="flex min-w-0 flex-1 gap-2">
                                            <span class="text-xs">{{ voucher_details?.chart_of_account?.acc_desc }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 shrink-0">
                                            <span class="font-medium text-indigo-600 hover:text-indigo-500">{{ voucher_details?.cr != 0 && voucher_details?.cr != '' && voucher_details?.cr != null ? withCommas(parseFloat(voucher_details?.cr).toFixed(0))+' Cr' : withCommas(parseFloat(voucher_details?.dr).toFixed(0))+' Dr' }}</span>
                                        </div>
                                        </li>
                                        </template>
                                    </ul>
                                </dd>
                            </div>
                        </td>
                        <td
                          class="px-1 py-0.5 text-sm text-gray-900"
                        >
                          {{ voucher_audit?.narration }}
                        </td>
                      </tr>
                      <template v-for="(
                          account_voucher, index
                        ) in account_vouchers"
                        :key="account_voucher.id"

                        >
                      <tr
                      class="bg-green-200"
                      >
                      <td class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900">
                        Voucher
                        </td>
                        <td class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900">
                          {{ account_voucher?.voucher_type?.voucher_name }}
                        </td>
                        <td class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900">
                        {{ new Date(account_voucher?.voucher_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}
                          <!-- {{ voucher?.voucher_date }} -->
                        </td>
                        <td class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900 text-right">
                          {{ withCommas(sum(account_voucher.voucher_details, 'dr')) }}
                        </td>
                        <td class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900 text-right">
                          {{ withCommas(sum(account_voucher.voucher_details, 'cr')) }}
                        </td>
                        <td class="whitespace-nowrap px-2 py-0.5 text-sm text-gray-900">
                            <div class="py-0.5">
                                <dd class="text-sm text-gray-900">
                                    <ul role="list" class="divide-y divide-gray-300 rounded-md border border-gray-300">
                                        <template v-for="voucher_details in account_voucher.voucher_details">
                                            <li class="flex items-center justify-between px-1.5 py-1 text-sm/6">
                                        <div class="flex flex-1 items-center">
                                            <div class="flex min-w-0 flex-1 gap-2">
                                            <span class="text-xs">{{ voucher_details?.chart_of_account?.acc_desc }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4 shrink-0">
                                            <span class="font-medium text-indigo-600 hover:text-indigo-500">{{ voucher_details?.cr != 0 && voucher_details?.cr != '' && voucher_details?.cr != null ? withCommas(parseFloat(voucher_details?.cr).toFixed(0))+' Cr' : withCommas(parseFloat(voucher_details?.dr).toFixed(0))+' Dr' }}</span>
                                        </div>
                                        </li>
                                        </template>
                                    </ul>
                                </dd>
                            </div>
                        </td>
                        <td class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900">
                          {{ account_voucher.narration }}
                        </td>
                        <td   v-if="roles.includes(1)"
                          class="relative whitespace-nowrap border-b border-gray-200 py-4 pr-4 pl-3 text-left text-sm font-medium sm:pr-8 lg:pr-8"

                        >
                          <input  type="checkbox" :checked="pre_voucher[account_voucher.id] && pre_voucher[account_voucher.id].is_posted == '1'" @click="setVoucherIsPosted(account_voucher, $event.target.checked);" />
                        </td>
                        <td  v-if="roles.includes(1)"
                          class="relative whitespace-nowrap border-b border-gray-200 py-4 pr-4 pl-3 text-left text-sm font-medium sm:pr-8 lg:pr-8"

                        >
                        <inertia-link :href="route('vouchers.edit', account_voucher.id)">
                            <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="w-5 lg:w-6 h-5 lg:h-6 text-primary hover:text-accent cursor-pointer">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                            </svg>
                            </inertia-link>
                        </td>
                      </tr>
                      </template>

                      <template v-for="(
                          voucher, index
                        ) in vouchers"
                        :key="voucher.id">
                      <tr
                      :class="highlightRow == voucher.id ? 'bg-yellow-200' : (!roles.includes(1) && voucher.is_posted == '1' ? 'bg-purple-100' :(index % 2 === 0 ? 'bg-blue-50' : 'bg-white'))" v-if="(actions == 'donor_fund' && voucher.voucher_audit_details && voucher.voucher_audit_details.some(voucherDetail =>
                            (voucherDetail.opd && voucherDetail.opd.careoff_id !== null) ||
                            (voucherDetail.ipd && voucherDetail.ipd.careoff_id !== null) ||
                            (voucherDetail.lab && voucherDetail.lab.careoff_id !== null)
                            )) || actions == voucher?.type || actions == ''" :id="'voucher_'+voucher.id"
                      >
                        <td
                          class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900"
                        >

                          {{ voucher?.type }}
                        </td>

                        <td
                          class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900"
                        >
                        <div>

                        <p v-if="voucher.voucher_audit_details && voucher.voucher_audit_details.some(voucherDetail =>
                            (voucherDetail.opd && voucherDetail.opd.careoff_id !== null) ||
                            (voucherDetail.ipd && voucherDetail.ipd.careoff_id !== null) ||
                            (voucherDetail.lab && voucherDetail.lab.careoff_id !== null)
                            )">
                        C/O
                        </p>
                        <p v-else>
                            {{
                            voucher?.voucher_type?.voucher_name
                          }}
                        </p>
                    </div>

                        </td>
                        <td
                          class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900"
                        >
                        {{ new Date(voucher?.voucher_date).toLocaleDateString('en-GB').replace(/\//g, '-') }}

                          <!-- {{ voucher?.voucher_date }} -->
                        </td>
                        <td
                          class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900 text-right"
                        >
                          {{ withCommas(sum(voucher.voucher_audit_details, 'dr')) }}
                        </td>
                        <td
                          class="whitespace-nowrap px-1 py-0.5 text-sm text-gray-900 text-right"
                        >
                          {{ withCommas(sum(voucher.voucher_audit_details, 'cr')) }}
                        </td>
                        <td
                          class="px-2 py-0.5 text-sm text-gray-900 w-96"
                        >
                        <div class="py-0.5">
                                <dd class="text-sm text-gray-900">
                                    <ul role="list" class="divide-y divide-gray-300 rounded-md border border-gray-300 px-1.5">
                                        <template v-for="voucher_audit_details in voucher.voucher_audit_details">
                                            <li class="flex items-center justify-between px-1.5 py-1 text-sm/6">
                                        <div class="flex w-0 flex-1 items-center">
                                            <div class="flex min-w-0 flex-1 gap-2">
                                            <span class="font-medium text-xs">{{ voucher_audit_details?.chart_of_account?.acc_desc }}</span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <span class="font-medium text-blue-600 hover:text-blue-500">{{ voucher_audit_details?.cr != 0 && voucher_audit_details?.cr != '' && voucher_audit_details?.cr != null ? withCommas(parseFloat(voucher_audit_details?.cr).toFixed(0))+' Cr' : withCommas(parseFloat(voucher_audit_details?.dr).toFixed(0))+' Dr' }}</span>
                                        </div>
                                        </li>
                                        </template>
                                    </ul>
                                </dd>
                            </div>


                        </td>
                        <td
                          class="px-1 py-0.5 text-sm text-gray-900"
                        >
                           {{ voucher.narration }} <!-- <span v-if="voucher?.parent_id">Slip NO{{ voucher?.parent_id }}</span> -->
                        </td>
                        <!-- <td
                          class="relative whitespace-nowrap px-1 py-0.5 text-sm text-gray-900"

                        >
                          <input v-if="roles.includes(1)" type="checkbox" :checked="voucher.is_admin_posted == '1'" @click="setIsPosted(voucher, $event.target.checked);voucher.is_admin_posted = voucher.is_admin_posted == '1' ? '0' : '1'" />

                          <input v-else type="checkbox" :checked="voucher.is_posted == '1'" @click="setIsPosted(voucher, $event.target.checked);voucher.is_posted = voucher.is_posted == '1' ? '0' : '1'" />
                        </td> -->
                        <td class="relative whitespace-nowrap px-1 py-0.5 text-sm text-gray-900">
                            <input v-if="roles.includes(1)"
                                type="checkbox"
                                :checked="voucher.is_admin_posted == '1'"
                                @click="setIsPosted(voucher, $event.target.checked);
                                        voucher.is_admin_posted = voucher.is_admin_posted == '1' ? '0' : '1';
                                        " />

                            <input v-else
                                type="checkbox"
                                :checked="voucher.is_posted == '1'"
                                @click="setIsPosted(voucher, $event.target.checked);
                                        voucher.is_posted = voucher.is_posted == '1' ? '0' : '1';
                                        storeInLocalStorage(voucher.id, voucher.is_posted)" />
                        </td>
                        <td
                          class="relative whitespace-nowrap border-b border-gray-200 py-4 pr-4 pl-3 text-left text-sm font-medium sm:pr-8 lg:pr-8"

                        >
                        <div class="flex items-center justify-center space-x-3">
                          <span class="cursor-pointer" @click="showDetailId = showDetailId == voucher.id ? '' : voucher.id"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" data-slot="icon" class="h-5 w-5"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"></path></svg></span>
                          <!-- <InertiaLink
                            :href="
                              route(
                                'vouchers-audit.edit',
                                voucher.id
                              )
                            "
                            > -->
                            <svg @click="editPage(voucher.id)"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="w-5 lg:w-6 h-5 lg:h-6 text-primary hover:text-accent cursor-pointer">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                            </svg>

                          <!-- </InertiaLink> -->

                          <button
                            @click.prevent="
                              deleteVoucher(voucher.id)
                            "
                            type="button"
                             v-if="roles.includes(1)"
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
                      </tr>
                      <tr v-show="showDetailId == voucher.id">
                        <td
                          class="whitespace-nowrap border-b border-gray-200 hidden px-3 py-4 text-sm text-gray-900 sm:table-cell" colspan="6"
                        >
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 hidden px-3 py-4 text-sm text-white sm:table-cell bg-slate-500"
                        >
                          Account
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 hidden px-3 py-4 text-sm text-white sm:table-cell bg-slate-500"
                        >
                          Debit.
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 hidden px-3 py-4 text-sm text-white sm:table-cell bg-slate-500"
                        >
                          Credit.
                        </td>
                      </tr>
                      <tr
                        v-show="showDetailId == voucher.id"
                        class="bg-white"
                        v-for="voucher_audit_detail in voucher.voucher_audit_details"
                      >
                        <td
                          class="whitespace-nowrap border-b border-gray-200 hidden px-3 py-4 text-sm text-gray-900 sm:table-cell" colspan="6"
                        >
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 hidden px-3 py-4 text-sm text-gray-900 sm:table-cell bg-green-100"
                        >
                          {{voucher_audit_detail?.chart_of_account?.acc_desc}}
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 hidden px-3 py-4 text-sm text-gray-900 sm:table-cell bg-green-100"
                        >
                          {{voucher_audit_detail?.dr}}
                        </td>
                        <td
                          class="whitespace-nowrap border-b border-gray-200 hidden px-3 py-4 text-sm text-gray-900 sm:table-cell bg-green-100"
                        >
                          {{voucher_audit_detail?.cr}}
                        </td>
                      </tr>
                      </template>
                    </tbody>
                  </table>
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
import { ref ,onMounted, computed} from 'vue';
import { Head, Link as InertiaLink, usePage, useForm ,router } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import Swal from 'sweetalert2';


const page = usePage()
const roles = ref([]);
const highlightRow = ref('');
if(page.props.auth.user?.user_role){
    page.props.auth.user?.user_role.map(user_role => {
        roles.value.push(user_role?.role_id);
    });
    console.log('roles', roles.value);
}


const editOptionEnable = (date) => {
  const dateString = date;
  const givenDate = new Date(dateString);
  const currentDate = new Date();
  const differenceInMilliseconds =  currentDate - givenDate;
  const differenceInMinutes = Math.floor(differenceInMilliseconds / 1000 / 60);
  return differenceInMinutes;
}

const forms = useForm({});
const voucher_audit = ref({});
const pre_voucher = ref({});
const actions = ref('');
const showDetailId = ref('');
const voucherAudit = ref(false);

const deleteVoucher = (id) => {
//   if (confirm("Are you sure you want to delete this voucher?")) {
//     return Inertia.delete(route("vouchers-audit.destroy", id));
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
            Inertia.delete(route("vouchers-audit.destroy", id));
        }
    });
};

const setIsPosted = (voucher, val) => {
  if(roles.value.includes(1)){
    voucher_audit.value[voucher.id] = {id: null, is_admin_posted: null};
    voucher_audit.value[voucher.id]['id'] = voucher.id;
    voucher_audit.value[voucher.id]['is_admin_posted'] = val == true ? '1' : '0';
    // Inertia.post(route("vouchers-audit.admin-post-checked"), voucher_audit.value);
    axios.post(route("vouchers-audit.admin-post-checked"), voucher_audit.value)
  }
  else{
    voucher_audit.value[voucher.id] = {id: null, is_posted: null};
    voucher_audit.value[voucher.id]['id'] = voucher.id;
    voucher_audit.value[voucher.id]['is_posted'] = val == true ? '1' : '0';
  }
};

const setVoucherIsPosted = (voucher, val) => {
  if(roles.value.includes(1)){
    pre_voucher.value[voucher.id] = {id: null, is_posted: null};
    pre_voucher.value[voucher.id]['id'] = voucher.id;
    pre_voucher.value[voucher.id]['is_posted'] = val == true ? '1' : '0';
  }
};
const approvedAll = () => {
//   if (confirm("Are you sure you want to Approved these checked voucher?")) {
//     Inertia.post(route("vouchers-audit.approved"));
//   }
Swal.fire({
        title: 'Are you sure you want to Approved these checked voucher?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Approved it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Inertia.post(route("vouchers-audit.approved"));
            // setVoucherIsPosted(account_voucher
        }
    });
// };
};
const postUpdate = (vouchers) => {
//   if (confirm("Are you sure you want to post these checked voucher?")) {
//     return Inertia.post(route("vouchers-audit.store"), voucher_audit.value);
//   }
// localStorage.clear();
Swal.fire({
        title: 'Are you sure you want to post these checked voucher?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Post it!'
    }).then((result) => {
        if (result.isConfirmed) {
            return router.post(route("vouchers-audit.store"), voucher_audit.value);
        }
    });
};
// const postUpdate = () => {
//   let voucherAudit = JSON.parse(localStorage.getItem('voucher_audit'));

//   if (!voucherAudit || Object.keys(voucherAudit).length === 0) {
//     if (voucher_audit?.value) {
//       voucherAudit = voucher_audit.value;
//     } else {
//       console.warn("No voucher audit data available.");
//       return;
//     }
//   }

//   Swal.fire({
//     title: 'Are you sure you want to post these checked voucher?',
//     text: "You won't be able to revert this!",
//     icon: 'warning',
//     showCancelButton: true,
//     confirmButtonColor: '#3085d6',
//     cancelButtonColor: '#d33',
//     confirmButtonText: 'Yes, Post it!'
//   }).then((result) => {
//     if (result.isConfirmed) {
//       router.post(route("vouchers-audit.store"),  voucherAudit)
//         .then(() => {
//           localStorage.removeItem('voucher_audit');
//         })
//         .catch(error => {
//           console.error('Error posting voucher audit:', error);
//         });
//     }
//   });
// };

const editPage = (id) => {
    localStorage.setItem("last_visit_id", id);
    router.visit(route("vouchers-audit.edit", id), {
        preserveScroll: true,
        preserveState: true
    });
};


const approvedPost = () => {
//   if (confirm("Are you sure you want to post these checked voucher?")) {
//     return Inertia.post(route("vouchers-audit.approved"), voucher_audit.value);
//   }
Swal.fire({
        title: 'Are you sure you want to post these checked voucher?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Post it!'
    }).then((result) => {
        if (result.isConfirmed) {
            return Inertia.post(route("vouchers-audit.approved"), pre_voucher.value);
        }
    });
};

const setAllIsPosted = (vouchers, val, account_vouchers = null) => {
  if(roles.value.includes(1)){
    vouchers.map(res => {
      if(actions.value != ''){
        if(actions.value == res.type){
          voucher_audit.value[res.id] = {id: null, is_admin_posted: null};
          voucher_audit.value[res.id]['id'] = res.id;
          voucher_audit.value[res.id]['is_admin_posted'] = val == true ? '1' : '0';
          res.is_admin_posted =  val == true ? '1' : '0';
        }
        else{
          voucher_audit.value[res.id] = {id: null, is_admin_posted: null};
          voucher_audit.value[res.id]['id'] = res.id;
          voucher_audit.value[res.id]['is_admin_posted'] = '0';
          res.is_admin_posted =  '0';
        }
      }
      else{
        voucher_audit.value[res.id] = {id: null, is_admin_posted: null};
        voucher_audit.value[res.id]['id'] = res.id;
        voucher_audit.value[res.id]['is_admin_posted'] = val == true ? '1' : '0';
        res.is_admin_posted =  val == true ? '1' : '0';
      }
    })
    axios.post(route("vouchers-audit.admin-post-checked"), voucher_audit.value);

    account_vouchers.map(account_voucher => {
      setVoucherIsPosted(account_voucher, val == true ? '1' : '0');
    });
    console.log(pre_voucher.value);
  }
  else{
    vouchers.map(res => {
      if(actions.value != ''){
        if(actions.value == res.type){
          voucher_audit.value[res.id] = {id: null, is_posted: null};
          voucher_audit.value[res.id]['id'] = res.id;
          voucher_audit.value[res.id]['is_posted'] = val == true ? '1' : '0';
          res.is_posted =  val == true ? '1' : '0';
        }
        else{
          voucher_audit.value[res.id] = {id: null, is_posted: null};
          voucher_audit.value[res.id]['id'] = res.id;
          voucher_audit.value[res.id]['is_posted'] = '0';
          res.is_posted =  '0';
        }
      }
      else{
        voucher_audit.value[res.id] = {id: null, is_posted: null};
        voucher_audit.value[res.id]['id'] = res.id;
        voucher_audit.value[res.id]['is_posted'] = val == true ? '1' : '0';
        res.is_posted =  val == true ? '1' : '0';
      }
    })
  }
};

const withCommas = (value) => {
  if (value == null || value == '') {
    return '0';
  }
  return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
};

const sum = (data, variable) => {
  let sum = 0
  data.map(res => {
    sum = +sum + +res[variable]
  })
  return sum;
};

const showRows = (type) => {
  if(actions.value != '' && type == actions.value){
    actions.value = '';
  }
  else{
    actions.value = type;
  }
};
const sumTotal = (vouchers, name, type) => {
  let sumAll = 0;
  vouchers.map(res => {
    if(name == null){
      sumAll = +sumAll + +sum(res.voucher_details, type);
    }
    else if(res.type == name){
      sumAll = +sumAll + +sum(res.voucher_audit_details, type);
    }
  });
  return parseFloat(sumAll);
};

const storeInLocalStorage = (id, is_posted) => {
  let storedVouchers = JSON.parse(localStorage.getItem('voucher_audit')) || {};
  storedVouchers[id] = {
    id: id,
    is_posted: String(is_posted)
  };
  localStorage.setItem('voucher_audit', JSON.stringify(storedVouchers));
};

    onMounted(() => {
    let id = localStorage.getItem("last_visit_id");
    if(id){
        highlightRow.value = id;
        setTimeout(function(){
        const element = document.getElementById('voucher_'+id);
        localStorage.setItem("last_visit_id", '');
        if (element) {
            element.scrollIntoView({
                behavior: 'smooth', // Optional: makes the scroll smooth
                block: 'start' // Aligns the scroll to the top of the element
            });
        }
        }, 1000);
    }

    });

const searchItem = async (val) => {
    try {
        router.visit(
            route("vouchers-audit.index", {
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

</script>
      <script>
export default {
  props: {
    vouchers: Array,
    date_vouchers: Array,
    is_posted_voucher: Array,
    current_date_vouchers: Array,
    ipd_users: String,
    account_vouchers: Array,
    filters:Object,
  },
  components: {
    AppLayout,
    Head,
    InertiaLink,
  },
  created() {
  const storedVouchers = JSON.parse(localStorage.getItem('voucher_audit')) || {};
  console.log(storedVouchers);

  if (Array.isArray(this.vouchers)) {
    this.vouchers.forEach(voucher => {
      if (storedVouchers[voucher.id]) {
        voucher.is_posted = storedVouchers[voucher.id].is_posted;
      }
    });
  } else {
    console.warn('this.vouchers is not an array:', this.vouchers);
  }
}

//   created(){
//     console.log(JSON.parse(localStorage.getItem('voucher_audit')))
//     const storedVouchers = JSON.parse(localStorage.getItem('voucher_audit')) || {};
//     if (Array.isArray(this.vouchers)) {
//     this.vouchers.forEach(voucher => {
//         if (storedVouchers[voucher.id]) {
//             voucher.is_posted = storedVouchers[voucher.id] === '1' ? '1' : '0';
//         }
//     });
//     } else {
//         console.warn('voucher_audit is not an array:', this.vouchers);
//     }
//   }
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
