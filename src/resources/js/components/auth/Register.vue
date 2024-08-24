<script setup>
import { ref } from 'vue';
const checkbox = ref(true);
import Logo from '@/layouts/full/logo/Logo.vue';
</script>

    <template>
    <div class="authentication">
        <v-container fluid class="pa-3">
            <v-row class="h-100vh d-flex justify-center align-center">
                <v-col cols="12" lg="4" xl="3" class="d-flex align-center">
                    <v-card rounded="md" elevation="10" class="px-sm-1 px-0 withbg mx-auto" max-width="500">
                        <v-card-item class="pa-sm-8">
                            <div class="d-flex justify-center py-4">
                                <Logo />
                            </div>
                            <div class="text-body-1 text-muted text-center mb-3">Your Social Campaigns</div>
                            <v-row class="d-flex mb-3">
                                <v-col cols="12">
                                    <v-label class="font-weight-bold mb-1">Name</v-label>
                                    <v-text-field variant="outlined" hide-details color="primary"></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-label class="font-weight-bold mb-1">Email Address</v-label>
                                    <v-text-field variant="outlined" type="email" hide-details color="primary"></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-label class="font-weight-bold mb-1">Password</v-label>
                                    <v-text-field variant="outlined" type="password" hide-details color="primary"></v-text-field>
                                </v-col>
                                <v-col cols="12">
                                    <v-btn to="/" color="primary" size="large" block flat>Sign up</v-btn>
                                </v-col>
                            </v-row>
                            <h6 class="text-h6 text-muted font-weight-medium d-flex justify-center align-center mt-3">
                                Already have an Account?
                                <RouterLink
                                    to="/auth/login"
                                    class="text-primary text-decoration-none text-body-1 opacity-1 font-weight-medium pl-2"
                                >
                                    Sign In</RouterLink
                                >
                            </h6>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>