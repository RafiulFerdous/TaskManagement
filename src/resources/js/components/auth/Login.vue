<script setup>
import { ref, reactive, onMounted } from "vue";
import Logo from "@/layouts/full/logo/Logo.vue";
import AuthService from "../../services/AuthService";
import HttpService from "../../services/HttpService.js";
import Form from "../../services/Form";
import { useSnackbarStore } from "../../store/useSnackbarStore.js";
import { useRoute, useRouter } from "vue-router";

// Write data definition statements here
const route = useRoute();
const router = useRouter();
const snackbar = useSnackbarStore();

let loading = ref(false);

let form = reactive(
  new Form({
    email: "test@gmail.com",
    password: "12345678",
    checkbox: true,
  })
);

onMounted(() => {
  // hasToken();
});

async function onLogin() {
  try {
    loading.value = true;
    let { data } = await HttpService.post(`login`, form.data(), false);

    if (data.loggedin) {
      // snackbar.success('You already have an active session !')
      router.push("/app/dashboard");
      return
    }
    if (data.data.token) {
      console.log(data);
      AuthService.setToken(data.data.token, data.data.expires_at);
      // snackbar.success('Login successful !')
      router.push(route.query?.redirect ?? "/app/dashboard");
    } else {
      snackbar.error('Login failed !')
    }
  } catch (error) {
    console.log(error)
    // this.setFormErrors(error);
    snackbar.error('Login failed !')
  } finally {
    loading.value = false;
  }
}

function hasToken() {
  loading.value = true;
  if (AuthService.getToken().token) {
    router.push("/app/dashboard");
  }
  loading.value = false;
}
</script>

<template>
  <div class="authentication">
    <v-container fluid class="pa-3">
      <v-row class="h-100vh d-flex justify-center align-center">
        <v-col cols="12" lg="4" xl="3" class="d-flex align-center">
          <v-card
            rounded="md"
            elevation="10"
            class="px-sm-1 px-0 withbg mx-auto"
            max-width="500"
          >
            <v-card-item class="pa-sm-8">
              <div class="d-flex justify-center py-4">
                <Logo />
              </div>
              <div class="text-body-1 text-muted text-center mb-3">
<!--                Your Social Campaigns-->
              </div>
              <v-row class="d-flex mb-3">
                <v-col cols="12">
                  <v-label class="font-weight-bold mb-1">
                    E-Mail
                  </v-label>
                  <v-text-field
                    variant="outlined"
                    hide-details
                    color="primary"
                    v-model="form.email"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-label class="font-weight-bold mb-1">Password</v-label>
                  <v-text-field
                    variant="outlined"
                    type="password"
                    v-model="form.password"
                    hide-details
                    color="primary"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" class="pt-0">
                  <div class="d-flex flex-wrap align-center ml-n2">
                    <v-checkbox
                      v-model="form.checkbox"
                      color="primary"
                      hide-details
                    >
                      <template v-slot:label class="text-body-1">
                        Remeber this Device
                      </template>
                    </v-checkbox>
                    <div class="ml-sm-auto">
                      <RouterLink
                        to="/app"
                        class="text-primary text-decoration-none text-body-1 opacity-1 font-weight-medium"
                      >
                        Forgot Password ?
                      </RouterLink>
                    </div>
                  </div>
                </v-col>
                <v-col cols="12" class="pt-0">
                  <v-btn
                    color="primary"
                    size="large"
                    block
                    flat
                    @click.prevent="onLogin"
                  >
                    Sign in
                  </v-btn>
                </v-col>
              </v-row>
            </v-card-item>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>
