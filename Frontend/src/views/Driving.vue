<template>
  <div class="pt-16">
    <h1 class="text-3xl font-semibold mb-4">{{ title }}</h1>
    <div>
      <div
        class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left"
        v-if="!trip.is_complete"
      >
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <GMapMap
              :zoom="14"
              :center="location.current.geometry"
              ref="gMap"
              style="width: 100%; height: 256px"
            >
              <GMapMarker
                :position="location.current.geometry"
                :icon="currentIcon"
              />
              <GMapMarker
                :position="location.destination.geometry"
                :icon="destinationIcon"
              />
            </GMapMap>
          </div>
          <div class="mt-2">
            <p class="text-xl">Going to <strong>pick up a passenger</strong></p>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button
            v-if="trip.is_started"
            @click="handleCompleteTrip"
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none"
          >
            Complete Trip
          </button>
          <button
            v-else
            @click="handlePassengerPickedUp"
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none"
          >
            Passenger Picked Up
          </button>
        </div>
      </div>
      <div class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left" v-else>
                <div class="bg-white px-4 py-5 sm:p-6">
                    <ByeAnimation/>
                </div>
            </div>
    </div>
  </div>
</template>

<script setup>
import http from "@/helpers/http";
import { useLocationStore } from "@/stores/location";
import { useTripStore } from "@/stores/trip";
import { onMounted, onUnmounted, ref } from "vue";
import { useRouter } from "vue-router";

const router = useRouter();

const gMap = ref(null);
const location = useLocationStore();
const trip = useTripStore();

const intervalRef = ref(null);

const title = ref("Driving to passenger...");
const currentIcon = {
  url: "https://openmoji.org/data/color/svg/1F698.svg",
  scaledSize: {
    width: 32,
    height: 32,
  },
};

const destinationIcon = {
  url: "https://openmoji.org/data/color/svg/1F920.svg",
  scaledSize: {
    width: 24,
    height: 24,
  },
};

const handlePassengerPickedUp = () => {
  http()
    .post(`/api/trip/${trip.id}/start`)
    .then((res) => {
      title.value = "Travelling to destination...";
      location.$patch({
        destination: {
          name: res.data.destination_name,
          geometry: res.data.destination,
        },
      });
      trip.$patch(res.data);
    })
    .catch((err) => {
      console.error(err);
    });
};

const handleCompleteTrip = () => {
  http()
    .post(`/api/trip/${trip.id}/end`)
    .then((res) => {
      title.value = "Trip Completed";
      trip.$patch(res.data);

      setTimeout(() => {
        trip.reset();
        location.reset();

        router.push({
          name: "standBy",
        });
      }, 3000);
    })
    .catch((err) => {
      console.error(err);
    });
};

const updateMapBounds = (mapObject) => {
  let originPoint = new google.maps.LatLng(location.current.geometry),
    destinationPoint = new google.maps.LatLng(location.destination.geometry),
    latLngBounds = new google.maps.LatLngBounds();

  latLngBounds.extend(originPoint);
  latLngBounds.extend(destinationPoint);

  mapObject.fitBounds(latLngBounds);
};

const broadcastDriverLocation = () => {
  http()
    .post(`/api/trip/${trip.id}/location`, {
      driver_location: location.current.geometry,
    })
    .then((res) => {})
    .catch((err) => {
      console.error(err);
    });
};

onMounted(() => {
  gMap.value.$mapPromise.then((mapObject) => {
    updateMapBounds(mapObject);

    intervalRef.value = setInterval(async () => {
      //update driver's current location and map bounds
      await location.updateCurrentLocation();

      // update driver's current location in the database
      broadcastDriverLocation();

      updateMapBounds(mapObject);
    }, 5000);
  });
});

onUnmounted(() => {
  clearInterval(intervalRef.value);
  intervalRef.value = null;
});
</script>
