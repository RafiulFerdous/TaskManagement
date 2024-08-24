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
let items = [
  { type: "List", value: 1 },
  { type: "Grid", value: 2 },
];
let statuses = [
  { type: "Active", value: 1 },
  { type: "Inactive", value: 2 },
];
let homepagecategories = ref({});
let categories = ref([]);
let meta = ref({});
let formId = ref(null);
let form = reactive(
  new Form({
    category_id: "",
    view_order: "",
    view_type: "",
    status: "",
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
  fetchHomepageCategories();
});
// End of mounted

// Write methods here
function beforeOpenDialog(event) {
  fetchCategories();
  _initForm(event, form, formId);
  // console.log(form.data());
}

function onCloseDialog() {
  dialogStatus.value = false;
}

function updateData(data, key, id) {
  try {
    if (!id) {
      homepagecategories.unshift(data);
      return;
    }
    let collectionItem =
      homepagecategories[homepagecategories.findIndex((item) => item.id == id)];
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
    let { data } = await HttpService[!id ? "post" : "put"](
      !id ? `homepage-categoris-setup` : `homepage-categoris-setup/${id}`,
      form.data()
    );
    updateData(data.data, "homepagecategories", formId.value);
    dialogStatus.value = false;
    loading.value = false;
    // flash(`Successfully ${this.id ? 'updated' : 'created'}`, 'success', false)
  } catch (error) {
    // this.setFormErrors(error);
    // flash('Failed to create batch !', 'danger', false)
  }
}

async function fetchHomepageCategories() {
  loading.value = true;
  const { data } = await HttpService.get("homepage-categoris-setup");
  homepagecategories = data.data;
  meta = data.meta;
  loading.value = false;
}
async function fetchCategories() {
  loading.value = true;
  const { data } = await HttpService.get("categories?fetch=all");
  data.data.forEach((element) => {
    categories.value.push({ key: element.title_en, value: element.id });
  });
  meta = data.meta;
  loading.value = false;
}

async function destroy(item) {
  if (!confirm("Do you want to delete ?")) {
    return false;
  }
  try {
    loading.value = true;
    const { data } = await HttpService.delete(
      `homepage-categoris-setup/${item.id}`
    );
    if (data.success == true) {
      homepagecategories.splice(homepagecategories.indexOf(item), 1);
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
          <v-col> Home Page Category News Setup </v-col>
          <v-col class="d-flex justify-end">
            <v-btn @click="create" icon="mdi-plus" color="primary"></v-btn>
          </v-col>
        </v-row>
      </v-card-title>

      <v-skeleton-loader :loading="loading" type="table-tbody">
        <v-table class="w-100">
          <thead>
            <tr>
              <th class="text-subtitle-1 font-weight-bold">Category</th>
              <th class="text-subtitle-1 font-weight-bold">Order</th>
              <th class="text-subtitle-1 font-weight-bold">Type</th>
              <th class="text-subtitle-1 font-weight-bold">Status</th>
              <th class="text-subtitle-1 font-weight-bold">Action</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="!loading && homepagecategories.length > 0">
              <tr
                v-for="(item, key) in homepagecategories"
                :key="key"
                class="month-item"
              >
                <td>
                  <div class="">
                    <h6 class="text-subtitle-1 font-weight-bold">
                      {{ item.category?.title_en }}
                    </h6>
                    <div class="text-13 mt-1 text-muted">
                      {{ item.category?.title_bn }}
                    </div>
                  </div>
                </td>
                <td>
                  <div class="">
                    <h6 class="text-subtitle-1 font-weight-bold">
                      {{ item.view_order }}
                    </h6>
                  </div>
                </td>
                <td>
                  <div class="">
                    <h6 class="text-subtitle-1 font-weight-bold">
                      {{ item.viewType_text }}
                    </h6>
                  </div>
                </td>

                <td>
                  <v-chip
                    class="text-body-1 bg-primary"
                    color="white"
                    size="small"
                  >
                    {{ item.status_text }}
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
            <NotFound
              v-if="!loading && homepagecategories.length == 0"
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
    title="Add Category view order setup"
  >
    <template #default>
      <v-card-text>
        <v-container>
          <v-row>
            <v-col cols="12">
              <v-select
                v-model="form.category_id"
                clearable
                label="Category"
                :items="categories"
                item-title="key"
                item-value="value"
                variant="outlined"
              ></v-select>
            </v-col>
            <v-col cols="12">
              <v-text-field
                variant="outlined"
                v-model="form.view_order"
                label="view Order *"
                required
              ></v-text-field>
            </v-col>

            <v-col cols="6">
              <v-select
              v-model="form.view_type"
                label="View type"
                variant="outlined"
                :items="items"
                item-title="type"
                item-value="value"
              ></v-select>
            </v-col>

            <v-col cols="6">
              <v-select
              v-model="form.status"
                label="Status *"
                variant="outlined"
                :items="statuses"
                item-title="type"
                item-value="value"
              ></v-select>
            </v-col>
          </v-row>
        </v-container>
        <small>*indicates required field</small>
      </v-card-text>
    </template>
  </Dialog>
</template>
