<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">Super Ride</h1>
    <div
      class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left"
    >
      <div class="bg-white px-4 py-5 sm:p-6">
        <div class="flex justify-between">
          <button
            @click="handleStartDriving"
            class="rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none"
          >
            Start Driving
          </button>
          <button
            @click="handleFindRide"
            class="rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none"
          >
            Find A Ride
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import http from "@/helpers/http";
import { useRouter } from "vue-router";

const router = useRouter();

const handleStartDriving = () => {
  http()
    .get("/api/driver")
    .then((res) => {
      if (res.data.driver) {
        router.push({
          name: "standBy", 
        });
      } else {
        router.push({
          name: "driver",
        });
      }
    })
    .catch((error) => {
        console.error(error)
    })
};

const handleFindRide = () => {
  router.push({ name: "location" });
};
</script>

<style></style>
