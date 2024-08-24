<script setup>
// Write dependency import statements here
import { ref, reactive, computed, onMounted, watch } from "vue";
import { useDate } from "vuetify";
import { EditIcon, TrashIcon } from "vue-tabler-icons";
import Dialog from "@/components/shared/Dialog.vue";
import NotFound from "@/components/common/NotFound.vue";
import HttpService from "../../services/HttpService.js";
import { useCommon } from "../../composables/useCommon";
import { useformAction } from "../../composables/useformAction";
import Form from "../../services/Form";
// End Dependency Import

// import composables/mixins here
let { _initForm } = useformAction();
let { getUrlParams } = useCommon();
// End of composables

// Write data definition statements here
let loading = ref(false);
let dialogStatus = ref(false);
let dialogData = [];

let categories = ref({});
let users = ref({});
let meta = ref({});
let items = [
  { type: "Active", value: 1 },
  { type: "Inactive", value: 2 },
];
let menus = [
  { type: "Main Menu", value: 1 },
  { type: "Others", value: 2 },
];
let formId = ref(null);
let form = reactive(
  new Form({
    title_en: "",
    title_bn: "",
    slug: "",
    status: "",
    meta_title_en: "",
    meta_title_bn: "",
    meta_description_en: "",
    meta_description_bn: "",
    level: 1,
    menu_type: "",
    view_order: "",
  })
);

// End of Data definition

// Write computed properties here
const test = computed(() => {
  return "Hello world";
});
// End of computed property

// Write computed properties here
watch(dialogStatus, (newValue) => {});
// End of computed properties

// Write Mounted here
onMounted(() => {
  fetchUsers();
});
// End of mounted

// Write methods here
function beforeOpenDialog(event) {
  _initForm(event, form, formId);
  // console.log(form.data());
}

function onCloseDialog() {
  dialogStatus.value = false;
}

function updateData(data, key, id) {
  try {
    if (!id) {
      categories.unshift(data);
      return;
    }
    let collectionItem =
      categories[categories.findIndex((item) => item.id == id)];
    for (let key in data) {
      collectionItem[key] = data[key];
    }
  } catch (e) {
    console.log(e);
  }
}

function create() {
  dialogStatus.value = true;
  dialogData = [];
}

function edit(data) {
  dialogStatus.value = true;
  dialogData = data;
}

async function onSubmitForm() {
  // console.log(form);
  // return;
  try {
    loading.value = true;
    let id = formId.value;
    form.level = 1;
    let { data } = await HttpService[!id ? "post" : "put"](
      !id ? `categories` : `categories/${id}`,
      form.data()
    );
    updateData(data.data, "categories", formId.value);
    dialogStatus.value = false;
    loading.value = false;
    // flash(`Successfully ${this.id ? 'updated' : 'created'}`, 'success', false)
  } catch (error) {
    // this.setFormErrors(error);
    // flash('Failed to create batch !', 'danger', false)
  }
}

async function fetchUsers() {
  loading.value = true;
  const { data } = await HttpService.get("users");
  users = data.data;
  meta = data.meta;
  loading.value = false;
}

async function destroy(item) {
  if (!confirm("Do you want to delete ?")) {
    return false;
  }
  try {
    loading.value = true;
    const { data } = await HttpService.delete(`categories/${item.id}`);
    if (data.success == true) {
      categories.splice(categories.indexOf(item), 1);
    } else {
      console.log(data.message);
    }
  } catch (error) {
    console.log(error);
    // flash('Failed to create batch !', 'danger', false)
  } finally {
    loading.value = false;
  }
}


  function userType(userType) {
    switch (userType) {
      case 1:
        return 'SuperAdmin';
      case 2:
        return 'General User';
      case 3:
        return 'Publisher';
      default:
        return 'Not set';
    }
  }

  function status(userType) {
    switch (userType) {
      case 1:
        return 'Active';
      case 2:
        return 'InActive';

      default:
        return 'Not set';
    }
  }



// End of methods
</script>

<template>
  <template>
    <v-skeleton-loader
      boilerplate
      class="mx-auto"
      elevation="2"
      max-width="360"
      type="card-avatar, article, actions"
    ></v-skeleton-loader>
  </template>

  <v-card elevation="10" class="month-table" variant="elevated">
    <v-card-item class="pa-6">
      <v-card-title class="text-h5 pt-sm-2 pb-7">
        <v-row>
          <v-col> Users </v-col>
          <v-col class="d-flex justify-end">
            <v-btn @click="create" icon="mdi-plus" color="primary"></v-btn>
          </v-col>
        </v-row>
      </v-card-title>

      <v-skeleton-loader :loading="loading" type="table-tbody">
        <v-table class="w-100">
          <thead>
          <tr>
            <th class="text-subtitle-1 font-weight-bold">Name</th>
            <th class="text-subtitle-1 font-weight-bold">User Type</th>
            <th class="text-subtitle-1 font-weight-bold">Contact</th>
            <th class="text-subtitle-1 font-weight-bold">Status</th>
            <th class="text-subtitle-1 font-weight-bold">Action</th>
          </tr>
          </thead>
          <tbody>
          <template v-if="!loading && users.length > 0">
            <tr
              v-for="(item, key) in users"
              :key="key"
              class="month-item"
            >
              <td>
                <div class="">
                  <h6 class="text-subtitle-1 font-weight-bold">
                    {{ item.full_name }}
                  </h6>
                  <div class="text-13 mt-1 text-muted">
                    {{ item.full_name }}
                  </div>
                </div>
              </td>


              <td>
                <div class="text-13 mt-1 text-muted">
                  {{ userType(item.user_type) }}
                </div>
              </td>


              <td>
                <div class="text-13 mt-1 text-muted">
                  {{ item.contact }}
                </div>
              </td>

              <td>
                <div class="text-13 mt-1 text-muted">
                  {{ status(item.status) }}
                </div>
              </td>






              <td>
                <v-menu>
                  <template v-slot:activator="{ props }">
                    <v-btn
                      elevation="0"
                      icon="mdi-dots-vertical"
                      v-bind="props"
                    ></v-btn>
                  </template>
                  <v-list density="compact">
                    <v-list-item
                      :value="'Edit'"
                      color="primary"
                      @click.prevent="edit(item)"
                    >
                      <v-list-item-title class="text-14 d-flex align-center">
                        <v-avatar rounded="0">
                          <v-icon :icon="EditIcon"></v-icon>
                        </v-avatar>
                        Edit
                      </v-list-item-title>
                    </v-list-item>
                    <v-list-item
                      :value="'Delete'"
                      color="primary"
                      @click="destroy(item)"
                    >
                      <v-list-item-title class="text-14 d-flex align-center">
                        <v-avatar rounded="0">
                          <v-icon :icon="TrashIcon" color="red"></v-icon>
                        </v-avatar>
                        Delete
                      </v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </td>
            </tr>
          </template>
          <NotFound
            v-if="!loading && categories.length == 0"
            :message="'Oops..! No data available in the system.'"
          />
          </tbody>
        </v-table>
      </v-skeleton-loader>
    </v-card-item>
  </v-card>

  <Dialog
    @dialogOpening="beforeOpenDialog"
    @dialogSubmit="onSubmitForm"
    @dialogClosed="onCloseDialog"
    v-if="dialogStatus"
    :isActive="dialogStatus"
    :dialogData="dialogData"
    :maxWidth="'50%'"
    title="Add New Category"
  >
    <template #default>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12">
              <v-text-field
                variant="outlined"
                v-model="form.title_en"
                label="Category Title(EN) *"
                required
                @keyup="form.slug = form.title_en.split(' ').join('-').toLowerCase()"
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field
                variant="outlined"
                v-model="form.title_bn"
                label="Category Title(BN) *"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field
                variant="outlined"
                v-model="form.slug"
                label="Url/Slug *"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-select
                v-model="form.status"
                label="Status *"
                variant="outlined"
                :items="items"
                item-title="type"
                item-value="value"
              ></v-select>
            </v-col>
            <v-col cols="6">
              <v-select
                v-model="form.menu_type"
                label="Menu Type *"
                variant="outlined"
                :items="menus"
                item-title="type"
                item-value="value"
              ></v-select>
            </v-col>
            <v-col cols="6">
              <v-text-field
                variant="outlined"
                v-model="form.view_order"
                label="Manu View Order *"
              ></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field
                variant="outlined"
                v-model="form.meta_title_en"
                label="Meta Title(EN)"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="6">
              <v-text-field
                variant="outlined"
                v-model="form.meta_title_bn"
                label="Meta Title(BN)"
                required
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-textarea
                variant="outlined"
                v-model="form.meta_description_en"
                clear-icon="mdi-close-circle"
                label="Meta Description(EN)"
                clearable
                counter
              ></v-textarea>
            </v-col>
            <v-col cols="12">
              <v-textarea
                variant="outlined"
                v-model="form.meta_description_bn"
                clear-icon="mdi-close-circle"
                label="Meta Description(BN)"
                clearable
                counter
              ></v-textarea>
            </v-col>
          </v-row>
        </v-container>
        <small>*indicates required field</small>
      </v-card-text>
    </template>
  </Dialog>
</template>
