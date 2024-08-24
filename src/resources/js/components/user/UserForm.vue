<script setup>
// Write dependency import statements here
import { ref, reactive, computed, onMounted, watch } from "vue";
import { useDate } from "vuetify";
import HttpService from "../../services/HttpService.js";
import AuthService from "../../services/AuthService.js";
import { useformAction } from "../../composables/useformAction";
import Form from "../../services/Form";
import { useSnackbarStore } from "../../store/useSnackbarStore";
// End Dependency Import

// emits here
const emit = defineEmits(["onCloseDialog"]);

// props here
const props = defineProps({
  isOpen: Boolean,
  dialogData: {
    default: [],
  },
});

// import composables/mixins here
let { _initForm } = useformAction();
const snackbar = useSnackbarStore();
// End of composables

// Write data definition statements here
let loading = ref(false);
let formId = ref(null);
let form = reactive(
  new Form({
    full_name: "",
    // User_name: "",
    email: "",
    contact: "",
    password: "",
    user_type:"",
    status:"",

  })
);
// validation rules
let rules = reactive({
  full_name: "required",
  // User_name: "required",
  email: "required|email",
  password: "required|min:6",
});

let addsImage = ref();

let dialogStatus = ref(false);
dialogStatus.value = props.isOpen;
if (dialogStatus.value === true) {
  beforeOpenDialog(props.dialogData);
}

// Write methods here
function beforeOpenDialog(event) {
  _initForm(event, form, formId, rules);
}

async function onSubmitForm(event) {
  console.log("image file",addsImage)
  // return;
  // console.log("submit btn clicked",event)
  try {
    const { valid, errors } = await event;

    if (!valid) return false;

    loading.value = true;
    let id = formId.value;
    let formData = new FormData();
    formData.append("image", addsImage);
    if (id) {
      formData.append("_method", "PUT");
    }

    let requestData = form.data();
    // console.log("submited data",form.data())
    // return
    for (let key in requestData) {
      formData.append(key, (requestData[key] || requestData[key] !== null) ? requestData[key] : '');
    }
    let { data } = await HttpService[!id ? "post" : "put"](
      !id ? `users` : `users/${id}`,
      // form.data()
      formData
    );
    dialogStatus.value = false;
    loading.value = false;
    snackbar.success(
      `User has been ${id ? "updated" : "created"} successfully!`
    );
    emit("onCloseDialog", data.data, formId.value);
  } catch (error) {
    console.log(error);
    emit("onCloseDialog");
    // this.setFormErrors(error);
    snackbar.error(`Failed to create User !`);
  }
}

function handleDialogAction(action = "close") {
  if (action == "close") {
    dialogStatus.value = false;
    emit("onCloseDialog");
  } else if (action == "clickOutside") {
    return false;
  }
}

function readUploadFile(event) {
  addsImage = event.target.files[0];
  const reader = new FileReader();
  reader.onload = (event) => {
    document.getElementById("thumbnail-name").classList.add("d-inline-block");
    document.getElementById("thumbnail-name").innerHTML = addsImage.name;
    document.getElementById(
      "thumbnail"
    ).innerHTML = `<img src="${event.target.result}" style="max-width=150px; max-height:100px;" /><br/>`;
  };
  reader.readAsDataURL(addsImage);
}
</script>
<template>
  <v-dialog
    v-model="dialogStatus"
    :maxWidth="'50%'"
    animateClick="handleDialogAction('clickOutside')"
    :persistent="true"
    :scrim="'dark'"
  >
    <v-card>
      <v-card-title>
        Add New User
        <v-icon
          icon="mdi-close"
          class="text-dark float-end cursor-pointer"
          size="small"
          border
          @click="handleDialogAction('close')"
        ></v-icon>
      </v-card-title>
      <v-form @submit.prevent="onSubmitForm">
        <v-card-text>
          <small>*indicates required field</small>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  variant="outlined"
                  v-model="form.full_name"
                  :rules="rules.full_name"
                  label="Full Name*"
                  required
                ></v-text-field>
              </v-col>

<!--              <v-col cols="12">-->
<!--                <v-text-field-->
<!--                  variant="outlined"-->
<!--                  v-model="form.user_name"-->
<!--                  :rules="rules.user_name"-->
<!--                  label="User Name*"-->
<!--                  required-->
<!--                ></v-text-field>-->
<!--              </v-col>-->
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="form.email"
                  :rules="rules.email"
                  variant="outlined"
                  label="Email *"
                  type="email"
                  required
                ></v-text-field>
              </v-col>

              <v-col cols="12" sm="6">
                <v-file-input
                  variant="outlined"
                  v-model="form.image"
                  label="profile Image *"
                  required
                  prepend-icon="mdi-camera"
                  show-size
                  @change="readUploadFile($event)"
                ></v-file-input>
              </v-col>

              <v-col cols="12">
                <div id="thumbnail"></div>
                <p
                  id="thumbnail-name"
                  class="d-none text-success text-truncate mb-0 small"
                  style="max-width: 200px"
                ></p>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="form.contact"
                  variant="outlined"
                  label="Contact No *"
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-text-field
                  v-model="form.password"
                  :rules="rules.password"
                  variant="outlined"
                  label="Change password *"
                  type="password"
                  required
                ></v-text-field>
              </v-col>
              <v-col cols="12" sm="6">
                <v-select
                  v-model="form.user_type"
                  clearable
                  label="User Type"
                  :items="[
                  { key: 'General User', value: 2 },
                  { key: 'Publisher ', value: 3 }
                ]"
                  item-title="key"
                  item-value="value"
                ></v-select>
              </v-col>

              <v-col cols="12" sm="6">
                <v-select
                  v-model="form.status"
                  clearable
                  label="Status"
                  :items="[
                  { key: 'Active', value: 1 },
                  { key: 'Inactive ', value: 2 }
                ]"
                  item-title="key"
                  item-value="value"
                ></v-select>
              </v-col>
            </v-row>
          </v-container>

        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn
            color="blue-darken-1"
            variant="text"
            @click="handleDialogAction('close')"
          >
            Close
          </v-btn>
          <v-btn color="blue-darken-1" variant="text" type="submit">
            Save
          </v-btn>
        </v-card-actions>
      </v-form>
    </v-card>
  </v-dialog>
</template>
