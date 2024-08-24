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
import { useSnackbarStore } from "../../store/useSnackbarStore";
import { useDatatableStore } from "../../store/useDatatableStore";
import Form from "../../services/Form";
import debounce from "lodash.debounce";
import UserForm from "../user/UserForm.vue";

import TaskForm from "./TaskForm.vue";
// End Dependency Import

// import composables/mixins here

let { _initForm } = useformAction();
// let { updateData } = useformAction();
const datatableStore = useDatatableStore();
const snackbar = useSnackbarStore();

let {
  getUrlParams,
  getURLQueryString,
  headers,
  changeOrderBy,
  applySearch,
  pageChange,
} = useCommon();
// End of composables

// Write data definition statements here
let loading = ref(false);
let dialogStatus = ref(false);
let dialogOpen = ref(false);
let dialogData = [];

let formname= ref("Add New Task");

let search = ref("");
let tasks = reactive([]);
let meta = reactive({
  per_page: 0,
  total: 0,
});
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
  fetchTasks();

});

watch(datatableStore, (value) => {
  if (datatableStore.$state.reloadDatatable == true) {
    fetchTasks();
  }
  datatableStore.$state.reloadDatatable = false
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
      tasks.unshift(data);
      return;
    }
    let collectionItem =
      tasks[tasks.findIndex((item) => item.id == id)];
    for (let key in data) {
      collectionItem[key] = data[key];
    }
  } catch (e) {
    console.log(e);
  }
}

function create() {
  dialogStatus.value = true;
  dialogOpen.value = true;
  dialogData = [];
  formname.value="Add New Task"
}

function edit(data) {
  dialogStatus.value = true;
  dialogOpen.value = true;
  dialogData = data;
  formname.value="Edit Task"
}

function dialogClosed(data = null, id = null) {
  if (data != null) {
    loading.value = true;
    updateData(data, tasks, id);
    if (!id) {
      meta.total = meta.total + 1
    }
    setTimeout(() => {
      loading.value = false;
    }, 500);
  }
  dialogOpen.value = false;
}

async function fetchTasks() {
  loading.value = true;
  const { data } = await HttpService.get(`tasks${getURLQueryString()}`);
  tasks = data.data;
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
      tasks.splice(tasks.indexOf(item), 1);
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


const debouncedCallback = debounce((args) => {
  applySearch(search.value);
}, 500);

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
          <v-col> Tasks </v-col>
          <v-col class="d-flex justify-end">
            <v-btn @click="create" icon="mdi-plus" color="primary"></v-btn>
          </v-col>
        </v-row>
      </v-card-title>

      <v-row>
        <v-col></v-col>
        <v-col cols="5">
          <v-text-field
            v-model="search"
            @input="debouncedCallback"
            density="compact"
            label="Search"
            prepend-inner-icon="mdi-magnify"
            variant="outlined"
            flat
            hide-details
            single-line
          ></v-text-field>
        </v-col>
      </v-row>

      <v-data-table-server
        v-model:items-per-page="meta.per_page"
        :headers="headers('Name', 'deadline', 'priority level', 'Status', 'Action')"
        :items="tasks"
        :items-length="meta.total"
        :loading="loading"
        @update:items-per-page="pageChange(1, $event)"
        @update:page="pageChange($event, meta.per_page)"
        @update:sort-by="changeOrderBy($event)"
      >
        <template v-slot:loading>
          <v-skeleton-loader type="table-tbody"></v-skeleton-loader>
        </template>
        <template v-if="!loading" v-slot:item="{ item }">
          <tr class="border-bottom">

            <td>
              <div class="">
                <h6 class="text-subtitle-1 font-weight-bold">
                  {{ item.name }}
                </h6>
                <div class="text-13 mt-1 text-muted">
                  {{ item.name }}
                </div>
              </div>
            </td>

            <td>
              <div class="text-13 mt-1 text-muted">
                {{ item.deadline }}
              </div>
            </td>

            <td>
              <div class="">
                <h6 class="text-subtitle-1 font-weight-bold">
                  {{ item.priority_level }}
                </h6>
                <div class="text-13 mt-1 text-muted">
                  {{ item.Status }}
                </div>
              </div>
            </td>


            <td>
              <v-chip
                class="text-body-1 bg-primary"
                color="white"
                size="small"
              >
                {{ item.priority_level }}
              </v-chip>
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
      </v-data-table-server>

    </v-card-item>
  </v-card>

  <TaskForm
    v-if="dialogOpen"
    :isOpen="dialogOpen"
    :dialogData="dialogData"
    :formname="formname"
    @onCloseDialog="dialogClosed"
  ></TaskForm>
</template>
