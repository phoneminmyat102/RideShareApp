<template>
  <div v-if="loading">
    <Spinner></Spinner>
  </div>
  <div class="pt-16">
    <div v-if="errMessage" class="sm:rounded-md max-w-sm mx-auto">
      <ErrorAlert :error="errMessage"></ErrorAlert>
    </div>

    <h1 v-if="!waitingForOTP" class="text-3xl font-semibold mb-4">
      Enter your phone number
    </h1>
    <h1 v-else class="text-3xl font-semibold mb-4">Fill OTP Code</h1>
    <form v-if="!waitingForOTP" @submit.prevent="handleLogin">
      <div
        class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left"
      >
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <input
              v-model="credentials.phone"
              type="text"
              v-maska
              data-maska="+## ##########"
              name="phone"
              id="phone"
              placeholder="+95 9#########"
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
    <form v-else @submit.prevent="handleVerify">
      <div
        class="overflow-hidden shadow sm:rounded-md max-w-sm mx-auto text-left"
      >
        <div class="bg-white px-4 py-5 sm:p-6">
          <div>
            <input
              v-model="code.login_code"
              type="text"
              v-maska
              data-maska="######"
              name="login_code"
              id="login_code"
              placeholder="******"
              class="mt-1 block w-full px-3 py-2 rounded-md border border-gray-300 shadow-sm focus:border-black focus:outline-none"
            />
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 text-right sm:px-6">
          <button
            class="inline-flex justify-center rounded-md border border-transparent bg-black py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-gray-600 focus:outline-none"
          >
            Verify
          </button>
        </div>
      </div>
    </form>
  </div>
</template>

<script setup>
import axios from "axios";
import { vMaska } from "maska";
import { onMounted, reactive, ref } from "vue";
import Spinner from "../components/Spinner.vue";
import ErrorAlert from "../components/ErrorAlert.vue";
import { useRouter } from "vue-router";
const credentials = reactive({
  phone: "",
});

const code = reactive({
  login_code: null,
});

const waitingForOTP = ref(false);
const loading = ref(false);
const errMessage = ref("");
const router = useRouter();

onMounted(()=>{
  if(localStorage.getItem('token')) {
    router.push({name:'index'})
  }
});

const handleLogin = () => {
  // console.log(credentials.phone);
  errMessage.value = "";
  loading.value = true;
  axios
    .post("http://localhost:8000/api/login", {
      phone: credentials.phone.replace(" ", ""),
    })
    .then((res) => {
      console.log(res.data);
      loading.value = false;
      waitingForOTP.value = true;
    })
    .catch((err) => {
      loading.value = false;
      console.log(err);
      errMessage.value = err.response.data.message;
    });
};

const handleVerify = () => {
  loading.value = true;
  errMessage.value = "";
  axios
    .post("http://localhost:8000/api/login/verify", {
      phone: credentials.phone.replace(" ", ""),
      login_code: code.login_code,
    })
    .then((res) => {
      loading.value = false;
      console.log(res.data);
      localStorage.setItem('token', res.data);
      router.push({name:'index'});
    })
    .catch((err) => {
      loading.value = false;
      console.log(err);
      errMessage.value = err.response.data.message;
    });
};
</script>
