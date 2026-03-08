<template>
    <div class="ml-2" style="width:100%">
        <v-breadcrumbs :items="breadcrumbs" class="ml-2"></v-breadcrumbs>
        <h2 class="ml-6" v-if="showEdit && !newContact">Update contact [{{ contactName }}]</h2>
        <h2 class="ml-6" v-else-if="newContact && !showEdit">New contact</h2>
        <h2 class="ml-6" v-else>Contact [{{ contactName }}]</h2>

        <v-row v-if="!showEdit && !newContact">
            <v-col cols="12">
                <v-btn @click="showEdit = true">Edit</v-btn>
            </v-col>
        </v-row>

        <v-dialog
            v-model="dialogLoadAres"
            max-width="600"
        >
            <template v-slot:activator="{ props: activatorProps }">
                <v-row v-if="!showEdit && newContact" align="end">
                    <v-col cols="12" md="2">
                        <v-btn
                            class="text-none font-weight-regular"
                            prepend-icon="mdi-account"
                            text="Load"
                            variant="tonal"
                            v-bind="activatorProps"
                        />
                    </v-col>
                </v-row>

            </template>
            <v-card
                prepend-icon="mdi-account"
                title="User Profile"
            >
                <v-card-text>
                    <v-row dense>
                        <v-col
                            cols="12"
                            md="6"
                            sm="6"
                        >
                            <v-text-field
                                label="Name"
                                v-model="searchByName"
                                density="compact"
                                required
                            />
                        </v-col>

                        <v-col
                            cols="6"
                            md="3"
                            sm="3"
                        >
                            <v-btn
                                class="text-none font-weight-regular"
                                prepend-icon="mdi-magnify"
                                text="Search in ARES"
                                variant="tonal"
                                v-on:click="searchInAresApi"
                            />
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-dialog>
        <v-form
            v-if="showEdit || newContact"
            :action="formUpdateUrl"
            method="PUT"
            v-on:submit="onFormSubmit"
        >
            <v-container class="ma-1">
                <v-row>
                    <v-col cols="6">
                        <v-card class="mb-2">
                            <v-card-title>Basic info</v-card-title>
                            <v-card-item>
                                <v-row dense>
                                    <v-col cols="12" dense>
                                        <v-text-field
                                            label="Company or Name"
                                            v-model="contact.name"
                                            required
                                            density="comfortable"
                                        ></v-text-field>
                                    </v-col>
                                </v-row>
                                <v-row>
                                    <v-col cols="6">
                                        <v-text-field
                                            label="IČ"
                                            v-model="contact.ic"
                                            required
                                            density="comfortable"
                                        />
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field
                                            label="DIČ"
                                            v-model="contact.dic"
                                            required
                                            density="comfortable"
                                        />
                                    </v-col>
                                </v-row>
                                <v-row dense>
                                    <v-text-field
                                        label="Mail"
                                        v-model="contact.mail"
                                        density="comfortable"
                                    />
                                </v-row>
                                <v-row dense>
                                    <v-text-field
                                        label="Phone"
                                        v-model="contact.phone"
                                        density="comfortable"
                                    />
                                </v-row>
                                <v-row dense>
                                    <v-text-field
                                        label="WEB"
                                        v-model="contact.web"
                                        density="comfortable"
                                    />
                                </v-row>
                                <v-row dense>
                                    <v-text-field
                                        label="Bank Number"
                                        v-model="contact.bank_number"
                                        density="comfortable"
                                    />
                                </v-row>
                                <v-row dense>
                                    <v-text-field
                                        label="Bank IBAN"
                                        v-model="contact.bank_iban"
                                        density="comfortable"
                                    />
                                </v-row>
                                <v-row dense>
                                    <v-text-field
                                        label="Bank SWIFT"
                                        v-model="contact.bank_swift"
                                        density="comfortable"
                                    />
                                </v-row>
                            </v-card-item>
                        </v-card>
                    </v-col>
                    <v-col cols="6">
                        <v-card>
                            <v-card-title>Address</v-card-title>
                            <v-card-item>
                                <v-text-field
                                    label="City"
                                    v-model="contact.address_city"
                                    density="comfortable"
                                />
                                <v-text-field
                                    label="Street"
                                    v-model="contact.address_street"
                                    density="comfortable"
                                />
                                <v-text-field
                                    label="ZIP Code"
                                    v-model="contact.address_zipcode"
                                    type="number"
                                    density="comfortable"
                                />
                            </v-card-item>
                        </v-card>
                    </v-col>
                    <v-col cols="6">
                        <v-card>
                            <v-card-title>Contact Person info</v-card-title>
                            <v-card-item>
                                <v-text-field
                                    label="Name"
                                    v-model="contact.contact_person"
                                    density="comfortable"
                                />
                                <v-text-field
                                    label="Phone"
                                    v-model="contact.contact_person_phone"
                                    density="comfortable"
                                />
                                <v-text-field
                                    label="Mail"
                                    v-model="contact.contact_person_email"
                                    density="comfortable"
                                />
                            </v-card-item>
                        </v-card>
                    </v-col>
                    <v-col cols="6">
                        <v-card>
                            <v-card-title>Note</v-card-title>
                            <v-card-item>
                                <v-textarea v-model="contact.note" rows="3" variant="outlined"></v-textarea>
                            </v-card-item>
                        </v-card>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <v-btn
                            type="submit"
                            color="primary"
                            :loading="loading"
                        >
                            {{newContact ? 'Add' : 'Update'}}
                        </v-btn>
                        <v-btn @click="showEdit = false">Cancel</v-btn>
                    </v-col>
                </v-row>
            </v-container>
        </v-form>
        <v-container v-else class="ma-1">
            <v-row>
                <v-col cols="6">
                    <v-card class="mb-2">
                        <v-card-title>Basic information</v-card-title>
                        <v-card-item>
                            <v-row dense>
                                <v-col cols="6">Name or Company</v-col>
                                <v-col cols="6">{{contact.name}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">IČ</v-col>
                                <v-col cols="6">{{contact.ic}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">DIČ</v-col>
                                <v-col cols="6">{{contact.dic}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">Mail</v-col>
                                <v-col cols="6">{{contact.mail}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">Phone</v-col>
                                <v-col cols="6">{{contact.phone}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">WEB</v-col>
                                <v-col cols="6">{{contact.web}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">Bank Number</v-col>
                                <v-col cols="6">{{contact.bank_number}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">Bank IBAN</v-col>
                                <v-col cols="6">{{contact.bank_iban}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">Bank SWIFT</v-col>
                                <v-col cols="6">{{contact.bank_swift}}</v-col>
                            </v-row>
                        </v-card-item>
                    </v-card>
                </v-col>
                <v-col cols="6">
                    <v-card>
                        <v-card-title>Address</v-card-title>
                        <v-card-item>
                            <v-row dense>
                                <v-col cols="6">City</v-col>
                                <v-col cols="6">{{contact.address_city}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">Street</v-col>
                                <v-col cols="6">{{contact.address_street}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">ZIP Code</v-col>
                                <v-col cols="6">{{contact.address_zipcode}}</v-col>
                            </v-row>
                        </v-card-item>
                    </v-card>
                </v-col>
                <v-col cols="6">
                    <v-card>
                        <v-card-title>Contact Person info</v-card-title>
                        <v-card-item>
                            <v-row dense>
                                <v-col cols="6">Name</v-col>
                                <v-col cols="6">{{contact.contact_person}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">Phone</v-col>
                                <v-col cols="6">{{contact.contact_person_phone}}</v-col>
                            </v-row>
                            <v-row dense>
                                <v-col cols="6">Mail</v-col>
                                <v-col cols="6">{{contact.contact_person_mail}}</v-col>
                            </v-row>
                        </v-card-item>
                    </v-card>
                </v-col>
                <v-col cols="6">
                    <v-card>
                        <v-card-item>
                            <v-row dense>
                                <v-col cols="6">Note</v-col>
                                <v-col cols="6">{{contact.note}}</v-col>
                            </v-row>
                        </v-card-item>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    name: 'Contact',
    data: () => {
        return {
            breadcrumbs: [
                {title: 'Home', disabled: false, href: '/'},
                {title: 'Contacts', disabled: false, href: '/contacts'},
            ],
            contact: {},
            contactName: '',
            formUpdateUrl: '/api/contacts/',
            loading: false,
            showEdit: false,
            dialogLoadAres: false,
            searchByName: '',
        }
    },
    props: [
        'newContact'
    ],
    mounted() {
        this.loadContact()
    },
    methods: {
        loadContact() {
            let self = this

            const id = this.$route.params.id

            if (id !== undefined) {
                axios.get('/api/contacts/' + id)
                    .then((response) => {
                        if (response.data) {
                            self.contact = response.data
                            self.contactName = self.contact.name
                            self.breadcrumbs.push({
                                'title': this.contact.name,
                                'disabled': true
                            })
                        }
                    })
            }
        },
        onFormSubmit(e) {
            e.preventDefault()
            let self = this
            self.loading = true

            if (self.showEdit) {
                this.formUpdateUrl += this.$route.params.id
                axios.patch(this.formUpdateUrl, this.contact)
                    .then((response) => {
                        self.loading = false
                        self.$router.push('/contacts')
                    })
            } else {
                axios.post(this.formUpdateUrl, this.contact)
                    .then((response) => {
                        self.loading = false
                        self.$router.push('/contacts')
                    })
            }
        },
        loadAresApi() {
            let self = this

            axios.get('/api/ares?ic=' + this.contact.ic)
                .then((response) => {
                    let data = response.data

                    if (data.error) {

                    } else {
                        self.contact.ic = data.ic
                        self.contact.name = data.name
                        self.contact.address_street = data.addressStreet
                        self.contact.address_city = data.addressCity
                        self.contact.address_zipcode = data.addressZipcode
                    }
                })
        },
        searchInAresApi() {
            let self = this

            axios.post('/api/ares/search', {search: self.searchByName})
                .then((response) => {
                    console.log(response.data)
                })
        }
    }
}

</script>

