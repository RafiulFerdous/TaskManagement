<script setup>
// Write dependency import statements here
import {ref, reactive, computed, onMounted, watch} from "vue";
import {useDate} from "vuetify";
import HttpService from "../../services/HttpService.js";
import AuthService from "../../services/AuthService.js";
import {useformAction} from "../../composables/useformAction";
import Form from "../../services/Form";
import {useSnackbarStore} from "../../store/useSnackbarStore";
// End Dependency Import

// emits here
const emit = defineEmits(["onCloseDialog"]);

// props here
const props = defineProps({
    isOpen: Boolean,
    formname: String,
    dialogData: {
        default: [],
    },
});

// import composables/mixins here
let {_initForm} = useformAction();
const snackbar = useSnackbarStore();
// End of composables

// Write data definition statements here
let loading = ref(false);
let formId = ref(null);
let form = reactive(
    new Form({
        name: "",
        description: "",
        userName: "",
        ProjectName: "",
        deadline: "",
        status: "",
        priority: "",

    })
);

let items = [
    {type: "Pending", value: 1},
    {type: "Finished", value: 2},
];
let menus = [
    {type: "High ", value: 1},
    {type: "Low", value: 2},
];
// validation rules
let rules = reactive({
    name: "required",
    // User_name: "required",
    description: "required",

});

let dialogStatus = ref(false);
let formname = ref("");
dialogStatus.value = props.isOpen;
formname.value = props.formname;
if (dialogStatus.value === true) {
    beforeOpenDialog(props.dialogData);
}

// Write methods here
function beforeOpenDialog(event) {
    _initForm(event, form, formId, rules);
}

async function onSubmitForm(event) {
    // console.log("submit btn clicked",event)
    try {
        const {valid, errors} = await event;

        if (!valid) return false;
        loading.value = true;
        let id = formId.value;

        form.level = 1;
        let {data} = await HttpService[!id ? "post" : "put"](
            !id ? `tasks` : `tasks/${id}`,
            form.data()
        );
        dialogStatus.value = false;
        loading.value = false;
        snackbar.success(
            `Task has been ${id ? "updated" : "created"} successfully!`
        );
        emit("onCloseDialog", data.data, formId.value);
    } catch (error) {
        console.log(error);
        emit("onCloseDialog");
        // this.setFormErrors(error);
        snackbar.error(`Failed to create Task !`);
    }
}

function handleDialogAction(action = "close") {
    if (action == "close") {
        dialogStatus.value = false;
        emit("onCloseDialog");
        formname.value = "Add New Task"
    } else if (action == "clickOutside") {
        return false;
    }
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
                {{ formname }}
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
                    <v-container>
                        <v-row>
                            <v-col cols="12">
                                <v-text-field
                                    variant="outlined"
                                    v-model="form.name"
                                    :rules="rules.name"
                                    label="Task Name *"
                                    required


                                ></v-text-field>
                            </v-col>
                            <v-col cols="12">
                                <v-text-field
                                    variant="outlined"
                                    v-model="form.description"
                                    :rules="rules.description"
                                    label="Description *"
                                    required

                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                    variant="outlined"
                                    v-model="form.userName"
                                    label="userName "
                                    type="number"

                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-select
                                    v-model="form.status"
                                    :rules="rules.status"
                                    label="Status *"
                                    variant="outlined"
                                    :items="items"
                                    item-title="type"
                                    item-value="value"
                                ></v-select>
                            </v-col>
                            <v-col cols="6">
                                <v-select
                                    v-model="form.priority"
                                    label="priority"
                                    variant="outlined"
                                    :items="menus"
                                    item-title="type"
                                    item-value="value"
                                ></v-select>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                    variant="outlined"
                                    v-model="form.ProjectName"
                                    label="ProjectName"
                                    type="number"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field
                                    variant="outlined"
                                    v-model="form.deadline"
                                    label="deadline"
                                    required
                                ></v-text-field>
                            </v-col>


                        </v-row>
                    </v-container>
                    <small>*indicates required field</small>
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
