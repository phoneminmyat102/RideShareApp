<template>
<div v-if="loading">
    <Spinner></Spinner>
  </div>
  <div class="pt-16">
    <div v-if="error" class="sm:rounded-md max-w-sm mx-auto">
      <ErrorAlert :error="error"></ErrorAlert>
    </div>
    <h1 class="text-3xl font-semibold mb-4">Driver and car details</h1>
    <form action="#" @submit.prevent="handleSaveDriver">
      <div
        class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left"
      >
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <input
              type="text"
              name="name"
              id="name"
              v-model="driverDetails.name"
              placeholder="Full Name"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none"
            />
          </div>
          <div class="mt-2">
            <input
              type="number"
              name="year"
              id="year"
              v-model="driverDetails.year"
              placeholder="Car Year"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none"
            />
          </div>
          <div class="mt-2">
            <input
              type="text"
              name="make"
              id="make"
              v-model="driverDetails.make"
              placeholder="Make"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none"
            />
          </div>
          <div class="mt-2">
            <input
              type="text"
              name="model"
              id="model"
              v-model="driverDetails.model"
              placeholder="Model"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none"
            />
          </div>
          <div class="mt-2">
            <input
              type="text"
              name="color"
              id="color"
              v-model="driverDetails.color"
              placeholder="Color"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none"
            />
          </div>
          <div class="mt-2">
            <input
              type="text"
              name="license_plate"
              v-model="driverDetails.license_plate"
              id="license_plate"
              placeholder="License Plate #"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none"
            />
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none"
          >
            Continue
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import http from "@/helpers/http";
import ErrorAlert from "../components/ErrorAlert.vue";
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import Spinner from "../components/Spinner.vue"
const router = useRouter();
const error = ref("");
const loading = ref(false);
const driverDetails = reactive({
  name: "",
  year: "",
  make: "",
  model: "",
  color: "",
  license_plate: "",
});

const handleSaveDriver = () => {
  error.value = "";
  loading.value = true;
  http()
    .post("/api/driver", driverDetails)
    .then((res) => {
      loading.value = false;

      router.push({
        name: "standBy",
      });
    })
    .catch((err) => {
      loading.value = false;

      console.error(err);
      error.value = err.response.data.message;
      console.log(error.value);
    });
};
</script>

<style></style>
