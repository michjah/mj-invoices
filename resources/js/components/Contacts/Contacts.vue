<template>
    <div style="width:100%">
        <v-breadcrumbs :items="breadcrumbs"></v-breadcrumbs>
        <h2 class="ml-4">Contacts</h2>

        <v-spacer class="mt-2"/>

        <v-container width="100vw" class="ma-2 ml-0" fluid fill-height>
            <v-row>
                <v-col cols="2" align-self="end">
                    <v-btn
                        prepend-icon="mdi-plus"
                        to="/contacts/new"
                    >
                        New
                    </v-btn>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12">
                    <v-data-table-server
                        v-model:items-per-page="totalItems"
                        v-model:sort-by="sortBy"
                        v-on:click:row="onRowClick"
                        :headers="headers"
                        :hover="true"
                        :items="serverItems"
                        :items-length="totalItems"
                        @update:options="loadItems"
                        class="elevation-1"
                        dense
                        :hide-default-footer="true"
                    >
                        <template v-slot:item.name="{ item }">
                            <a v-bind:href="'/contacts/'+item.id">{{item.name}}</a>
                        </template>
                    </v-data-table-server>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
    import axios from "axios";

    export default {
        name: 'Contacts',
        data: () => {
            return {
                breadcrumbs: [
                    {title: 'Home', disabled: false, href: '/'},
                    {title: 'Contacts', disabled: true},
                ],
                serverItems: [],
                totalItems: 0,
                countPages: 1,
                loading: false,
                sortBy: [{ key: 'name', order: 'desc' }],
                p: 1,
                headers: [
                    {
                        title: 'Name',
                        key: 'name',
                        sortable: true
                    }, {
                        title: 'IČ',
                        key: 'ic',
                        sortable: false
                    }, {
                        title: 'Email',
                        key: 'mail',
                        sortable: false
                    }, {
                        title: 'Phone',
                        key: 'phone',
                        sortable: false
                    }, {
                        title: 'Contact Person',
                        key: 'contact_person',
                        sortable: false
                    },
                ]
            }
        },
        methods: {
            loadItems (options) {
                let self = this
                self.loading = true

                const opt = {
                    'page': options.page,
                    'sortBy': options.sortBy.key ?? self.sortBy,
                    'sortDesc': self.sortBy.order ?? self.sortDesc
                }

                axios.get('/api/contacts', {params: opt})
                    .then((response) => {
                        self.serverItems = response.data.data
                        self.totalItems = response.data.total

                        self.countPages = self.totalItems / self.itemsPerPage
                        self.loading = false
                    })
            },
            onRowClick (e, row) {
                this.$router.push('/contacts/' + row.item.id)
            }
        }
    }
</script>
